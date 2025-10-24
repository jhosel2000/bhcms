<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Services\AppointmentEmailService;
use App\Services\AppointmentConflictService;

class DoctorAppointmentsController extends Controller
{
    public function index(Request $request)
    {
        $doctorId = auth()->user()->doctor->id;
        $status = $request->get('status', 'all'); // default to all

        $appointments = $this->getFilteredAppointments($request, $doctorId);

        // If AJAX request, return JSON with HTML
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('doctor.partials.appointment-list', compact('appointments', 'status'))->render(),
                'pagination' => $appointments->onEachSide(1)->links()->toHtml(),
                'count' => $appointments->total()
            ]);
        }

        return view('doctor.appointments', compact('appointments', 'status'));
    }

    /**
     * Get filtered appointments query
     */
    private function getFilteredAppointments(Request $request, $doctorId)
    {
        return Appointment::where('doctor_id', $doctorId)
            ->when($request->get('status') !== 'all', function($q) use ($request) {
                $status = $request->get('status');
                if ($status === 'pending') {
                    $q->where('status', 'pending');
                } elseif ($status === 'approved') {
                    $q->where('status', 'approved');
                } elseif ($status === 'completed') {
                    $q->where('status', 'completed');
                } elseif ($status === 'declined') {
                    $q->where('status', 'declined');
                }
            })
            ->with('patient')
            ->when($request->filled('search'), function($q) use ($request) {
                $search = $request->get('search');
                $q->where(function($query) use ($search) {
                    // Search in patient full name
                    $query->whereHas('patient', function($patientQuery) use ($search) {
                        $patientQuery->where('full_name', 'like', "%{$search}%");
                    })
                    // Search in appointment reason
                    ->orWhere('reason', 'like', "%{$search}%")
                    // Search in appointment notes
                    ->orWhere('notes', 'like', "%{$search}%")
                    // Search by date (if search looks like a date)
                    ->orWhereDate('appointment_date', 'like', "%{$search}%");
                });
            })
            // Date range filter
            ->when($request->filled('date_from'), function($q) use ($request) {
                $q->whereDate('appointment_date', '>=', $request->get('date_from'));
            })
            ->when($request->filled('date_to'), function($q) use ($request) {
                $q->whereDate('appointment_date', '<=', $request->get('date_to'));
            })
            // Urgency filter
            ->when($request->filled('urgency'), function($q) use ($request) {
                if ($request->get('urgency') === 'urgent') {
                    $q->whereIn('urgency_level', ['urgent', 'maternal']);
                } elseif ($request->get('urgency') === 'normal') {
                    $q->where('urgency_level', 'normal');
                }
            })
            ->orderByRaw("CASE WHEN urgency_level IN ('urgent', 'maternal') THEN 1 ELSE 2 END") // Urgent/maternal appointments first
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->paginate(10);
    }

    public function create()
    {
        $patients = Patient::all();
        return view('doctor.appointments.create', compact('patients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['doctor_id'] = auth()->user()->doctor->id;
        $validated['status'] = 'pending';

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

        return redirect()->route('doctor.appointments.index')->with('success', 'Appointment scheduled successfully.');
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        return view('doctor.appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        $patients = Patient::all();
        return view('doctor.appointments.edit', compact('appointment', 'patients'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'reason' => 'required|string|max:255',
            'status' => 'required|in:pending,approved,declined,completed,expired',
            'notes' => 'nullable|string',
        ]);

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

        // Send emails using the email service (with deduplication)
        $emailService = new AppointmentEmailService();
        $emailType = 'booking';
        if ($appointment->status === 'confirmed' && $appointment->getOriginal('status') !== 'confirmed') {
            $emailType = 'reminder';
        }
        $emailService->sendAppointmentEmailsToAll($appointment, $emailType);

        return redirect()->route('doctor.appointments.index')->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        $appointment->delete();
        return redirect()->route('doctor.appointments.index')->with('success', 'Appointment deleted successfully.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $this->authorize('updateStatus', $appointment);

        $validated = $request->validate([
            'status' => 'required|in:pending,approved,declined,completed,expired',
        ]);

        $oldStatus = $appointment->status;
        $appointment->update($validated);

        // Send emails using the email service (with deduplication)
        $emailService = new AppointmentEmailService();
        $emailType = 'booking';
        if ($appointment->status === 'confirmed' && $oldStatus !== 'confirmed') {
            $emailType = 'reminder';
        }
        $emailService->sendAppointmentEmailsToAll($appointment, $emailType);

        return redirect()->route('dashboard.doctor')->with('success', 'Appointment status updated successfully.');
    }

    public function bulkUpdateStatus(Request $request)
    {
        $validated = $request->validate([
            'appointment_ids' => 'required|array',
            'appointment_ids.*' => 'exists:appointments,id',
            'status' => 'required|in:approved,declined',
        ]);

        $doctorId = auth()->user()->doctor->id;
        $appointmentIds = $validated['appointment_ids'];
        $status = $validated['status'];

        // Get appointments that belong to this doctor and are pending
        $appointments = Appointment::whereIn('id', $appointmentIds)
            ->where('doctor_id', $doctorId)
            ->where('status', 'pending')
            ->get();

        $updatedCount = 0;
        $emailService = new AppointmentEmailService();

        foreach ($appointments as $appointment) {
            $oldStatus = $appointment->status;
            $appointment->update(['status' => $status]);

            // Send emails
            $emailType = $status === 'approved' ? 'approval' : 'decline';
            $emailService->sendAppointmentEmailsToAll($appointment, $emailType);

            $updatedCount++;
        }

        $message = "Successfully {$status} {$updatedCount} appointment" . ($updatedCount > 1 ? 's' : '') . '.';

        return redirect()->route('dashboard.doctor')->with('success', $message);
    }

    public function approve($id)
    {
        $doctorId = auth()->user()->doctor->id;
        $appointment = Appointment::where('id', $id)->where('doctor_id', $doctorId)->firstOrFail();

        if ($appointment->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Only pending appointments can be approved.']);
        }

        // Check for conflicts
        $conflictService = new AppointmentConflictService();
        $conflictCheck = $conflictService->checkConflicts([
            'doctor_id' => $doctorId,
            'appointment_date' => $appointment->appointment_date,
            'appointment_time' => $appointment->appointment_time,
        ]);

        if ($conflictCheck['has_conflict']) {
            return response()->json(['success' => false, 'message' => $conflictCheck['message']]);
        }

        $appointment->status = 'approved';
        $appointment->approved_at = now();
        $appointment->save();

        // Send approval email
        $emailService = new AppointmentEmailService();
        $emailService->sendAppointmentEmailsToAll($appointment, 'approval');

        return response()->json(['success' => true, 'message' => 'Appointment approved successfully.']);
    }

    public function decline(Request $request, $id)
    {
        $doctorId = auth()->user()->doctor->id;
        $appointment = Appointment::where('id', $id)->where('doctor_id', $doctorId)->firstOrFail();

        if ($appointment->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Only pending appointments can be declined.']);
        }

        $validated = $request->validate([
            'declined_reason' => 'nullable|string|max:255',
        ]);

        $appointment->status = 'declined';
        $appointment->declined_reason = $validated['declined_reason'] ?? null;
        $appointment->save();

        // Send decline email
        $emailService = new AppointmentEmailService();
        $emailService->sendAppointmentEmailsToAll($appointment, 'decline');

        return response()->json(['success' => true, 'message' => 'Appointment declined successfully.']);
    }

    public function complete($id)
    {
        $doctorId = auth()->user()->doctor->id;
        $appointment = Appointment::where('id', $id)->where('doctor_id', $doctorId)->firstOrFail();

        if ($appointment->status !== 'approved') {
            return response()->json(['success' => false, 'message' => 'Only approved appointments can be marked as completed.']);
        }

        $appointment->status = 'completed';
        $appointment->completed_at = now();
        $appointment->save();

        // Send completion email
        $emailService = new AppointmentEmailService();
        $emailService->sendAppointmentEmailsToAll($appointment, 'completion');

        // Auto-sync to patient's EHR
        $this->syncAppointmentToEhr($appointment);

        return response()->json(['success' => true, 'message' => 'Appointment marked as completed.']);
    }

    /**
     * Sync completed appointment to patient's EHR
     */
    private function syncAppointmentToEhr(Appointment $appointment)
    {
        $description = "Appointment completed on " . \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') . " at " . $appointment->appointment_time->format('g:i A');
        if ($appointment->doctor) {
            $description .= " with Dr. " . $appointment->doctor->full_name;
        } elseif ($appointment->midwife) {
            $description .= " with Midwife " . $appointment->midwife->full_name;
        }

        $ehrRecord = \App\Models\EhrRecord::create([
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $appointment->doctor_id,
            'midwife_id' => $appointment->midwife_id,
            'appointment_id' => $appointment->id,
            'record_type' => 'appointment',
            'description' => $description,
            'notes' => $appointment->notes,
            'uploaded_files' => $appointment->uploaded_files,
            'created_by_role' => 'system',
        ]);

        // Send EHR update notification to patient
        $this->sendEhrUpdateNotification($ehrRecord);
    }

    /**
     * Send EHR update notification to patient
     */
    private function sendEhrUpdateNotification(\App\Models\EhrRecord $ehrRecord)
    {
        $patient = $ehrRecord->patient;
        if ($patient && $patient->user && $patient->user->email) {
            \Illuminate\Support\Facades\Mail::to($patient->user->email)->send(new \App\Mail\EhrUpdateNotification($ehrRecord));
        }
    }

    public function dashboard()
    {
        // Check if user has a doctor record
        $doctor = auth()->user()->doctor;
        if (!$doctor) {
            return redirect()->route('dashboard')->with('error', 'Doctor profile not found. Please contact administrator.');
        }

        $doctorId = $doctor->id;

        // Today's appointments
        $todayAppointments = Appointment::where('appointment_date', today())
            ->where('doctor_id', $doctorId)
            ->with('patient')
            ->get();

        // Total patients (distinct patients who have appointments with this doctor)
        $totalPatients = Appointment::where('doctor_id', $doctorId)
            ->distinct('patient_id')
            ->count('patient_id');

        // Active prescriptions
        $activePrescriptions = \App\Models\Prescription::where('doctor_id', $doctorId)
            ->where('status', 'active')
            ->count();

        // Recent appointments (last 5 completed or confirmed)
        $recentAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereIn('status', ['completed', 'confirmed'])
            ->with('patient')
            ->orderBy('appointment_date', 'desc')
            ->take(5)
            ->get();

        // Upcoming appointments
        $upcomingAppointments = Appointment::where('appointment_date', '>', today())
            ->where('doctor_id', $doctorId)
            ->with('patient')
            ->orderBy('appointment_date')
            ->take(5)
            ->get();

        // Pending appointments (for approval)
        $pendingAppointments = Appointment::where('doctor_id', $doctorId)
            ->where('status', 'pending')
            ->with('patient')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(5)
            ->get();

        // Monthly statistics
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $monthlyAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereMonth('appointment_date', $currentMonth)
            ->whereYear('appointment_date', $currentYear)
            ->count();

        $monthlyPrescriptions = \App\Models\Prescription::where('doctor_id', $doctorId)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // New patients this month (patients who had their first appointment with this doctor this month)
        $newPatientsThisMonth = Appointment::where('doctor_id', $doctorId)
            ->whereMonth('appointment_date', $currentMonth)
            ->whereYear('appointment_date', $currentYear)
            ->distinct('patient_id')
            ->count('patient_id');

        // Analytics: Patient visits this week
        $startOfWeek = now()->startOfWeek();
        $patientVisitsThisWeek = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $count = Appointment::where('doctor_id', $doctorId)
                ->whereDate('appointment_date', $date)
                ->count();
            $patientVisitsThisWeek[] = $count;
        }

        // Analytics: Most common diagnoses (top 5 reasons)
        $commonDiagnoses = Appointment::where('doctor_id', $doctorId)
            ->whereNotNull('reason')
            ->selectRaw('reason, COUNT(*) as count')
            ->groupBy('reason')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        return view('dashboard-doctor', compact(
            'todayAppointments',
            'totalPatients',
            'activePrescriptions',
            'recentAppointments',
            'upcomingAppointments',
            'pendingAppointments',
            'monthlyAppointments',
            'monthlyPrescriptions',
            'newPatientsThisMonth',
            'patientVisitsThisWeek',
            'commonDiagnoses'
        ));
    }
}
