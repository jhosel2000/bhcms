<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PatientsController extends Controller
{
    /**
     * Search patients by name, ID, or phone number
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $doctorId = auth()->user()->doctor->id;

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $patients = Patient::whereHas('appointments', function ($q) use ($doctorId) {
                $q->where('doctor_id', $doctorId);
            })
            ->where(function ($q) use ($query) {
                $q->where('full_name', 'like', '%' . $query . '%')
                  ->orWhere('id', 'like', '%' . $query . '%')
                  ->orWhere('contact_number', 'like', '%' . $query . '%');
            })
            ->limit(10)
            ->get(['id', 'full_name', 'date_of_birth', 'contact_number', 'gender']);

        return response()->json($patients);
    }

    /**
     * Get patient details
     */
    public function show(Patient $patient): JsonResponse
    {
        // Check if doctor has access to this patient
        $doctorId = auth()->user()->doctor->id;
        if (!$patient->appointments()->where('doctor_id', $doctorId)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $patient->load(['appointments' => function ($query) {
            $query->latest()->limit(5);
        }]);

        return response()->json($patient);
    }

    /**
     * Get patient overview with medical summary
     */
    public function getOverview(Patient $patient): JsonResponse
    {
        // Check if doctor has access to this patient
        $doctorId = auth()->user()->doctor->id;
        if (!$patient->appointments()->where('doctor_id', $doctorId)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $overview = [
            'patient' => $patient->only(['id', 'full_name', 'date_of_birth', 'gender', 'contact_number']),
            'summary' => [
                'total_allergies' => $patient->allergies()->count(),
                'active_allergies' => $patient->allergies()->active()->count(),
                'critical_allergies' => $patient->allergies()->active()->critical()->count(),
                'current_medications' => $patient->medications()->current()->count(),
                'total_medications' => $patient->medications()->count(),
                'recent_lab_results' => $patient->labResults()->recent()->count(),
                'critical_lab_results' => $patient->labResults()->critical()->count(),

                'active_risks' => $patient->risks()->active()->count(),
                'critical_risks' => $patient->risks()->active()->critical()->count(),
                'last_visit' => $patient->appointments()->latest()->first()?->appointment_date,
            ],
            'recent_activity' => [
                'latest_appointment' => $patient->appointments()->latest()->first(),
                'recent_lab_results' => $patient->labResults()->recent()->limit(3)->get(),
                'current_medications' => $patient->medications()->current()->limit(5)->get(),
                'critical_allergies' => $patient->allergies()->active()->critical()->get(),
                'active_risks' => $patient->risks()->active()->requiresAlert()->limit(3)->get(),
            ]
        ];

        return response()->json($overview);
    }
}
