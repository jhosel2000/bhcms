<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\LabResult;
use App\Services\LabResultService;
use App\Http\Requests\StoreLabResultRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LabResultsController extends Controller
{
    protected LabResultService $labResultService;

    public function __construct(LabResultService $labResultService)
    {
        $this->labResultService = $labResultService;
        $this->middleware('auth');
        $this->middleware('can:viewAny,App\Models\LabResult');
    }

    /**
     * Display a listing of lab results for a patient
     */
    public function index(Request $request, Patient $patient): View
    {
        $filters = $request->only(['search', 'status', 'category', 'date_from', 'date_to']);
        $labResults = $this->labResultService->getPatientLabResults($patient, $filters);
        $statistics = $this->labResultService->getLabResultStatistics($patient);

        return view('doctor.patients.lab-results', compact('patient', 'labResults', 'statistics', 'filters'));
    }

    /**
     * Show the form for creating a new lab result
     */
    public function create(Patient $patient): View
    {
        $this->authorize('create', LabResult::class);

        $categories = $this->labResultService->getTestCategories();
        $statuses = $this->labResultService->getStatusOptions();

        return view('doctor.patients.lab-results.create', compact('patient', 'categories', 'statuses'));
    }

    /**
     * Store a newly created lab result
     */
    public function store(StoreLabResultRequest $request, Patient $patient)
    {
        try {
            $labResult = $this->labResultService->create($request->validated(), $patient);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Lab result created successfully.',
                    'data' => $labResult
                ]);
            }

            return redirect()
                ->route('doctor.patient.lab-results.index', $patient)
                ->with('success', 'Lab result created successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create lab result. Please try again.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create lab result. Please try again.');
        }
    }

    /**
     * Display the specified lab result
     */
    public function show(Patient $patient, LabResult $labResult): View
    {
        $this->authorize('view', $labResult);

        return view('doctor.patients.lab-results.show', compact('patient', 'labResult'));
    }

    /**
     * Show the form for editing the specified lab result
     */
    public function edit(Patient $patient, LabResult $labResult): View
    {
        $this->authorize('update', $labResult);

        $categories = $this->labResultService->getTestCategories();
        $statuses = $this->labResultService->getStatusOptions();

        return view('doctor.patients.lab-results.edit', compact('patient', 'labResult', 'categories', 'statuses'));
    }

    /**
     * Update the specified lab result
     */
    public function update(StoreLabResultRequest $request, Patient $patient, LabResult $labResult)
    {
        $this->authorize('update', $labResult);

        try {
            $updatedLabResult = $this->labResultService->update($labResult, $request->validated());

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Lab result updated successfully.',
                    'data' => $updatedLabResult
                ]);
            }

            return redirect()
                ->route('doctor.patient.lab-results.index', $patient)
                ->with('success', 'Lab result updated successfully.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update lab result. Please try again.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update lab result. Please try again.');
        }
    }

    /**
     * Remove the specified lab result
     */
    public function destroy(Patient $patient, LabResult $labResult)
    {
        $this->authorize('delete', $labResult);

        try {
            $this->labResultService->delete($labResult);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Lab result deleted successfully.'
                ]);
            }

            return redirect()
                ->route('doctor.patient.lab-results.index', $patient)
                ->with('success', 'Lab result deleted successfully.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete lab result. Please try again.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Failed to delete lab result. Please try again.');
        }
    }

    /**
     * Get critical lab results for a patient (API endpoint)
     */
    public function getCritical(Patient $patient): JsonResponse
    {
        $criticalResults = $this->labResultService->getCriticalResults($patient);

        return response()->json([
            'success' => true,
            'data' => $criticalResults,
            'count' => $criticalResults->count()
        ]);
    }

    /**
     * Get lab result statistics for a patient (API endpoint)
     */
    public function getStatistics(Patient $patient): JsonResponse
    {
        $statistics = $this->labResultService->getLabResultStatistics($patient);

        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }
}
