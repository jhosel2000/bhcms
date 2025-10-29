<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Appointment;

class DoctorAnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard for the doctor.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $doctorId = auth()->id();

        // Patient statistics
        $totalPatients = Patient::count();
        $activePatients = Patient::whereHas('appointments', function($q) use ($doctorId) {
            $q->where('doctor_id', $doctorId);
        })->count(); // Patients with appointments to this doctor

        // Appointment trends
        $totalAppointments = Appointment::where('doctor_id', $doctorId)->count();
        $upcomingAppointments = Appointment::where('doctor_id', $doctorId)->where('appointment_date', '>', now())->count();
        $completedAppointments = Appointment::where('doctor_id', $doctorId)->where('status', 'completed')->count();

        // Trends: Appointments per month (last 12 months)
        $appointmentTrends = Appointment::where('doctor_id', $doctorId)
            ->where('created_at', '>=', now()->subMonths(12))
            ->selectRaw('EXTRACT(YEAR FROM created_at) as year, EXTRACT(MONTH FROM created_at) as month, COUNT(*) as count')
            ->groupByRaw('EXTRACT(YEAR FROM created_at), EXTRACT(MONTH FROM created_at)')
            ->orderByRaw('EXTRACT(YEAR FROM created_at) desc, EXTRACT(MONTH FROM created_at) desc')
            ->get()
            ->map(function ($item) {
                $item->month_name = date('F Y', mktime(0, 0, 0, $item->month, 1, $item->year));
                return $item;
            });

        return view('doctor.analytics.index', compact(
            'totalPatients',
            'activePatients',
            'totalAppointments',
            'upcomingAppointments',
            'completedAppointments',
            'appointmentTrends'
        ));
    }
}
