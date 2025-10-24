<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;

class PatientPrescriptionsController extends Controller
{
    public function index()
    {
        $patientId = auth()->user()->patient->id;
        $prescriptions = Prescription::where('patient_id', $patientId)->with('doctor')->paginate(10);

        return view('patient.prescriptions.index', compact('prescriptions'));
    }

    public function show(Prescription $prescription)
    {
        if ($prescription->patient_id !== auth()->user()->patient->id) {
            abort(403);
        }

        return view('patient.prescriptions.show', compact('prescription'));
    }
}
