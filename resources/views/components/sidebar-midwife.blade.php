<div class="bg-white border-r border-gray-200 w-64 min-h-screen p-4 flex flex-col">
    <div class="mb-6 pt-4">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white font-semibold mr-3">
                {{ strtoupper(substr(auth()->user()->name ?? 'MW', 0, 2)) }}
            </div>
            <div>
                <h2 class="text-sm font-semibold text-gray-900">Midwife Portal</h2>
                <p class="text-xs text-gray-500">{{ auth()->user()->name }}</p>
            </div>
        </div>
    </div>
    <ul class="space-y-1 flex-1">
        <li>
            <a href="{{ route('dashboard.midwife') }}"
               class="flex items-center py-2 px-3 rounded-lg transition-colors duration-150 {{ request()->routeIs('dashboard.midwife') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                <span class="text-sm font-medium">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('midwife.patients.index') }}"
               class="flex items-center py-2 px-3 rounded-lg transition-colors duration-150 {{ request()->routeIs('midwife.patients.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                <span class="text-sm font-medium">Patients</span>
            </a>
        </li>
        <li>
            <a href="{{ route('midwife.appointments.index') }}"
               class="flex items-center py-2 px-3 rounded-lg transition-colors duration-150 {{ request()->routeIs('midwife.appointments.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                <span class="text-sm font-medium">Appointments</span>
            </a>
        </li>
        <li>
            <a href="{{ route('midwife.announcements.index') }}"
               class="flex items-center py-2 px-3 rounded-lg transition-colors duration-150 {{ request()->routeIs('midwife.announcements.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                <span class="text-sm font-medium">Announcements</span>
            </a>
        </li>
        <li>
            <button type="button"
                    class="w-full text-left py-2 px-3 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center justify-between transition-colors duration-150"
                    onclick="document.getElementById('maternal-care-submenu').classList.toggle('hidden')"
                    aria-expanded="false"
                    aria-controls="maternal-care-submenu">
                <span class="flex items-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg> <span class="text-sm font-medium">Maternal Care Records</span></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <ul id="maternal-care-submenu" class="hidden pl-8 mt-2 space-y-1" aria-label="Maternal Care Submenu">
                @foreach(['prenatal' => 'Prenatal Visits', 'postnatal' => 'Postnatal Visits', 'maternal_health' => 'Maternal Health Visits', 'vitals' => 'Vital Signs', 'checkup' => 'Checkups', 'followup' => 'Follow-ups'] as $type => $label)
                <li>
                    <a href="{{ route('midwife.maternal.index', ['type' => $type]) }}"
                       class="block py-2 px-3 rounded-lg transition-colors duration-150 {{ request()->routeIs('midwife.maternal.index') && request('type') === $type ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} text-sm">
                        <span class="font-medium">{{ $label }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </li>
        <li>
            <button type="button"
                    class="w-full text-left py-2 px-3 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center justify-between transition-colors duration-150"
                    onclick="document.getElementById('reports-analytics-submenu').classList.toggle('hidden')"
                    aria-expanded="false"
                    aria-controls="reports-analytics-submenu">
                <span class="flex items-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg> <span class="text-sm font-medium">Reports & Analytics</span></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <ul id="reports-analytics-submenu" class="hidden pl-8 mt-2 space-y-1" aria-label="Reports & Analytics Submenu">
                <li>
                    <a href="{{ route('midwife.reports.statistics') }}"
                       class="block py-2 px-3 rounded-lg transition-colors duration-150 {{ request()->routeIs('midwife.reports.statistics') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} text-sm">
                        <span class="font-medium">Statistics</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('midwife.reports.printable') }}"
                       class="block py-2 px-3 rounded-lg transition-colors duration-150 {{ request()->routeIs('midwife.reports.printable') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }} text-sm">
                        <span class="font-medium">Printable Reports</span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{ route('messages.index') }}"
               class="flex items-center justify-between py-2 px-3 rounded-lg transition-colors duration-150 {{ request()->routeIs('messages.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                    <span class="text-sm font-medium">Messages / Feedback</span>
                </span>
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