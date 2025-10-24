<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AppointmentsController extends Controller
{
    /**
     * Get appointments for calendar view
     */
    public function calendar(Request $request): JsonResponse
    {
        $request->validate([
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        $doctorId = auth()->user()->doctor->id;

        $appointments = Appointment::where('doctor_id', $doctorId)
            ->whereBetween('appointment_date', [$request->start, $request->end])
            ->with('patient')
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->patient->full_name . ' - ' . $appointment->reason,
                    'start' => $appointment->appointment_date->toDateString() . 'T' . $appointment->appointment_time->toTimeString(),
                    'end' => $appointment->appointment_date->toDateString() . 'T' . $appointment->appointment_time->addMinutes(30)->toTimeString(),
                    'backgroundColor' => $this->getStatusColor($appointment->status),
                    'borderColor' => $this->getStatusColor($appointment->status),
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'status' => $appointment->status,
                        'patient' => $appointment->patient->full_name,
                        'reason' => $appointment->reason,
                        'time' => $appointment->appointment_time->format('g:i A'),
                        'notes' => $appointment->notes,
                    ],
                ];
            });

        return response()->json($appointments);
    }

    /**
     * Get color based on appointment status
     */
    private function getStatusColor(string $status): string
    {
        return match ($status) {
            'pending' => '#f59e0b', // amber
            'approved' => '#3b82f6', // blue
            'completed' => '#10b981', // emerald
            'declined' => '#ef4444', // red
            default => '#6b7280', // gray
        };
    }
}
