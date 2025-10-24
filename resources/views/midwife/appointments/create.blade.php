<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Book New Appointment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('midwife.appointments.store') }}" id="midwife-appointment-form" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="patient_id" :value="__('Patient')" />
                            <select name="patient_id" id="patient_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select a patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>{{ $patient->full_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Healthcare Provider</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Doctor</label>
                                    <select name="doctor_id" id="doctor_id" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Select Doctor</option>
                                        @foreach($doctors as $doctor)
                                            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>{{ $doctor->user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">Midwife</label>
                                    <select name="midwife_id" id="midwife_id" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Select Midwife</option>
                                        @foreach($midwives as $midwife)
                                            <option value="{{ $midwife->id }}" {{ old('midwife_id') == $midwife->id ? 'selected' : '' }}>{{ $midwife->user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-600 mb-1">BHW</label>
                                    <select name="bhw_id" id="bhw_id" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Select BHW</option>
                                        @foreach($bhws as $bhw)
                                            <option value="{{ $bhw->id }}" {{ old('bhw_id') == $bhw->id ? 'selected' : '' }}>{{ $bhw->user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-600">Select one healthcare provider (Doctor, Midwife, or BHW)</p>
                            <x-input-error :messages="$errors->get('doctor_id')" class="mt-2" />
                            <x-input-error :messages="$errors->get('midwife_id')" class="mt-2" />
                            <x-input-error :messages="$errors->get('bhw_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="appointment_date" :value="__('Appointment Date')" />
                            <x-text-input id="appointment_date" name="appointment_date" type="date" class="mt-1 block w-full" :value="old('appointment_date')" required />
                            <p class="mt-1 text-sm text-gray-600">Appointments can only be booked on Wednesdays.</p>
                            <x-input-error :messages="$errors->get('appointment_date')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="appointment_time" :value="__('Appointment Time')" />
                            <x-text-input id="appointment_time" name="appointment_time" type="time" class="mt-1 block w-full" :value="old('appointment_time')" required />
                            <x-input-error :messages="$errors->get('appointment_time')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="reason" :value="__('Reason for Visit')" />
                            <x-text-input id="reason" name="reason" type="text" class="mt-1 block w-full" :value="old('reason')" placeholder="e.g., Prenatal checkup, Vaccination" required />
                            <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="urgency_level" :value="__('Urgency Level')" />
                            <select name="urgency_level" id="urgency_level" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="normal" {{ old('urgency_level', 'normal') == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="urgent" {{ old('urgency_level') == 'urgent' ? 'selected' : '' }}>🚨 Urgent</option>
                                <option value="maternal" {{ old('urgency_level') == 'maternal' ? 'selected' : '' }}>🤰 Maternal Care</option>
                            </select>
                            <p class="mt-1 text-sm text-gray-600">Select urgency level for this appointment.</p>
                            <x-input-error :messages="$errors->get('urgency_level')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="uploaded_files" :value="__('Supporting Files (Optional)')" />
                            <input type="file" name="uploaded_files[]" id="uploaded_files" class="mt-1 block w-full" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
                            <p class="mt-1 text-sm text-gray-600">Upload lab results, referral letters, etc. Allowed: PDF, DOC, DOCX, JPG, JPEG, PNG. Max 5MB each.</p>
                            <x-input-error :messages="$errors->get('uploaded_files')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3" placeholder="Additional notes or reasons for the appointment">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-secondary-button onclick="window.history.back()">
                                {{ __('Cancel') }}
                            </x-secondary-button>

                            <x-primary-button class="ml-4">
                                {{ __('Book Appointment') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
