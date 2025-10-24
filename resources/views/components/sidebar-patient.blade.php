<div class="bg-white border-r border-gray-200 w-64 min-h-screen p-4 flex flex-col">
    <div class="mb-6 pt-4">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white font-semibold mr-3">
                {{ strtoupper(substr(auth()->user()->name ?? 'PT', 0, 2)) }}
            </div>
            <div>
                <h2 class="text-sm font-semibold text-gray-900">Patient Portal</h2>
                <p class="text-xs text-gray-500">{{ auth()->user()->name }}</p>
            </div>
        </div>
    </div>
    <ul class="space-y-1 flex-1">
        <li>
            <a href="{{ route('dashboard.patient') }}"
               class="flex items-center py-2 px-3 rounded-lg transition-colors duration-150 {{ request()->routeIs('dashboard.patient') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"/></svg>
                <span class="text-sm font-medium">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('patient.ehr.index') }}"
               class="flex items-center py-2 px-3 rounded-lg transition-colors duration-150 {{ request()->routeIs('patient.ehr.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293L20 9.414A1 1 0 0120.293 10H19v9a2 2 0 01-2 2z"/></svg>
                <span class="text-sm font-medium">EHR</span>
            </a>
        </li>
        <li>
            <a href="{{ route('patient.prescriptions.index') }}"
               class="flex items-center py-2 px-3 rounded-lg transition-colors duration-150 {{ request()->routeIs('patient.prescriptions.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m-6 4h6m-9 6h12M5 7h.01M5 11h.01M5 15h.01"/></svg>
                <span class="text-sm font-medium">My Prescriptions</span>
            </a>
        </li>
        <li>
            <a href="{{ route('patient.appointments.index') }}"
               class="flex items-center py-2 px-3 rounded-lg transition-colors duration-150 {{ request()->routeIs('patient.appointments.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="text-sm font-medium">Appointments</span>
            </a>
        </li>
        <li>
            <a href="{{ route('patient.announcements.index') }}"
               class="flex items-center py-2 px-3 rounded-lg transition-colors duration-150 {{ request()->routeIs('patient.announcements.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                <span class="text-sm font-medium">Announcements</span>
            </a>
        </li>
        <li>
            <button type="button"
                    class="w-full text-left py-2 px-3 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center justify-between transition-colors duration-150"
                    onclick="document.getElementById('patient-maternal-submenu').classList.toggle('hidden')"
                    aria-expanded="false"
                    aria-controls="patient-maternal-submenu">
                <span class="flex items-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293L20 9.414A1 1 0 0120.293 10H19v9a2 2 0 01-2 2z"/></svg> <span class="text-sm font-medium">Maternal Care Records</span></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <ul id="patient-maternal-submenu" class="hidden pl-8 mt-2 space-y-1" aria-label="Maternal Care Submenu">
                @foreach(['prenatal' => 'Prenatal Visits', 'postnatal' => 'Postnatal Visits', 'maternal_health' => 'Maternal Health Visits', 'vitals' => 'Vital Signs', 'checkup' => 'Checkups', 'followup' => 'Follow-ups'] as $type => $label)
                <li>
                    <a href="{{ route('patient.maternal.index', ['type' => $type]) }}"
                       class="block py-2 px-3 rounded-lg transition-colors duration-150 {{ request()->routeIs('patient.maternal.index') && request('type') === $type ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} text-sm">
                        <span class="font-medium">{{ $label }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </li>
        <li>
            <a href="{{ route('messages.index') }}"
               class="flex items-center justify-between py-2 px-3 rounded-lg transition-colors duration-150 {{ request()->routeIs('messages.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <span class="text-sm font-medium">Messages / Feedback</span>
                @php
                    $unreadCount = \App\Models\Message::where('receiver_id', auth()->id())
                        ->where('status', 'unread')
                        ->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="inline-flex items-center justify-center h-5 w-5 rounded-full text-[10px] font-semibold bg-red-600 text-white">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>
        </li>
    </ul>
</div>
