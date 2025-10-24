<?php

namespace App\Http\Controllers;

use App\Models\MaternalCareRecord;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MidwifeMaternalController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', MaternalCareRecord::class);

        $type = $request->query('type');
        $records = MaternalCareRecord::when($type, function ($query) use ($type) {
                return $query->where('type', $type);
            })
            ->with('patient')
            ->orderBy('visit_date', 'desc')
            ->paginate(10);

        $typeLabels = [
            'prenatal' => 'Prenatal Visits',
            'postnatal' => 'Postnatal Visits',
            'maternal_health' => 'Maternal Health Visits',
            'vitals' => 'Vital Signs',
            'checkup' => 'Checkups',
            'followup' => 'Follow-ups',
        ];

        return view('midwife.maternal.index', compact('records', 'type', 'typeLabels'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', MaternalCareRecord::class);

        $type = $request->query('type');
        $patients = Patient::all();

        $typeLabels = [
            'prenatal' => 'Prenatal Visit',
            'postnatal' => 'Postnatal Visit',
            'maternal_health' => 'Maternal Health Visit',
            'vitals' => 'Vital Signs Record',
            'checkup' => 'Checkup',
            'followup' => 'Follow-up',
        ];

        return view('midwife.maternal.create', compact('type', 'patients', 'typeLabels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'type' => 'required|in:prenatal,postnatal,maternal_health,vitals,checkup,followup',
            'visit_date' => 'required|date',
            'visit_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string',
            'blood_pressure_systolic' => 'nullable|numeric|min:0|max:300',
            'blood_pressure_diastolic' => 'nullable|numeric|min:0|max:200',
            'weight' => 'nullable|numeric|min:0|max:500',
            'height' => 'nullable|numeric|min:0|max:300',
            'heart_rate' => 'nullable|integer|min:0|max:300',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'additional_findings' => 'nullable|string',
            'next_visit_date' => 'nullable|date|after:visit_date',
        ]);

        MaternalCareRecord::create([
            'patient_id' => $request->patient_id,
            'midwife_id' => Auth::user()->midwife->id,
            'type' => $request->type,
            'visit_date' => $request->visit_date,
            'visit_time' => $request->visit_time,
            'notes' => $request->notes,
            'blood_pressure_systolic' => $request->blood_pressure_systolic,
            'blood_pressure_diastolic' => $request->blood_pressure_diastolic,
            'weight' => $request->weight,
            'height' => $request->height,
            'heart_rate' => $request->heart_rate,
            'temperature' => $request->temperature,
            'additional_findings' => $request->additional_findings,
            'next_visit_date' => $request->next_visit_date,
        ]);

        return redirect()->route('midwife.maternal.index', ['type' => $request->type])
            ->with('success', 'Record created successfully.');
    }

    public function show(MaternalCareRecord $maternal)
    {
        $this->authorize('view', $maternal);

        $typeLabels = [
            'prenatal' => 'Prenatal Visit',
            'postnatal' => 'Postnatal Visit',
            'maternal_health' => 'Maternal Health Visit',
            'vitals' => 'Vital Signs Record',
            'checkup' => 'Checkup',
            'followup' => 'Follow-up',
        ];

        return view('midwife.maternal.show', compact('maternal', 'typeLabels'));
    }

    public function edit(MaternalCareRecord $maternal)
    {
        $this->authorize('update', $maternal);

        $patients = Patient::all();

        $typeLabels = [
            'prenatal' => 'Prenatal Visit',
            'postnatal' => 'Postnatal Visit',
            'maternal_health' => 'Maternal Health Visit',
            'vitals' => 'Vital Signs Record',
            'checkup' => 'Checkup',
            'followup' => 'Follow-up',
        ];

        return view('midwife.maternal.edit', compact('maternal', 'patients', 'typeLabels'));
    }

    public function update(Request $request, MaternalCareRecord $maternal)
    {
        $this->authorize('update', $maternal);

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'type' => 'required|in:prenatal,postnatal,maternal_health,vitals,checkup,followup',
            'visit_date' => 'required|date',
            'visit_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string',
            'blood_pressure_systolic' => 'nullable|numeric|min:0|max:300',
            'blood_pressure_diastolic' => 'nullable|numeric|min:0|max:200',
            'weight' => 'nullable|numeric|min:0|max:500',
            'height' => 'nullable|numeric|min:0|max:300',
            'heart_rate' => 'nullable|integer|min:0|max:300',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'additional_findings' => 'nullable|string',
            'next_visit_date' => 'nullable|date|after:visit_date',
        ]);

        $maternal->update($request->all());

        return redirect()->route('midwife.maternal.index', ['type' => $request->type])
            ->with('success', 'Record updated successfully.');
    }

    public function destroy(MaternalCareRecord $maternal)
    {
        $this->authorize('delete', $maternal);

        $type = $maternal->type;
        $maternal->delete();

        return redirect()->route('midwife.maternal.index', ['type' => $type])
            ->with('success', 'Record deleted successfully.');
    }
}
