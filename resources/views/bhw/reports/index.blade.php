<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('BHW - Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    <h3 class="text-2xl font-bold mb-8 text-gray-800">Community Health Reports</h3>

                    <div class="mb-8 flex gap-4">
                        <a href="{{ route('bhw.reports.download-csv') }}" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                            Download CSV Report
                        </a>
                        <a href="{{ route('bhw.reports.download-pdf') }}" class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                            Download PDF Report
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl shadow-md hover:shadow-lg transition duration-300">
                            <div class="flex items-center mb-2">
                                <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <h4 class="font-semibold text-blue-800">Total Patients</h4>
                            </div>
                            <p class="text-3xl font-bold text-blue-600">{{ $totalPatients }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl shadow-md hover:shadow-lg transition duration-300">
                            <div class="flex items-center mb-2">
                                <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                <h4 class="font-semibold text-green-800">Total Announcements</h4>
                            </div>
                            <p class="text-3xl font-bold text-green-600">{{ $totalAnnouncements }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 rounded-xl shadow-md hover:shadow-lg transition duration-300">
                            <div class="flex items-center mb-2">
                                <svg class="w-6 h-6 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                <h4 class="font-semibold text-yellow-800">Active Announcements</h4>
                            </div>
                            <p class="text-3xl font-bold text-yellow-600">{{ $activeAnnouncements }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl shadow-md hover:shadow-lg transition duration-300">
                            <div class="flex items-center mb-2">
                                <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <h4 class="font-semibold text-purple-800">Total Appointments</h4>
                            </div>
                            <p class="text-3xl font-bold text-purple-600">{{ $totalAppointments }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl shadow-md hover:shadow-lg transition duration-300">
                            <h4 class="font-semibold mb-4 text-gray-800">Patient Demographics</h4>
                            <p class="text-gray-700">Male Patients: <span class="font-bold">{{ $malePatients }}</span></p>
                            <p class="text-gray-700">Female Patients: <span class="font-bold">{{ $femalePatients }}</span></p>
                            <p class="text-gray-700">Other: <span class="font-bold">{{ $otherPatients }}</span></p>
                        </div>
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl shadow-md hover:shadow-lg transition duration-300">
                            <h4 class="font-semibold mb-4 text-gray-800">Age Groups</h4>
                            <p class="text-gray-700">Under 18: <span class="font-bold">{{ $under18 }}</span></p>
                            <p class="text-gray-700">18-35: <span class="font-bold">{{ $age18to35 }}</span></p>
                            <p class="text-gray-700">36-55: <span class="font-bold">{{ $age36to55 }}</span></p>
                            <p class="text-gray-700">56+: <span class="font-bold">{{ $over55 }}</span></p>
                        </div>
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl shadow-md hover:shadow-lg transition duration-300">
                            <h4 class="font-semibold mb-4 text-gray-800">Upcoming Appointments</h4>
                            <p class="text-3xl font-bold text-gray-800">{{ $upcomingAppointments }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl shadow-md hover:shadow-lg transition duration-300">
                            <h4 class="font-semibold mb-4 text-gray-800">Civil Status Distribution</h4>
                            @foreach($civilStatusStats as $stat)
                                <p class="text-gray-700">{{ ucfirst($stat->civil_status) }}: <span class="font-bold">{{ $stat->count }}</span></p>
                            @endforeach
                        </div>
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl shadow-md hover:shadow-lg transition duration-300">
                            <h4 class="font-semibold mb-4 text-gray-800">Appointment Status</h4>
                            @foreach($appointmentStatus as $stat)
                                <p class="text-gray-700">{{ ucfirst($stat->status) }}: <span class="font-bold">{{ $stat->count }}</span></p>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl shadow-md mb-8">
                        <h4 class="font-semibold mb-6 text-gray-800">Monthly Patient Registrations (Last 6 Months)</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto bg-white rounded-lg shadow-sm">
                                <thead class="bg-gray-200">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registrations</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($monthlyRegistrations as $reg)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ date('F', mktime(0, 0, 0, $reg->month, 1)) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reg->year }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reg->count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h4 class="font-semibold mb-6 text-gray-800">Recent Announcements</h4>
                        @if($recentAnnouncements->count() > 0)
                            <ul class="space-y-3">
                                @foreach($recentAnnouncements as $announcement)
                                    <li class="bg-white border border-gray-200 p-4 rounded-lg shadow-sm hover:shadow-md transition duration-300">
                                        <strong class="text-gray-900">{{ $announcement->title }}</strong> <span class="text-gray-500">- {{ $announcement->published_at ? $announcement->published_at->format('M d, Y') : 'Draft' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic">No recent announcements.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
