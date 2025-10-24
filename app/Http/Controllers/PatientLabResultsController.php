<?php

namespace App\Http\Controllers;

use App\Models\LabResult;
use Illuminate\Http\Request;

class PatientLabResultsController extends Controller
{
    /**
     * Display a listing of the patient's lab results (READ-ONLY).
     */
    public function index()
    {
        $patientId = auth()->user()->patient->id;

        // Get all lab results for the patient
        $labResults = LabResult::where('patient_id', $patientId)
            ->orderBy('test_date', 'desc')
            ->paginate(15);

        return view('patient.lab-results.index', compact('labResults'));
    }

    /**
     * Display the specified lab result (READ-ONLY).
     */
    public function show(LabResult $labResult)
    {
        // Ensure the lab result belongs to the authenticated patient
        if ($labResult->patient_id !== auth()->user()->patient->id) {
            abort(403, 'You do not have permission to view this lab result.');
        }

        return view('patient.lab-results.show', compact('labResult'));
    }
}
