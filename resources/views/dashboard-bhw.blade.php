<x-app-layout>
    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Welcome back, BHW {{ auth()->user()->name }}</h2>
                <p class="text-gray-600 mt-2">Manage community health programs and resident care.</p>
            </div>

            <!-- Top Stats Cards - Compact -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
                <!-- Today's Activities Card -->
                <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-600 text-xs font-medium">Today's Activities</p>
                            <p class="text-lg font-bold text-gray-900">{{ $todayActivities ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Patients Card -->
                <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-600 text-xs font-medium">Total Patients</p>
                            <p class="text-lg font-bold text-gray-900">{{ $totalPatients ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Active Announcements Card -->
                <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-600 text-xs font-medium">Active Announcements</p>
                            <p class="text-lg font-bold text-gray-900">{{ $activeAnnouncements ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions - Compact -->
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 mb-3">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('bhw.patients.create') }}" class="flex items-center bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium py-2 px-3 rounded-lg transition-colors duration-200 text-sm" aria-label="Add new patient">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Add Patient
                    </a>
                    <a href="{{ route('bhw.announcements.create') }}" class="flex items-center bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium py-2 px-3 rounded-lg transition-colors duration-200 text-sm" aria-label="Create new announcement">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                        Create Announcement
                    </a>
                    <a href="{{ route('bhw.reports.index') }}" class="flex items-center bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium py-2 px-3 rounded-lg transition-colors duration-200 text-sm" aria-label="View reports">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        View Reports
                    </a>
                </div>
            </div>

            <!-- Main Content Grid - 3 Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-3">
                <!-- Left Column - Patient Registration Request -->
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900">Patient Registration</h3>
                        <a href="{{ route('bhw.patients.index') }}" class="text-blue-600 text-xs font-medium hover:text-blue-700">See All</a>
                    </div>

                    <div class="space-y-2">
                        @php
                            // Get recent patient registrations for BHW to review
                            $recentPatients = App\Models\Patient::latest()->take(3)->get();
                        @endphp

                        @forelse($recentPatients as $patient)
                        <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 rounded-full mr-2 flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-600">{{ strtoupper(substr($patient->full_name ?? 'N/A', 0, 2)) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">{{ $patient->full_name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $patient->full_address ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="flex space-x-1">
                                <a href="{{ route('bhw.patients.show', $patient->id) }}" class="px-2 py-1 bg-green-100 text-green-600 text-xs rounded hover:bg-green-200 transition-colors" aria-label="View patient {{ $patient->full_name }}">
                                    View
                                </a>
                                <a href="{{ route('bhw.patients.edit', $patient->id) }}" class="px-2 py-1 bg-blue-100 text-blue-600 text-xs rounded hover:bg-blue-200 transition-colors" aria-label="Edit patient {{ $patient->full_name }}">
                                    Edit
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <p class="text-gray-500 text-sm">No recent patient registrations</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Middle Column - Calendar -->
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900">Health Program Calendar</h3>
                        <span class="text-xs text-gray-600">{{ \Carbon\Carbon::now()->format('F - Y') }}</span>
                    </div>

                    <div class="grid grid-cols-7 gap-1 text-center text-xs font-medium text-gray-500 mb-2">
                        <div class="py-1">Su</div>
                        <div class="py-1">Mo</div>
                        <div class="py-1">Tu</div>
                        <div class="py-1">We</div>
                        <div class="py-1">Th</div>
                        <div class="py-1">Fr</div>
                        <div class="py-1">Sa</div>
                    </div>

                    <div class="grid grid-cols-7 gap-1 text-xs">
                        @php
                            $today = \Carbon\Carbon::today();
                            $startOfMonth = $today->copy()->startOfMonth();
                            $endOfMonth = $today->copy()->endOfMonth();
                            $startOfCalendar = $startOfMonth->copy()->startOfWeek();
                            $endOfCalendar = $endOfMonth->copy()->endOfWeek();
                            $currentDate = $startOfCalendar->copy();
                        @endphp

                        @while($currentDate <= $endOfCalendar)
                            @if($currentDate < $startOfMonth)
                                <!-- Previous month days -->
                                <div class="py-1 text-gray-300">{{ $currentDate->day }}</div>
                            @elseif($currentDate > $endOfMonth)
                                <!-- Next month days -->
                                <div class="py-1 text-gray-300">{{ $currentDate->day }}</div>
                            @else
                                <!-- Current month days -->
                                @if($currentDate->isToday())
                                    <div class="py-1 bg-blue-500 text-white rounded font-medium">{{ $currentDate->day }}</div>
                                @else
                                    <div class="py-1 text-gray-700 hover:bg-gray-100 rounded cursor-pointer transition-colors" role="button" tabindex="0" aria-label="Day {{ $currentDate->day }}">{{ $currentDate->day }}</div>
                                @endif
                            @endif
                            @php $currentDate->addDay(); @endphp
                        @endwhile
                    </div>
                </div>

                <!-- Right Column - Today's Schedule -->
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900">Today's Schedule</h3>
                        <a href="{{ route('bhw.announcements.index') }}" class="text-blue-600 text-xs font-medium hover:text-blue-700">See All</a>
                    </div>

                    <div class="space-y-2">
                        @forelse($recentActivities->take(3) as $activity)
                        <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 rounded-full mr-2 flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-600">{{ strtoupper(substr($activity->title ?? 'N/A', 0, 2)) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">{{ Str::limit($activity->title ?? 'N/A', 20) }}</p>
                                    <p class="text-xs text-gray-500">{{ $activity->date->format('h:i A') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-medium {{ $activity->status === 'Active' ? 'text-green-600' : 'text-blue-600' }}">
                                    {{ $activity->status }}
                                </p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <p class="text-gray-500 text-sm">No scheduled activities for today</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Patient Summary Chart & Community Health Details - Full Width Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                <!-- Patient Summary Chart -->
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Community Health Summary {{ \Carbon\Carbon::now()->format('F Y') }}</h3>
                    <div class="flex flex-col items-center justify-center text-center">
                        <div class="relative w-full max-w-32 h-32 flex items-center justify-center mb-3">
                            <canvas id="communityHealthChart" width="128" height="128"></canvas>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center justify-center">
                                <div class="w-3 h-3 bg-blue-500 rounded mr-2"></div>
                                <span class="text-xs text-gray-600">Male Patients</span>
                            </div>
                            <div class="flex items-center justify-center">
                                <div class="w-3 h-3 bg-pink-400 rounded mr-2"></div>
                                <span class="text-xs text-gray-600">Female Patients</span>
                            </div>
                            <div class="flex items-center justify-center">
                                <div class="w-3 h-3 bg-gray-300 rounded mr-2"></div>
                                <span class="text-xs text-gray-600">Total Patients</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Community Health Program Details -->
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Community Health Programs</h3>

                    <div class="text-center mb-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-full mx-auto mb-2 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 text-sm">Barangay Health Programs</h4>
                        <p class="text-xs text-gray-500">Community health initiatives</p>
                    </div>

                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Active Programs</span>
                            <span class="font-medium">{{ $activeAnnouncements ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Monthly Activities</span>
                            <span class="font-medium">{{ $monthlyAnnouncements ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">New Registrations</span>
                            <span class="font-medium">{{ $newPatientsThisMonth ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Coverage Area</span>
                            <span class="font-medium">Barangay Level</span>
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <a href="{{ route('bhw.announcements.create') }}" class="block w-full bg-blue-100 text-blue-600 py-1.5 px-3 rounded text-xs font-medium hover:bg-blue-200 text-center transition-colors" aria-label="Create new health program">
                            Create Program
                        </a>
                        <a href="{{ route('bhw.reports.index') }}" class="block w-full bg-green-100 text-green-600 py-1.5 px-3 rounded text-xs font-medium hover:bg-green-200 text-center transition-colors" aria-label="View health reports">
                            View Reports
                        </a>
                        <a href="{{ route('bhw.patients.index') }}" class="block w-full bg-orange-100 text-orange-600 py-1.5 px-3 rounded text-xs font-medium hover:bg-orange-200 text-center transition-colors" aria-label="View patient list">
                            Patient List
                        </a>
                    </div>

                    <div class="mt-3 pt-2 border-t border-gray-100">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-600">Program Status</span>
                            <div class="flex space-x-1">
                                <button class="text-green-600 hover:text-green-700 transition-colors" aria-label="View program status">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                <button class="text-blue-600 hover:text-blue-700 transition-colors" aria-label="Edit program">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Community Health Doughnut Chart
            const ctx = document.getElementById('communityHealthChart');
            if (ctx) {
                const chartCtx = ctx.getContext('2d');

                // Get patient demographics data
                const demographics = @json($patientDemographics ?? []);
                const maleCount = demographics.find(d => d.category === 'Male')?.count || 0;
                const femaleCount = demographics.find(d => d.category === 'Female')?.count || 0;
                const totalCount = maleCount + femaleCount;

                new Chart(chartCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Male Patients', 'Female Patients'],
                        datasets: [{
                            data: [maleCount, femaleCount],
                            backgroundColor: [
                                '#3B82F6', // Blue for Male
                                '#EC4899'  // Pink for Female
                            ],
                            borderWidth: 0,
                            cutout: '70%'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const percentage = totalCount > 0 ? ((context.parsed / totalCount) * 100).toFixed(1) : 0;
                                        return context.label + ': ' + context.parsed + ' patients (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
