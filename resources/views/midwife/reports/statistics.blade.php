<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports & Analytics - Statistics') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Maternal Care Statistics</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="bg-blue-50 p-3 sm:p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                <h4 class="text-xs sm:text-sm font-medium text-blue-800">Total Patients</h4>
                                <p class="text-xl sm:text-2xl font-bold text-blue-600">{{ $totalPatients ?? 0 }}</p>
                            </div>
                            <div class="bg-green-50 p-3 sm:p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                <h4 class="text-xs sm:text-sm font-medium text-green-800">Prenatal Visits</h4>
                                <p class="text-xl sm:text-2xl font-bold text-green-600">{{ $prenatalVisits ?? 0 }}</p>
                            </div>
                            <div class="bg-yellow-50 p-3 sm:p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                <h4 class="text-xs sm:text-sm font-medium text-yellow-800">Postnatal Visits</h4>
                                <p class="text-xl sm:text-2xl font-bold text-yellow-600">{{ $postnatalVisits ?? 0 }}</p>
                            </div>
                            <div class="bg-purple-50 p-3 sm:p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                <h4 class="text-xs sm:text-sm font-medium text-purple-800">Monthly Checkups</h4>
                                <p class="text-xl sm:text-2xl font-bold text-purple-600">{{ $monthlyCheckups ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Patient Visits Overview</h3>
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                            <div class="w-full h-64 sm:h-80">
                                <canvas id="visitsChart" class="w-full h-full"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
                        <div class="overflow-x-auto shadow-sm rounded-lg">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-4 sm:py-3 sm:text-xs">Patient</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-4 sm:py-3 sm:text-xs">Visit Type</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-4 sm:py-3 sm:text-xs">Date</th>
                                        <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sm:px-4 sm:py-3 sm:text-xs">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($recentRecords ?? [] as $record)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-2 py-3 whitespace-nowrap text-xs sm:text-sm font-medium text-gray-900 sm:px-4 sm:py-4">
                                                {{ $record->patient->full_name ?? 'N/A' }}
                                            </td>
                                            <td class="px-2 py-3 whitespace-nowrap text-xs sm:text-sm text-gray-500 sm:px-4 sm:py-4">
                                                {{ ucfirst(str_replace('_', ' ', $record->type)) }}
                                            </td>
                                            <td class="px-2 py-3 whitespace-nowrap text-xs sm:text-sm text-gray-500 sm:px-4 sm:py-4">
                                                {{ $record->visit_date ? $record->visit_date->format('M d, Y') : 'N/A' }}
                                            </td>
                                            <td class="px-2 py-3 whitespace-nowrap sm:px-4 sm:py-4">
                                                <span class="px-1 sm:px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Completed
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-2 py-3 text-center text-gray-500 sm:px-4 sm:py-4">No recent records found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('visitsChart').getContext('2d');
            const visitsData = {!! json_encode($patientVisits ?? []) !!};
            const months = {!! json_encode($months ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']) !!};

            const visitsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Patient Visits',
                        data: visitsData,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
