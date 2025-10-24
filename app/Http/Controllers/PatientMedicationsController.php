<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use Illuminate\Http\Request;

class PatientMedicationsController extends Controller
{
    /**
     * Display a listing of the patient's medications (READ-ONLY).
     */
    public function index()
    {
        $patientId = auth()->user()->patient->id;

        // Get all medications for the patient
        $medications = Medication::where('patient_id', $patientId)
            ->orderBy('start_date', 'desc')
            ->paginate(15);

        return view('patient.medications.index', compact('medications'));
    }

    /**
     * Display the specified medication (READ-ONLY).
     */
    public function show(Medication $medication)
    {
        // Ensure the medication belongs to the authenticated patient
        if ($medication->patient_id !== auth()->user()->patient->id) {
            abort(403, 'You do not have permission to view this medication record.');
        }

        return view('patient.medications.show', compact('medication'));
    }
}
