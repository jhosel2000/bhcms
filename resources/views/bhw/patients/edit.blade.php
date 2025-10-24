<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Patient') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('bhw.patients.update', $patient) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="full_name" :value="__('Full Name')" />
                                    <x-text-input id="full_name" class="block mt-1 w-full" type="text" name="full_name" :value="old('full_name', $patient->full_name)" required autofocus autocomplete="full_name" />
                                    <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                                    <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth" :value="old('date_of_birth', $patient->date_of_birth ? $patient->date_of_birth->format('Y-m-d') : '')" required />
                                    <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="gender" :value="__('Gender')" />
                                    <select id="gender" name="gender" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $patient->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="contact_number" :value="__('Contact Number')" />
                                    <x-text-input id="contact_number" class="block mt-1 w-full" type="text" name="contact_number" :value="old('contact_number', $patient->contact_number)" required />
                                    <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="civil_status" :value="__('Civil Status')" />
                                    <select id="civil_status" name="civil_status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="single" {{ old('civil_status', $patient->civil_status) == 'single' ? 'selected' : '' }}>Single</option>
                                        <option value="married" {{ old('civil_status', $patient->civil_status) == 'married' ? 'selected' : '' }}>Married</option>
                                        <option value="divorced" {{ old('civil_status', $patient->civil_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                        <option value="widowed" {{ old('civil_status', $patient->civil_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('civil_status')" class="mt-2" />
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="full_address" :value="__('Full Address')" />
                                    <textarea id="full_address" name="full_address" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('full_address', $patient->full_address) }}</textarea>
                                    <x-input-error :messages="$errors->get('full_address')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="emergency_contact_name" :value="__('Emergency Contact Name')" />
                                    <x-text-input id="emergency_contact_name" class="block mt-1 w-full" type="text" name="emergency_contact_name" :value="old('emergency_contact_name', $patient->emergency_contact_name)" required />
                                    <x-input-error :messages="$errors->get('emergency_contact_name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="emergency_contact_number" :value="__('Emergency Contact Number')" />
                                    <x-text-input id="emergency_contact_number" class="block mt-1 w-full" type="text" name="emergency_contact_number" :value="old('emergency_contact_number', $patient->emergency_contact_number)" required />
                                    <x-input-error :messages="$errors->get('emergency_contact_number')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="occupation" :value="__('Occupation')" />
                                    <x-text-input id="occupation" class="block mt-1 w-full" type="text" name="occupation" :value="old('occupation', $patient->occupation)" />
                                    <x-input-error :messages="$errors->get('occupation')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="religion" :value="__('Religion')" />
                                    <x-text-input id="religion" class="block mt-1 w-full" type="text" name="religion" :value="old('religion', $patient->religion)" />
                                    <x-input-error :messages="$errors->get('religion')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('bhw.patients.show', $patient) }}" class="mr-4 text-gray-600 hover:text-gray-900">Cancel</a>
                            <x-primary-button>
                                {{ __('Update Patient') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
