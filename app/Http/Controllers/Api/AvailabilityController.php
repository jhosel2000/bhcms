<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Midwife;
use App\Models\BHW;
use App\Services\AppointmentConflictService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AvailabilityController extends Controller
{
    /**
     * Get available time slots for a doctor on a specific date
     */
    public function getDoctorSlots(Request $request, Doctor $doctor): JsonResponse
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
        ]);

        $conflictService = new AppointmentConflictService();
        $availability = $conflictService->getAvailableSlots(
            $doctor->id,
            'doctor',
            $request->date
        );

        return response()->json([
            'success' => true,
            'data' => $availability
        ]);
    }

    /**
     * Get available time slots for a midwife on a specific date
     */
    public function getMidwifeSlots(Request $request, Midwife $midwife): JsonResponse
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
        ]);

        $conflictService = new AppointmentConflictService();
        $availability = $conflictService->getAvailableSlots(
            $midwife->id,
            'midwife',
            $request->date
        );

        return response()->json([
            'success' => true,
            'data' => $availability
        ]);
    }

    /**
     * Get available time slots for a BHW on a specific date
     */
    public function getBHWSlots(Request $request, BHW $bhw): JsonResponse
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
        ]);

        $conflictService = new AppointmentConflictService();
        $availability = $conflictService->getAvailableSlots(
            $bhw->id,
            'bhw',
            $request->date
        );

        return response()->json([
            'success' => true,
            'data' => $availability
        ]);
    }

    /**
     * Check if a specific time slot is available for a provider
     */
    public function checkSlotAvailability(Request $request): JsonResponse
    {
        $request->validate([
            'provider_id' => 'required|integer',
            'provider_type' => 'required|in:doctor,midwife,bhw',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'duration_minutes' => 'integer|min:15|max:480|default:30',
        ]);

        $appointmentData = [
            'appointment_date' => $request->date,
            'appointment_time' => $request->time,
            'duration_minutes' => $request->duration_minutes,
        ];

        // Add the appropriate provider ID
        switch ($request->provider_type) {
            case 'doctor':
                $appointmentData['doctor_id'] = $request->provider_id;
                break;
            case 'midwife':
                $appointmentData['midwife_id'] = $request->provider_id;
                break;
            case 'bhw':
                $appointmentData['bhw_id'] = $request->provider_id;
                break;
        }

        $conflictService = new AppointmentConflictService();
        $conflictCheck = $conflictService->checkConflicts($appointmentData);

        return response()->json([
            'success' => true,
            'available' => !$conflictCheck['has_conflict'],
            'conflicts' => $conflictCheck['conflicts'],
            'message' => $conflictCheck['message']
        ]);
    }

    /**
     * Get provider's schedule for a specific date
     */
    public function getProviderSchedule(Request $request): JsonResponse
    {
        $request->validate([
            'provider_id' => 'required|integer',
            'provider_type' => 'required|in:doctor,midwife,bhw',
            'date' => 'required|date',
        ]);

        $conflictService = new AppointmentConflictService();
        $schedule = $conflictService->getProviderSchedule(
            $request->provider_id,
            $request->provider_type,
            $request->date
        );

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'No schedule found for this provider on this date'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $schedule
        ]);
    }

    /**
     * Get available time slots for a provider by type and ID
     */
    public function getSlotsByType(Request $request, string $providerType, int $providerId): JsonResponse
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
        ]);

        $conflictService = new AppointmentConflictService();
        $availability = $conflictService->getAvailableSlots(
            $providerId,
            $providerType,
            $request->date
        );

        return response()->json([
            'success' => true,
            'data' => $availability
        ]);
    }
}
