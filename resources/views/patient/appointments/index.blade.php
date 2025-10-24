<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Appointments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">My Appointments</h3>
                        <p class="text-gray-600">View and manage your scheduled appointments.</p>
                    </div>
                    <div>
                        <button type="button" onclick="openBookingModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-semibold">
                            Book Appointment
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 border rounded-lg shadow-sm">
                        <h4 class="font-semibold mb-2">Upcoming Appointments</h4>
                        <div class="space-y-2">
                            @forelse ($upcomingAppointments as $appointment)
                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                <div>
                                    @if($appointment->doctor)
                                    <p class="font-medium">Dr. {{ $appointment->doctor->user->name }}</p>
                                    @elseif($appointment->midwife)
                                    <p class="font-medium">Midwife {{ $appointment->midwife->user->name }}</p>
                                    @endif
                                    <p class="text-sm text-gray-600">{{ $appointment->appointment_date->format('M j, Y') }}, {{ $appointment->appointment_time->format('g:i A') }}</p>
                                    <p class="text-sm text-gray-600">{{ $appointment->reason }}</p>
                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                        @if($appointment->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($appointment->status == 'approved') bg-blue-100 text-blue-800
                                        @elseif($appointment->status == 'completed') bg-green-100 text-green-800
                                        @elseif($appointment->status == 'declined') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                                <div class="flex flex-col space-y-1">
                                    <a href="{{ route('patient.appointments.show', $appointment->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm text-center">
                                        View
                                    </a>
                                    @if($appointment->status == 'pending')
                                        <a href="{{ route('patient.appointments.edit', $appointment->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white px-3 py-1 rounded text-sm text-center">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('patient.appointments.destroy', $appointment->id) }}" onsubmit="return confirm('Are you sure you want to cancel this appointment?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded text-sm w-full">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500">No upcoming appointments.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="p-4 border rounded-lg shadow-sm">
                        <h4 class="font-semibold mb-2">Past Appointments</h4>
                        <div class="space-y-2">
                            @forelse ($pastAppointments as $appointment)
                            <div class="p-2 bg-gray-50 rounded">
                                @if($appointment->doctor)
                                <p class="font-medium">Dr. {{ $appointment->doctor->user->name }}</p>
                                @elseif($appointment->midwife)
                                <p class="font-medium">Midwife {{ $appointment->midwife->user->name }}</p>
                                @endif
                                <p class="text-sm text-gray-600">{{ $appointment->appointment_date->format('M j, Y') }}, {{ $appointment->appointment_time->format('g:i A') }}</p>
                                <p class="text-sm text-gray-600">{{ $appointment->reason }}</p>
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full {{ $appointment->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </div>
                            @empty
                            <p class="text-gray-500">No past appointments.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Booking Modal -->
<div id="bookingModal" class="fixed inset-0 bg-gray-800/60 backdrop-blur-sm hidden z-50 overflow-y-auto">
    <div class="relative max-w-5xl mx-auto mt-10 mb-6 bg-white rounded-xl shadow-xl overflow-hidden max-h-[85vh] flex flex-col">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Book an Appointment</h3>
                <p class="text-sm text-gray-500">Select a date (Wednesdays only), provider, and time slot.</p>
            </div>
            <button type="button" onclick="closeBookingModal()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6 flex-1 overflow-y-auto">
            <!-- Calendar Column -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-2">
                        <button type="button" class="p-2 rounded hover:bg-gray-100" onclick="prevMonth()" aria-label="Previous month">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button type="button" class="p-2 rounded hover:bg-gray-100" onclick="nextMonth()" aria-label="Next month">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                    <h4 id="calendarTitle" class="text-sm font-semibold text-gray-900">Month YYYY</h4>
                    <div></div>
                </div>

                <!-- Days of Week -->
                <div class="grid grid-cols-7 gap-1 mb-2">
                    @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                        <div class="text-center py-1 text-xs font-medium text-gray-600">{{ $day }}</div>
                    @endforeach
                </div>

                <!-- Calendar Grid -->
                <div id="calendarGrid" class="grid grid-cols-7 gap-1"></div>

                <div class="mt-3 flex items-center space-x-3 text-xs text-gray-600">
                    <div class="flex items-center space-x-1"><span class="inline-block w-3 h-3 rounded bg-emerald-500"></span><span>Selected</span></div>
                    <div class="flex items-center space-x-1"><span class="inline-block w-3 h-3 rounded bg-blue-500"></span><span>Today</span></div>
                    <div class="flex items-center space-x-1"><span class="inline-block w-3 h-3 rounded bg-gray-200 border"></span><span>Unavailable</span></div>
                </div>
            </div>

            <!-- Form Column -->
            <div>
                <form id="bookingForm" method="POST" action="{{ route('patient.appointments.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" id="appointment_date" name="appointment_date">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Doctor</label>
                            <select name="doctor_id" id="doctor_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Select doctor (optional)</option>
                                @foreach(\App\Models\Doctor::all() as $doc)
                                    <option value="{{ $doc->id }}">Dr. {{ $doc->full_name ?? $doc->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Midwife</label>
                            <select name="midwife_id" id="midwife_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Select midwife (optional)</option>
                                @foreach(\App\Models\Midwife::all() as $mw)
                                    <option value="{{ $mw->id }}">{{ $mw->full_name ?? $mw->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Time Slot</label>
                        <div id="timeSlots" class="grid grid-cols-3 gap-2"></div>
                        <input type="hidden" id="appointment_time" name="appointment_time">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                        <input type="text" name="reason" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500" placeholder="e.g., Prenatal checkup" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload Files (optional)</label>
                        <input type="file" name="uploaded_files[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="w-full border-gray-300 rounded-md shadow-sm file:mr-4 file:py-2 file:px-3 file:rounded file:border-0 file:text-sm file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        <p class="mt-1 text-xs text-gray-500">Max 5MB each, PDF/DOC/JPG/PNG.</p>
                    </div>

                    <div class="flex items-center justify-end space-x-2 pt-2">
                        <button type="button" onclick="closeBookingModal()" class="px-4 py-2 rounded border text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded bg-emerald-600 hover:bg-emerald-700 text-white">Confirm Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Modal controls
    function openBookingModal() {
        document.getElementById('bookingModal').classList.remove('hidden');
        initCalendar();
    }
    function closeBookingModal() {
        document.getElementById('bookingModal').classList.add('hidden');
    }

    // Calendar state
    let current = new Date();
    let selectedDate = null; // YYYY-MM-DD

    function formatDate(d) {
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${y}-${m}-${day}`;
    }

    function isWednesday(d) {
        return d.getDay() === 3; // 0=Sun
    }

    function setCalendarTitle() {
        const title = document.getElementById('calendarTitle');
        const month = current.toLocaleString('default', { month: 'long' });
        title.textContent = `${month} ${current.getFullYear()}`;
    }

    function buildCalendarGrid() {
        const grid = document.getElementById('calendarGrid');
        grid.innerHTML = '';

        const year = current.getFullYear();
        const month = current.getMonth();
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const start = new Date(firstDay);
        start.setDate(firstDay.getDate() - firstDay.getDay()); // back to Sunday
        const end = new Date(lastDay);
        end.setDate(lastDay.getDate() + (6 - lastDay.getDay())); // forward to Saturday

        const todayStr = formatDate(new Date());

        for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
            const dateStr = formatDate(d);
            const inMonth = d.getMonth() === month;
            const wednesday = isWednesday(d);
            const isPast = dateStr < todayStr;

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'aspect-square rounded text-sm flex items-center justify-center ' +
                (dateStr === todayStr ? 'border border-blue-500 text-blue-600 ' : '') +
                (selectedDate === dateStr ? 'bg-emerald-500 text-white ' : '') +
                (!inMonth ? 'text-gray-300 ' : '') +
                (!wednesday || isPast ? 'bg-gray-100 text-gray-400 cursor-not-allowed ' : 'hover:bg-gray-50 ');
            btn.textContent = d.getDate();
            if (wednesday && inMonth && !isPast) {
                btn.addEventListener('click', () => {
                    selectedDate = dateStr;
                    document.getElementById('appointment_date').value = selectedDate;
                    buildCalendarGrid();
                    loadTimeSlots();
                });
            }
            grid.appendChild(btn);
        }
    }

    function prevMonth() {
        current.setMonth(current.getMonth() - 1);
        setCalendarTitle();
        buildCalendarGrid();
    }
    function nextMonth() {
        current.setMonth(current.getMonth() + 1);
        setCalendarTitle();
        buildCalendarGrid();
    }

    function initCalendar() {
        setCalendarTitle();
        buildCalendarGrid();
        loadTimeSlots();
    }

    // Time slots (static 30-min slots 08:00-16:30; could be replaced by AJAX)
    function loadTimeSlots() {
        const container = document.getElementById('timeSlots');
        container.innerHTML = '';
        const times = [];
        for (let h = 8; h <= 16; h++) {
            for (let m = 0; m < 60; m += 30) {
                const hh = String(h).padStart(2, '0');
                const mm = String(m).padStart(2, '0');
                times.push(`${hh}:${mm}`);
            }
        }
        times.forEach(t => {
            const b = document.createElement('button');
            b.type = 'button';
            b.textContent = t;
            b.className = 'py-2 px-3 rounded border text-sm ' + (document.getElementById('appointment_time').value === t ? 'bg-emerald-600 text-white border-emerald-600' : 'hover:bg-gray-50');
            b.addEventListener('click', () => {
                document.getElementById('appointment_time').value = t;
                loadTimeSlots();
            });
            container.appendChild(b);
        });
    }
</script>
