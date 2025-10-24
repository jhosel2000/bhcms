<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientRisk;
use App\Services\PatientRiskService;
use App\Http\Requests\StorePatientRiskRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class PatientRisksController extends Controller
{
    protected PatientRiskService $patientRiskService;

    public function __construct(PatientRiskService $patientRiskService)
    {
        $this->patientRiskService = $patientRiskService;
        $this->middleware('auth');
        $this->middleware('can:viewAny,App\Models\PatientRisk');
    }

    /**
     * Display a listing of patient risks for a patient
     */
    public function index(Request $request, Patient $patient): View
    {
        $filters = $request->only(['search', 'status', 'severity', 'risk_type', 'alert_level', 'date_from', 'date_to']);
        $patientRisks = $this->patientRiskService->getPatientRiskAssessments($patient, $filters);
        $statistics = $this->patientRiskService->getPatientRiskStatistics($patient);

        return view('doctor.patients.risks', compact('patient', 'patientRisks', 'statistics', 'filters'));
    }

    /**
     * Show the form for creating a new patient risk
     */
    public function create(Patient $patient): View
    {
        $this->authorize('create', PatientRisk::class);

        $riskTypes = $this->patientRiskService->getRiskTypes();
        $severities = $this->patientRiskService->getSeverityOptions();
        $statuses = $this->patientRiskService->getStatusOptions();

        return view('doctor.patients.risks.create', compact('patient', 'riskTypes', 'severities', 'statuses'));
    }

    /**
     * Store a newly created patient risk
     */
    public function store(Request $request, Patient $patient): RedirectResponse|JsonResponse
    {
        $this->authorize('create', PatientRisk::class);

        try {
            $validatedData = $request->validate([
                'risk_type' => 'required|string',
                'title' => 'required|string|max:255',
                'severity' => 'required|in:low,medium,high,critical',
                'status' => 'nullable|in:active,monitoring,resolved',
                'identified_date' => 'required|date',
                'review_date' => 'nullable|date|after:identified_date',
                'description' => 'required|string',
                'management_plan' => 'nullable|string',
                'notes' => 'nullable|string',
                'requires_alert' => 'nullable|boolean',
                'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240'
            ]);

            $patientRisk = $this->patientRiskService->create($validatedData, $patient);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Patient risk assessment created successfully.',
                    'data' => $patientRisk
                ], 201);
            }

            return redirect()
                ->route('doctor.patient.risks.index', $patient)
                ->with('success', 'Patient risk assessment created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create patient risk assessment. Please try again.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create patient risk assessment. Please try again.');
        }
    }

    /**
     * Display the specified patient risk
     */
    public function show(Request $request, Patient $patient, PatientRisk $patientRisk): View|JsonResponse
    {
        $this->authorize('view', $patientRisk);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $patientRisk->load('patient')
            ]);
        }

        return view('doctor.patients.risks.show', compact('patient', 'patientRisk'));
    }

    /**
     * Show the form for editing the specified patient risk
     */
    public function edit(Patient $patient, PatientRisk $patientRisk): View
    {
        $this->authorize('update', $patientRisk);

        $riskTypes = $this->patientRiskService->getRiskTypes();
        $severities = $this->patientRiskService->getSeverityOptions();
        $statuses = $this->patientRiskService->getStatusOptions();

        return view('doctor.patients.risks.edit', compact('patient', 'patientRisk', 'riskTypes', 'severities', 'statuses'));
    }

    /**
     * Update the specified patient risk
     */
    public function update(Request $request, Patient $patient, PatientRisk $patientRisk): RedirectResponse|JsonResponse
    {
        $this->authorize('update', $patientRisk);

        try {
            $validatedData = $request->validate([
                'risk_type' => 'required|string',
                'title' => 'required|string|max:255',
                'severity' => 'required|in:low,medium,high,critical',
                'status' => 'nullable|in:active,monitoring,resolved',
                'identified_date' => 'required|date',
                'review_date' => 'nullable|date|after:identified_date',
                'description' => 'required|string',
                'management_plan' => 'nullable|string',
                'notes' => 'nullable|string',
                'requires_alert' => 'nullable|boolean',
                'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240'
            ]);

            $this->patientRiskService->update($patientRisk, $validatedData);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Patient risk assessment updated successfully.',
                    'data' => $patientRisk
                ]);
            }

            return redirect()
                ->route('doctor.patient.risks.index', $patient)
                ->with('success', 'Patient risk assessment updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update patient risk assessment. Please try again.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update patient risk assessment. Please try again.');
        }
    }

    /**
     * Remove the specified patient risk
     */
    public function destroy(Request $request, Patient $patient, PatientRisk $patientRisk): RedirectResponse|JsonResponse
    {
        Log::info('Destroy method called', [
            'patient_id' => $patient->id,
            'patientRisk_id' => $patientRisk->id,
            'patientRisk_patient_id' => $patientRisk->patient_id,
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role ?? 'no role',
        ]);

        $patientRisk->load('patient.medicalRecords');
        $this->authorize('delete', $patientRisk);

        try {
            $this->patientRiskService->delete($patientRisk);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Patient risk assessment deleted successfully'
                ]);
            }

            // Redirect back to the same page (risks index) without changing URL
            return redirect()->back()->with('success', 'Patient risk assessment deleted successfully.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete patient risk assessment',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Failed to delete patient risk assessment. Please try again.');
        }
    }

    /**
     * Get critical patient risks for a patient (API endpoint)
     */
    public function getCritical(Patient $patient): JsonResponse
    {
        $criticalRisks = $this->patientRiskService->getCriticalRisks($patient);

        return response()->json([
            'success' => true,
            'data' => $criticalRisks,
            'count' => $criticalRisks->count()
        ]);
    }

    /**
     * Get patient risk statistics for a patient (API endpoint)
     */
    public function getStatistics(Patient $patient): JsonResponse
    {
        $statistics = $this->patientRiskService->getPatientRiskStatistics($patient);

        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }

    /**
     * Assess overall patient risk level (API endpoint)
     */
    public function assessOverallRisk(Patient $patient): JsonResponse
    {
        $assessment = $this->patientRiskService->assessOverallRisk($patient);

        return response()->json([
            'success' => true,
            'data' => $assessment
        ]);
    }

    /**
     * Get patient risks by severity (API endpoint)
     */
    public function getBySeverity(Patient $patient, string $severity): JsonResponse
    {
        $risks = $this->patientRiskService->getRisksBySeverity($patient, $severity);

        return response()->json([
            'success' => true,
            'data' => $risks,
            'count' => $risks->count()
        ]);
    }

    /**
     * Get alert risks for a patient (API endpoint)
     */
    public function getAlertRisks(Patient $patient): JsonResponse
    {
        $alertRisks = $this->patientRiskService->getAlertRisks($patient);

        return response()->json([
            'success' => true,
            'data' => $alertRisks,
            'count' => $alertRisks->count()
        ]);
    }

    /**
     * Toggle risk status (API endpoint)
     */
    public function toggleStatus(Request $request, Patient $patient, PatientRisk $patientRisk): JsonResponse
    {
        $this->authorize('update', $patientRisk);

        try {
            $newStatus = $request->input('status');
            $this->patientRiskService->updateStatus($patientRisk, $newStatus);

            return response()->json([
                'success' => true,
                'message' => 'Risk status updated successfully.',
                'data' => $patientRisk
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update risk status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
