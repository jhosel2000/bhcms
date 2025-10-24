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
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <form method="GET" action="{{ route('doctor.ehr.show', $patient) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <!-- Type Filter -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Record Type</label>
                    <select name="type" id="type" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>All Types</option>
                        <option value="appointment" {{ request('type') == 'appointment' ? 'selected' : '' }}>Appointment</option>
                        <option value="prescription" {{ request('type') == 'prescription' ? 'selected' : '' }}>Prescription</option>
                        <option value="lab_result" {{ request('type') == 'lab_result' ? 'selected' : '' }}>Lab Result</option>
                        <option value="vital_signs" {{ request('type') == 'vital_signs' ? 'selected' : '' }}>Vital Signs</option>
                        <option value="diagnosis" {{ request('type') == 'diagnosis' ? 'selected' : '' }}>Diagnosis</option>
                        <option value="referral" {{ request('type') == 'referral' ? 'selected' : '' }}>Referral</option>
                        <option value="treatment" {{ request('type') == 'treatment' ? 'selected' : '' }}>Treatment</option>
                        <option value="follow_up" {{ request('type') == 'follow_up' ? 'selected' : '' }}>Follow-up</option>
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
                        <a href="{{ route('doctor.ehr.show', $patient) }}" class="ml-3 text-gray-500 hover:text-gray-700 underline">Clear Filters</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Record Type Tabs -->
        @if(count($recordTypeCounts) > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('doctor.ehr.show', $patient) }}"
                       class="px-4 py-2 rounded-md text-sm font-medium {{ request('type') == 'all' || !request('type') ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        All ({{ array_sum($recordTypeCounts) }})
                    </a>
                    @foreach($recordTypeCounts as $type => $count)
                        <a href="{{ route('doctor.ehr.show', $patient) }}?type={{ $type }}"
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
                                    <h3 class="text-lg font-medium text-gray-900">{{ $record->record_type_display }}</h3>
                                    <p class="text-sm text-gray-500">
                                        Created by {{ $record->created_by_role_display }} on {{ $record->created_at->format('M d, Y \a\t g:i A') }}
                                        @if($record->doctor)
                                            - Dr. {{ $record->doctor->full_name }}
                                        @elseif($record->midwife)
                                            - Midwife {{ $record->midwife->full_name }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($record->appointment)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Appointment #{{ $record->appointment->id }}
                                    </span>
                                @endif
                                <div class="flex space-x-1">
                                    <a href="{{ route('doctor.ehr.edit', [$patient, $record]) }}" class="text-indigo-600 hover:text-indigo-900">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4">
                        <h4 class="text-lg font-medium text-gray-900 mb-2">{{ $record->title }}</h4>

                        @if($record->description)
                            <p class="text-gray-900 mb-4">{{ $record->description }}</p>
                        @endif

                        @if($record->notes)
                            <div class="bg-gray-50 rounded-md p-4 mb-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Additional Notes</h4>
                                <p class="text-sm text-gray-700">{{ $record->notes }}</p>
                            </div>
                        @endif

                        @if($record->uploaded_files && count($record->uploaded_files) > 0)
                            <div class="border-t border-gray-200 pt-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Attachments ({{ count($record->uploaded_files) }})</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($record->uploaded_files as $file)
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

                        <!-- Action Buttons for EHR-based creation -->
                        <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-200">
                            <button type="button"
                                    onclick="openPrescriptionModal({{ $record->id }}, '{{ $patient->full_name }}')"
                                    class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Create Prescription
                            </button>

                            <button type="button"
                                    onclick="openReferralModal({{ $record->id }}, '{{ $patient->full_name }}')"
                                    class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Create Referral
                            </button>
                        </div>
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
                        <a href="{{ route('doctor.ehr.create', $patient) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create First Record
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

<!-- Prescription Modal -->
<div id="prescriptionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Create Prescription from EHR</h3>
                <button onclick="closePrescriptionModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="prescriptionForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Patient</label>
                        <p class="mt-1 text-sm text-gray-900" id="prescriptionPatientName"></p>
                    </div>

                    <div>
                        <label for="medication_name" class="block text-sm font-medium text-gray-700">Medication Name</label>
                        <input type="text" id="medication_name" name="medication_name" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="dosage" class="block text-sm font-medium text-gray-700">Dosage</label>
                            <input type="text" id="dosage" name="dosage" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="frequency" class="block text-sm font-medium text-gray-700">Frequency</label>
                            <input type="text" id="frequency" name="frequency" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label for="instructions" class="block text-sm font-medium text-gray-700">Instructions</label>
                        <textarea id="instructions" name="instructions" rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closePrescriptionModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        Create Prescription
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Referral Modal -->
<div id="referralModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Create Referral from EHR</h3>
                <button onclick="closeReferralModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="referralForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Patient</label>
                        <p class="mt-1 text-sm text-gray-900" id="referralPatientName"></p>
                    </div>

                    <div>
                        <label for="referral_type" class="block text-sm font-medium text-gray-700">Referral Type</label>
                        <select id="referral_type" name="referral_type" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select type</option>
                            <option value="specialist">Specialist Consultation</option>
                            <option value="hospital">Hospital Admission</option>
                            <option value="diagnostic">Diagnostic Tests</option>
                            <option value="therapy">Therapy Services</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label for="referred_to" class="block text-sm font-medium text-gray-700">Referred To</label>
                        <input type="text" id="referred_to" name="referred_to" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="Name of specialist/hospital/department">
                    </div>

                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Referral</label>
                        <textarea id="reason" name="reason" rows="3" required
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>

                    <div>
                        <label for="urgency" class="block text-sm font-medium text-gray-700">Urgency Level</label>
                        <select id="urgency" name="urgency" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="routine">Routine</option>
                            <option value="urgent">Urgent</option>
                            <option value="emergency">Emergency</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeReferralModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Create Referral
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openPrescriptionModal(ehrRecordId, patientName) {
    document.getElementById('prescriptionPatientName').textContent = patientName;
    document.getElementById('prescriptionForm').action = `/doctor/ehr/${ehrRecordId}/create-prescription`;
    document.getElementById('prescriptionModal').classList.remove('hidden');
}

function closePrescriptionModal() {
    document.getElementById('prescriptionModal').classList.add('hidden');
    document.getElementById('prescriptionForm').reset();
}

function openReferralModal(ehrRecordId, patientName) {
    document.getElementById('referralPatientName').textContent = patientName;
    document.getElementById('referralForm').action = `/doctor/ehr/${ehrRecordId}/create-referral`;
    document.getElementById('referralModal').classList.remove('hidden');
}

function closeReferralModal() {
    document.getElementById('referralModal').classList.add('hidden');
    document.getElementById('referralForm').reset();
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    const prescriptionModal = document.getElementById('prescriptionModal');
    const referralModal = document.getElementById('referralModal');

    if (event.target === prescriptionModal) {
        closePrescriptionModal();
    }
    if (event.target === referralModal) {
        closeReferralModal();
    }
});
</script>
@endsection
