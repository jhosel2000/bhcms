<div id="sidebar-doctor" class="bg-white border-r border-gray-200 w-64 min-h-screen p-4 flex flex-col">
    <div class="mb-6 pt-4">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white font-semibold mr-3">
                {{ strtoupper(substr(auth()->user()->name ?? 'DR', 0, 2)) }}
            </div>
            <div>
                <h2 class="text-sm font-semibold text-gray-900">Doctor Portal</h2>
                <p class="text-xs text-gray-500">{{ auth()->user()->name }}</p>
            </div>
        </div>
    </div>

    <ul class="space-y-1 flex-1">
        <li>
            <a href="{{ route('dashboard.doctor') }}" class="flex items-center py-2 px-3 rounded-lg transition-colors duration-150 {{ request()->routeIs('dashboard.doctor') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <span class="text-sm font-medium">Dashboard</span>
            </a>
        </li>

        <li>
            <a href="{{ route('doctor.patients.index') }}" class="flex items-center py-2 px-3 rounded-lg transition-colors duration-150 {{ request()->routeIs('doctor.patients.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="text-sm font-medium">Patients</span>
            </a>
        </li>

        <li>
            <a href="{{ route('doctor.appointments.index') }}" class="flex items-center py-2 px-3 rounded-lg transition-colors duration-150 {{ request()->routeIs('doctor.appointments.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-sm font-medium">Appointments</span>
            </a>
        </li>

        <li>
            <a href="{{ route('doctor.prescriptions.index') }}" class="flex items-center py-2 px-3 rounded-lg transition-colors duration-150 {{ request()->routeIs('doctor.prescriptions.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-sm font-medium">Prescriptions</span>
            </a>
        </li>

        <li>
            <a href="{{ route('doctor.ehr.index') }}" class="flex items-center py-2 px-3 rounded-lg transition-colors duration-150 {{ request()->routeIs('doctor.ehr.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-sm font-medium">EHR Records</span>
            </a>
        </li>

        <li>
            <button type="button" class="w-full text-left flex items-center justify-between py-2 px-3 rounded-lg transition-colors duration-150 text-gray-700 hover:bg-gray-50" onclick="toggleReportsMenu()">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="text-sm font-medium">Reports</span>
                </div>
                <svg id="reports-chevron" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <ul id="reports-analytics-submenu-doctor" class="pl-8 mt-1 space-y-1 hidden transition-all duration-300">
                <li>
                    <a href="{{ route('doctor.analytics.index') }}" class="flex items-center py-2 px-3 rounded-lg transition-colors duration-150 hover:bg-gray-50 text-sm {{ request()->routeIs('doctor.analytics.index') ? 'bg-blue-50 text-blue-700' : 'text-gray-700' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                        Statistics
                    </a>
                </li>
                <li>
                    <a href="{{ route('doctor.reports.printable') }}" class="flex items-center py-2 px-3 rounded-lg transition-colors duration-150 hover:bg-gray-50 text-sm {{ request()->routeIs('doctor.reports.printable') ? 'bg-blue-50 text-blue-700' : 'text-gray-700' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Printable Reports
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="{{ route('messages.index') }}" class="flex items-center justify-between py-2 px-3 rounded-lg transition-colors duration-150 {{ request()->routeIs('messages.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <span class="text-sm font-medium">Messages / Feedback</span>
                </div>
                @php
                    $unreadCount = \App\Models\Message::where('receiver_id', auth()->id())
                        ->where('status', 'unread')
                        ->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-red-500 text-[10px] font-semibold text-white">{{ $unreadCount }}</span>
                @endif
            </a>
        </li>
    </ul>
</div>

<script>
    function toggleReportsMenu() {
        const submenu = document.getElementById('reports-analytics-submenu-doctor');
        const chevron = document.getElementById('reports-chevron');

        submenu.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }
</script>

<style>
    .rotate-180 { transform: rotate(180deg); }
</style>