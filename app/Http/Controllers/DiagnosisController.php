<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Diagnosis;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Patient $patient)
    {
        $diagnoses = $patient->diagnoses()->latest()->paginate(10);
        return view('doctor.patient.diagnoses.index', compact('patient', 'diagnoses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Patient $patient)
    {
        return view('doctor.patient.diagnoses.create', compact('patient'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Patient $patient)
    {
        $request->validate([
            'diagnosis' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $patient->diagnoses()->create([
            'doctor_id' => auth()->id(),
            'diagnosis' => $request->diagnosis,
            'notes' => $request->notes,
        ]);

        return redirect()->route('doctor.patient.diagnoses.index', $patient)
            ->with('success', 'Diagnosis added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient, Diagnosis $diagnosis)
    {
        return view('doctor.patient.diagnoses.show', compact('patient', 'diagnosis'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient, Diagnosis $diagnosis)
    {
        return view('doctor.patient.diagnoses.edit', compact('patient', 'diagnosis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient, Diagnosis $diagnosis)
    {
        $request->validate([
            'diagnosis' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $diagnosis->update([
            'diagnosis' => $request->diagnosis,
            'notes' => $request->notes,
        ]);

        return redirect()->route('doctor.patient.diagnoses.index', $patient)
            ->with('success', 'Diagnosis updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient, Diagnosis $diagnosis)
    {
        $diagnosis->delete();

        return redirect()->route('doctor.patient.diagnoses.index', $patient)
            ->with('success', 'Diagnosis deleted successfully.');
    }
}
