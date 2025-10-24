<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4 sm:mb-0">
                {{ __('Create New Patient') }}
            </h2>
            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('doctor.patients.index') }}"
                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Patients
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    <form method="POST" action="{{ route('doctor.patients.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
                            <div class="space-y-6">
                                <div>
                                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ __('Full Name') }}
                                    </label>
                                    <input id="full_name" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" type="text" name="full_name" :value="old('full_name')" required autofocus autocomplete="full_name" />
                                    <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Date of Birth') }}</label>
                                    <input id="date_of_birth" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" type="date" name="date_of_birth" :value="old('date_of_birth')" required />
                                    <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Gender') }}</label>
                                    <select id="gender" name="gender" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Contact Number') }}</label>
                                    <input id="contact_number" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" type="text" name="contact_number" :value="old('contact_number')" required />
                                    <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="civil_status" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Civil Status') }}</label>
                                    <select id="civil_status" name="civil_status" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                        <option value="">Select Civil Status</option>
                                        <option value="single" {{ old('civil_status') == 'single' ? 'selected' : '' }}>Single</option>
                                        <option value="married" {{ old('civil_status') == 'married' ? 'selected' : '' }}>Married</option>
                                        <option value="divorced" {{ old('civil_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                        <option value="widowed" {{ old('civil_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('civil_status')" class="mt-2" />
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label for="full_address" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Full Address') }}</label>
                                    <textarea id="full_address" name="full_address" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>{{ old('full_address') }}</textarea>
                                    <x-input-error :messages="$errors->get('full_address')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Emergency Contact Name') }}</label>
                                    <input id="emergency_contact_name" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" type="text" name="emergency_contact_name" :value="old('emergency_contact_name')" required />
                                    <x-input-error :messages="$errors->get('emergency_contact_name')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="emergency_contact_number" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Emergency Contact Number') }}</label>
                                    <input id="emergency_contact_number" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" type="text" name="emergency_contact_number" :value="old('emergency_contact_number')" required />
                                    <x-input-error :messages="$errors->get('emergency_contact_number')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="occupation" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Occupation') }}</label>
                                    <input id="occupation" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" type="text" name="occupation" :value="old('occupation')" />
                                    <x-input-error :messages="$errors->get('occupation')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="religion" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Religion') }}</label>
                                    <input id="religion" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" type="text" name="religion" :value="old('religion')" />
                                    <x-input-error :messages="$errors->get('religion')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row sm:justify-end gap-3 pt-6 border-t border-gray-200">
                            <a href="{{ route('doctor.patients.index') }}"
                               class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ __('Create Patient') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
