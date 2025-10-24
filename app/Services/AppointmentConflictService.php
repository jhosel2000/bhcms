<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Midwife;
use App\Models\BHW;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AppointmentConflictService
{
    /**
     * Check if a new appointment would conflict with existing appointments
     *
     * @param array $appointmentData
     * @return array ['has_conflict' => bool, 'conflicts' => array, 'message' => string]
     */
    public function checkConflicts(array $appointmentData): array
    {
        $conflicts = [];
        $messages = [];

        // Get the provider ID and type
        $providerId = null;
        $providerType = null;

        if (!empty($appointmentData['doctor_id'])) {
            $providerId = $appointmentData['doctor_id'];
            $providerType = 'doctor';
        } elseif (!empty($appointmentData['midwife_id'])) {
            $providerId = $appointmentData['midwife_id'];
            $providerType = 'midwife';
        } elseif (!empty($appointmentData['bhw_id'])) {
            $providerId = $appointmentData['bhw_id'];
            $providerType = 'bhw';
        }

        if (!$providerId) {
            return [
                'has_conflict' => false,
                'conflicts' => [],
                'message' => 'No provider selected'
            ];
        }

        // Calculate appointment end time - handle different time formats
        $startTime = $this->parseTimeString($appointmentData['appointment_time']);
        $duration = isset($appointmentData['duration_minutes']) ? $appointmentData['duration_minutes'] : 30;
        $endTime = $startTime->copy()->addMinutes($duration);

        // Check for overlapping appointments with the same provider
        $overlappingAppointments = Appointment::where('appointment_date', $appointmentData['appointment_date'])
            ->where(function ($query) use ($providerType, $providerId) {
                switch ($providerType) {
                    case 'doctor':
                        $query->where('doctor_id', $providerId);
                        break;
                    case 'midwife':
                        $query->where('midwife_id', $providerId);
                        break;
                    case 'bhw':
                        $query->where('bhw_id', $providerId);
                        break;
                }
            })
            ->whereIn('status', ['pending', 'approved', 'scheduled', 'confirmed'])
            ->get();

        foreach ($overlappingAppointments as $appointment) {
            // Parse existing appointment time
            $existingStart = $this->parseTimeString($appointment->appointment_time);
            $existingEnd = $existingStart->copy()->addMinutes(isset($appointment->duration_minutes) ? $appointment->duration_minutes : 30);

            // Check for overlap
            if ($this->timesOverlap($startTime, $endTime, $existingStart, $existingEnd)) {
                $patientName = 'Unknown';
                if ($appointment->patient && $appointment->patient->user) {
                    $patientName = $appointment->patient->user->name;
                }

                $conflicts[] = [
                    'appointment_id' => $appointment->id,
                    'patient_name' => $patientName,
                    'start_time' => $appointment->appointment_time,
                    'end_time' => $existingEnd->format('H:i'),
                    'reason' => $appointment->reason
                ];

                $messages[] = "Conflicts with existing appointment for " . $patientName . " at " . $appointment->appointment_time;
            }
        }

        return [
            'has_conflict' => !empty($conflicts),
            'conflicts' => $conflicts,
            'message' => empty($messages) ? 'No conflicts found' : implode('; ', $messages)
        ];
    }

    /**
     * Parse time string to Carbon instance, handling multiple formats
     */
    private function parseTimeString(string $timeString): Carbon
    {
        // Try H:i format first (e.g., "14:30")
        try {
            return Carbon::createFromFormat('H:i', $timeString);
        } catch (\Exception $e) {
            // Try H:i:s format (e.g., "14:30:00")
            try {
                return Carbon::createFromFormat('H:i:s', $timeString);
            } catch (\Exception $e2) {
                // Fallback to general parsing
                return Carbon::parse($timeString);
            }
        }
    }

    /**
     * Check if two time ranges overlap
     */
    private function timesOverlap(Carbon $start1, Carbon $end1, Carbon $start2, Carbon $end2): bool
    {
        return $start1->lt($end2) && $start2->lt($end1);
    }

    /**
     * Get available time slots for a provider on a specific date
     *
     * @param int $providerId
     * @param string $providerType
     * @param string $date
     * @return array
     */
    public function getAvailableSlots(int $providerId, string $providerType, string $date): array
    {
        // Get provider's schedule for the day
        $schedule = $this->getProviderSchedule($providerId, $providerType, $date);

        if (!$schedule) {
            return ['message' => 'No schedule found for this provider on this date'];
        }

        // Get existing appointments for the day
        $existingAppointments = Appointment::where('appointment_date', $date)
            ->where(function ($query) use ($providerType, $providerId) {
                switch ($providerType) {
                    case 'doctor':
                        $query->where('doctor_id', $providerId);
                        break;
                    case 'midwife':
                        $query->where('midwife_id', $providerId);
                        break;
                    case 'bhw':
                        $query->where('bhw_id', $providerId);
                        break;
                }
            })
            ->whereIn('status', ['pending', 'approved', 'scheduled', 'confirmed'])
            ->orderBy('appointment_time')
            ->get();

        // Generate available slots
        $availableSlots = [];
        try {
            $currentTime = Carbon::createFromFormat('H:i:s', $schedule['start_time']);
            $endTime = Carbon::createFromFormat('H:i:s', $schedule['end_time']);
        } catch (\Exception $e) {
            // Fallback to default parsing
            $currentTime = Carbon::parse($schedule['start_time']);
            $endTime = Carbon::parse($schedule['end_time']);
        }
        $slotDuration = $schedule['slot_duration_minutes'];

        // For today's date, only show future slots
        $isToday = Carbon::parse($date)->isToday();
        $now = Carbon::now();

        while ($currentTime->lt($endTime)) {
            $slotEnd = $currentTime->copy()->addMinutes($slotDuration);

            // Skip past slots for today's date
            if ($isToday && $slotEnd->isPast()) {
                $currentTime = $slotEnd;
                continue;
            }

            // Check if this slot conflicts with existing appointments
            $isAvailable = true;
            foreach ($existingAppointments as $appointment) {
                $appointmentStart = $this->parseTimeString($appointment->appointment_time);
                $appointmentEnd = $appointmentStart->copy()->addMinutes(isset($appointment->duration_minutes) ? $appointment->duration_minutes : 30);

                if ($this->timesOverlap($currentTime, $slotEnd, $appointmentStart, $appointmentEnd)) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                $availableSlots[] = [
                    'start_time' => $currentTime->format('H:i'),
                    'end_time' => $slotEnd->format('H:i')
                ];
            }

            $currentTime = $slotEnd;
        }

        return [
            'available_slots' => $availableSlots,
            'schedule' => $schedule,
            'existing_appointments_count' => $existingAppointments->count()
        ];
    }

    /**
     * Get provider's schedule for a specific date
     */
    public function getProviderSchedule(int $providerId, string $providerType, string $date): ?array
    {
        $dayOfWeek = Carbon::parse($date)->format('l'); // Get day name

        // Query the provider_availability_schedules table
        $schedule = null;

        switch ($providerType) {
            case 'doctor':
                $schedule = DB::table('provider_availability_schedules')
                    ->where('doctor_id', $providerId)
                    ->where('day_of_week', strtolower($dayOfWeek))
                    ->where('is_available', true)
                    ->first();
                break;
            case 'midwife':
                $schedule = DB::table('provider_availability_schedules')
                    ->where('midwife_id', $providerId)
                    ->where('day_of_week', strtolower($dayOfWeek))
                    ->where('is_available', true)
                    ->first();
                break;
            case 'bhw':
                $schedule = DB::table('provider_availability_schedules')
                    ->where('bhw_id', $providerId)
                    ->where('day_of_week', strtolower($dayOfWeek))
                    ->where('is_available', true)
                    ->first();
                break;
        }

        if (!$schedule) {
            // Return default schedule if no specific schedule found
            return [
                'day_of_week' => strtolower($dayOfWeek),
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'slot_duration_minutes' => 30,
                'buffer_minutes' => 15
            ];
        }

        return [
            'day_of_week' => $schedule->day_of_week,
            'start_time' => $schedule->start_time,
            'end_time' => $schedule->end_time,
            'slot_duration_minutes' => $schedule->slot_duration_minutes,
            'buffer_minutes' => $schedule->buffer_minutes
        ];
    }

    /**
     * Validate appointment data before booking
     */
    public function validateAppointmentData(array $data): array
    {
        $errors = [];

        // Check required fields
        $requiredFields = ['appointment_date', 'appointment_time', 'reason'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $errors[] = "The {$field} field is required.";
            }
        }

        // Validate date is not in the past
        if (!empty($data['appointment_date']) && $data['appointment_date'] < date('Y-m-d')) {
            $errors[] = 'Appointment date cannot be in the past.';
        }

        // Validate at least one provider is selected
        if (empty($data['doctor_id']) && empty($data['midwife_id']) && empty($data['bhw_id'])) {
            $errors[] = 'Please select a doctor, midwife, or BHW.';
        }

        // Validate only one provider is selected
        $providerCount = 0;
        if (!empty($data['doctor_id'])) $providerCount++;
        if (!empty($data['midwife_id'])) $providerCount++;
        if (!empty($data['bhw_id'])) $providerCount++;

        if ($providerCount > 1) {
            $errors[] = 'Please select only one healthcare provider.';
        }

        return $errors;
    }
}
