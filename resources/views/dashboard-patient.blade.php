<x-app-layout>
    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }}</h2>
                <p class="text-gray-600 mt-2">Manage your health records and appointments efficiently.</p>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 mb-4">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('patient.appointments.index') }}" class="flex items-center bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium py-2 px-3 rounded-lg transition-colors duration-200 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Book Appointment
                    </a>
                </div>
            </div>

            <!-- Top Stats Cards -->
            <div class="grid grid-cols-3 gap-3 mb-4">
                <!-- Upcoming Appointments Card -->
                <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-600 text-xs font-medium">Upcoming Appointments</p>
                            <p class="text-lg font-bold text-gray-900">{{ $upcomingAppointments->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Appointments Card -->
                <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-600 text-xs font-medium">Total Appointments</p>
                            <p class="text-lg font-bold text-gray-900">{{ $totalAppointments }}</p>
                        </div>
                    </div>
                </div>

                <!-- Active Prescriptions Card -->
                <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-600 text-xs font-medium">Active Prescriptions</p>
                            <p class="text-lg font-bold text-gray-900">{{ $activePrescriptions }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-3">
                <!-- Recent Appointments -->
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900">Recent Appointments</h3>
                        <a href="{{ route('patient.appointments.index') }}" class="text-blue-600 text-xs font-medium hover:text-blue-700">See All</a>
                    </div>

                    <div class="space-y-2">
                        @forelse($recentAppointments as $appointment)
                        <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-200 rounded-full mr-2 flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-600">{{ strtoupper(substr($appointment->patient->full_name ?? 'N/A', 0, 2)) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">{{ $appointment->patient->full_name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $appointment->reason ?? 'General Checkup' }}</p>
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
                            <p class="text-gray-500 text-sm">No recent appointments</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Doctor Availability Calendar -->
                <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Doctor Availability</h3>
                        <span class="text-sm text-gray-600">{{ \Carbon\Carbon::now()->format('F Y') }}</span>
                    </div>

                    <!-- Days of Week Header -->
                    <div class="grid grid-cols-7 gap-1 mb-3">
                        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                        <div class="text-center py-2 text-sm font-medium text-gray-700 bg-gray-50 rounded">
                            {{ $day }}
                        </div>
                        @endforeach
                    </div>

                    <!-- Calendar Grid -->
                    <div class="grid grid-cols-7 gap-1">
                        @php
                            $today = \Carbon\Carbon::today();
                            $startOfMonth = $today->copy()->startOfMonth();
                            $endOfMonth = $today->copy()->endOfMonth();
                            $startOfCalendar = $startOfMonth->copy()->startOfWeek(0); // Sunday
                            $endOfCalendar = $endOfMonth->copy()->endOfWeek(6); // Saturday
                            $currentDate = $startOfCalendar->copy();

                            // Get doctors' leaves for current month
                            $doctorLeaves = App\Models\DoctorLeave::where(function($query) use ($startOfMonth, $endOfMonth) {
                                $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                                      ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                                      ->orWhere(function($q) use ($startOfMonth, $endOfMonth) {
                                          $q->where('start_date', '<=', $startOfMonth)
                                            ->where('end_date', '>=', $endOfMonth);
                                      });
                            })
                            ->get()
                            ->keyBy(function($leave) {
                                return $leave->start_date . '-' . $leave->end_date . '-' . $leave->type;
                            });
                        @endphp

                        @while($currentDate <= $endOfCalendar)
                            @php
                                $isLeaveDay = false;
                                foreach($doctorLeaves as $leave) {
                                    if($currentDate->between(\Carbon\Carbon::parse($leave->start_date), \Carbon\Carbon::parse($leave->end_date))) {
                                        $isLeaveDay = true;
                                        break;
                                    }
                                }
                                $isWednesday = $currentDate->dayOfWeek == 3; // Wednesday
                                $isAvailableDay = $isWednesday && !$isLeaveDay;
                            @endphp

                            <div class="aspect-square flex items-center justify-center">
                                @if($currentDate < $startOfMonth || $currentDate > $endOfMonth)
                                    <!-- Previous/Next month days -->
                                    <div class="w-8 h-8 flex items-center justify-center text-xs text-gray-400">
                                        {{ $currentDate->day }}
                                    </div>
                                @else
                                    <!-- Current month days -->
                                    @if($currentDate->isToday())
                                        <div class="w-8 h-8 bg-blue-600 text-white rounded-lg flex items-center justify-center text-sm font-semibold">
                                            {{ $currentDate->day }}
                                        </div>
                                    @elseif($isLeaveDay && $isWednesday)
                                        <div class="w-8 h-8 bg-orange-500 text-white rounded-lg flex items-center justify-center text-sm font-semibold" title="Doctor on Leave">
                                            {{ $currentDate->day }}
                                        </div>
                                    @elseif($isAvailableDay)
                                        <div class="w-8 h-8 bg-green-500 text-white rounded-lg flex items-center justify-center text-sm font-semibold" title="Doctor Available">
                                            {{ $currentDate->day }}
                                        </div>
                                    @else
                                        <div class="w-8 h-8 hover:bg-gray-100 rounded-lg flex items-center justify-center text-sm text-gray-700">
                                            {{ $currentDate->day }}
                                        </div>
                                    @endif
                                @endif
                            </div>
                            @php $currentDate->addDay(); @endphp
                        @endwhile
                    </div>

                    <!-- Legend -->
                    <div class="mt-4 pt-3 border-t border-gray-200">
                        <div class="flex items-center justify-center space-x-6 text-xs">
                            <div class="flex items-center space-x-1">
                                <div class="w-3 h-3 bg-blue-600 rounded"></div>
                                <span class="text-gray-600">Today</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <div class="w-3 h-3 bg-green-500 rounded"></div>
                                <span class="text-gray-600">Available</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <div class="w-3 h-3 bg-orange-500 rounded"></div>
                                <span class="text-gray-600">Leave</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Health Tips -->
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900">Health Tips</h3>
                        <a href="#" class="text-blue-600 text-xs font-medium hover:text-blue-700">See All</a>
                    </div>

                    <div class="space-y-3">
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <h4 class="font-medium text-gray-900 text-sm mb-1">Stay Hydrated</h4>
                            <p class="text-xs text-gray-600">Drink at least 8 glasses of water daily.</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg">
                            <h4 class="font-medium text-gray-900 text-sm mb-1">Daily Exercise</h4>
                            <p class="text-xs text-gray-600">Take a 30-minute walk daily.</p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-lg">
                            <h4 class="font-medium text-gray-900 text-sm mb-1">Mental Health</h4>
                            <p class="text-xs text-gray-600">Practice mindfulness for 10 minutes.</p>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>


</x-app-layout>
