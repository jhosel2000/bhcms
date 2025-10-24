<?php

namespace App\Http\Controllers;

use App\Models\EhrRecord;
use App\Services\EhrService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class PatientEhrController extends Controller
{
    protected EhrService $ehrService;

    public function __construct(EhrService $ehrService)
    {
        $this->ehrService = $ehrService;
    }

    /**
     * Display a listing of the patient's EHR records.
     */
    public function index(Request $request)
    {
        $patientId = auth()->user()->patient->id;

        $query = EhrRecord::where('patient_id', $patientId)
            ->with(['creator', 'appointment'])
            ->orderBy('created_at', 'desc');

        // Filter by record type if specified
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('record_type', $request->type);
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('record_type', 'like', "%{$search}%");
            });
        }

        $ehrRecords = $query->paginate(20)->appends($request->query());

        return view('patient.ehr.index', compact('ehrRecords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('patient.ehr.create');
    }

    /**
     * Store a newly uploaded file or personal note.
     */
    public function store(Request $request)
    {
        $request->validate([
            'record_type' => ['required', Rule::in(['file_upload', 'personal_note'])],
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        $patient = auth()->user()->patient;
        $data = $request->only(['record_type', 'title', 'content']);
        $data['attachments'] = $request->file('attachments', []);

        $this->ehrService->createRecord($data, $patient, auth()->user());

        return redirect()->route('patient.ehr.index')->with('success', 'EHR record added successfully.');
    }

    /**
     * Display the specified EHR record.
     */
    public function show(EhrRecord $ehrRecord)
    {
        // Ensure the record belongs to the authenticated patient
        if ($ehrRecord->patient_id !== auth()->user()->patient->id) {
            abort(403, 'You do not have permission to view this EHR record.');
        }

        return view('patient.ehr.show', compact('ehrRecord'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EhrRecord $ehrRecord)
    {
        // Ensure the record belongs to the authenticated patient
        if ($ehrRecord->patient_id !== auth()->user()->patient->id) {
            abort(403, 'You do not have permission to edit this EHR record.');
        }

        // Only allow editing of patient-created records
        if ($ehrRecord->created_by_role !== 'patient') {
            abort(403, 'You can only edit records you created.');
        }

        return view('patient.ehr.edit', compact('ehrRecord'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EhrRecord $ehrRecord)
    {
        // Ensure the record belongs to the authenticated patient
        if ($ehrRecord->patient_id !== auth()->user()->patient->id) {
            abort(403, 'You do not have permission to edit this EHR record.');
        }

        // Only allow editing of patient-created records
        if ($ehrRecord->created_by_role !== 'patient') {
            abort(403, 'You can only edit records you created.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        $data = $request->only(['title', 'content']);
        $data['attachments'] = $request->file('attachments', []);

        $this->ehrService->updateRecord($ehrRecord, $data);

        return redirect()->route('patient.ehr.index')->with('success', 'EHR record updated successfully.');
    }

    /**
     * Remove the specified EHR record (only patient-created records).
     */
    public function destroy(EhrRecord $ehrRecord)
    {
        // Ensure the record belongs to the authenticated patient
        if ($ehrRecord->patient_id !== auth()->user()->patient->id) {
            abort(403, 'You do not have permission to delete this EHR record.');
        }

        // Only allow deletion of patient-created records
        if ($ehrRecord->created_by_role !== 'patient') {
            return redirect()->route('patient.ehr.index')->with('error', 'You can only delete records you created.');
        }

        $this->ehrService->deleteRecord($ehrRecord);

        return redirect()->route('patient.ehr.index')->with('success', 'EHR record deleted successfully.');
    }

    /**
     * Download PDF of all patient EHR records
     */
    public function downloadPdf(Request $request)
    {
        $patient = auth()->user()->patient;
        $filters = $request->only(['type', 'start_date', 'end_date', 'search']);

        try {
            return $this->ehrService->generatePatientRecordsPdf($patient, $filters);
        } catch (\Exception $e) {
            Log::error('PDF generation failed', [
                'patient_id' => $patient->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('patient.ehr.index')->with('error', 'Failed to generate PDF. Please try again.');
        }
    }

    /**
     * Download PDF of single EHR record
     */
    public function downloadSinglePdf(EhrRecord $ehrRecord)
    {
        // Ensure the record belongs to the authenticated patient
        if ($ehrRecord->patient_id !== auth()->user()->patient->id) {
            abort(403, 'You do not have permission to access this EHR record.');
        }

        try {
            return $this->ehrService->generateSingleRecordPdf($ehrRecord);
        } catch (\Exception $e) {
            Log::error('Single PDF generation failed', [
                'record_id' => $ehrRecord->id,
                'patient_id' => $ehrRecord->patient_id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('patient.ehr.show', $ehrRecord)->with('error', 'Failed to generate PDF. Please try again.');
        }
    }
}
