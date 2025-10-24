<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Services\AppointmentEmailService;
use App\Services\AppointmentConflictService;

class PatientAppointmentsController extends Controller
{
    public function index()
    {
        $upcomingAppointments = Appointment::where('patient_id', auth()->user()->patient->id)
            ->where('appointment_date', '>=', today())
            ->with(['doctor', 'midwife'])
            ->orderBy('appointment_date')
            ->get();

        $pastAppointments = Appointment::where('patient_id', auth()->user()->patient->id)
            ->where('appointment_date', '<', today())
            ->with(['doctor', 'midwife'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('patient.appointments.index', compact('upcomingAppointments', 'pastAppointments'));
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        $appointment->load(['doctor', 'midwife']);
        return view('patient.appointments.show', compact('appointment'));
    }

    public function create()
    {
        $doctors = \App\Models\Doctor::all();
        $midwives = \App\Models\Midwife::all();
        $bhws = \App\Models\BHW::all();

        return view('patient.appointments.create', compact('doctors', 'midwives', 'bhws'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'reason' => 'required|string|max:255',
            'urgency_level' => 'nullable|in:normal,urgent,maternal',
            'doctor_id' => 'nullable|exists:doctors,id',
            'midwife_id' => 'nullable|exists:midwives,id',
            'bhw_id' => 'nullable|exists:bhws,id',
            'uploaded_files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        // Add default duration_minutes if not provided
        if (!isset($validated['duration_minutes'])) {
            $validated['duration_minutes'] = 30;
        }

        // Validate Wednesday-only booking
        $appointmentDate = \Carbon\Carbon::parse($validated['appointment_date']);
        if ($appointmentDate->dayOfWeek !== 3) { // 3 = Wednesday (0=Sunday, 6=Saturday)
            return back()->withErrors(['appointment_date' => 'Appointments can only be booked on Wednesdays.'])->withInput();
        }

        if (empty($validated['doctor_id']) && empty($validated['midwife_id']) && empty($validated['bhw_id'])) {
            return back()->withErrors(['doctor_id' => 'Please select a doctor, midwife, or BHW.']);
        }

        $patientId = auth()->user()->patient->id;

        // Prevent multiple pending/approved appointments for same patient on same date
        $existingAppointment = Appointment::where('patient_id', $patientId)
            ->where('appointment_date', $validated['appointment_date'])
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($existingAppointment) {
            return back()->withErrors(['appointment_date' => 'You already have a pending or approved appointment on this date.'])->withInput();
        }

        $validated['patient_id'] = $patientId;
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

        return redirect()->route('patient.appointments.index')->with('success', 'Appointment booked successfully. It is now pending approval.');
    }

    /**
     * Get available time slots for a doctor on a specific Wednesday date via AJAX
     */
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date',
        ]);

        $date = $request->date;
        $doctorId = $request->doctor_id;

        // Validate that the date is a Wednesday
        $appointmentDate = \Carbon\Carbon::parse($date);
        if ($appointmentDate->dayOfWeek !== 3) { // 3 = Wednesday (0=Sunday, 6=Saturday)
            return response()->json(['error' => 'Appointments are only available on Wednesdays.'], 400);
        }

        // Check if date is in the future
        if ($appointmentDate->isPast() && !$appointmentDate->isToday()) {
            return response()->json(['error' => 'Cannot book appointments in the past.'], 400);
        }

        $conflictService = new AppointmentConflictService();
        $slotsData = $conflictService->getAvailableSlots($doctorId, 'doctor', $date);

        if (isset($slotsData['message'])) {
            return response()->json(['error' => $slotsData['message']], 400);
        }

        return response()->json([
            'available_slots' => $slotsData['available_slots'],
            'schedule' => $slotsData['schedule'],
        ]);
    }

    /**
     * Show the form for editing the specified appointment (only if pending)
     */
    public function edit(Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        if ($appointment->status !== 'pending') {
            return redirect()->route('patient.appointments.index')->with('error', 'You can only edit pending appointments.');
        }

        $doctors = \App\Models\Doctor::all();
        $midwives = \App\Models\Midwife::all();
        $bhws = \App\Models\BHW::all();

        return view('patient.appointments.edit', compact('appointment', 'doctors', 'midwives', 'bhws'));
    }

    /**
     * Update the specified appointment (only if pending)
     */
    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        if ($appointment->status !== 'pending') {
            return redirect()->route('patient.appointments.index')->with('error', 'You can only edit pending appointments.');
        }

        $validated = $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'reason' => 'required|string|max:255',
            'urgency_level' => 'nullable|in:normal,urgent,maternal',
            'doctor_id' => 'nullable|exists:doctors,id',
            'midwife_id' => 'nullable|exists:midwives,id',
            'bhw_id' => 'nullable|exists:bhws,id',
            'uploaded_files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        // Validate Wednesday-only booking
        $appointmentDate = \Carbon\Carbon::parse($validated['appointment_date']);
        if ($appointmentDate->dayOfWeek !== 3) { // 3 = Wednesday (0=Sunday, 6=Saturday)
            return back()->withErrors(['appointment_date' => 'Appointments can only be booked on Wednesdays.'])->withInput();
        }

        if (empty($validated['doctor_id']) && empty($validated['midwife_id']) && empty($validated['bhw_id'])) {
            return back()->withErrors(['doctor_id' => 'Please select a doctor, midwife, or BHW.']);
        }

        $patientId = auth()->user()->patient->id;

        // Prevent multiple pending/approved appointments for same patient on same date (excluding current appointment)
        $existingAppointment = Appointment::where('patient_id', $patientId)
            ->where('appointment_date', $validated['appointment_date'])
            ->whereIn('status', ['pending', 'approved'])
            ->where('id', '!=', $appointment->id)
            ->exists();

        if ($existingAppointment) {
            return back()->withErrors(['appointment_date' => 'You already have a pending or approved appointment on this date.'])->withInput();
        }

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

        // Check for conflicts before updating appointment
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

        $appointment->update($validated);

        return redirect()->route('patient.appointments.index')->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified appointment (only if pending)
     */
    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);

        if ($appointment->status !== 'pending') {
            return redirect()->route('patient.appointments.index')->with('error', 'You can only cancel pending appointments.');
        }

        $appointment->delete();

        return redirect()->route('patient.appointments.index')->with('success', 'Appointment cancelled successfully.');
    }
}
