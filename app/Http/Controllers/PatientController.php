<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function dashboard()
    {
        $patient = auth()->user()->patient;
        if (!$patient) {
            return redirect()->route('dashboard')->with('error', 'Patient profile not found. Please contact administrator.');
        }
        $patientId = $patient->id;

        // Upcoming appointments
        $upcomingAppointments = Appointment::where('patient_id', $patientId)
            ->where('appointment_date', '>=', today())
            ->with(['doctor', 'midwife'])
            ->orderBy('appointment_date')
            ->take(5)
            ->get();

        // Recent appointments
        $recentAppointments = Appointment::where('patient_id', $patientId)
            ->where('appointment_date', '<', today())
            ->with(['doctor', 'midwife'])
            ->orderBy('appointment_date', 'desc')
            ->take(5)
            ->get();

        // Active prescriptions
        $activePrescriptions = Prescription::where('patient_id', $patientId)
            ->where('status', 'active')
            ->count();

        // Total appointments
        $totalAppointments = Appointment::where('patient_id', $patientId)->count();

        return view('dashboard-patient', compact(
            'upcomingAppointments',
            'recentAppointments',
            'activePrescriptions',
            'totalAppointments'
        ));
    }
}
