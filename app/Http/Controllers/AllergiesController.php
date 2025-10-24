<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Allergy;
use App\Services\AllergyService;
use App\Http\Requests\StoreAllergyRequest;
use App\Http\Requests\UpdateAllergyRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AllergiesController extends Controller
{
    protected AllergyService $allergyService;

    public function __construct(AllergyService $allergyService)
    {
        $this->allergyService = $allergyService;
        $this->middleware('auth');
    }

    /**
     * Display a listing of allergies for a patient
     */
    public function index(Request $request, Patient $patient): View|JsonResponse
    {
        $this->authorize('viewAny', Allergy::class);

        $search = $request->input('search');
        $severity = $request->input('severity');
        $status = $request->input('status');
        $type = $request->input('type');

        $query = $patient->allergies();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('allergen_name', 'like', "%{$search}%")
                  ->orWhere('reaction_description', 'like', "%{$search}%");
            });
        }

        if ($severity) {
            $query->where('severity', $severity);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($type) {
            $query->where('allergen_type', $type);
        }

        $allergies = $query->orderBy('created_at', 'desc')->paginate(15);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $allergies,
                'statistics' => $this->allergyService->getAllergyStatistics($patient)
            ]);
        }

        return view('doctor.allergies.index', compact('allergies', 'patient', 'search', 'severity', 'status', 'type'));
    }

    /**
     * Show the form for creating a new allergy
     */
    public function create(Patient $patient): View
    {
        $this->authorize('create', Allergy::class);

        $allergenTypes = $this->allergyService->getAllergenTypes();
        $severities = $this->allergyService->getSeverityLevels();

        return view('doctor.allergies.create', compact('patient', 'allergenTypes', 'severities'));
    }

    /**
     * Store a newly created allergy
     */
    public function store(StoreAllergyRequest $request, Patient $patient): RedirectResponse|JsonResponse
    {
        try {
            $allergy = $this->allergyService->create($request->validated(), $patient);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Allergy record created successfully',
                    'data' => $allergy
                ], 201);
            }

            return redirect()->route('doctor.allergies.show', [$patient, $allergy])
                           ->with('success', 'Allergy record created successfully');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create allergy record',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Failed to create allergy record');
        }
    }

    /**
     * Display the specified allergy
     */
    public function show(Request $request, Patient $patient, Allergy $allergy): View|JsonResponse
    {
        $this->authorize('view', $allergy);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $allergy
            ]);
        }

        return view('doctor.allergies.show', compact('patient', 'allergy'));
    }

    /**
     * Show the form for editing the specified allergy
     */
    public function edit(Patient $patient, Allergy $allergy): View
    {
        $this->authorize('update', $allergy);

        $allergenTypes = $this->allergyService->getAllergenTypes();
        $severities = $this->allergyService->getSeverityLevels();

        return view('doctor.allergies.edit', compact('patient', 'allergy', 'allergenTypes', 'severities'));
    }

    /**
     * Update the specified allergy
     */
    public function update(UpdateAllergyRequest $request, Patient $patient, Allergy $allergy): RedirectResponse|JsonResponse
    {
        try {
            $updatedAllergy = $this->allergyService->update($allergy, $request->validated());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Allergy record updated successfully',
                    'data' => $updatedAllergy
                ]);
            }

            return redirect()->route('doctor.allergies.show', [$patient, $updatedAllergy])
                           ->with('success', 'Allergy record updated successfully');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update allergy record',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withInput()->with('error', 'Failed to update allergy record');
        }
    }

    /**
     * Remove the specified allergy
     */
    public function destroy(Request $request, Patient $patient, Allergy $allergy): RedirectResponse|JsonResponse
    {
        try {
            $this->authorize('delete', $allergy);

            $this->allergyService->delete($allergy);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Allergy record deleted successfully'
                ]);
            }

            return redirect()->route('doctor.allergies.index', $patient)
                           ->with('success', 'Allergy record deleted successfully');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete allergy record',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to delete allergy record');
        }
    }

    /**
     * Get allergies by severity
     */
    public function getBySeverity(Request $request, Patient $patient, string $severity): JsonResponse
    {
        $allergies = $this->allergyService->getBySeverity($patient, $severity);

        return response()->json([
            'success' => true,
            'data' => $allergies
        ]);
    }

    /**
     * Get critical allergies
     */
    public function getCritical(Patient $patient): JsonResponse
    {
        $allergies = $this->allergyService->getCriticalAllergies($patient);

        return response()->json([
            'success' => true,
            'data' => $allergies,
            'count' => $allergies->count()
        ]);
    }

    /**
     * Get allergy statistics
     */
    public function getStatistics(Patient $patient): JsonResponse
    {
        $statistics = $this->allergyService->getAllergyStatistics($patient);

        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }

    /**
     * Toggle allergy status
     */
    public function toggleStatus(Request $request, Patient $patient, Allergy $allergy): JsonResponse
    {
        try {
            $this->authorize('update', $allergy);

            $updatedAllergy = $this->allergyService->toggleStatus($allergy);

            return response()->json([
                'success' => true,
                'message' => 'Allergy status updated successfully',
                'data' => $updatedAllergy
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update allergy status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
