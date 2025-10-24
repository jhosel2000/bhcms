<?php

namespace App\Http\Controllers;

use App\Models\MaternalCareRecord;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MidwifeReportsController extends Controller
{
    public function statistics()
    {
        // Get statistics data
        $totalPatients = Patient::count();

        $prenatalVisits = MaternalCareRecord::where('type', 'prenatal')->count();
        $postnatalVisits = MaternalCareRecord::where('type', 'postnatal')->count();
        $monthlyCheckups = MaternalCareRecord::where('type', 'checkup')->count();

        // Get recent records
        $recentRecords = MaternalCareRecord::with('patient')
            ->orderBy('visit_date', 'desc')
            ->limit(10)
            ->get();

        // Get patient visits data for the last 6 months
        $patientVisits = [];
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = MaternalCareRecord::whereYear('visit_date', $date->year)
                ->whereMonth('visit_date', $date->month)
                ->count();
            $patientVisits[] = $count;
            $months[] = $date->format('M');
        }

        return view('midwife.reports.statistics', compact(
            'totalPatients',
            'prenatalVisits',
            'postnatalVisits',
            'monthlyCheckups',
            'recentRecords',
            'patientVisits',
            'months'
        ));
    }

    public function printable()
    {
        return view('midwife.reports.printable');
    }

    public function generatePrintable(Request $request)
    {
        $reportType = $request->query('report_type');
        $month = $request->query('month');

        // Parse month for filtering
        $startDate = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = \Carbon\Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        // Get records for the period
        $records = MaternalCareRecord::with('patient')
            ->whereBetween('visit_date', [$startDate, $endDate])
            ->orderBy('visit_date', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total_records' => $records->count(),
            'prenatal' => $records->where('type', 'prenatal')->count(),
            'postnatal' => $records->where('type', 'postnatal')->count(),
            'checkups' => $records->where('type', 'checkup')->count(),
            'followups' => $records->where('type', 'followup')->count(),
        ];

        return view('midwife.reports.printable_report', compact('records', 'stats', 'reportType', 'month'));
    }
}
