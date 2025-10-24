<x-app-layout>
    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Welcome back, Dr. {{ auth()->user()->name }}</h2>
                <p class="text-gray-600 mt-2">Manage your appointments and patient care efficiently.</p>
            </div>

            <!-- Quick Actions - Compact -->
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 mb-4">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('doctor.appointments.index') }}" class="flex items-center bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium py-2 px-3 rounded-lg transition-colors duration-200 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        View Appointments
                    </a>
                    <a href="{{ route('doctor.patients.index') }}" class="flex items-center bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium py-2 px-3 rounded-lg transition-colors duration-200 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        View Patients
                    </a>
                    <a href="{{ route('doctor.prescriptions.create') }}" class="flex items-center bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium py-2 px-3 rounded-lg transition-colors duration-200 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Create Prescription
                    </a>
                    <a href="{{ route('doctor.ehr.index') }}" class="flex items-center bg-green-50 hover:bg-green-100 text-green-700 font-medium py-2 px-3 rounded-lg transition-colors duration-200 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        EHR Records
                    </a>
                    <a href="{{ route('doctor.leaves.create') }}?type=leave" class="flex items-center bg-orange-50 hover:bg-orange-100 text-orange-700 font-medium py-2 px-3 rounded-lg transition-colors duration-200 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Mark Leave
                    </a>


                </div>
            </div>

            <!-- Top Stats Cards - Compact -->
            <div class="grid grid-cols-3 gap-3 mb-4">
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
                            <p class="text-gray-600 text-xs font-medium">Today Patient</p>
                            <p class="text-lg font-bold text-gray-900">{{ $todayAppointments->count() }}</p>
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
                        <h3 class="text-sm font-semibold text-gray-900">Appointment Request</h3>
                        <a href="{{ route('doctor.appointments.index') }}" class="text-blue-600 text-xs font-medium hover:text-blue-700">See All</a>
                    </div>

                    @php
                        // Get pending appointments for approval
                        $pendingAppointments = App\Models\Appointment::where('doctor_id', auth()->user()->doctor->id)
                            ->where('status', 'pending')
                            ->with('patient')
                            ->orderBy('appointment_date')
                            ->orderBy('appointment_time')
                            ->take(3)
                            ->get();
                    @endphp

                    @if($pendingAppointments->count() > 0)
                    <!-- Bulk Actions -->
                    <form method="POST" action="{{ route('doctor.appointments.bulk-update-status') }}" id="bulk-appointment-form" class="mb-3">
                        @csrf
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" id="select-all" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="select-all" class="text-xs text-gray-600">Select All</label>
                            </div>
                            <div class="flex space-x-1">
                                <button type="submit" name="status" value="approved" class="px-2 py-1 bg-blue-100 text-blue-600 text-xs rounded hover:bg-blue-200 disabled:opacity-50" disabled id="bulk-approve">
                                    Approve Selected
                                </button>
                                <button type="submit" name="status" value="declined" class="px-2 py-1 bg-red-100 text-red-600 text-xs rounded hover:bg-red-200 disabled:opacity-50" disabled id="bulk-decline" onclick="return confirm('Are you sure you want to decline the selected appointments?')">
                                    Decline Selected
                                </button>
                            </div>
                        </div>
                    </form>
                    @endif

                    <div class="space-y-2">
                        @forelse($pendingAppointments as $appointment)
                        <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <input type="checkbox" name="appointment_ids[]" value="{{ $appointment->id }}" form="bulk-appointment-form" class="appointment-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 mr-2">
                                <div class="w-8 h-8 bg-gray-200 rounded-full mr-2 flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-600">{{ strtoupper(substr($appointment->patient->full_name ?? 'N/A', 0, 2)) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">{{ $appointment->patient->full_name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $appointment->reason ?? 'General Checkup' }}</p>
                                </div>
                            </div>
                            <div class="flex space-x-1">
                                <form method="POST" action="{{ route('doctor.appointments.status.update', $appointment->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to decline this appointment?')">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="declined">
                                    <button type="submit" class="px-2 py-1 bg-red-100 text-red-600 text-xs rounded hover:bg-red-200">
                                        Decline
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('doctor.appointments.status.update', $appointment->id) }}" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="px-2 py-1 bg-blue-100 text-blue-600 text-xs rounded hover:bg-blue-200">
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
                <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Calendar</h3>
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
                            $startOfCalendar = $startOfMonth->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
                            $endOfCalendar = $endOfMonth->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
                            $currentDate = $startOfCalendar->copy();

                            // Get doctor's leaves for current month
                            $doctorLeaves = App\Models\DoctorLeave::where('doctor_id', auth()->user()->doctor->id)
                                ->where(function($query) use ($startOfMonth, $endOfMonth) {
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
                                $leaveType = null;
                                foreach($doctorLeaves as $leave) {
                                    if($currentDate->between(\Carbon\Carbon::parse($leave->start_date), \Carbon\Carbon::parse($leave->end_date))) {
                                        $isLeaveDay = true;
                                        $leaveType = $leave->type;
                                        break;
                                    }
                                }
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
                                    @elseif($isLeaveDay)
                                        @if($leaveType === 'leave')
                                            <div class="w-8 h-8 bg-orange-500 text-white rounded-lg flex items-center justify-center text-sm font-semibold" title="On Leave">
                                                {{ $currentDate->day }}
                                            </div>
                                        @else
                                            <div class="w-8 h-8 bg-red-500 text-white rounded-lg flex items-center justify-center text-sm font-semibold" title="Sick Leave">
                                                {{ $currentDate->day }}
                                            </div>
                                        @endif
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
                                <div class="w-3 h-3 bg-orange-500 rounded"></div>
                                <span class="text-gray-600">Leave</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <div class="w-3 h-3 bg-red-500 rounded"></div>
                                <span class="text-gray-600">Sick Leave</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Today Appointment -->
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900">Today Appointment</h3>
                        <a href="{{ route('doctor.appointments.index') }}" class="text-blue-600 text-xs font-medium hover:text-blue-700">See All</a>
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
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Patients Summary {{ \Carbon\Carbon::now()->format('F Y') }}</h3>
                    <div class="flex flex-col items-center justify-center text-center">
                        <div class="relative w-32 h-32 flex items-center justify-center mb-3">
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
                        <p class="text-xs text-gray-500">{{ $nextAppointment->reason ?? 'General Checkup' }}</p>
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
                        <a href="{{ route('doctor.appointments.show', $nextAppointment->id) }}" class="block w-full bg-blue-100 text-blue-600 py-1.5 px-3 rounded text-xs font-medium hover:bg-blue-200 text-center">
                            View Details
                        </a>
                        @endif
                    </div>

                    <div class="mt-3 pt-2 border-t border-gray-100">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-600">Last Prescription</span>
                            <div class="flex space-x-1">
                                <button class="text-blue-600 hover:text-blue-700">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                <button class="text-blue-600 hover:text-blue-700">
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
            const ctx = document.getElementById('patientSummaryChart').getContext('2d');

            new Chart(ctx, {
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
                                    return context.label + ': ' + context.parsed + '%';
                                }
                            }
                        }
                    }
                }
            });

            // Bulk appointment actions
            const selectAllCheckbox = document.getElementById('select-all');
            const appointmentCheckboxes = document.querySelectorAll('.appointment-checkbox');
            const bulkApproveBtn = document.getElementById('bulk-approve');
            const bulkDeclineBtn = document.getElementById('bulk-decline');

            if (selectAllCheckbox && appointmentCheckboxes.length > 0) {
                // Handle select all checkbox
                selectAllCheckbox.addEventListener('change', function() {
                    appointmentCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateBulkButtons();
                });

                // Handle individual checkboxes
                appointmentCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const checkedBoxes = document.querySelectorAll('.appointment-checkbox:checked');
                        selectAllCheckbox.checked = checkedBoxes.length === appointmentCheckboxes.length;
                        selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < appointmentCheckboxes.length;
                        updateBulkButtons();
                    });
                });

                function updateBulkButtons() {
                    const checkedBoxes = document.querySelectorAll('.appointment-checkbox:checked');
                    const hasSelection = checkedBoxes.length > 0;
                    bulkApproveBtn.disabled = !hasSelection;
                    bulkDeclineBtn.disabled = !hasSelection;
                }
            }
        });
    </script>
</x-app-layout>
