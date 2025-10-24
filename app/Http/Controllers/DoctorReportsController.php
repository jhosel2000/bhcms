<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Http\Request;

class DoctorReportsController extends Controller
{
    public function printable()
    {
        return view('doctor.reports.printable');
    }

    public function generatePrintable(Request $request)
    {
        $reportType = $request->query('report_type');
        $month = $request->query('month');

        $startDate = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = \Carbon\Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        $doctorId = auth()->id();

        $records = Appointment::with('patient')
            ->where('doctor_id', $doctorId)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->orderBy('appointment_date', 'desc')
            ->get();

        $stats = [
            'total_records' => $records->count(),
            'completed' => $records->where('status', 'completed')->count(),
            'upcoming' => $records->where('status', 'upcoming')->count(),
            'cancelled' => $records->where('status', 'cancelled')->count(),
        ];

        return view('doctor.reports.printable_report', compact('records', 'stats', 'reportType', 'month'));
    }
}
