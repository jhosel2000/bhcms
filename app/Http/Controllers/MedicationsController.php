<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Medication;
use App\Services\MedicationService;
use App\Http\Requests\StoreMedicationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MedicationsController extends Controller
{
    protected MedicationService $medicationService;

    public function __construct(MedicationService $medicationService)
    {
        $this->medicationService = $medicationService;
        $this->middleware('auth');
    }

    /**
     * Display a listing of medications for a patient
     */
    public function index(Request $request, Patient $patient): View|JsonResponse
    {
        $this->authorize('viewAny', Medication::class);

        $search = $request->input('search');
        $status = $request->input('status');
        $route = $request->input('route');
        $frequency = $request->input('frequency');

        $query = $patient->medications();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('medication_name', 'like', "%{$search}%")
                  ->orWhere('indication', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($route) {
            $query->where('route', $route);
        }

        if ($frequency) {
            $query->where('frequency', $frequency);
        }

        $medications = $query->orderBy('created_at', 'desc')->paginate(15);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $medications,
                'statistics' => $this->medicationService->getMedicationStatistics($patient)
            ]);
        }

        return view('doctor.patients.medications', compact('patient'));
    }

    /**
     * Show the form for creating a new medication
     */
    public function create(Patient $patient): View
    {
        $this->authorize('create', Medication::class);

        $routes = $this->medicationService->getRoutes();
        $frequencies = $this->medicationService->getFrequencies();
        $statuses = $this->medicationService->getStatuses();

        return view('doctor.medications.create', compact('patient', 'routes', 'frequencies', 'statuses'));
    }

    /**
     * Store a newly created medication
     */
    public function store(StoreMedicationRequest $request, Patient $patient): RedirectResponse|JsonResponse
    {
        try {
            $medication = $this->medicationService->create($request->validated(), $patient);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Medication record created successfully',
                    'data' => $medication
                ], 201);
            }

            return redirect()->route('doctor.patient.medications.index', $patient)
                           ->with('success', 'Medication record created successfully');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create medication record',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Failed to create medication record');
        }
    }

    /**
     * Display the specified medication
     */
    public function show(Request $request, Patient $patient, Medication $medication): View|JsonResponse
    {
        $this->authorize('view', $medication);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $medication->load('patient')
            ]);
        }

        return view('doctor.medications.show', compact('patient', 'medication'));
    }

    /**
     * Show the form for editing the specified medication
     */
    public function edit(Patient $patient, Medication $medication): View
    {
        $this->authorize('update', $medication);

        $routes = $this->medicationService->getRoutes();
        $frequencies = $this->medicationService->getFrequencies();
        $statuses = $this->medicationService->getStatuses();

        return view('doctor.medications.edit', compact('patient', 'medication', 'routes', 'frequencies', 'statuses'));
    }

    /**
     * Update the specified medication
     */
    public function update(StoreMedicationRequest $request, Patient $patient, Medication $medication): RedirectResponse|JsonResponse
    {
        try {
            $updatedMedication = $this->medicationService->update($medication, $request->validated());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Medication record updated successfully',
                    'data' => $updatedMedication
                ]);
            }

            return redirect()->route('doctor.patient.medications.index', $patient)
                           ->with('success', 'Medication record updated successfully');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update medication record',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Failed to update medication record');
        }
    }

    /**
     * Remove the specified medication
     */
    public function destroy(Request $request, Patient $patient, Medication $medication)
    {
        $this->authorize('delete', $medication);

        try {
            $this->medicationService->delete($medication);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Medication record deleted successfully'
                ]);
            }

            return redirect()->route('doctor.patient.medications.index', $patient)
                           ->with('success', 'Medication record deleted successfully');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete medication record',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to delete medication record');
        }
    }

    /**
     * Get current medications
     */
    public function getCurrent(Patient $patient): JsonResponse
    {
        $medications = $this->medicationService->getCurrentMedications($patient);

        return response()->json([
            'success' => true,
            'data' => $medications,
            'count' => $medications->count()
        ]);
    }

    /**
     * Get medications by status
     */
    public function getByStatus(Request $request, Patient $patient, string $status): JsonResponse
    {
        $medications = $this->medicationService->getByStatus($patient, $status);

        return response()->json([
            'success' => true,
            'data' => $medications
        ]);
    }

    /**
     * Get medication statistics
     */
    public function getStatistics(Patient $patient): JsonResponse
    {
        $statistics = $this->medicationService->getMedicationStatistics($patient);

        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }

    /**
     * Check for drug interactions
     */
    public function checkInteractions(Request $request, Patient $patient): JsonResponse
    {
        $medicationNames = $request->input('medications', []);

        $interactions = $this->medicationService->checkDrugInteractions($patient, $medicationNames);

        return response()->json([
            'success' => true,
            'data' => $interactions
        ]);
    }

    /**
     * Get medications expiring soon
     */
    public function getExpiringSoon(Patient $patient, int $days = 30): JsonResponse
    {
        $medications = $this->medicationService->getExpiringMedications($patient, $days);

        return response()->json([
            'success' => true,
            'data' => $medications,
            'count' => $medications->count()
        ]);
    }

    /**
     * Toggle medication status
     */
    public function toggleStatus(Request $request, Patient $patient, Medication $medication): JsonResponse
    {
        try {
            $this->authorize('update', $medication);

            $updatedMedication = $this->medicationService->toggleStatus($medication);

            return response()->json([
                'success' => true,
                'message' => 'Medication status updated successfully',
                'data' => $updatedMedication
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update medication status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
