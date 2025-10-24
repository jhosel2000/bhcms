<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Announcement;
use App\Models\MaternalCareRecord;
use App\Services\AppointmentEmailService;
use App\Services\AppointmentConflictService;
use Illuminate\Support\Facades\DB;

class MidwifeAppointmentsController extends Controller
{
    /**
     * Display the midwife dashboard.
     */
    public function dashboard()
    {
        $midwifeId = auth()->user()->midwife->id;

        // Total patients for this midwife (distinct patients who have appointments with this midwife)
        $totalPatients = Appointment::where('midwife_id', $midwifeId)
            ->distinct('patient_id')
            ->count('patient_id');

        // Total appointments for this midwife
        $totalAppointments = Appointment::where('midwife_id', $midwifeId)->count();

        // Today's appointments for this midwife
        $todayAppointments = Appointment::whereDate('appointment_date', today())
            ->where('midwife_id', $midwifeId)
            ->with('patient')
            ->get();

        // Recent appointments for this midwife (last 5)
        $recentAppointments = Appointment::where('midwife_id', $midwifeId)
            ->with('patient')
            ->latest()
            ->take(5)
            ->get();

        // Upcoming appointments for this midwife (next 5)
        $upcomingAppointments = Appointment::where('appointment_date', '>', today())
            ->where('midwife_id', $midwifeId)
            ->with('patient')
            ->take(5)
            ->get();

        // Active maternal records (recent 30 days)
        $activeMaternalRecords = MaternalCareRecord::where('midwife_id', $midwifeId)
            ->where('visit_date', '>=', now()->subDays(30))
            ->count();

        // Monthly appointments
        $monthlyAppointments = Appointment::where('midwife_id', $midwifeId)
            ->whereMonth('appointment_date', now()->month)
            ->whereYear('appointment_date', now()->year)
            ->count();

        // Monthly maternal records
        $monthlyMaternalRecords = MaternalCareRecord::where('midwife_id', $midwifeId)
            ->whereMonth('visit_date', now()->month)
            ->whereYear('visit_date', now()->year)
            ->count();

        // New patients this month
        $newPatientsThisMonth = Appointment::where('midwife_id', $midwifeId)
            ->whereMonth('appointment_date', now()->month)
            ->whereYear('appointment_date', now()->year)
            ->distinct('patient_id')
            ->count('patient_id');

        // Patient visits this week
        $patientVisitsThisWeek = [];
        for ($i = 0; $i < 7; $i++) {
            $date = now()->startOfWeek()->addDays($i);
            $count = Appointment::where('midwife_id', $midwifeId)
                ->whereDate('appointment_date', $date)
                ->count();
            $patientVisitsThisWeek[] = $count;
        }

        // Common maternal types
        $commonTypes = MaternalCareRecord::where('midwife_id', $midwifeId)
            ->select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get()
            ->toArray();

        // Additional data for matching UI
        $todayActivities = $todayAppointments->count();
        $activeAnnouncements = Announcement::where('is_active', true)->count();

        $recentActivities = $recentAppointments->map(function ($appointment) {
            return (object) [
                'title' => 'Appointment with ' . ($appointment->patient->full_name ?? 'Patient'),
                'date' => $appointment->appointment_date,
                'status' => ucfirst($appointment->status ?? 'Scheduled'),
            ];
        });

        $upcomingActivities = $upcomingAppointments->map(function ($appointment) {
            return (object) [
                'title' => 'Appointment with ' . ($appointment->patient->full_name ?? 'Patient'),
                'date' => $appointment->appointment_date,
                'status' => ucfirst($appointment->status ?? 'Scheduled'),
            ];
        });

        $monthlyAnnouncements = Announcement::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $monthlyReports = $monthlyMaternalRecords;

        // Live data for charts
        $patientDemographics = Patient::join('appointments', 'patients.id', '=', 'appointments.patient_id')
            ->where('appointments.midwife_id', $midwifeId)
            ->selectRaw('patients.gender, count(distinct patients.id) as count')
            ->groupBy('patients.gender')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => ucfirst($item->gender),
                    'count' => $item->count
                ];
            });

        $healthTrends = Appointment::where('midwife_id', $midwifeId)
            ->selectRaw('MONTH(appointment_date) as month, YEAR(appointment_date) as year, count(*) as count')
            ->where('appointment_date', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => date('M Y', strtotime($item->year . '-' . $item->month . '-01')),
                    'count' => $item->count
                ];
            });

        return view('dashboard-midwife', compact(
            'totalPatients',
            'totalAppointments',
            'todayAppointments',
            'recentAppointments',
            'upcomingAppointments',
            'activeMaternalRecords',
            'monthlyAppointments',
            'monthlyMaternalRecords',
            'newPatientsThisMonth',
            'patientVisitsThisWeek',
            'commonTypes',
            'todayActivities',
            'activeAnnouncements',
            'recentActivities',
            'upcomingActivities',
            'monthlyAnnouncements',
            'monthlyReports',
            'patientDemographics',
            'healthTrends'
        ));
    }

    /**
     * Display a listing of appointments for midwife.
     */
    public function index(Request $request)
    {
        $midwife = auth()->user()->midwife;
        $status = $request->get('status', 'all');
        $urgent = $request->get('urgent', null);

        $appointments = Appointment::whereHas('patient', function ($query) use ($midwife) {
            $query->where('barangay', $midwife->area_of_assignment);
        })
        ->with(['patient', 'doctor', 'midwife', 'bhw'])
        ->when($status !== 'all', fn($q) => $q->where('status', $status))
        ->when($urgent === '1', fn($q) => $q->urgent())
        ->orderBy('appointment_date', 'desc')
        ->paginate(10);

        return view('midwife.appointments.index', compact('appointments', 'status', 'urgent'));
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create()
    {
        $midwife = auth()->user()->midwife;
        $patients = Patient::where('barangay', $midwife->area_of_assignment)->get();
        $doctors = \App\Models\Doctor::all();
        $midwives = \App\Models\Midwife::all();
        $bhws = \App\Models\BHW::all();
        return view('midwife.appointments.create', compact('patients', 'doctors', 'midwives', 'bhws'));
    }

    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'midwife_id' => 'nullable|exists:midwives,id',
            'bhw_id' => 'nullable|exists:bhws,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'reason' => 'required|string|max:255',
            'urgency_level' => 'required|in:normal,urgent,maternal',
            'notes' => 'nullable|string',
            'uploaded_files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        // Validate Wednesday-only booking
        $appointmentDate = \Carbon\Carbon::parse($validated['appointment_date']);
        if ($appointmentDate->dayOfWeek !== 3) { // 3 = Wednesday
            return back()->withErrors(['appointment_date' => 'Appointments can only be booked on Wednesdays.'])->withInput();
        }

        if (empty($validated['doctor_id']) && empty($validated['midwife_id']) && empty($validated['bhw_id'])) {
            return back()->withErrors(['doctor_id' => 'Please select a doctor, midwife, or BHW.']);
        }

        $validated['created_by_role'] = 'midwife';
        $validated['status'] = 'pending';

        // Handle file uploads
        $uploadedFiles = [];
        if ($request->hasFile('uploaded_files')) {
            foreach ($request->file('uploaded_files') as $file) {
                $path = $file->store('appointments', 'public');
                $uploadedFiles[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ];
            }
        }
        $validated['uploaded_files'] = $uploadedFiles;

        // Check for conflicts before creating appointment
        $conflictService = new AppointmentConflictService();
        $conflictCheck = $conflictService->checkConflicts($validated);

        if ($conflictCheck['has_conflict']) {
            return back()->withErrors([
                'appointment_time' => $conflictCheck['message']
            ])->withInput();
        }

        // Validate appointment data
        $validationErrors = $conflictService->validateAppointmentData($validated);
        if (!empty($validationErrors)) {
            return back()->withErrors($validationErrors)->withInput();
        }

        $appointment = Appointment::create($validated);

        // Send emails using the email service (with deduplication)
        $emailService = new AppointmentEmailService();
        $emailService->sendAppointmentEmailsToAll($appointment, 'booking');

        return redirect()->route('midwife.appointments.index')->with('success', 'Appointment booked successfully. It is now pending approval.');
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        return view('midwife.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     */
    public function edit(Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        if ($appointment->status !== 'pending') {
            return redirect()->route('midwife.appointments.index')->with('error', 'You can only edit pending appointments.');
        }

        $midwife = auth()->user()->midwife;
        $patients = Patient::where('barangay', $midwife->area_of_assignment)->get();
        $doctors = \App\Models\Doctor::all();
        $midwives = \App\Models\Midwife::all();
        $bhws = \App\Models\BHW::all();
        return view('midwife.appointments.edit', compact('appointment', 'patients', 'doctors', 'midwives', 'bhws'));
    }

    /**
     * Update the specified appointment in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        if ($appointment->status !== 'pending') {
            return redirect()->route('midwife.appointments.index')->with('error', 'You can only edit pending appointments.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'midwife_id' => 'nullable|exists:midwives,id',
            'bhw_id' => 'nullable|exists:bhws,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'reason' => 'required|string|max:255',
            'urgency_level' => 'required|in:normal,urgent,maternal',
            'notes' => 'nullable|string',
            'uploaded_files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        // Validate Wednesday-only booking
        $appointmentDate = \Carbon\Carbon::parse($validated['appointment_date']);
        if ($appointmentDate->dayOfWeek !== 3) {
            return back()->withErrors(['appointment_date' => 'Appointments can only be booked on Wednesdays.'])->withInput();
        }

        if (empty($validated['doctor_id']) && empty($validated['midwife_id']) && empty($validated['bhw_id'])) {
            return back()->withErrors(['doctor_id' => 'Please select a doctor, midwife, or BHW.']);
        }

        // urgency_level is already validated

        // Handle file uploads (append to existing files)
        $uploadedFiles = $appointment->uploaded_files ?? [];
        if ($request->hasFile('uploaded_files')) {
            foreach ($request->file('uploaded_files') as $file) {
                $path = $file->store('appointments', 'public');
                $uploadedFiles[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ];
            }
        }
        $validated['uploaded_files'] = $uploadedFiles;

        // Check for conflicts if date or time changed
        if ($appointment->appointment_date != $validated['appointment_date'] ||
            $appointment->appointment_time != $validated['appointment_time']) {

            $conflictService = new AppointmentConflictService();
            $conflictCheck = $conflictService->checkConflicts($validated);

            if ($conflictCheck['has_conflict']) {
                return back()->withErrors([
                    'appointment_time' => $conflictCheck['message']
                ])->withInput();
            }
        }

        $appointment->update($validated);

        return redirect()->route('midwife.appointments.index')->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified appointment from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);

        if ($appointment->status !== 'pending') {
            return redirect()->route('midwife.appointments.index')->with('error', 'You can only cancel pending appointments.');
        }

        $appointment->delete();
        return redirect()->route('midwife.appointments.index')->with('success', 'Appointment cancelled successfully.');
    }


}
