<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorPrescriptionsController extends Controller
{
    /**
     * Display a listing of prescriptions with filtering and search
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $search = $request->input('search');

        // Get filtered prescriptions
        $prescriptions = $this->getFilteredPrescriptions($status, $search);

        // Handle AJAX requests
        if ($request->ajax()) {
            $html = view('doctor.partials.prescription-list', [
                'prescriptions' => $prescriptions,
                'status' => $status
            ])->render();

            $pagination = $prescriptions->onEachSide(1)->links()->toHtml();

            return response()->json([
                'success' => true,
                'html' => $html,
                'pagination' => $pagination,
            ]);
        }

        // Get statistics
        $stats = $this->getPrescriptionStatistics();

        return view('doctor.prescriptions', compact(
            'prescriptions',
            'status',
            'search',
            'stats'
        ));
    }

    /**
     * Get filtered prescriptions based on status and search
     */
    private function getFilteredPrescriptions($status, $search)
    {
        $doctorId = auth()->user()->doctor->id;

        $query = Prescription::forDoctor($doctorId)
            ->with('patient')
            ->recent();

        // Apply status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Apply search filter
        if ($search) {
            $query->search($search);
        }

        return $query->paginate(12)->withQueryString();
    }

    /**
     * Get prescription statistics for the dashboard
     */
    private function getPrescriptionStatistics()
    {
        $doctorId = auth()->user()->doctor->id;

        return [
            'total' => Prescription::forDoctor($doctorId)->count(),
            'active' => Prescription::forDoctor($doctorId)->active()->count(),
            'completed' => Prescription::forDoctor($doctorId)->completed()->count(),
            'pending_refill' => Prescription::forDoctor($doctorId)->pendingRefill()->count(),
            'this_month' => Prescription::forDoctor($doctorId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_week' => Prescription::forDoctor($doctorId)
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'most_common' => Prescription::forDoctor($doctorId)
                ->select('medication_name', DB::raw('COUNT(*) as count'))
                ->groupBy('medication_name')
                ->orderByDesc('count')
                ->first(),
            'recent_patients' => Prescription::forDoctor($doctorId)
                ->with('patient')
                ->recent()
                ->take(5)
                ->get()
                ->pluck('patient')
                ->unique('id')
                ->take(3),
        ];
    }

    /**
     * Show the form for creating a new prescription
     */
    public function create()
    {
        $patients = Patient::orderBy('full_name')->get();
        return view('doctor.prescriptions.create', compact('patients'));
    }

    /**
     * Store a newly created prescription
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medication_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'status' => 'required|string|in:active,completed,pending_refill',
        ]);

        $validated['doctor_id'] = auth()->user()->doctor->id;

        $prescription = Prescription::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Prescription created successfully.',
                'prescription' => $prescription->load('patient'),
            ]);
        }

        return redirect()
            ->route('doctor.prescriptions.index')
            ->with('success', 'Prescription created successfully.');
    }

    /**
     * Display the specified prescription
     */
    public function show(Prescription $prescription)
    {
        $this->authorize('view', $prescription);

        $prescription->load('patient', 'doctor');

        return view('doctor.prescriptions.show', compact('prescription'));
    }

    /**
     * Show the form for editing the specified prescription
     */
    public function edit(Prescription $prescription)
    {
        $this->authorize('update', $prescription);

        $patients = Patient::orderBy('full_name')->get();

        return view('doctor.prescriptions.edit', compact('prescription', 'patients'));
    }

    /**
     * Update the specified prescription
     */
    public function update(Request $request, Prescription $prescription)
    {
        $this->authorize('update', $prescription);

        $validated = $request->validate([
            'medication_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'status' => 'required|string|in:active,completed,pending_refill',
        ]);

        $prescription->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Prescription updated successfully.',
                'prescription' => $prescription->load('patient'),
            ]);
        }

        return redirect()
            ->route('doctor.prescriptions.index')
            ->with('success', 'Prescription updated successfully.');
    }

    /**
     * Remove the specified prescription
     */
    public function destroy(Prescription $prescription)
    {
        $this->authorize('delete', $prescription);

        $prescription->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Prescription deleted successfully.',
            ]);
        }

        return redirect()
            ->route('doctor.prescriptions.index')
            ->with('success', 'Prescription deleted successfully.');
    }

    /**
     * Update prescription status (Quick action)
     */
    public function updateStatus(Request $request, Prescription $prescription)
    {
        $this->authorize('update', $prescription);

        $validated = $request->validate([
            'status' => 'required|string|in:active,completed,pending_refill',
        ]);

        $prescription->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Prescription status updated successfully.',
            'prescription' => $prescription->load('patient'),
        ]);
    }

    /**
     * Print prescription
     */
    public function print(Prescription $prescription)
    {
        $this->authorize('view', $prescription);

        $prescription->load('patient', 'doctor.user');

        return view('doctor.prescriptions.print', compact('prescription'));
    }
}
