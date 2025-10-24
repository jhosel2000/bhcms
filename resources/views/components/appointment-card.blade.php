@props(['appointment'])

@php
    $borderColor = $appointment->urgency_level === 'urgent' ? 'border-l-red-500 bg-red-50' : ($appointment->urgency_level === 'maternal' ? 'border-l-purple-500 bg-purple-50' : 'border-l-blue-500 bg-white');
    $patientInitials = strtoupper(substr($appointment->patient->first_name, 0, 1) . substr($appointment->patient->last_name, 0, 1));
@endphp

<div class="bg-white rounded-lg shadow-sm border-l-4 {{ $borderColor }} p-6 hover:shadow-md transition-shadow">
    <div class="flex items-start justify-between">
        <div class="flex items-start space-x-4 flex-1">
            <!-- Patient Avatar -->
            <div class="flex-shrink-0">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                    {{ $patientInitials }}
                </div>
            </div>

            <!-- Appointment Details -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center space-x-2 mb-2">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                    </h3>
                    @if($appointment->urgency_level === 'urgent')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 animate-pulse">
                            🚨 URGENT
                        </span>
                    @elseif($appointment->urgency_level === 'maternal')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800 animate-pulse">
                            🤰 MATERNAL CARE
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        {{ $appointment->patient->contact_number ?? 'N/A' }}
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-3 mb-3">
                    <p class="text-sm text-gray-700">
                        <span class="font-semibold text-gray-900">Reason:</span>
                        {{ $appointment->reason }}
                    </p>
                    @if($appointment->notes)
                    <p class="text-sm text-gray-600 mt-2">
                        <span class="font-semibold text-gray-900">Notes:</span>
                        {{ Str::limit($appointment->notes, 100) }}
                    </p>
                    @endif
                </div>

                <div class="flex items-center space-x-3 text-xs text-gray-500">
                    @if($appointment->uploaded_files && count($appointment->uploaded_files) > 0)
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-blue-100 text-blue-700">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                            {{ count($appointment->uploaded_files) }} file(s)
                        </span>
                    @endif
                    @if($appointment->status === 'declined' && $appointment->declined_reason)
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-red-100 text-red-700">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Declined: {{ Str::limit($appointment->declined_reason, 30) }}
                        </span>
                    @endif
                    <span>Requested {{ $appointment->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        <!-- Actions: restore approve/decline/complete + keep Quick View -->
        <div class="flex flex-col space-y-2 ml-6">
            <button
                class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors shadow-sm btn-appointment-quick-view"
                data-id="{{ $appointment->id }}"
                data-patient="{{ $appointment->patient->full_name ?? ($appointment->patient->first_name.' '.$appointment->patient->last_name) }}"
                data-date="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}"
                data-time="{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}"
                data-reason="{{ $appointment->reason }}"
                data-status="{{ ucfirst($appointment->status) }}"
                data-notes="{{ $appointment->notes }}"
            >
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Quick View
            </button>

            <a href="{{ route('doctor.appointments.show', $appointment->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                View Details
            </a>

            @if($appointment->status === 'pending')
                <button onclick="approveAppointment({{ $appointment->id }})" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Approve
                </button>
                <button onclick="declineAppointment({{ $appointment->id }})" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Decline
                </button>
            @elseif($appointment->status === 'approved')
                <button onclick="completeAppointment({{ $appointment->id }})" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Mark Complete
                </button>
            @endif

            <a href="{{ route('doctor.patients.show', $appointment->patient->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                View Patient
            </a>
        </div>
    </div>
</div>
