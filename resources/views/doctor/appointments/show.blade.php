<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Appointment Details - {{ $appointment->patient->full_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-8">
        <!-- Header with Actions -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900">Appointment Details</h1>
                <div class="flex space-x-2">
                    @if($appointment->status === 'pending')
                    <button onclick="approveAppointment({{ $appointment->id }})" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Approve
                    </button>
                    <button onclick="declineAppointment({{ $appointment->id }})" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Decline
                    </button>
                    @elseif($appointment->status === 'approved')
                    <button onclick="completeAppointment({{ $appointment->id }})" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Mark Complete
                    </button>
                    @endif
                    <a href="{{ route('doctor.appointments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back
                    </a>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Status Banner -->
            <div class="mb-6 rounded-lg p-4
                @if($appointment->status === 'pending') bg-yellow-50 border border-yellow-200
                @elseif($appointment->status === 'approved') bg-blue-50 border border-blue-200
                @elseif($appointment->status === 'completed') bg-green-50 border border-green-200
                @elseif($appointment->status === 'declined') bg-red-50 border border-red-200
                @else bg-gray-50 border border-gray-200
                @endif">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        @if($appointment->status === 'pending')
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        @elseif($appointment->status === 'approved')
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        @elseif($appointment->status === 'completed')
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        @elseif($appointment->status === 'declined')
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        @endif
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium
                            @if($appointment->status === 'pending') text-yellow-800
                            @elseif($appointment->status === 'approved') text-blue-800
                            @elseif($appointment->status === 'completed') text-green-800
                            @elseif($appointment->status === 'declined') text-red-800
                            @else text-gray-800
                            @endif">
                            Appointment Status: {{ ucfirst($appointment->status) }}
                        </h3>
                        <div class="mt-1 text-sm
                            @if($appointment->status === 'pending') text-yellow-700
                            @elseif($appointment->status === 'approved') text-blue-700
                            @elseif($appointment->status === 'completed') text-green-700
                            @elseif($appointment->status === 'declined') text-red-700
                            @else text-gray-700
                            @endif">
                            @if($appointment->status === 'pending')
                                This appointment is awaiting your approval.
                            @elseif($appointment->status === 'approved')
                                This appointment has been approved and is scheduled for {{ $appointment->appointment_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}.
                            @elseif($appointment->status === 'completed')
                                This appointment was completed on {{ $appointment->completed_at ? $appointment->completed_at->format('M d, Y g:i A') : $appointment->appointment_date->format('M d, Y') }}.
                            @elseif($appointment->status === 'declined')
                                This appointment was declined{{ $appointment->declined_reason ? ': ' . $appointment->declined_reason : '.' }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Patient Info -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Patient Information</h3>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center mb-6">
                                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-2xl font-bold">
                                    {{ strtoupper(substr($appointment->patient->full_name, 0, 2)) }}
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $appointment->patient->full_name }}</h4>
                                    <p class="text-sm text-gray-500">Patient ID: #{{ $appointment->patient->id }}</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Contact</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $appointment->patient->contact_number ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Date of Birth</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $appointment->patient->date_of_birth ? $appointment->patient->date_of_birth->format('M d, Y') : 'N/A' }}</p>
                                        @if($appointment->patient->date_of_birth)
                                        <p class="text-xs text-gray-500">Age: {{ $appointment->patient->date_of_birth->age }} years</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Gender</p>
                                        <p class="text-sm font-medium text-gray-900">{{ ucfirst($appointment->patient->gender ?? 'N/A') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Address</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $appointment->patient->address ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <a href="{{ route('doctor.patients.show', $appointment->patient->id) }}" class="block w-full text-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors duration-200 text-sm font-medium">
                                    View Full Patient Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Appointment Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Appointment Information -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Appointment Information</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <label class="text-xs text-gray-500 uppercase tracking-wide font-medium">Appointment Date</label>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-lg font-semibold text-gray-900">{{ $appointment->appointment_date->format('l, F j, Y') }}</p>
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <label class="text-xs text-gray-500 uppercase tracking-wide font-medium">Appointment Time</label>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="text-lg font-semibold text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</p>
                                    </div>
                                </div>

                                <div class="space-y-1 md:col-span-2">
                                    <label class="text-xs text-gray-500 uppercase tracking-wide font-medium">Reason for Visit</label>
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-base text-gray-900">{{ $appointment->reason }}</p>
                                    </div>
                                </div>

                                @if($appointment->urgency_level)
                                <div class="space-y-1">
                                    <label class="text-xs text-gray-500 uppercase tracking-wide font-medium">Urgency Level</label>
                                    <div class="flex items-center">
                                        @if($appointment->urgency_level === 'urgent')
                                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                🚨 Urgent
                                            </span>
                                        @elseif($appointment->urgency_level === 'maternal')
                                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                                🤰 Maternal Care
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                Normal
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                @if($appointment->notes)
                                <div class="space-y-1 md:col-span-2">
                                    <label class="text-xs text-gray-500 uppercase tracking-wide font-medium">Notes</label>
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <p class="text-sm text-gray-700">{{ $appointment->notes }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Uploaded Files -->
                    @if($appointment->uploaded_files && count($appointment->uploaded_files) > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Uploaded Documents</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($appointment->uploaded_files as $file)
                                <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors duration-200">
                                    <div class="flex-shrink-0">
                                        <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $file['original_name'] ?? 'Unknown file' }}</p>
                                        <p class="text-xs text-gray-500">{{ number_format(($file['size'] ?? 0) / 1024, 1) }} KB</p>
                                    </div>
                                    @if(isset($file['path']))
                                        <a href="{{ Storage::url($file['path']) }}" target="_blank" class="ml-4 text-purple-600 hover:text-purple-700">
                                    @else
                                        <span class="ml-4 text-gray-400 cursor-not-allowed">
                                    @endif
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Patient EHR Records -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Patient EHR Records</h3>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <a href="{{ route('doctor.ehr.show', $appointment->patient) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    View Full EHR Records
                                </a>
                            </div>

                            @if($appointment->patient->ehrRecords && count($appointment->patient->ehrRecords) > 0)
                                <div class="space-y-3">
                                    @foreach($appointment->patient->ehrRecords->take(5) as $record)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <h4 class="text-sm font-medium text-gray-900">{{ $record->record_type_display }}</h4>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        {{ $record->created_at->format('M d, Y') }} •
                                                        @if($record->doctor)
                                                            Dr. {{ $record->doctor->full_name }}
                                                        @elseif($record->midwife)
                                                            Midwife {{ $record->midwife->full_name }}
                                                        @else
                                                            {{ $record->created_by_role_display }}
                                                        @endif
                                                    </p>
                                                    @if($record->description)
                                                        <p class="text-sm text-gray-700 mt-2 line-clamp-2">{{ Str::limit($record->description, 100) }}</p>
                                                    @endif
                                                </div>
                                                <div class="ml-4 flex-shrink-0">
                                                    <a href="{{ route('doctor.ehr.edit', [$appointment->patient, $record]) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                                        Edit
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if(count($appointment->patient->ehrRecords) > 5)
                                        <div class="text-center pt-2">
                                            <a href="{{ route('doctor.ehr.show', $appointment->patient) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                                View all {{ count($appointment->patient->ehrRecords) }} records →
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No EHR records found</h3>
                                    <p class="mt-1 text-sm text-gray-500">Create the first record for this patient.</p>
                                    <div class="mt-4">
                                        <a href="{{ route('doctor.ehr.create', $appointment->patient) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Create First Record
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Quick Actions</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <a href="{{ route('doctor.ehr.create', $appointment->patient) }}" class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200 transition-colors duration-200">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-green-900">Add EHR Record</h4>
                                        <p class="text-xs text-green-700">Create a new medical record</p>
                                    </div>
                                </a>

                                <a href="{{ route('doctor.prescriptions.create', $appointment->patient) }}" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200 transition-colors duration-200">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-blue-900">Write Prescription</h4>
                                        <p class="text-xs text-blue-700">Prescribe medication</p>
                                    </div>
                                </a>

                                <a href="{{ route('doctor.patient.referrals.create', $appointment->patient) }}" class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg border border-purple-200 transition-colors duration-200">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-purple-900">Create Referral</h4>
                                        <p class="text-xs text-purple-700">Refer to specialist</p>
                                    </div>
                                </a>

                                <a href="{{ route('doctor.patient.lab-results.create', $appointment->patient) }}" class="flex items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg border border-orange-200 transition-colors duration-200">
                                    <div class="flex-shrink-0">
                                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-orange-900">Order Lab Test</h4>
                                        <p class="text-xs text-orange-700">Request laboratory tests</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-600 to-gray-700 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">Appointment Timeline</h3>
                        </div>
                        <div class="p-6">
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    <li>
                                        <div class="relative pb-8">
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-900 font-medium">Appointment Created</p>
                                                        <p class="text-xs text-gray-500">Patient requested appointment</p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        {{ $appointment->created_at->format('M d, Y g:i A') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    @if($appointment->approved_at)
                                    <li>
                                        <div class="relative pb-8">
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-900 font-medium">Appointment Approved</p>
                                                        <p class="text-xs text-gray-500">Doctor approved the appointment</p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        {{ $appointment->approved_at->format('M d, Y g:i A') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif

                                    @if($appointment->completed_at)
                                    <li>
                                        <div class="relative pb-8">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-purple-500 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-900 font-medium">Appointment Completed</p>
                                                        <p class="text-xs text-gray-500">Consultation finished</p>
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        {{ $appointment->completed_at->format('M d, Y g:i A') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif

                                    @if($appointment->status === 'declined')
                                    <li>
                                        <div class="relative pb-8">
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-900 font-medium">Appointment Declined</p>
                                                        @if($appointment->declined_reason)
                                                        <p class="text-xs text-gray-500">Reason: {{ $appointment->declined_reason }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        {{ $appointment->updated_at->format('M d, Y g:i A') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve/Decline Modals -->
    <div id="declineModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Decline Appointment</h3>
                <form id="declineForm" onsubmit="submitDecline(event)">
                    @csrf
                    <div class="mb-4">
                        <label for="declined_reason" class="block text-sm font-medium text-gray-700">Reason for declining (optional)</label>
                        <textarea id="declined_reason" name="declined_reason" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeDeclineModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Decline</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Approve Appointment</h3>
                <p class="text-sm text-gray-600 mb-4">Approve this pending appointment? Conflicts will be checked automatically.</p>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeApproveModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Cancel</button>
                    <button type="button" onclick="submitApprove()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Approve</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function declineAppointment(appointmentId) {
            window.currentAppointmentId = appointmentId;
            document.getElementById('declineForm').action = `/doctor/appointments/${appointmentId}/decline`;
            document.getElementById('declineModal').classList.remove('hidden');
        }

        function submitDecline(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const reason = formData.get('declined_reason');

            fetch(event.target.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ declined_reason: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Appointment declined successfully!');
                    closeDeclineModal();
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('An error occurred. Please try again.');
            });
        }

        function closeDeclineModal() {
            document.getElementById('declineModal').classList.add('hidden');
        }

        function approveAppointment(appointmentId) {
            window.currentAppointmentId = appointmentId;
            document.getElementById('approveModal').classList.remove('hidden');
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
        }

        function submitApprove() {
            const appointmentId = window.currentAppointmentId;
            if (!appointmentId) return;
            fetch(`/doctor/appointments/${appointmentId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeApproveModal();
                    alert('Appointment approved successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(() => alert('An error occurred. Please try again.'));
        }

        function completeAppointment(appointmentId) {
            if (confirm('Are you sure you want to mark this appointment as completed?')) {
                fetch(`/doctor/appointments/${appointmentId}/complete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Appointment marked as completed!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('An error occurred. Please try again.');
                });
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const declineModal = document.getElementById('declineModal');
            if (event.target == declineModal) {
                declineModal.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
