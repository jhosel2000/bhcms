@extends('layouts.app')

@section('title', 'EHR Records - ' . $patient->full_name)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $patient->full_name }}</h1>
                    <p class="mt-2 text-lg text-gray-600">Electronic Health Records</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('midwife.ehr.create', $patient) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        Add New Record
                    </a>
                    <a href="{{ route('midwife.ehr.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        Back to Patients
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <form method="GET" action="{{ route('midwife.ehr.show', $patient) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <!-- Type Filter -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Record Type</label>
                    <select name="type" id="type" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>All Types</option>
                        <option value="appointment" {{ request('type') == 'appointment' ? 'selected' : '' }}>Appointment</option>
                        <option value="vital_signs" {{ request('type') == 'vital_signs' ? 'selected' : '' }}>Vital Signs</option>
                        <option value="observation" {{ request('type') == 'observation' ? 'selected' : '' }}>Observation</option>
                        <option value="referral" {{ request('type') == 'referral' ? 'selected' : '' }}>Referral</option>
                        <option value="maternal_care" {{ request('type') == 'maternal_care' ? 'selected' : '' }}>Maternal Care</option>
                        <option value="child_health" {{ request('type') == 'child_health' ? 'selected' : '' }}>Child Health</option>
                        <option value="vaccination" {{ request('type') == 'vaccination' ? 'selected' : '' }}>Vaccination</option>
                        <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Search records...">
                </div>

                <div class="md:col-span-4">
                    <button type="submit" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        Filter
                    </button>
                    @if(request()->hasAny(['type', 'start_date', 'end_date', 'search']))
                        <a href="{{ route('midwife.ehr.show', $patient) }}" class="ml-3 text-gray-500 hover:text-gray-700 underline">Clear Filters</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Record Type Tabs -->
        @if(count($recordTypeCounts) > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('midwife.ehr.show', $patient) }}"
                       class="px-4 py-2 rounded-md text-sm font-medium {{ request('type') == 'all' || !request('type') ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        All ({{ array_sum($recordTypeCounts) }})
                    </a>
                    @foreach($recordTypeCounts as $type => $count)
                        <a href="{{ route('midwife.ehr.show', $patient) }}?type={{ $type }}"
                           class="px-4 py-2 rounded-md text-sm font-medium {{ request('type') == $type ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ ucwords(str_replace('_', ' ', $type)) }} ({{ $count }})
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Records Timeline -->
        <div class="space-y-6">
            @forelse($ehrRecords as $record)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100">
                                        <svg class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">{{ $record->title }}</h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $record->record_type_display }} • Created by {{ $record->created_by_role_display }} on {{ $record->created_at->format('M d, Y \a\t g:i A') }}
                                        @if($record->creator)
                                            - {{ $record->creator->name }}
                                        @endif
                                    </p>
                                    @if($record->status !== 'approved')
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($record->status === 'pending_review') bg-yellow-100 text-yellow-800
                                                @elseif($record->status === 'flagged') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $record->status_display }}
                                            </span>
                                            @if($record->status === 'flagged' && $record->review_notes)
                                                <div class="mt-2 p-2 bg-red-50 rounded text-xs text-red-700">
                                                    <strong>Review Notes:</strong> {{ $record->review_notes }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($record->appointment)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Appointment #{{ $record->appointment->id }}
                                    </span>
                                @endif
                                <div class="flex space-x-1">
                                    @if($record->created_by === auth()->id() && $record->created_by_role === 'midwife')
                                        <a href="{{ route('midwife.ehr.edit', [$patient, $record]) }}" class="text-indigo-600 hover:text-indigo-900">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('midwife.ehr.destroy', [$patient, $record]) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this record?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4">
                        @if($record->content)
                            <p class="text-gray-900 mb-4">{{ $record->content }}</p>
                        @endif

                        @if($record->attachments && count($record->attachments) > 0)
                            <div class="border-t border-gray-200 pt-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Attachments ({{ count($record->attachments) }})</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($record->attachments as $file)
                                        <div class="flex items-center p-3 bg-gray-50 rounded-md">
                                            <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $file['original_name'] ?? 'Unknown file' }}</p>
                                                <p class="text-xs text-gray-500">{{ number_format(($file['size'] ?? 0) / 1024, 1) }} KB</p>
                                            </div>
                                            @if(isset($file['path']))
                                                <a href="{{ Storage::url($file['path']) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                            @else
                                                <span class="text-gray-400 cursor-not-allowed">
                                            @endif
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No EHR records found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating the first record for this patient.</p>
                    <div class="mt-6">
                        <a href="{{ route('midwife.ehr.create', $patient) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add First Record
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($ehrRecords->hasPages())
            <div class="mt-8">
                {{ $ehrRecords->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
