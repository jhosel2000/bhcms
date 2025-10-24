<?php

namespace App\Http\Controllers;

use App\Models\MaternalCareRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientMaternalController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');
        $patient = Auth::user()->patient;

        $records = MaternalCareRecord::where('patient_id', $patient->id)
            ->when($type, function ($query) use ($type) {
                return $query->where('type', $type);
            })
            ->with('midwife')
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

        return view('patient.maternal.index', compact('records', 'type', 'typeLabels'));
    }

    public function show(MaternalCareRecord $maternalCareRecord)
    {
        $this->authorize('view', $maternalCareRecord);

        $typeLabels = [
            'prenatal' => 'Prenatal Visit',
            'postnatal' => 'Postnatal Visit',
            'maternal_health' => 'Maternal Health Visit',
            'vitals' => 'Vital Signs Record',
            'checkup' => 'Checkup',
            'followup' => 'Follow-up',
        ];

        return view('patient.maternal.show', compact('maternalCareRecord', 'typeLabels'));
    }
}
