<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Announcement;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class BHWReportsController extends Controller
{
    /**
     * Display reports dashboard.
     */
    public function index()
    {
        // Get statistics
        $totalPatients = Patient::count();
        $totalAnnouncements = Announcement::count();
        $activeAnnouncements = Announcement::where('is_active', true)->count();
        $totalAppointments = Appointment::count();
        $upcomingAppointments = Appointment::where('appointment_date', '>=', now())->count();

        // Recent announcements
        $recentAnnouncements = Announcement::latest()->take(5)->get();

        // Patient demographics
        $malePatients = Patient::where('gender', 'male')->count();
        $femalePatients = Patient::where('gender', 'female')->count();
        $otherPatients = Patient::where('gender', 'other')->count();

        // Age groups
        $under18 = Patient::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 18')->count();
        $age18to35 = Patient::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 18 AND 35')->count();
        $age36to55 = Patient::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 36 AND 55')->count();
        $over55 = Patient::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) > 55')->count();

        // Civil status
        $civilStatusStats = Patient::select('civil_status', DB::raw('count(*) as count'))
            ->groupBy('civil_status')
            ->get();

        // Monthly patient registrations (last 6 months)
        $monthlyRegistrations = Patient::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Appointment status
        $appointmentStatus = Appointment::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        return view('bhw.reports.index', compact(
            'totalPatients',
            'totalAnnouncements',
            'activeAnnouncements',
            'totalAppointments',
            'upcomingAppointments',
            'recentAnnouncements',
            'malePatients',
            'femalePatients',
            'otherPatients',
            'under18',
            'age18to35',
            'age36to55',
            'over55',
            'civilStatusStats',
            'monthlyRegistrations',
            'appointmentStatus'
        ));
    }

    /**
     * Download reports as CSV.
     */
    public function downloadCsv()
    {
        $data = $this->getReportData();

        $filename = 'bhw_report_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');

            // Write headers
            fputcsv($file, ['Report Type', 'Value']);

            // Write data
            fputcsv($file, ['Total Patients', $data['totalPatients']]);
            fputcsv($file, ['Total Announcements', $data['totalAnnouncements']]);
            fputcsv($file, ['Active Announcements', $data['activeAnnouncements']]);
            fputcsv($file, ['Total Appointments', $data['totalAppointments']]);
            fputcsv($file, ['Upcoming Appointments', $data['upcomingAppointments']]);
            fputcsv($file, ['Male Patients', $data['malePatients']]);
            fputcsv($file, ['Female Patients', $data['femalePatients']]);
            fputcsv($file, ['Other Patients', $data['otherPatients']]);
            fputcsv($file, ['Under 18', $data['under18']]);
            fputcsv($file, ['18-35', $data['age18to35']]);
            fputcsv($file, ['36-55', $data['age36to55']]);
            fputcsv($file, ['56+', $data['over55']]);

            // Civil status
            foreach ($data['civilStatusStats'] as $stat) {
                fputcsv($file, [ucfirst($stat->civil_status), $stat->count]);
            }

            // Appointment status
            foreach ($data['appointmentStatus'] as $stat) {
                fputcsv($file, [ucfirst($stat->status), $stat->count]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Download reports as PDF.
     */
    public function downloadPdf()
    {
        $data = $this->getReportData();

        $pdf = Pdf::loadView('bhw.reports.pdf', $data);

        $filename = 'bhw_report_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Helper method to get report data.
     */
    protected function getReportData()
    {
        return [
            'totalPatients' => Patient::count(),
            'totalAnnouncements' => Announcement::count(),
            'activeAnnouncements' => Announcement::where('is_active', true)->count(),
            'totalAppointments' => Appointment::count(),
            'upcomingAppointments' => Appointment::where('appointment_date', '>=', now())->count(),
            'recentAnnouncements' => Announcement::latest()->take(5)->get(),
            'malePatients' => Patient::where('gender', 'male')->count(),
            'femalePatients' => Patient::where('gender', 'female')->count(),
            'otherPatients' => Patient::where('gender', 'other')->count(),
            'under18' => Patient::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 18')->count(),
            'age18to35' => Patient::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 18 AND 35')->count(),
            'age36to55' => Patient::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 36 AND 55')->count(),
            'over55' => Patient::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) > 55')->count(),
            'civilStatusStats' => Patient::select('civil_status', DB::raw('count(*) as count'))
                ->groupBy('civil_status')
                ->get(),
            'monthlyRegistrations' => Patient::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get(),
            'appointmentStatus' => Appointment::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get(),
        ];
    }
}
