<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Services\LabResultService;
use Illuminate\Http\Request;

class DoctorPatientsController extends Controller
{
    protected LabResultService $labResultService;

    public function __construct(LabResultService $labResultService)
    {
        $this->labResultService = $labResultService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $patients = Patient::query();

        if ($search) {
            $patients = $patients->where('full_name', 'like', '%' . $search . '%');
        }

        if ($request->ajax()) {
            $patients = $patients->get();
            return response()->json($patients);
        }

        $patients = $patients->paginate(10);

        return view('doctor.patients', compact('patients', 'search'));
    }

    public function create()
    {
        return view('doctor.patients.create');
    }

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

        return redirect()->route('doctor.patients.index')->with('success', 'Patient created successfully.');
    }

    public function show(Patient $patient)
    {
        $patient->load([
            'appointments' => function($query) {
                $query->orderBy('appointment_date', 'desc');
            },
            'allergies' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'medications' => function($query) {
                $query->orderBy('start_date', 'desc');
            },
            'labResults' => function($query) {
                $query->orderBy('test_date', 'desc');
            },
            // Removed clinicalNotes relation as feature is deleted
            //'clinicalNotes' => function($query) {
            //    $query->orderBy('created_at', 'desc');
            //},
            'risks' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'ehrRecords' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'medicalAttachments' => function($query) {
                $query->orderBy('created_at', 'desc');
            }
        ]);

        // Get lab results data for the included view
        $labResults = $this->labResultService->getPatientLabResults($patient, []);
        $labResultsStatistics = $this->labResultService->getLabResultStatistics($patient);
        $labResultsFilters = [];

        return view('doctor.patients.show', compact('patient', 'labResults', 'labResultsStatistics', 'labResultsFilters'));
    }

    public function edit(Patient $patient)
    {
        return view('doctor.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
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

        return redirect()->route('doctor.patients.index')->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('doctor.patients.index')->with('success', 'Patient deleted successfully.');
    }
}
