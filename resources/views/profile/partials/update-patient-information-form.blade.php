<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Personal Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your personal and contact information.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Full Name -->
            <div>
                <x-input-label for="full_name" :value="__('Full Name')" />
                <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full"
                    :value="old('full_name', $patient->full_name)" required autofocus />
                <x-input-error class="mt-2" :messages="$errors->get('full_name')" />
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email Address')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                    :value="old('email', $user->email)" required />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-sm text-gray-800">
                            {{ __('Your email address is unverified.') }}
                            <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Date of Birth -->
            <div>
                <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full"
                    :value="old('date_of_birth', $patient->date_of_birth?->format('Y-m-d'))" required />
                <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
            </div>

            <!-- Gender -->
            <div>
                <x-input-label for="gender" :value="__('Gender')" />
                <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    <option value="">Select Gender</option>
                    <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender', $patient->gender) == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('gender')" />
            </div>

            <!-- Contact Number -->
            <div>
                <x-input-label for="contact_number" :value="__('Contact Number')" />
                <x-text-input id="contact_number" name="contact_number" type="text" class="mt-1 block w-full"
                    :value="old('contact_number', $patient->contact_number)" required
                    placeholder="e.g., 09123456789" />
                <x-input-error class="mt-2" :messages="$errors->get('contact_number')" />
            </div>

            <!-- Civil Status -->
            <div>
                <x-input-label for="civil_status" :value="__('Civil Status')" />
                <select id="civil_status" name="civil_status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Select Civil Status</option>
                    <option value="single" {{ old('civil_status', $patient->civil_status) == 'single' ? 'selected' : '' }}>Single</option>
                    <option value="married" {{ old('civil_status', $patient->civil_status) == 'married' ? 'selected' : '' }}>Married</option>
                    <option value="divorced" {{ old('civil_status', $patient->civil_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                    <option value="widowed" {{ old('civil_status', $patient->civil_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('civil_status')" />
            </div>

            <!-- Occupation -->
            <div>
                <x-input-label for="occupation" :value="__('Occupation')" />
                <x-text-input id="occupation" name="occupation" type="text" class="mt-1 block w-full"
                    :value="old('occupation', $patient->occupation)" />
                <x-input-error class="mt-2" :messages="$errors->get('occupation')" />
            </div>

            <!-- Religion -->
            <div>
                <x-input-label for="religion" :value="__('Religion')" />
                <x-text-input id="religion" name="religion" type="text" class="mt-1 block w-full"
                    :value="old('religion', $patient->religion)" />
                <x-input-error class="mt-2" :messages="$errors->get('religion')" />
            </div>
        </div>

        <!-- Full Address -->
        <div>
            <x-input-label for="full_address" :value="__('Full Address')" />
            <textarea id="full_address" name="full_address" rows="2"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                required>{{ old('full_address', $patient->full_address) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('full_address')" />
        </div>

        <!-- Barangay -->
        <div>
            <x-input-label for="barangay" :value="__('Barangay')" />
            <x-text-input id="barangay" name="barangay" type="text" class="mt-1 block w-full"
                :value="old('barangay', $patient->barangay)" />
            <x-input-error class="mt-2" :messages="$errors->get('barangay')" />
        </div>

        <!-- Emergency Contact Section -->
        <div class="border-t pt-6 mt-6">
            <h3 class="text-md font-medium text-gray-900 mb-4">{{ __('Emergency Contact Information') }}</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Emergency Contact Name -->
                <div>
                    <x-input-label for="emergency_contact_name" :value="__('Emergency Contact Name')" />
                    <x-text-input id="emergency_contact_name" name="emergency_contact_name" type="text"
                        class="mt-1 block w-full" :value="old('emergency_contact_name', $patient->emergency_contact_name)" />
                    <x-input-error class="mt-2" :messages="$errors->get('emergency_contact_name')" />
                </div>

                <!-- Emergency Contact Number -->
                <div>
                    <x-input-label for="emergency_contact_number" :value="__('Emergency Contact Number')" />
                    <x-text-input id="emergency_contact_number" name="emergency_contact_number" type="text"
                        class="mt-1 block w-full" :value="old('emergency_contact_number', $patient->emergency_contact_number)"
                        placeholder="e.g., 09123456789" />
                    <x-input-error class="mt-2" :messages="$errors->get('emergency_contact_number')" />
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>
</section>
