<?php

namespace App\Http\Controllers;

use App\Models\EhrRecord;
use App\Models\Patient;
use App\Models\Appointment;
use App\Services\EhrService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class DoctorEhrController extends Controller
{
    protected EhrService $ehrService;

    public function __construct(EhrService $ehrService)
    {
        $this->ehrService = $ehrService;
    }

    /**
     * Display a listing of patients with EHR summary and pending records.
     */
    public function index(Request $request)
    {
        try {
            $doctorId = auth()->user()->doctor->id;

            // Get pending EHR records for review (from midwives/BHWs) - Optimized with eager loading
            $pendingRecords = EhrRecord::where('status', EhrRecord::STATUS_PENDING_REVIEW)
                ->whereIn('created_by_role', ['midwife', 'bhw'])
                ->whereHas('patient.appointments', function ($query) use ($doctorId) {
                    $query->where('doctor_id', $doctorId);
                })
                ->with(['patient:id,full_name', 'creator:id,name', 'appointment:id,appointment_date'])
                ->orderBy('created_at', 'asc')
                ->paginate(10, ['*'], 'pending_page');

            // Get patients who have appointments with this doctor - Optimized query
            $query = Patient::whereHas('appointments', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            })
            ->with([
                'ehrRecords' => function ($query) {
                    $query->select('id', 'patient_id', 'record_type', 'created_at', 'status')
                          ->latest()
                          ->take(5); // Recent 5 records for summary
                },
                'appointments' => function ($query) use ($doctorId) {
                    $query->select('id', 'patient_id', 'doctor_id', 'appointment_date', 'status')
                          ->where('doctor_id', $doctorId)
                          ->where('status', 'completed')
                          ->orderByDesc('appointment_date')
                          ->orderByDesc('created_at')
                          ->take(10);
                },
            ])
            ->withCount(['ehrRecords as total_records'])
            ->select('id', 'full_name', 'created_at', 'updated_at');

            // Search functionality
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                      ->orWhere('id', 'like', "%{$search}%");
                });
            }

            // Status filter for pending items first
            if ($request->has('status') && in_array($request->status, [
                EhrRecord::STATUS_PENDING_REVIEW,
                EhrRecord::STATUS_APPROVED,
                EhrRecord::STATUS_FLAGGED,
                'needs_action'
            ], true)) {
                if ($request->status === 'needs_action') {
                    $query->whereHas('ehrRecords', function ($subQuery) {
                        $subQuery->where('status', EhrRecord::STATUS_PENDING_REVIEW);
                    });
                } else {
                    $query->whereHas('ehrRecords', function ($subQuery) use ($request) {
                        $subQuery->where('status', $request->status);
                    });
                }
            }

            // Optimized sorting - avoid N+1 queries
            $sortBy = $request->get('sort', 'name');
            $sortDirection = $request->get('direction', 'asc');

            switch ($sortBy) {
                case 'name':
                    $query->orderBy('full_name', $sortDirection);
                    break;
                case 'last_updated':
                    // Use subquery for better performance instead of leftJoin
                    $query->leftJoinSub(
                        EhrRecord::select('patient_id', EhrRecord::raw('MAX(created_at) as latest_record_date'))
                                ->groupBy('patient_id'),
                        'latest_records',
                        'patients.id',
                        '=',
                        'latest_records.patient_id'
                    )
                    ->orderBy('latest_records.latest_record_date', $sortDirection)
                    ->select('patients.*');
                    break;
                case 'records':
                    $query->orderBy('total_records', $sortDirection);
                    break;
                default:
                    $query->orderBy('full_name', 'asc');
            }

            $patients = $query->paginate(20)->appends($request->query());

            // Get analytics data for dashboard
            $analytics = $this->ehrService->getDoctorAnalytics($doctorId);

            Log::info('Doctor EHR index accessed', [
                'doctor_id' => $doctorId,
                'pending_records_count' => $pendingRecords->total(),
                'patients_count' => $patients->total()
            ]);

            return view('doctor.ehr.index', compact('patients', 'pendingRecords', 'analytics'));

        } catch (\Exception $e) {
            Log::error('Error in DoctorEhrController index', [
                'error' => $e->getMessage(),
                'doctor_id' => auth()->user()->doctor->id ?? null
            ]);

            return back()->with('error', 'Unable to load EHR records. Please try again.');
        }
    }

    /**
     * Display the EHR timeline for a specific patient.
     */
    public function show(Request $request, Patient $patient)
    {
        $doctorId = auth()->user()->doctor->id;

        // Ensure doctor has access to this patient
        $hasAccess = Appointment::where('patient_id', $patient->id)
            ->where('doctor_id', $doctorId)
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

        return view('doctor.ehr.show', compact('patient', 'ehrRecords', 'recordTypeCounts', 'filters'));
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

        // Only allow editing of doctor-created records
        if ($ehrRecord->created_by_role !== 'doctor') {
            abort(403, 'You can only edit records you created.');
        }

        // Only allow editing by the creator
        if ($ehrRecord->created_by !== auth()->id()) {
            abort(403, 'You can only edit your own records.');
        }

        return view('doctor.ehr.edit', compact('patient', 'ehrRecord'));
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

        // Only allow editing of doctor-created records
        if ($ehrRecord->created_by_role !== 'doctor') {
            abort(403, 'You can only edit records you created.');
        }

        // Only allow editing by the creator
        if ($ehrRecord->created_by !== auth()->id()) {
            abort(403, 'You can only edit your own records.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        $data = $request->only(['title', 'description', 'notes']);
        $data['attachments'] = $request->file('attachments', []);

        $this->ehrService->updateRecord($ehrRecord, $data);

        return redirect()->route('doctor.ehr.show', $patient)->with('success', 'EHR record updated successfully.');
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

        // Only allow deletion of doctor-created records
        if ($ehrRecord->created_by_role !== 'doctor') {
            abort(403, 'You can only delete records you created.');
        }

        // Only allow deletion by the creator
        if ($ehrRecord->created_by !== auth()->id()) {
            abort(403, 'You can only delete your own records.');
        }

        $this->ehrService->deleteRecord($ehrRecord);

        return redirect()->route('doctor.ehr.show', $patient)->with('success', 'EHR record deleted successfully.');
    }

    /**
     * Review and approve a pending EHR record.
     */
    public function approveRecord(Request $request, EhrRecord $ehrRecord)
    {
        $request->validate([
            'review_notes' => 'nullable|string|max:1000',
        ]);

        // Ensure the record is pending review and created by midwife/BHW
        if ($ehrRecord->status !== EhrRecord::STATUS_PENDING_REVIEW ||
            !in_array($ehrRecord->created_by_role, ['midwife', 'bhw'])) {
            abort(403, 'This record is not eligible for review.');
        }

        // Ensure doctor has access to this patient's records
        $doctorId = auth()->user()->doctor->id;
        $hasAccess = Appointment::where('patient_id', $ehrRecord->patient_id)
            ->where('doctor_id', $doctorId)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'You do not have access to review this record.');
        }

        $this->ehrService->reviewRecord($ehrRecord, auth()->user(), EhrRecord::STATUS_APPROVED, $request->review_notes);

        return redirect()->back()->with('success', 'EHR record approved successfully.');
    }

    /**
     * Flag a pending EHR record with notes.
     */
    public function flagRecord(Request $request, EhrRecord $ehrRecord)
    {
        $request->validate([
            'review_notes' => 'required|string|max:1000',
        ]);

        // Ensure the record is pending review and created by midwife/BHW
        if ($ehrRecord->status !== EhrRecord::STATUS_PENDING_REVIEW ||
            !in_array($ehrRecord->created_by_role, ['midwife', 'bhw'])) {
            abort(403, 'This record is not eligible for review.');
        }

        // Ensure doctor has access to this patient's records
        $doctorId = auth()->user()->doctor->id;
        $hasAccess = Appointment::where('patient_id', $ehrRecord->patient_id)
            ->where('doctor_id', $doctorId)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'You do not have access to review this record.');
        }

        $this->ehrService->reviewRecord($ehrRecord, auth()->user(), EhrRecord::STATUS_FLAGGED, $request->review_notes);

        return redirect()->back()->with('success', 'EHR record flagged for review.');
    }

    /**
     * Sync completed appointments to create EHR records.
     */
    public function syncCompletedAppointments()
    {
        $doctorId = auth()->user()->doctor->id;

        // Find completed appointments without EHR records
        $completedAppointments = Appointment::where('doctor_id', $doctorId)
            ->where('status', 'completed')
            ->whereDoesntHave('ehrRecord')
            ->get();

        $createdRecords = 0;
        foreach ($completedAppointments as $appointment) {
            $this->ehrService->syncAppointmentToEhr($appointment);
            $createdRecords++;
        }

        return redirect()->route('doctor.ehr.index')->with('success', "Synced {$createdRecords} completed appointments.");
    }

    public function downloadEhrPdf(Patient $patient)
    {
        $patient->load(
            'diagnoses.doctor',
            'referrals.doctor',
            'prescriptions.doctor',
            'allergies',
            'medications',
            'labResults'
        );

        $pdf = PDF::loadView('doctor.ehr.pdf', compact('patient'));

        return $pdf->download("ehr-summary-{$patient->id}.pdf");
    }
}
