<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4 sm:mb-0">
                {{ __('Patient Profile') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Patient Header -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg mb-6">
                <div class="p-6 sm:p-8 text-gray-900">
                    <!-- Patient Name and Avatar -->
                    <div class="flex items-center space-x-6 mb-8 pb-6 border-b border-gray-200">
                        <div class="flex-shrink-0 w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center text-3xl sm:text-4xl font-bold shadow-md">
                            {{ strtoupper(substr($patient->full_name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-1">{{ $patient->full_name }}</h3>
                            <p class="text-base text-gray-500">Patient ID: #{{ $patient->id }}</p>
                        </div>
                    </div>

                    <!-- Patient Information Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Personal Information -->
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Personal Information</h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Age</p>
                                    <p class="text-base font-semibold text-gray-800">{{ $patient->date_of_birth->age }} years old</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Date of Birth</p>
                                    <p class="text-base font-semibold text-gray-800">{{ $patient->date_of_birth->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Gender</p>
                                    <p class="text-base font-semibold text-gray-800">{{ ucfirst($patient->gender) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Civil Status</p>
                                    <p class="text-base font-semibold text-gray-800">{{ ucfirst($patient->civil_status) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Contact Information</h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Phone Number</p>
                                    <p class="text-base font-semibold text-gray-800">{{ $patient->contact_number }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Emergency Contact</p>
                                    <p class="text-base font-semibold text-gray-800">{{ $patient->emergency_contact_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $patient->emergency_contact_number }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Address</p>
                                    <p class="text-base font-semibold text-gray-800">{{ $patient->full_address }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Additional Information</h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Occupation</p>
                                    <p class="text-base font-semibold text-gray-800">{{ $patient->occupation }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Religion</p>
                                    <p class="text-base font-semibold text-gray-800">{{ $patient->religion }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medical Records Management -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="p-6 sm:p-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Medical Records</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <a href="{{ route('doctor.ehr.show', $patient) }}" class="block p-6 bg-gray-50 hover:bg-gray-100 rounded-lg transition-all duration-300">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0 w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center">
                                    <i class="fas fa-file-medical-alt text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-800">View EHR</h4>
                                    <p class="text-sm text-gray-600">View the patient's electronic health record.</p>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('doctor.patient.diagnoses.index', $patient) }}" class="block p-6 bg-gray-50 hover:bg-gray-100 rounded-lg transition-all duration-300">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0 w-12 h-12 bg-green-500 text-white rounded-full flex items-center justify-center">
                                    <i class="fas fa-stethoscope text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-800">Manage Diagnoses</h4>
                                    <p class="text-sm text-gray-600">View and manage the patient's diagnoses.</p>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('doctor.patient.referrals.index', $patient) }}" class="block p-6 bg-gray-50 hover:bg-gray-100 rounded-lg transition-all duration-300">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0 w-12 h-12 bg-yellow-500 text-white rounded-full flex items-center justify-center">
                                    <i class="fas fa-share-square text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-800">Manage Referrals</h4>
                                    <p class="text-sm text-gray-600">View and manage the patient's referrals.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
