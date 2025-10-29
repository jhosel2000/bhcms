<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Patient;

class BHWController extends Controller
{
    /**
     * Display the BHW dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Fetch data for dashboard
        $todayActivities = Announcement::whereDate('created_at', now())->count();
        $totalPatients = Patient::count();
        $activeAnnouncements = Announcement::where('is_active', true)->count();

        $recentActivities = Announcement::orderBy('created_at', 'desc')->take(5)->get()->map(function ($announcement) {
            return (object) [
                'title' => $announcement->title,
                'date' => $announcement->published_at ?? $announcement->created_at,
                'status' => $announcement->is_active ? 'Active' : 'Inactive',
            ];
        });

        // Get upcoming activities (announcements scheduled for future)
        $upcomingActivities = Announcement::where('is_active', true)
            ->whereDate('published_at', '>', now())
            ->orderBy('published_at')
            ->take(5)
            ->get()
            ->map(function ($announcement) {
                return (object) [
                    'title' => $announcement->title,
                    'date' => $announcement->published_at,
                    'status' => 'Scheduled',
                ];
            });

        $monthlyAnnouncements = Announcement::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $monthlyReports = 0; // Placeholder, implement as needed
        $newPatientsThisMonth = Patient::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        // Recent patients for registration review
        $recentPatients = Patient::latest()->take(5)->get();

        // Community health statistics
        $communityStats = [
            'total_households' => Patient::distinct('full_address')->count(), // Use full_address instead of barangay
            'active_programs' => $activeAnnouncements,
            'coverage_rate' => $totalPatients > 0 ? round(($totalPatients / 1000) * 100, 1) : 0, // Assuming 1000 is target population
        ];

        // Live data for charts
        $patientDemographics = Patient::selectRaw('gender, count(*) as count')
            ->groupBy('gender')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => ucfirst($item->gender),
                    'count' => $item->count
                ];
            });

        $healthTrends = Patient::selectRaw('EXTRACT(MONTH FROM created_at) as month, EXTRACT(YEAR FROM created_at) as year, count(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupByRaw('EXTRACT(YEAR FROM created_at), EXTRACT(MONTH FROM created_at)')
            ->orderByRaw('EXTRACT(YEAR FROM created_at), EXTRACT(MONTH FROM created_at)')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => date('M Y', strtotime($item->year . '-' . $item->month . '-01')),
                    'count' => $item->count
                ];
            });

        return view('dashboard-bhw', compact(
            'todayActivities',
            'totalPatients',
            'activeAnnouncements',
            'recentActivities',
            'upcomingActivities',
            'monthlyAnnouncements',
            'monthlyReports',
            'newPatientsThisMonth',
            'recentPatients',
            'communityStats',
            'patientDemographics',
            'healthTrends'
        ));
    }
}
