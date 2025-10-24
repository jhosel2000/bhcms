@extends('layouts.app')

@section('title', 'Medical Record Details')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $ehrRecord->title }}</h1>
                    <p class="mt-2 text-lg text-gray-600">{{ $ehrRecord->record_type_display }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('patient.ehr.download-pdf', $ehrRecord) }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        Download PDF
                    </a>
                    @if($ehrRecord->created_by_role === 'patient')
                        <a href="{{ route('patient.ehr.edit', $ehrRecord) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                            Edit Record
                        </a>
                    @endif
                    <a href="{{ route('patient.ehr.index') }}" class="text-indigo-600 hover:text-indigo-900">
                        ← Back to Records
                    </a>
                </div>
            </div>
        </div>

        <!-- Record Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <!-- Record Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100">
                                <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </span>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $ehrRecord->title }}</h3>
                            <p class="text-sm text-gray-500">
                                Created {{ $ehrRecord->created_at->format('M d, Y \a\t g:i A') }}
                                @if($ehrRecord->created_by_role !== 'patient')
                                    • Added by {{ $ehrRecord->created_by_role_display }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if($ehrRecord->status === 'approved')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                Approved
                            </span>
                        @elseif($ehrRecord->status === 'pending_review')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                Pending Review
                            </span>
                        @elseif($ehrRecord->status === 'flagged')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                Flagged
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Record Content -->
            <div class="px-6 py-6">
                @if($ehrRecord->content)
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Description</h4>
                        <div class="bg-gray-50 rounded-md p-4">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $ehrRecord->content }}</p>
                        </div>
                    </div>
                @endif

                <!-- Metadata -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Record Information</h4>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-xs text-gray-500">Type</dt>
                                <dd class="text-sm text-gray-900">{{ $ehrRecord->record_type_display }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500">Created By</dt>
                                <dd class="text-sm text-gray-900">{{ $ehrRecord->created_by_role_display }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-500">Created Date</dt>
                                <dd class="text-sm text-gray-900">{{ $ehrRecord->created_at->format('M d, Y \a\t g:i A') }}</dd>
                            </div>
                            @if($ehrRecord->reviewed_at)
                                <div>
                                    <dt class="text-xs text-gray-500">Reviewed Date</dt>
                                    <dd class="text-sm text-gray-900">{{ $ehrRecord->reviewed_at->format('M d, Y \a\t g:i A') }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    @if($ehrRecord->appointment)
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Related Appointment</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-xs text-gray-500">Appointment ID</dt>
                                    <dd class="text-sm text-gray-900">#{{ $ehrRecord->appointment->id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-500">Date</dt>
                                    <dd class="text-sm text-gray-900">{{ $ehrRecord->appointment->appointment_date->format('M d, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-500">Time</dt>
                                    <dd class="text-sm text-gray-900">{{ $ehrRecord->appointment->appointment_time }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-500">Reason</dt>
                                    <dd class="text-sm text-gray-900">{{ $ehrRecord->appointment->reason }}</dd>
                                </div>
                            </dl>
                        </div>
                    @endif
                </div>

                <!-- Review Notes -->
                @if($ehrRecord->review_notes)
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Review Notes</h4>
                        <div class="bg-blue-50 rounded-md p-4">
                            <p class="text-sm text-blue-900">{{ $ehrRecord->review_notes }}</p>
                        </div>
                    </div>
                @endif

                <!-- Attachments -->
                @if($ehrRecord->attachments && count($ehrRecord->attachments) > 0)
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Attachments ({{ count($ehrRecord->attachments) }})</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($ehrRecord->attachments as $file)
                                @if(is_array($file) && isset($file['original_name']) && isset($file['path']) && isset($file['size']) && isset($file['uploaded_at']))
                                    <div class="flex items-center p-4 bg-gray-50 rounded-md border">
                                        <svg class="h-6 w-6 text-gray-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $file['original_name'] }}</p>
                                            <p class="text-xs text-gray-500">
                                                {{ number_format($file['size'] / 1024, 1) }} KB •
                                                {{ \Carbon\Carbon::parse($file['uploaded_at'])->format('M d, Y') }}
                                            </p>
                                        </div>
                                        <a href="{{ Storage::url($file['path']) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 ml-2">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        @if($ehrRecord->created_by_role === 'patient')
            <div class="mt-6 flex justify-end space-x-3">
                <form method="POST" action="{{ route('patient.ehr.destroy', $ehrRecord) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this record? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                        Delete Record
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
