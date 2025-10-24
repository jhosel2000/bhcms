<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Patient Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4">Manage Patients</h3>
                    <p class="text-gray-600">View and manage patients.</p>
                </div>

                <div class="mb-6 relative">
                    <input type="text" id="search-input" placeholder="Search patients by name..." class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Search patients">
                    <button id="clear-search" class="absolute right-2 top-2 text-gray-400 hover:text-gray-600" aria-label="Clear search">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="mb-6">
                    <a href="{{ route('doctor.patients.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">

                    </a>
                </div>

                <div id="loading-indicator" class="hidden mb-6 text-center">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                    <p class="mt-2 text-gray-600">Searching...</p>
                </div>

                <div id="patients-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($patients as $patient)
                    <div class="p-4 border rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-300 cursor-pointer" tabindex="0" role="button" aria-pressed="false" aria-label="View details for {{ $patient->full_name }}">
                        <div class="flex items-center space-x-4 mb-3">
                            <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center text-lg font-bold">
                                {{ strtoupper(substr($patient->full_name, 0, 1)) }}
                            </div>
                            <h4 class="font-semibold text-lg">{{ $patient->full_name }}</h4>
                        </div>
                        <p class="text-sm text-gray-600">Age: {{ $patient->date_of_birth->age }}</p>
                        <p class="text-sm text-gray-600">Last Visit: {{ $patient->updated_at->format('Y-m-d') }}</p>
                        <a href="{{ route('doctor.patients.show', $patient->id) }}" class="mt-3 inline-block bg-gray-500 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm">
                            View Details
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const clearSearchBtn = document.getElementById('clear-search');
            const patientsGrid = document.getElementById('patients-grid');
            const loadingIndicator = document.getElementById('loading-indicator');

            let debounceTimer;

            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    const query = searchInput.value.trim();
                    fetchPatients(query);
                }, 300);
            });

            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                fetchPatients('');
            });

            async function fetchPatients(search = '') {
                loadingIndicator.classList.remove('hidden');
                try {
                    const url = '{{ route("doctor.patients.index") }}' + (search ? '?search=' + encodeURIComponent(search) : '');
                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    });
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    const data = await response.json();
                    updatePatientsGrid(data);
                } catch (error) {
                    console.error('Error fetching patients:', error);
                    // Optionally show error message to user
                    alert('Failed to fetch patients. Please try again.');
                } finally {
                    loadingIndicator.classList.add('hidden');
                }
            }

            function updatePatientsGrid(patients) {
                let html = '';
                patients.forEach(patient => {
                    const initial = patient.full_name.charAt(0).toUpperCase();
                    const age = calculateAge(patient.date_of_birth);
                    const lastVisit = new Date(patient.updated_at).toISOString().split('T')[0];
                    html += `
                        <div class="p-4 border rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-300 cursor-pointer" tabindex="0" role="button" aria-pressed="false" aria-label="View details for ${patient.full_name}">
                            <div class="flex items-center space-x-4 mb-3">
                                <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center text-lg font-bold">
                                    ${initial}
                                </div>
                                <h4 class="font-semibold text-lg">${patient.full_name}</h4>
                            </div>
                            <p class="text-sm text-gray-600">Age: ${age}</p>
                            <p class="text-sm text-gray-600">Last Visit: ${lastVisit}</p>
                            <a href="/doctor/patients/${patient.id}" class="mt-3 inline-block bg-gray-500 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm">
                                View Details
                            </a>
                        </div>
                    `;
                });
                patientsGrid.innerHTML = html;
            }

            function calculateAge(birthDate) {
                const today = new Date();
                const birth = new Date(birthDate);
                let age = today.getFullYear() - birth.getFullYear();
                const monthDiff = today.getMonth() - birth.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                    age--;
                }
                return age;
            }
        });
    </script>
</x-app-layout>
