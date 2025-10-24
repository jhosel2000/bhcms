<?php

namespace App\Http\Controllers;

use App\Models\EhrRecord;
use App\Models\Patient;
use App\Models\Appointment;
use App\Services\EhrService;
use App\Mail\EhrUpdateNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class MidwifeEhrController extends Controller
{
    protected EhrService $ehrService;

    public function __construct(EhrService $ehrService)
    {
        $this->ehrService = $ehrService;
    }

    /**
     * Display a listing of patients with EHR summary.
     */
    public function index(Request $request)
    {
        $midwifeId = auth()->user()->midwife->id;

        // Get patients who have appointments with this midwife
        $query = Patient::whereHas('appointments', function ($query) use ($midwifeId) {
            $query->where('midwife_id', $midwifeId);
        })
        ->with([
            'ehrRecords' => function ($query) {
                $query->latest()->take(5); // Recent 5 records for summary
            },
            'appointments' => function ($query) use ($midwifeId) {
                $query->where('midwife_id', $midwifeId)
                      ->where('status', 'completed')
                      ->orderByDesc('appointment_date')
                      ->orderByDesc('created_at')
                      ->take(10);
            },
        ])
        ->withCount(['ehrRecords as total_records']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');

        switch ($sortBy) {
            case 'name':
                $query->orderBy('full_name', $sortDirection);
                break;
            case 'last_updated':
                $query->leftJoin('ehr_records', 'patients.id', '=', 'ehr_records.patient_id')
                      ->orderBy('ehr_records.created_at', $sortDirection)
                      ->select('patients.*');
                break;
            case 'records':
                $query->orderBy('total_records', $sortDirection);
                break;
            default:
                $query->orderBy('full_name', 'asc');
        }

        $patients = $query->paginate(20)->appends($request->query());

        return view('midwife.ehr.index', compact('patients'));
    }

    /**
     * Display the EHR timeline for a specific patient.
     */
    public function show(Request $request, Patient $patient)
    {
        $midwifeId = auth()->user()->midwife->id;

        // Ensure midwife has access to this patient
        $hasAccess = Appointment::where('patient_id', $patient->id)
            ->where('midwife_id', $midwifeId)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'You do not have access to this patient\'s records.');
        }

        $filters = $request->only(['type', 'start_date', 'end_date', 'search']);
        $ehrRecords = $this->ehrService->getPatientRecords($patient, $filters);

        // Get record type counts for filter tabs
        $recordTypeCounts = $patient->ehrRecords()
            ->selectRaw('record_type, COUNT(*) as count')
            ->groupBy('record_type')
            ->pluck('count', 'record_type')
            ->toArray();

        return view('midwife.ehr.show', compact('patient', 'ehrRecords', 'recordTypeCounts', 'filters'));
    }

    /**
     * Show the form for creating a new EHR record.
     */
    public function create(Patient $patient)
    {
        $midwifeId = auth()->user()->midwife->id;

        // Ensure midwife has access to this patient
        $hasAccess = Appointment::where('patient_id', $patient->id)
            ->where('midwife_id', $midwifeId)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'You do not have access to this patient\'s records.');
        }

        // Get recent appointments for linking
        $appointments = Appointment::where('patient_id', $patient->id)
            ->where('midwife_id', $midwifeId)
            ->where('status', 'completed')
            ->orderByDesc('appointment_date')
            ->take(10)
            ->get();

        return view('midwife.ehr.create', compact('patient', 'appointments'));
    }

    /**
     * Store a newly created EHR record.
     */
    public function store(Request $request, Patient $patient)
    {
        $midwifeId = auth()->user()->midwife->id;

        // Ensure midwife has access to this patient
        $hasAccess = Appointment::where('patient_id', $patient->id)
            ->where('midwife_id', $midwifeId)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'You do not have access to this patient\'s records.');
        }

        $request->validate([
            'record_type' => ['required', Rule::in([
                'vital_signs', 'observation', 'referral', 'maternal_care',
                'child_health', 'vaccination', 'other'
            ])],
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'appointment_id' => 'nullable|exists:appointments,id',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        $data = $request->only(['record_type', 'title', 'content', 'appointment_id']);
        $data['attachments'] = $request->file('attachments', []);

        $record = $this->ehrService->createRecord($data, $patient, auth()->user());

        // Send notification to patient
        try {
            Mail::to($patient->email)->send(new EhrUpdateNotification($record));
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send EHR update notification: ' . $e->getMessage());
        }

        return redirect()->route('midwife.ehr.show', $patient)->with('success', 'EHR record submitted for review.');
    }

    /**
     * Show the form for editing an EHR record.
     */
    public function edit(Patient $patient, EhrRecord $ehrRecord)
    {
        // Ensure the record belongs to the patient
        if ($ehrRecord->patient_id !== $patient->id) {
            abort(404);
        }

        // Only allow editing of midwife-created records
        if ($ehrRecord->created_by_role !== 'midwife') {
            abort(403, 'You can only edit records you created.');
        }

        // Only allow editing by the creator
        if ($ehrRecord->created_by !== auth()->id()) {
            abort(403, 'You can only edit your own records.');
        }

        return view('midwife.ehr.edit', compact('patient', 'ehrRecord'));
    }

    /**
     * Update the specified EHR record.
     */
    public function update(Request $request, Patient $patient, EhrRecord $ehrRecord)
    {
        // Ensure the record belongs to the patient
        if ($ehrRecord->patient_id !== $patient->id) {
            abort(404);
        }

        // Only allow editing of midwife-created records
        if ($ehrRecord->created_by_role !== 'midwife') {
            abort(403, 'You can only edit records you created.');
        }

        // Only allow editing by the creator
        if ($ehrRecord->created_by !== auth()->id()) {
            abort(403, 'You can only edit your own records.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        $data = $request->only(['title', 'content']);
        $data['attachments'] = $request->file('attachments', []);

        // If record was previously reviewed, reset to pending on update (re-submission)
        if (in_array($ehrRecord->status, [EhrRecord::STATUS_APPROVED, EhrRecord::STATUS_FLAGGED])) {
            $data['status'] = EhrRecord::STATUS_PENDING_REVIEW;
            $data['reviewed_by'] = null;
            $data['review_notes'] = null;
            $data['reviewed_at'] = null;
        }

        $this->ehrService->updateRecord($ehrRecord, $data);

        $message = in_array($ehrRecord->status, [EhrRecord::STATUS_APPROVED, EhrRecord::STATUS_FLAGGED])
            ? 'EHR record updated and re-submitted for review.'
            : 'EHR record updated successfully.';

        return redirect()->route('midwife.ehr.show', $patient)->with('success', $message);
    }

    /**
     * Remove the specified EHR record.
     */
    public function destroy(Patient $patient, EhrRecord $ehrRecord)
    {
        // Ensure the record belongs to the patient
        if ($ehrRecord->patient_id !== $patient->id) {
            abort(404);
        }

        // Only allow deletion of midwife-created records
        if ($ehrRecord->created_by_role !== 'midwife') {
            abort(403, 'You can only delete records you created.');
        }

        // Only allow deletion by the creator
        if ($ehrRecord->created_by !== auth()->id()) {
            abort(403, 'You can only delete your own records.');
        }

        $this->ehrService->deleteRecord($ehrRecord);

        return redirect()->route('midwife.ehr.show', $patient)->with('success', 'EHR record deleted successfully.');
    }
}
