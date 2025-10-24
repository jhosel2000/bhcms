<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Appointment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('midwife.appointments.update', $appointment) }}" id="midwife-appointment-edit-form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <x-input-label for="patient_id" :value="__('Patient')" />
                            <select name="patient_id" id="patient_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select a patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ $appointment->patient_id == $patient->id ? 'selected' : '' }}>{{ $patient->full_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="doctor_id" :value="__('Doctor')" />
                            <select name="doctor_id" id="doctor_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select a doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ $appointment->doctor_id == $doctor->id ? 'selected' : '' }}>{{ $doctor->user->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('doctor_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="appointment_date" :value="__('Appointment Date')" />
                            <x-text-input id="appointment_date" name="appointment_date" type="date" class="mt-1 block w-full" :value="old('appointment_date', $appointment->appointment_date)" required />
                            <x-input-error :messages="$errors->get('appointment_date')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="appointment_time" :value="__('Appointment Time')" />
                            <x-text-input id="appointment_time" name="appointment_time" type="time" class="mt-1 block w-full" :value="old('appointment_time', $appointment->appointment_time)" required />
                            <x-input-error :messages="$errors->get('appointment_time')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="reason" :value="__('Reason for Visit')" />
                            <x-text-input id="reason" name="reason" type="text" class="mt-1 block w-full" :value="old('reason', $appointment->reason)" placeholder="e.g., Prenatal checkup, Vaccination" required />
                            <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="urgency_level" :value="__('Urgency Level')" />
                            <select name="urgency_level" id="urgency_level" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="normal" {{ old('urgency_level', $appointment->urgency_level) == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="urgent" {{ old('urgency_level', $appointment->urgency_level) == 'urgent' ? 'selected' : '' }}>🚨 Urgent</option>
                                <option value="maternal" {{ old('urgency_level', $appointment->urgency_level) == 'maternal' ? 'selected' : '' }}>🤰 Maternal Care</option>
                            </select>
                            <x-input-error :messages="$errors->get('urgency_level')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select name="status" id="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="pending" {{ $appointment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $appointment->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="declined" {{ $appointment->status == 'declined' ? 'selected' : '' }}>Declined</option>
                                <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="expired" {{ $appointment->status == 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="uploaded_files" :value="__('Upload Additional Files')" />
                            <input type="file" name="uploaded_files[]" id="uploaded_files" class="mt-1 block w-full" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
                            <p class="mt-1 text-sm text-gray-600">Allowed file types: PDF, DOC, DOCX, JPG, JPEG, PNG. Max 5MB each.</p>
                            @if($appointment->uploaded_files)
                                <p class="mt-1 text-sm text-gray-600">Existing files: {{ count($appointment->uploaded_files) }}</p>
                            @endif
                            <x-input-error :messages="$errors->get('uploaded_files')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('notes', $appointment->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <!-- Availability Widget Section -->
                        <div class="mt-6 p-4 bg-pink-50 rounded-lg border border-pink-200">
                            <h3 class="text-lg font-medium text-pink-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Available Time Slots
                            </h3>

                            <x-appointment-availability
                                :provider-id="auth()->user()->midwife->id"
                                provider-type="midwife"
                                :selected-date="old('appointment_date', $appointment->appointment_date)"
                            />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-secondary-button onclick="window.history.back()">
                                {{ __('Cancel') }}
                            </x-secondary-button>

                            <x-primary-button class="ml-4">
                                {{ __('Update Appointment') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
