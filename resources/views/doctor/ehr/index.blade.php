@extends('layouts.app')

@section('title', 'EHR Records - Doctor Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">EHR Records</h1>
                    <p class="mt-2 text-lg text-gray-600">Manage electronic health records for your patients</p>
                </div>
                <div class="text-right">
                    <a href="{{ route('doctor.analytics.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        View Analytics
                    </a>
                </div>
            </div>
        </div>

        <!-- Analytics Dashboard -->
        @if(isset($analytics))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Records -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Records</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($analytics['total_records']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Pending Review -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending Review</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($analytics['pending_records']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Approval Rate -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Approval Rate</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $analytics['approval_rate'] }}%</p>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Recent Activity (30d)</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($analytics['recent_activity']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('doctor.ehr.sync-appointments') }}" class="flex items-center p-4 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors">
                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-green-800">Sync Appointments</p>
                        <p class="text-sm text-green-600">Create EHR records from completed appointments</p>
                    </div>
                </a>

                <a href="#pending-section" class="flex items-center p-4 bg-yellow-50 border border-yellow-200 rounded-lg hover:bg-yellow-100 transition-colors">
                    <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-yellow-800">Review Pending</p>
                        <p class="text-sm text-yellow-600">Review records from midwives/BHWs</p>
                    </div>
                </a>

                <a href="{{ route('doctor.reports.printable') }}" class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                    <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-blue-800">Generate Reports</p>
                        <p class="text-sm text-blue-600">Create printable EHR reports</p>
                    </div>
                </a>
            </div>
        </div>
        @endif

        <!-- Pending Records Section -->
        <div id="pending-section"></div>
        @if($pendingRecords->count() > 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-yellow-800">Pending Review ({{ $pendingRecords->total() }})</h2>
                <span class="text-sm text-yellow-600">Records awaiting your approval</span>
            </div>

            <div class="space-y-4">
                @foreach($pendingRecords as $record)
                <div class="bg-white rounded-lg border border-yellow-200 p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $record->record_type_display }}
                                </span>
                                <span class="text-sm text-gray-500">by {{ $record->creator_name }} ({{ $record->created_by_role_display }})</span>
                                <span class="text-sm text-gray-500">{{ $record->created_at->diffForHumans() }}</span>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900 mb-1">{{ $record->title }}</h3>
                            <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($record->description, 150) }}</p>
                            <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
                                <span>Patient: {{ $record->patient->full_name }}</span>
                                @if($record->appointment)
                                    <span>Appointment: {{ $record->appointment->appointment_date->format('M d, Y') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <form method="POST" action="{{ route('doctor.ehr.approve', $record) }}" class="inline">
                                @csrf
                                <input type="hidden" name="review_notes" value="">
                                <button type="submit" class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                    Approve
                                </button>
                            </form>
                            <button onclick="openFlagModal({{ $record->id }}, '{{ addslashes($record->title) }}')" class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                                Flag
                            </button>
                            <a href="{{ route('doctor.ehr.show', $record->patient) }}" class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination for pending records -->
            @if($pendingRecords->hasPages())
            <div class="mt-4">
                {{ $pendingRecords->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
        @endif

        <!-- Search and Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <form method="GET" action="{{ route('doctor.ehr.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Patients</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Search by name or ID">
                </div>

                <!-- Sort -->
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                    <select name="sort" id="sort" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="last_updated" {{ request('sort') == 'last_updated' ? 'selected' : '' }}>Last Updated</option>
                        <option value="records" {{ request('sort') == 'records' ? 'selected' : '' }}>Total Records</option>
                    </select>
                </div>

                <!-- Direction -->
                <div>
                    <label for="direction" class="block text-sm font-medium text-gray-700 mb-2">Direction</label>
                    <select name="direction" id="direction" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Descending</option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <button type="submit" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'sort', 'direction']))
                        <a href="{{ route('doctor.ehr.index') }}" class="ml-3 text-gray-500 hover:text-gray-700 underline">Clear Filters</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Flag Modal -->
        <div id="flagModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Flag Record for Review</h3>
                    <form id="flagForm" method="POST" action="">
                        @csrf
                        <div class="mb-4">
                            <label for="flag_notes" class="block text-sm font-medium text-gray-700 mb-2">Review Notes (Required)</label>
                            <textarea name="review_notes" id="flag_notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" placeholder="Please provide detailed feedback on why this record needs revision..." required></textarea>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeFlagModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                Flag Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Patients Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Patients ({{ $patients->total() }})</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Records</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recent Activity</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($patients as $patient)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100 text-indigo-600 font-medium">
                                                {{ substr($patient->full_name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $patient->full_name }}</div>
                                            <div class="text-sm text-gray-500">ID: {{ $patient->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $patient->total_records }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($patient->ehrRecords->isNotEmpty())
                                        {{ $patient->ehrRecords->first()->created_at->format('M d, Y') }}
                                    @else
                                        No records
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($patient->ehrRecords->isNotEmpty())
                                        {{ $patient->ehrRecords->first()->record_type_display }}
                                    @else
                                        No activity
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('doctor.ehr.show', $patient) }}" class="text-indigo-600 hover:text-indigo-900">View Records</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No patients found</h3>
                                    <p class="mt-1 text-sm text-gray-500">Get started by creating your first patient record.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($patients->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $patients->links() }}
                </div>
            @endif
        </div>

        <!-- Sync Appointments Button -->
        <div class="mt-6 text-center">
            <form method="POST" action="{{ route('doctor.ehr.sync-appointments') }}" class="inline">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-md transition-colors">
                    Sync Completed Appointments
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

<script>
function openFlagModal(recordId, recordTitle) {
    document.getElementById('flagModal').classList.remove('hidden');
    document.getElementById('flagForm').action = `/doctor/ehr/${recordId}/flag`;
    document.querySelector('#flagModal h3').textContent = `Flag "${recordTitle}" for Review`;
}

function closeFlagModal() {
    document.getElementById('flagModal').classList.add('hidden');
    document.getElementById('flag_notes').value = '';
}

// Close modal when clicking outside
document.getElementById('flagModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeFlagModal();
    }
});
</script>
