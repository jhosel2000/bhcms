<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;

class MidwifePatientsController extends Controller
{
    /**
     * Display a listing of patients under midwife care.
     */
    public function index()
    {
        // Fetch all patients with their appointments
        $patients = Patient::with('appointments')->get();
        return view('midwife.patients.index', compact('patients'));
    }

    /**
     * Display the specified patient.
     */
    public function show($id)
    {
        $patient = Patient::with('appointments')->findOrFail($id);
        return view('midwife.patients.show', compact('patient'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create()
    {
        return view('midwife.patients.create');
    }

    /**
     * Store a newly created patient in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'full_address' => 'required|string',
            'contact_number' => 'required|string|max:20',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_number' => 'required|string|max:20',
            'civil_status' => 'required|in:single,married,divorced,widowed',
            'occupation' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
        ]);

        Patient::create($validated);

        return redirect()->route('midwife.patients.index')->with('success', 'Patient created successfully.');
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        return view('midwife.patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'full_address' => 'required|string',
            'contact_number' => 'required|string|max:20',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_number' => 'required|string|max:20',
            'civil_status' => 'required|in:single,married,divorced,widowed',
            'occupation' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
        ]);

        $patient->update($validated);

        return redirect()->route('midwife.patients.index')->with('success', 'Patient updated successfully.');
    }

    /**
     * Remove the specified patient from storage.
     */
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();
        return redirect()->route('midwife.patients.index')->with('success', 'Patient deleted successfully.');
    }
}
