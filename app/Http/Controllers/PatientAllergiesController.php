<?php

namespace App\Http\Controllers;

use App\Models\Allergy;
use Illuminate\Http\Request;

class PatientAllergiesController extends Controller
{
    /**
     * Display a listing of the patient's allergies (READ-ONLY).
     */
    public function index()
    {
        $patientId = auth()->user()->patient->id;

        // Get all allergies for the patient
        $allergies = Allergy::where('patient_id', $patientId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('patient.allergies.index', compact('allergies'));
    }

    /**
     * Display the specified allergy (READ-ONLY).
     */
    public function show(Allergy $allergy)
    {
        // Ensure the allergy belongs to the authenticated patient
        if ($allergy->patient_id !== auth()->user()->patient->id) {
            abort(403, 'You do not have permission to view this allergy record.');
        }

        return view('patient.allergies.show', compact('allergy'));
    }
}
