<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BHW Community Health Reports</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; text-align: center; }
        h2 { color: #555; margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .stats { display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px; }
        .stat-card { border: 1px solid #ddd; padding: 15px; border-radius: 5px; width: 200px; }
        .stat-card h3 { margin: 0; color: #333; }
        .stat-card p { font-size: 24px; font-weight: bold; margin: 5px 0; }
    </style>
</head>
<body>
    <h1>Community Health Reports</h1>
    <p>Generated on: {{ now()->format('F d, Y H:i') }}</p>

    <h2>Key Statistics</h2>
    <div class="stats">
        <div class="stat-card">
            <h3>Total Patients</h3>
            <p>{{ $totalPatients }}</p>
        </div>
        <div class="stat-card">
            <h3>Total Announcements</h3>
            <p>{{ $totalAnnouncements }}</p>
        </div>
        <div class="stat-card">
            <h3>Active Announcements</h3>
            <p>{{ $activeAnnouncements }}</p>
        </div>
        <div class="stat-card">
            <h3>Total Appointments</h3>
            <p>{{ $totalAppointments }}</p>
        </div>
    </div>

    <h2>Patient Demographics</h2>
    <table>
        <tr><th>Category</th><th>Count</th></tr>
        <tr><td>Male Patients</td><td>{{ $malePatients }}</td></tr>
        <tr><td>Female Patients</td><td>{{ $femalePatients }}</td></tr>
        <tr><td>Other</td><td>{{ $otherPatients }}</td></tr>
    </table>

    <h2>Age Groups</h2>
    <table>
        <tr><th>Age Group</th><th>Count</th></tr>
        <tr><td>Under 18</td><td>{{ $under18 }}</td></tr>
        <tr><td>18-35</td><td>{{ $age18to35 }}</td></tr>
        <tr><td>36-55</td><td>{{ $age36to55 }}</td></tr>
        <tr><td>56+</td><td>{{ $over55 }}</td></tr>
    </table>

    <h2>Civil Status Distribution</h2>
    <table>
        <tr><th>Civil Status</th><th>Count</th></tr>
        @foreach($civilStatusStats as $stat)
        <tr><td>{{ ucfirst($stat->civil_status) }}</td><td>{{ $stat->count }}</td></tr>
        @endforeach
    </table>

    <h2>Appointment Status</h2>
    <table>
        <tr><th>Status</th><th>Count</th></tr>
        @foreach($appointmentStatus as $stat)
        <tr><td>{{ ucfirst($stat->status) }}</td><td>{{ $stat->count }}</td></tr>
        @endforeach
    </table>

    <h2>Monthly Patient Registrations (Last 6 Months)</h2>
    <table>
        <tr><th>Month</th><th>Year</th><th>Registrations</th></tr>
        @foreach($monthlyRegistrations as $reg)
        <tr><td>{{ date('F', mktime(0, 0, 0, $reg->month, 1)) }}</td><td>{{ $reg->year }}</td><td>{{ $reg->count }}</td></tr>
        @endforeach
    </table>

    <h2>Recent Announcements</h2>
    <ul>
        @foreach($recentAnnouncements as $announcement)
        <li>{{ $announcement->title }} - {{ $announcement->published_at ? $announcement->published_at->format('M d, Y') : 'Draft' }}</li>
        @endforeach
    </ul>
</body>
</html>
