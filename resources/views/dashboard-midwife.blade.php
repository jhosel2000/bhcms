<x-app-layout>
    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }}</h2>
                <p class="text-gray-600 mt-2">Manage maternal care and patient appointments efficiently.</p>
            </div>

            <!-- Quick Actions - Compact -->
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 mb-4">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('midwife.appointments.create') }}" class="flex items-center bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium py-2 px-3 rounded-lg transition-colors duration-200 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Schedule Appointment
                    </a>
                    <a href="{{ route('midwife.patients.index') }}" class="flex items-center bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium py-2 px-3 rounded-lg transition-colors duration-200 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        View Patients
                    </a>
                    <a href="{{ route('midwife.ehr.index') }}" class="flex items-center bg-green-50 hover:bg-green-100 text-green-700 font-medium py-2 px-3 rounded-lg transition-colors duration-200 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        EHR Records
                    </a>

                </div>
            </div>

            <!-- Top Stats Cards - Compact -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
                <!-- Total Patient Card -->
                <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-600 text-xs font-medium">Total Patient</p>
                            <p class="text-lg font-bold text-gray-900">{{ $totalPatients ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Today Patient Card -->
                <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-600 text-xs font-medium">Today Patients</p>
                            <p class="text-lg font-bold text-gray-900">{{ $todayPatients ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Today Appointments Card -->
                <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-600 text-xs font-medium">Today Appointments</p>
                            <p class="text-lg font-bold text-gray-900">{{ $todayAppointments->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid - 3 Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-3">
                <!-- Left Column - Appointment Request -->
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900">Appointment Requests</h3>
                        <a href="{{ route('midwife.appointments.index') }}" class="text-blue-600 text-xs font-medium hover:text-blue-700 transition-colors">See All</a>
                    </div>

                    <div class="space-y-2">
                        @php
                            // Get pending appointments (scheduled status) for approval
                            $pendingAppointments = App\Models\Appointment::where('midwife_id', auth()->user()->midwife->id)
                                ->where('status', 'scheduled')
                                ->with('patient')
                                ->orderBy('appointment_date')
                                ->orderBy('appointment_time')
                                ->take(3)
                                ->get();
                        @endphp

                        @forelse($pendingAppointments as $appointment)
                        <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 rounded-full mr-2 flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-600">{{ strtoupper(substr($appointment->patient->full_name ?? 'N/A', 0, 2)) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">{{ $appointment->patient->full_name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $appointment->reason ?? 'Maternal Checkup' }}</p>
                                </div>
                            </div>
                            <div class="flex space-x-1">
                                <form method="POST" action="{{ route('midwife.appointments.status.update', $appointment->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to decline this appointment?')">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="px-2 py-1 bg-red-100 text-red-600 text-xs rounded hover:bg-red-200 transition-colors" aria-label="Decline appointment">
                                        Decline
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('midwife.appointments.status.update', $appointment->id) }}" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="px-2 py-1 bg-blue-100 text-blue-600 text-xs rounded hover:bg-blue-200 transition-colors" aria-label="Accept appointment">
                                        Accept
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <p class="text-gray-500 text-sm">No pending appointment requests</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Middle Column - Calendar -->
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900">Calendar</h3>
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

                <!-- Right Column - Today Appointment -->
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900">Today Appointment</h3>
                        <a href="{{ route('midwife.appointments.index') }}" class="text-blue-600 text-xs font-medium hover:text-blue-700">See All</a>
                    </div>

                    <div class="space-y-2">
                        @forelse($todayAppointments as $appointment)
                        <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 rounded-full mr-2 flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-600">{{ strtoupper(substr($appointment->patient->full_name ?? 'N/A', 0, 2)) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">{{ $appointment->patient->full_name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $appointment->reason ?? 'Maternal Checkup' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-medium {{ $appointment->status === 'completed' ? 'text-green-600' : 'text-blue-600' }}">
                                    {{ ucfirst($appointment->status ?? 'scheduled') }}
                                </p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <p class="text-gray-500 text-sm">No appointments scheduled for today</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Patient Summary Chart & Next Patient Details - Full Width Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                <!-- Patient Summary Chart -->
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Patient Summary {{ \Carbon\Carbon::now()->format('F Y') }}</h3>
                    <div class="flex flex-col items-center justify-center text-center">
                        <div class="relative w-full max-w-32 h-32 flex items-center justify-center mb-3">
                            <canvas id="patientSummaryChart" width="128" height="128"></canvas>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center justify-center">
                                <div class="w-3 h-3 bg-blue-500 rounded mr-2"></div>
                                <span class="text-xs text-gray-600">New Patients</span>
                            </div>
                            <div class="flex items-center justify-center">
                                <div class="w-3 h-3 bg-orange-400 rounded mr-2"></div>
                                <span class="text-xs text-gray-600">Old Patients</span>
                            </div>
                            <div class="flex items-center justify-center">
                                <div class="w-3 h-3 bg-gray-300 rounded mr-2"></div>
                                <span class="text-xs text-gray-600">Total Patients</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Next Patient Details -->
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Next Patient Details</h3>

                    @php
                        $nextAppointment = $upcomingAppointments->first();
                    @endphp

                    @if($nextAppointment && $nextAppointment->patient)
                    <div class="text-center mb-3">
                        <div class="w-12 h-12 bg-gray-200 rounded-full mx-auto mb-2 flex items-center justify-center">
                            <span class="text-sm font-medium text-gray-600">{{ strtoupper(substr($nextAppointment->patient->full_name ?? 'N/A', 0, 2)) }}</span>
                        </div>
                        <h4 class="font-semibold text-gray-900 text-sm">{{ $nextAppointment->patient->full_name ?? 'N/A' }}</h4>
                        <p class="text-xs text-gray-500">{{ $nextAppointment->reason ?? 'Maternal Checkup' }}</p>
                    </div>

                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Patient ID</span>
                            <span class="font-medium">{{ $nextAppointment->patient->id ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sex</span>
                            <span class="font-medium">{{ ucfirst($nextAppointment->patient->gender ?? 'N/A') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Age</span>
                            <span class="font-medium">
                                @if($nextAppointment->patient->date_of_birth)
                                    {{ \Carbon\Carbon::parse($nextAppointment->patient->date_of_birth)->age }} Years
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Time</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($nextAppointment->appointment_time)->format('h:i A') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Date</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($nextAppointment->appointment_date)->format('d M Y') }}</span>
                        </div>
                    </div>
                    @else
                    <div class="text-center mb-3">
                        <div class="w-12 h-12 bg-gray-200 rounded-full mx-auto mb-2 flex items-center justify-center">
                            <span class="text-sm font-medium text-gray-600">N/A</span>
                        </div>
                        <h4 class="font-semibold text-gray-900 text-sm">No Upcoming Appointments</h4>
                        <p class="text-xs text-gray-500">No scheduled appointments</p>
                    </div>
                    @endif

                    <div class="mt-3 space-y-1">
                        @if($nextAppointment)
                        <a href="{{ route('midwife.appointments.edit', $nextAppointment->id) }}" class="block w-full bg-orange-100 text-orange-600 py-1.5 px-3 rounded text-xs font-medium hover:bg-orange-200 text-center transition-colors" aria-label="Reschedule appointment">
                            Reschedule
                        </a>
                        <a href="{{ route('midwife.appointments.show', $nextAppointment->id) }}" class="block w-full bg-blue-100 text-blue-600 py-1.5 px-3 rounded text-xs font-medium hover:bg-blue-200 text-center transition-colors" aria-label="View appointment details">
                            View Details
                        </a>
                        <form method="POST" action="{{ route('midwife.appointments.destroy', $nextAppointment->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-100 text-red-600 py-1.5 px-3 rounded text-xs font-medium hover:bg-red-200 transition-colors" aria-label="Cancel appointment">
                                Cancel
                            </button>
                        </form>
                        @endif
                    </div>

                    <div class="mt-3 pt-2 border-t border-gray-100">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-600">Last Prescription</span>
                            <div class="flex space-x-1">
                                <button class="text-blue-600 hover:text-blue-700 transition-colors" aria-label="View prescription">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                <button class="text-blue-600 hover:text-blue-700 transition-colors" aria-label="Edit prescription">
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
            // Patient Summary Doughnut Chart
            const ctx = document.getElementById('patientSummaryChart');
            if (ctx) {
                const chartCtx = ctx.getContext('2d');

                new Chart(chartCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['New Patients', 'Old Patients'],
                        datasets: [{
                            data: [{{ $newPatientsThisMonth ?? 0 }}, {{ ($totalPatients ?? 0) - ($newPatientsThisMonth ?? 0) }}],
                            backgroundColor: [
                                '#3B82F6', // Blue
                                '#FB923C'  // Orange
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
                                        return context.label + ': ' + context.parsed + ' patients';
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
