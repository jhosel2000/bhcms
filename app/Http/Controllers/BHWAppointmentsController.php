<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Patient;
use App\Services\AppointmentEmailService;
use App\Services\AppointmentConflictService;

class BHWAppointmentsController extends Controller
{
    public function index(Request $request)
    {
        $bhw = auth()->user()->bhw;
        $status = $request->get('status', 'all');
        $urgent = $request->get('urgent', null);

        $appointments = Appointment::whereHas('patient', function ($query) use ($bhw) {
            $query->where('barangay', $bhw->barangay_id_number);
        })
        ->with(['patient', 'doctor', 'midwife', 'bhw'])
        ->when($status !== 'all', fn($q) => $q->where('status', $status))
        ->when($urgent === '1', fn($q) => $q->urgent())
        ->orderBy('appointment_date', 'desc')
        ->paginate(10);

        return view('bhw.appointments.index', compact('appointments', 'status', 'urgent'));
    }

    public function create()
    {
        $bhw = auth()->user()->bhw;
        $patients = Patient::where('barangay', $bhw->barangay_id_number)->get();
        $doctors = \App\Models\Doctor::all();
        $midwives = \App\Models\Midwife::all();
        return view('bhw.appointments.create', compact('patients', 'doctors', 'midwives'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'midwife_id' => 'nullable|exists:midwives,id',
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

        if (empty($validated['doctor_id']) && empty($validated['midwife_id'])) {
            return back()->withErrors(['doctor_id' => 'Please select a doctor or midwife.']);
        }

        $validated['created_by_role'] = 'bhw';
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

        return redirect()->route('bhw.appointments.index')->with('success', 'Appointment booked successfully. It is now pending approval.');
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        return view('bhw.appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        if ($appointment->status !== 'pending') {
            return redirect()->route('bhw.appointments.index')->with('error', 'You can only edit pending appointments.');
        }

        $bhw = auth()->user()->bhw;
        $patients = Patient::where('barangay', $bhw->barangay_id_number)->get();
        $doctors = \App\Models\Doctor::all();
        $midwives = \App\Models\Midwife::all();
        return view('bhw.appointments.edit', compact('appointment', 'patients', 'doctors', 'midwives'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        if ($appointment->status !== 'pending') {
            return redirect()->route('bhw.appointments.index')->with('error', 'You can only edit pending appointments.');
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'midwife_id' => 'nullable|exists:midwives,id',
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

        if (empty($validated['doctor_id']) && empty($validated['midwife_id'])) {
            return back()->withErrors(['doctor_id' => 'Please select a doctor or midwife.']);
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

        return redirect()->route('bhw.appointments.index')->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);

        if ($appointment->status !== 'pending') {
            return redirect()->route('bhw.appointments.index')->with('error', 'You can only cancel pending appointments.');
        }

        $appointment->delete();
        return redirect()->route('bhw.appointments.index')->with('success', 'Appointment cancelled successfully.');
    }
}
