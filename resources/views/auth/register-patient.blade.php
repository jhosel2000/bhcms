<x-guest-layout>
    <div class="relative min-h-screen bg-amber-950">
        <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
            <div class="absolute left-1/2 top-[-12%] h-[28rem] w-[28rem] -translate-x-1/2 rounded-full bg-amber-400/30 blur-3xl"></div>
            <div class="absolute right-[12%] bottom-[-14%] h-[22rem] w-[22rem] rounded-full bg-amber-300/25 blur-3xl"></div>
            <div class="absolute left-[6%] bottom-[18%] h-64 w-64 rounded-full bg-amber-500/15 blur-2xl"></div>
        </div>

        <div class="relative mx-auto flex min-h-screen max-w-6xl items-center px-4 py-14 sm:px-8">
            <div class="w-full overflow-hidden rounded-3xl border border-white/10 bg-white/80 shadow-2xl backdrop-blur-2xl">
                <div class="grid lg:grid-cols-12">
                    <aside class="relative overflow-hidden bg-gradient-to-br from-amber-600 via-amber-500 to-amber-700 px-8 py-12 text-white sm:px-10 lg:col-span-5 lg:py-16">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.18),_rgba(0,0,0,0))] opacity-70"></div>
                        <div class="relative z-10 flex h-full flex-col justify-between gap-10">
                            <div>
                                <div class="flex items-center gap-2 text-amber-100/70 text-xs uppercase tracking-[0.35em]">
                                    <span class="inline-flex h-1.5 w-1.5 rounded-full bg-white/80"></span>
                                    Barangay Services
                                </div>
                                <h1 class="mt-5 text-3xl font-bold sm:text-4xl">Patient Registration</h1>
                                <p class="mt-5 max-w-md text-sm text-amber-50/90 sm:text-base">
                                    Build your personal health profile, stay informed about appointments, and collaborate with care teams through a compassionate, patient-centered experience.
                                </p>
                            </div>

                            <div class="space-y-6">
                                <div class="rounded-3xl border border-white/20 bg-white/10 p-6 shadow-xl shadow-amber-900/25">
                                    <p class="text-sm font-semibold uppercase tracking-wider text-amber-50/75">What you gain</p>
                                    <ul class="mt-4 space-y-4 text-sm text-amber-50/80">
                                        <li class="flex items-start gap-3">
                                            <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/20 text-xs font-semibold">✔</span>
                                            <span><strong class="font-semibold">Streamlined scheduling</strong> with appointment reminders and status tracking.</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/20 text-xs font-semibold">✔</span>
                                            <span><strong class="font-semibold">Centralized health records</strong> for quick access to vital information.</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/20 text-xs font-semibold">✔</span>
                                            <span><strong class="font-semibold">Secure access</strong> with guided password strength and privacy safeguards.</span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="flex items-center gap-4">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-white/20 bg-white/10 shadow-lg shadow-amber-900/30">
                                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto">
                                    </div>
                                    <p class="text-sm text-amber-50/85">Barangay Health Center Monitoring Scheduling System</p>
                                </div>
                            </div>
                        </div>
                    </aside>

                    <div class="bg-white/70 px-8 py-10 sm:px-10 lg:col-span-7 lg:px-12 lg:py-14">
                        <div class="mx-auto w-full max-w-xl space-y-8">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-amber-600">Registration Journey</p>
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Guided steps</span>
                                </div>
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between" data-progress>
                                    <div class="flex items-center gap-3" data-progress-group>
                                        <div class="progress-step transition-all duration-300"
                                             data-base-class="progress-step flex h-10 w-10 items-center justify-center rounded-full border border-amber-100 bg-white text-amber-500/70 shadow-sm"
                                             data-active-class="bg-amber-500 text-white border-transparent shadow-xl shadow-amber-200 ring-4 ring-amber-100">1</div>
                                        <span class="progress-label text-xs font-medium uppercase tracking-wide text-slate-500"
                                              data-base-class="progress-label text-xs font-medium uppercase tracking-wide text-slate-500"
                                              data-active-class="text-amber-600">Personal Info</span>
                                    </div>
                                    <div class="flex items-center gap-3" data-progress-group>
                                        <div class="progress-step transition-all duration-300"
                                             data-base-class="progress-step flex h-10 w-10 items-center justify-center rounded-full border border-amber-100 bg-white text-amber-500/70 shadow-sm"
                                             data-active-class="bg-amber-500 text-white border-transparent shadow-xl shadow-amber-200 ring-4 ring-amber-100">2</div>
                                        <span class="progress-label text-xs font-medium uppercase tracking-wide text-slate-500"
                                              data-base-class="progress-label text-xs font-medium uppercase tracking-wide text-slate-500"
                                              data-active-class="text-amber-600">Contact Details</span>
                                    </div>
                                    <div class="flex items-center gap-3" data-progress-group>
                                        <div class="progress-step transition-all duration-300"
                                             data-base-class="progress-step flex h-10 w-10 items-center justify-center rounded-full border border-amber-100 bg-white text-amber-500/70 shadow-sm"
                                             data-active-class="bg-amber-500 text-white border-transparent shadow-xl shadow-amber-200 ring-4 ring-amber-100">3</div>
                                        <span class="progress-label text-xs font-medium uppercase tracking-wide text-slate-500"
                                              data-base-class="progress-label text-xs font-medium uppercase tracking-wide text-slate-500"
                                              data-active-class="text-amber-600">Account Setup</span>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('register.patient') }}" class="space-y-8">
                                @csrf

                                <section class="space-y-6 rounded-3xl border border-amber-100/60 bg-white/80 p-6 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h2 class="text-lg font-semibold text-slate-800">Personal Information</h2>
                                            <p class="text-sm text-slate-500">Let us know who you are so we can tailor your care.</p>
                                        </div>
                                        <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-600">Step 01</span>
                                    </div>
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                        <div class="sm:col-span-2">
                                            <label for="full_name" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Full Name</label>
                                            <div class="relative">
                                                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-amber-400">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                                <input id="full_name" type="text" name="full_name" value="{{ old('full_name') }}"
                                                       class="block w-full rounded-2xl border border-slate-200 bg-white/90 py-3.5 pl-11 pr-4 text-sm text-slate-700 shadow-inner shadow-amber-50 transition focus:border-amber-400 focus:outline-none focus:ring-4 focus:ring-amber-100"
                                                       required autofocus>
                                                <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div>
                                            <label for="date_of_birth" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Date of Birth</label>
                                            <input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                                                   class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-amber-50 transition focus:border-amber-400 focus:outline-none focus:ring-4 focus:ring-amber-100"
                                                   required>
                                            <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                                        </div>
                                        <div>
                                            <label for="gender" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Gender</label>
                                            <select id="gender" name="gender"
                                                    class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-amber-50 transition focus:border-amber-400 focus:outline-none focus:ring-4 focus:ring-amber-100"
                                                    required>
                                                <option value="">Select Gender</option>
                                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                                        </div>
                                        <div>
                                            <label for="civil_status" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Civil Status</label>
                                            <input id="civil_status" type="text" name="civil_status" value="{{ old('civil_status') }}"
                                                   class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-amber-50 transition focus:border-amber-400 focus:outline-none focus:ring-4 focus:ring-amber-100"
                                                   required>
                                            <x-input-error :messages="$errors->get('civil_status')" class="mt-2" />
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label for="religion" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Religion</label>
                                            <input id="religion" type="text" name="religion" value="{{ old('religion') }}"
                                                   class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-amber-50 transition focus:border-amber-400 focus:outline-none focus:ring-4 focus:ring-amber-100"
                                                   required>
                                            <x-input-error :messages="$errors->get('religion')" class="mt-2" />
                                        </div>
                                    </div>
                                </section>

                                <section class="space-y-6 rounded-3xl border border-amber-100/60 bg-white/80 p-6 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h2 class="text-lg font-semibold text-slate-800">Contact & Emergency Details</h2>
                                            <p class="text-sm text-slate-500">Keep your support network and health team connected.</p>
                                        </div>
                                        <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-600">Step 02</span>
                                    </div>
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                        <div class="sm:col-span-2">
                                            <label for="full_address" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Full Address</label>
                                            <textarea id="full_address" name="full_address"
                                                      class="block min-h-[120px] w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-amber-50 transition focus:border-amber-400 focus:outline-none focus:ring-4 focus:ring-amber-100"
                                                      required>{{ old('full_address') }}</textarea>
                                            <x-input-error :messages="$errors->get('full_address')" class="mt-2" />
                                        </div>
                                        <div>
                                            <label for="contact_number" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Contact Number</label>
                                            <input id="contact_number" type="text" name="contact_number" value="{{ old('contact_number') }}"
                                                   class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-amber-50 transition focus:border-amber-400 focus:outline-none focus:ring-4 focus:ring-amber-100"
                                                   required>
                                            <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
                                        </div>
                                        <div>
                                            <label for="email_address" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Email Address</label>
                                            <input id="email_address" type="email" name="email_address" value="{{ old('email_address') }}"
                                                   class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-amber-50 transition focus:border-amber-400 focus:outline-none focus:ring-4 focus:ring-amber-100"
                                                   required>
                                            <x-input-error :messages="$errors->get('email_address')" class="mt-2" />
                                        </div>
                                        <div>
                                            <label for="occupation" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Occupation</label>
                                            <input id="occupation" type="text" name="occupation" value="{{ old('occupation') }}"
                                                   class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-amber-50 transition focus:border-amber-400 focus:outline-none focus:ring-4 focus:ring-amber-100"
                                                   required>
                                            <x-input-error :messages="$errors->get('occupation')" class="mt-2" />
                                        </div>
                                        <div>
                                            <label for="emergency_contact_name" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Emergency Contact Name</label>
                                            <input id="emergency_contact_name" type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}"
                                                   class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-amber-50 transition focus:border-amber-400 focus:outline-none focus:ring-4 focus:ring-amber-100"
                                                   required>
                                            <x-input-error :messages="$errors->get('emergency_contact_name')" class="mt-2" />
                                        </div>
                                        <div>
                                            <label for="emergency_contact_number" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Emergency Contact Number</label>
                                            <input id="emergency_contact_number" type="text" name="emergency_contact_number" value="{{ old('emergency_contact_number') }}"
                                                   class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-amber-50 transition focus:border-amber-400 focus:outline-none focus:ring-4 focus:ring-amber-100"
                                                   required>
                                            <x-input-error :messages="$errors->get('emergency_contact_number')" class="mt-2" />
                                        </div>
                                    </div>
                                </section>

                                <section class="space-y-6 rounded-3xl border border-amber-100/60 bg-white/80 p-6 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h2 class="text-lg font-semibold text-slate-800">Account Security</h2>
                                            <p class="text-sm text-slate-500">Create a strong password to protect your account.</p>
                                        </div>
                                        <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-600">Step 03</span>
                                    </div>
                                    <div class="grid grid-cols-1 gap-6">
                                        <div>
                                            <label for="password" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Password</label>
                                            <div class="relative">
                                                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-amber-400">
                                                    <i class="fas fa-lock"></i>
                                                </span>
                                                <input id="password" type="password" name="password"
                                                       class="block w-full rounded-2xl border border-slate-200 bg-white/90 py-3.5 pl-11 pr-4 text-sm text-slate-700 shadow-inner shadow-amber-50 transition focus:border-amber-400 focus:outline-none focus:ring-4 focus:ring-amber-100"
                                                       required autocomplete="new-password">
                                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                            </div>
                                            <div id="password-strength" class="mt-3 space-y-2">
                                                <div class="flex space-x-1">
                                                    <div id="strength-1" class="h-2 w-1/4 rounded bg-slate-200"></div>
                                                    <div id="strength-2" class="h-2 w-1/4 rounded bg-slate-200"></div>
                                                    <div id="strength-3" class="h-2 w-1/4 rounded bg-slate-200"></div>
                                                    <div id="strength-4" class="h-2 w-1/4 rounded bg-slate-200"></div>
                                                </div>
                                                <p id="strength-text" class="text-xs text-slate-500">Password strength</p>
                                            </div>
                                        </div>
                                        <div>
                                            <label for="password_confirmation" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Confirm Password</label>
                                            <input id="password_confirmation" type="password" name="password_confirmation"
                                                   class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-amber-50 transition focus:border-amber-400 focus:outline-none focus:ring-4 focus:ring-amber-100"
                                                   required autocomplete="new-password">
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                        </div>
                                    </div>
                                </section>

                                <div class="space-y-6">
                                    <div class="flex items-start gap-3 rounded-2xl border border-slate-200/80 bg-white/75 p-4">
                                        <input id="terms" name="terms" type="checkbox"
                                               class="mt-1 h-4 w-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500"
                                               required>
                                        <label for="terms" class="text-sm text-slate-600">
                                            I agree to the <a href="#" class="font-semibold text-amber-600 hover:text-amber-500">Terms of Service</a> and <a href="#" class="font-semibold text-amber-600 hover:text-amber-500">Privacy Policy</a> of the Barangay Health Center Monitoring Scheduling System.
                                        </label>
                                    </div>

                                    <div class="flex items-start gap-3 rounded-2xl border border-slate-200/80 bg-white/75 p-4">
                                        <input id="sms_notifications" name="sms_notifications" type="checkbox"
                                               class="mt-1 h-4 w-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                                        <label for="sms_notifications" class="text-sm text-slate-600">
                                            I would like to receive SMS notifications for appointments and health updates.
                                        </label>
                                    </div>

                                    <input type="hidden" name="role" value="patient" />

                                    <button type="submit" class="group relative flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-amber-600 via-amber-500 to-amber-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-amber-600/30 transition hover:scale-[1.01] hover:from-amber-500 hover:to-amber-700 focus:outline-none focus:ring-4 focus:ring-amber-200">
                                        <span>Register as Patient</span>
                                        <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                                    </button>

                                    <div class="text-center text-sm text-slate-600">
                                        Already have an account?
                                        <a href="{{ route('login') }}" class="font-semibold text-amber-600 hover:text-amber-500">Sign in here</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const strengthBars = [
            document.getElementById('strength-1'),
            document.getElementById('strength-2'),
            document.getElementById('strength-3'),
            document.getElementById('strength-4')
        ];
        const strengthText = document.getElementById('strength-text');
        const form = document.querySelector('form');
        const progressGroups = document.querySelectorAll('[data-progress-group]');
        const progressSteps = Array.from(progressGroups).map(group => group.querySelector('.progress-step'));
        const progressLabels = Array.from(progressGroups).map(group => group.querySelector('.progress-label'));

        passwordInput.addEventListener('input', function () {
            const password = this.value;
            let strength = 0;

            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;

            strengthBars.forEach((bar, index) => {
                if (index < strength) {
                    bar.className = 'h-2 w-1/4 rounded bg-amber-500 shadow-sm shadow-amber-200';
                } else {
                    bar.className = 'h-2 w-1/4 rounded bg-slate-200';
                }
            });

            const strengthMessages = ['Weak', 'Fair', 'Good', 'Strong'];
            strengthText.textContent = strengthMessages[Math.max(0, strength - 1)] || 'Weak';
            const textColors = ['text-red-500', 'text-amber-500', 'text-blue-500', 'text-green-500'];
            strengthText.className = `text-xs ${textColors[Math.max(0, strength - 1)] || 'text-slate-500'}`;
        });

        const sectionFieldGroups = [
            ['full_name', 'date_of_birth', 'gender', 'civil_status', 'religion'],
            ['full_address', 'contact_number', 'email_address', 'occupation', 'emergency_contact_name', 'emergency_contact_number'],
            ['password', 'password_confirmation']
        ];

        form.addEventListener('input', function () {
            sectionFieldGroups.forEach((fields, index) => {
                const isComplete = fields.every(fieldId => {
                    const field = document.getElementById(fieldId);
                    return field && field.value.trim() !== '';
                });
                updateProgress(index, isComplete);
            });
        });

        function updateProgress(stepIndex, isComplete) {
            const step = progressSteps[stepIndex];
            const label = progressLabels[stepIndex];
            const baseClass = step.getAttribute('data-base-class');
            const activeClass = step.getAttribute('data-active-class');
            const labelBase = label.getAttribute('data-base-class');
            const labelActive = label.getAttribute('data-active-class');

            if (isComplete) {
                step.className = `${baseClass} ${activeClass}`;
                label.className = `${labelBase} ${labelActive}`;
            } else {
                step.className = baseClass;
                label.className = labelBase;
            }
        }
    </script>
</x-guest-layout>
