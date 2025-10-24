{{--
    Prescription Create Modal Component
    Modern modal form for creating new prescriptions
--}}
@props(['patients' => []])

<x-modal name="prescription-create-modal" maxWidth="4xl">
    <div class="bg-white">
        {{-- Modal Header --}}
        <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-emerald-600">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">Create New Prescription</h3>
                        <p class="text-green-100 text-sm">Add a new medication prescription for your patient</p>
                    </div>
                </div>
                <button @click="$dispatch('close-modal', { name: 'prescription-create-modal' })"
                        class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-1 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Modal Body --}}
        <div class="px-6 py-6">
            <form id="prescription-create-form" method="POST" action="{{ route('doctor.prescriptions.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Left Column --}}
                    <div class="space-y-6">
                        {{-- Patient Selection --}}
                        <div>
                            <label for="modal_patient_id" class="block text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Patient <span class="text-red-500">*</span>
                            </label>
                            <select id="modal_patient_id" name="patient_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                                    required>
                                <option value="">Select a patient...</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}">
                                        {{ $patient->full_name }} ({{ $patient->id }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />
                        </div>

                        {{-- Medication Name --}}
                        <div>
                            <label for="modal_medication_name" class="block text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                                Medication Name <span class="text-red-500">*</span>
                            </label>
                            <input id="modal_medication_name" type="text" name="medication_name"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                   placeholder="e.g., Amoxicillin 500mg, Ibuprofen 200mg"
                                   required>
                            <x-input-error :messages="$errors->get('medication_name')" class="mt-2" />
                        </div>

                        {{-- Dosage --}}
                        <div>
                            <label for="modal_dosage" class="block text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                Dosage <span class="text-red-500">*</span>
                            </label>
                            <input id="modal_dosage" type="text" name="dosage"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                   placeholder="e.g., 1 tablet, 5ml, 10mg"
                                   required>
                            <x-input-error :messages="$errors->get('dosage')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-6">
                        {{-- Frequency --}}
                        <div>
                            <label for="modal_frequency" class="block text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Frequency <span class="text-red-500">*</span>
                            </label>
                            <input id="modal_frequency" type="text" name="frequency"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                                   placeholder="e.g., Twice daily, Every 8 hours, As needed"
                                   required>
                            <x-input-error :messages="$errors->get('frequency')" class="mt-2" />
                        </div>

                        {{-- Duration --}}
                        <div>
                            <label for="modal_duration" class="block text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2z"></path>
                                </svg>
                                Duration <span class="text-red-500">*</span>
                            </label>
                            <input id="modal_duration" type="text" name="duration"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                                   placeholder="e.g., 7 days, 2 weeks, 1 month"
                                   required>
                            <x-input-error :messages="$errors->get('duration')" class="mt-2" />
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="modal_status" class="block text-sm font-semibold text-gray-900 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Status
                            </label>
                            <select id="modal_status" name="status"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200 bg-white">
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                                <option value="pending_refill">Pending Refill</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                    </div>
                </div>

                {{-- Instructions --}}
                <div>
                    <label for="modal_instructions" class="block text-sm font-semibold text-gray-900 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Instructions
                    </label>
                    <textarea id="modal_instructions" name="instructions" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 resize-none"
                              placeholder="Special instructions for the patient (e.g., take with food, avoid alcohol, etc.)"></textarea>
                    <x-input-error :messages="$errors->get('instructions')" class="mt-2" />
                </div>
            </form>
        </div>

        {{-- Modal Footer --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
            <button @click="$dispatch('close-modal', { name: 'prescription-create-modal' })"
                    type="button"
                    class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 font-medium">
                Cancel
            </button>

            <div class="flex items-center space-x-3">
                <button type="button"
                        onclick="resetPrescriptionForm()"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                    Reset Form
                </button>

                <button type="submit"
                        form="prescription-create-form"
                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Create Prescription
                </button>
            </div>
        </div>
    </div>

    <script>
        function resetPrescriptionForm() {
            document.getElementById('prescription-create-form').reset();
        }
    </script>
</x-modal>
