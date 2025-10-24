<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Prescription Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('doctor.prescriptions.print', $prescription) }}" 
                   target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print
                </a>
                <a href="{{ route('doctor.prescriptions.edit', $prescription) }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('doctor.prescriptions.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg shadow-sm transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                
                {{-- Header Section --}}
                <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-green-50 to-emerald-50">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">
                                {{ $prescription->medication_name }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                Prescription ID: #{{ str_pad($prescription->id, 6, '0', STR_PAD_LEFT) }}
                            </p>
                        </div>
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold border {{ $prescription->status_badge_class }}">
                            {{ $prescription->status_label }}
                        </span>
                    </div>
                </div>

                {{-- Main Content --}}
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        {{-- Left Column - Prescription Details --}}
                        <div class="lg:col-span-2 space-y-6">
                            
                            {{-- Medication Information Card --}}
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-6 border border-green-200">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                    </svg>
                                    Medication Details
                                </h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Medication Name</label>
                                        <p class="text-lg font-semibold text-gray-900">{{ $prescription->medication_name }}</p>
                                    </div>
                                    
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Dosage</label>
                                        <p class="text-lg font-semibold text-gray-900">{{ $prescription->dosage }}</p>
                                    </div>
                                    
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Frequency</label>
                                        <p class="text-lg font-semibold text-gray-900">{{ $prescription->frequency }}</p>
                                    </div>
                                    
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Duration</label>
                                        <p class="text-lg font-semibold text-gray-900">{{ $prescription->duration }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Instructions Card --}}
                            @if($prescription->instructions)
                                <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-lg p-6 border border-orange-200">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                        <svg class="w-6 h-6 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Special Instructions
                                    </h4>
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <p class="text-gray-700 leading-relaxed">{{ $prescription->instructions }}</p>
                                    </div>
                                </div>
                            @endif

                            {{-- Timeline Card --}}
                            <div class="bg-white rounded-lg p-6 border border-gray-200">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Timeline
                                </h4>
                                
                                <div class="space-y-3">
                                    <div class="flex items-center text-sm">
                                        <span class="text-gray-500 w-32">Prescribed on:</span>
                                        <span class="font-medium text-gray-900">{{ $prescription->created_at->format('F d, Y \a\t h:i A') }}</span>
                                    </div>
                                    @if($prescription->updated_at != $prescription->created_at)
                                        <div class="flex items-center text-sm">
                                            <span class="text-gray-500 w-32">Last updated:</span>
                                            <span class="font-medium text-gray-900">{{ $prescription->updated_at->format('F d, Y \a\t h:i A') }}</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center text-sm">
                                        <span class="text-gray-500 w-32">Time elapsed:</span>
                                        <span class="font-medium text-gray-900">{{ $prescription->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Right Column - Patient Information --}}
                        <div class="space-y-6">
                            
                            {{-- Patient Card --}}
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-6 border border-blue-200">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Patient Information
                                </h4>
                                
                                <div class="space-y-3">
                                    <div class="bg-white rounded-lg p-3 shadow-sm">
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Full Name</label>
                                        <p class="font-semibold text-gray-900">{{ $prescription->patient->full_name }}</p>
                                    </div>
                                    
                                    <div class="bg-white rounded-lg p-3 shadow-sm">
                                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Patient ID</label>
                                        <p class="font-semibold text-gray-900">#{{ str_pad($prescription->patient->id, 6, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                    
                                    @if($prescription->patient->contact_number)
                                        <div class="bg-white rounded-lg p-3 shadow-sm">
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Contact Number</label>
                                            <p class="font-semibold text-gray-900">{{ $prescription->patient->contact_number }}</p>
                                        </div>
                                    @endif
                                    
                                    @if($prescription->patient->date_of_birth)
                                        <div class="bg-white rounded-lg p-3 shadow-sm">
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Date of Birth</label>
                                            <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($prescription->patient->date_of_birth)->format('M d, Y') }}</p>
                                            <p class="text-xs text-gray-500 mt-1">Age: {{ \Carbon\Carbon::parse($prescription->patient->date_of_birth)->age }} years</p>
                                        </div>
                                    @endif
                                    
                                    @if($prescription->patient->gender)
                                        <div class="bg-white rounded-lg p-3 shadow-sm">
                                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Gender</label>
                                            <p class="font-semibold text-gray-900">{{ ucfirst($prescription->patient->gender) }}</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-4">
                                    <a href="{{ route('doctor.patients.show', $prescription->patient->id) }}" 
                                       class="block w-full text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                                        View Patient Profile
                                    </a>
                                </div>
                            </div>

                            {{-- Quick Actions Card --}}
                            <div class="bg-white rounded-lg p-6 border border-gray-200">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h4>
                                
                                <div class="space-y-2">
                                    <button onclick="updateStatus('active')" 
                                            class="w-full px-4 py-2 text-left text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 rounded-lg transition-colors duration-200 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Mark as Active
                                    </button>
                                    
                                    <button onclick="updateStatus('completed')" 
                                            class="w-full px-4 py-2 text-left text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors duration-200 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Mark as Completed
                                    </button>
                                    
                                    <button onclick="updateStatus('pending_refill')" 
                                            class="w-full px-4 py-2 text-left text-sm font-medium text-orange-700 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors duration-200 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Mark as Pending Refill
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Status Update Script --}}
    <script>
        function updateStatus(status) {
            if (!confirm('Are you sure you want to update the prescription status?')) {
                return;
            }

            fetch('{{ route("doctor.prescriptions.updateStatus", $prescription) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Failed to update status. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    </script>
</x-app-layout>
