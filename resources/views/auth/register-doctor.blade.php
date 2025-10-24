<x-guest-layout>
    <div class="relative min-h-screen bg-slate-950">
        <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
            <div class="absolute left-1/2 top-[-12%] h-[30rem] w-[30rem] -translate-x-1/2 rounded-full bg-blue-500/20 blur-3xl"></div>
            <div class="absolute right-[10%] bottom-[-18%] h-[26rem] w-[26rem] rounded-full bg-blue-400/20 blur-3xl"></div>
            <div class="absolute left-[6%] bottom-[22%] h-64 w-64 rounded-full bg-sky-500/15 blur-2xl"></div>
        </div>

        <div class="relative mx-auto flex min-h-screen max-w-6xl items-center px-4 py-14 sm:px-8">
            <div class="w-full overflow-hidden rounded-3xl border border-white/10 bg-white/80 shadow-2xl backdrop-blur-2xl">
                <div class="grid lg:grid-cols-12">
                    <aside class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-blue-600 to-blue-900 px-8 py-12 text-white sm:px-10 lg:col-span-5 lg:py-16">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.15),_rgba(0,0,0,0))] opacity-70"></div>
                        <div class="relative z-10 flex h-full flex-col justify-between gap-10">
                            <div>
                                <div class="flex items-center gap-2 text-blue-100/70 text-xs uppercase tracking-[0.35em]">
                                    <span class="inline-flex h-1.5 w-1.5 rounded-full bg-white/80"></span>
                                    Medical Pro Network
                                </div>
                                <h1 class="mt-5 text-3xl font-bold sm:text-4xl">Doctor Registration</h1>
                                <p class="mt-5 max-w-md text-sm text-blue-50/90 sm:text-base">
                                    Streamline patient engagements, coordinate with barangay health teams, and manage schedules through a unified professional suite designed specifically for physicians.
                                </p>
                            </div>

                            <div class="space-y-6">
                                <div class="rounded-3xl border border-white/20 bg-white/10 p-6 shadow-xl shadow-blue-900/25">
                                    <p class="text-sm font-semibold uppercase tracking-wider text-blue-50/75">Built for specialists</p>
                                    <ul class="mt-4 space-y-4 text-sm text-blue-50/85">
                                        <li class="flex items-start gap-3">
                                            <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/20 text-xs font-semibold">✔</span>
                                            <span><strong class="font-semibold">Patient-first tools</strong> for mapping barangay cases and monitoring follow-up visits.</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/20 text-xs font-semibold">✔</span>
                                            <span><strong class="font-semibold">Integrated credentials</strong> that simplify license verification and specialization data.</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/20 text-xs font-semibold">✔</span>
                                            <span><strong class="font-semibold">Intelligent security</strong> to safeguard patient information and practice records.</span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="flex items-center gap-4">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-white/20 bg-white/10 shadow-lg shadow-blue-900/30">
                                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto">
                                    </div>
                                    <p class="text-sm text-blue-50/85">Barangay Health Center Monitoring Scheduling System</p>
                                </div>
                            </div>
                        </div>
                    </aside>

                    <div class="bg-white/70 px-8 py-10 sm:px-10 lg:col-span-7 lg:px-12 lg:py-14">
                        <div class="mx-auto w-full max-w-xl space-y-8">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-blue-600">Registration Journey</p>
                                    <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">Guided steps</span>
                                </div>
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between" data-progress>
                                    <div class="flex items-center gap-3" data-progress-group>
                                        <div class="progress-step transition-all duration-300"
                                             data-base-class="progress-step flex h-10 w-10 items-center justify-center rounded-full border border-blue-100 bg-white text-blue-500/70 shadow-sm"
                                             data-active-class="bg-blue-600 text-white border-transparent shadow-xl shadow-blue-200 ring-4 ring-blue-100">1</div>
                                        <span class="progress-label text-xs font-medium uppercase tracking-wide text-slate-500"
                                              data-base-class="progress-label text-xs font-medium uppercase tracking-wide text-slate-500"
                                              data-active-class="text-blue-600">Personal Info</span>
                                    </div>
                                    <div class="flex items-center gap-3" data-progress-group>
                                        <div class="progress-step transition-all duration-300"
                                             data-base-class="progress-step flex h-10 w-10 items-center justify-center rounded-full border border-blue-100 bg-white text-blue-500/70 shadow-sm"
                                             data-active-class="bg-blue-600 text-white border-transparent shadow-xl shadow-blue-200 ring-4 ring-blue-100">2</div>
                                        <span class="progress-label text-xs font-medium uppercase tracking-wide text-slate-500"
                                              data-base-class="progress-label text-xs font-medium uppercase tracking-wide text-slate-500"
                                              data-active-class="text-blue-600">Credentials</span>
                                    </div>
                                    <div class="flex items-center gap-3" data-progress-group>
                                        <div class="progress-step transition-all duration-300"
                                             data-base-class="progress-step flex h-10 w-10 items-center justify-center rounded-full border border-blue-100 bg-white text-blue-500/70 shadow-sm"
                                             data-active-class="bg-blue-600 text-white border-transparent shadow-xl shadow-blue-200 ring-4 ring-blue-100">3</div>
                                        <span class="progress-label text-xs font-medium uppercase tracking-wide text-slate-500"
                                              data-base-class="progress-label text-xs font-medium uppercase tracking-wide text-slate-500"
                                              data-active-class="text-blue-600">Account Setup</span>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('register.doctor') }}" class="space-y-8">
                                @csrf

                                <section class="space-y-6 rounded-3xl border border-slate-200/60 bg-white/85 p-6 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h2 class="text-lg font-semibold text-slate-800">Personal Information</h2>
                                            <p class="text-sm text-slate-500">Help us recognize you across barangay health facilities.</p>
                                        </div>
                                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-600">Step 01</span>
                                    </div>
                                    <div class="grid grid-cols-1 gap-6">
                                        <div>
                                            <label for="full_name" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Full Name</label>
                                            <div class="relative">
                                                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-blue-400">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                                <input id="full_name" type="text" name="full_name" value="{{ old('full_name') }}"
                                                       class="block w-full rounded-2xl border border-slate-200 bg-white/90 py-3.5 pl-11 pr-4 text-sm text-slate-700 shadow-inner shadow-blue-50 transition focus:border-blue-400 focus:outline-none focus:ring-4 focus:ring-blue-100"
                                                       required autofocus>
                                                <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <section class="space-y-6 rounded-3xl border border-slate-200/60 bg-white/85 p-6 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h2 class="text-lg font-semibold text-slate-800">Professional Credentials</h2>
                                            <p class="text-sm text-slate-500">Provide your license details to authenticate your practice.</p>
                                        </div>
                                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-600">Step 02</span>
                                    </div>
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                        <div class="sm:col-span-2">
                                            <label for="professional_license_number" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Professional License Number</label>
                                            <input id="professional_license_number" type="text" name="professional_license_number" value="{{ old('professional_license_number') }}"
                                                   class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-blue-50 transition focus:border-blue-400 focus:outline-none focus:ring-4 focus:ring-blue-100"
                                                   required>
                                            <x-input-error :messages="$errors->get('professional_license_number')" class="mt-2" />
                                        </div>
                                        <div>
                                            <label for="specialization" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Specialization</label>
                                            <select id="specialization" name="specialization"
                                                    class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-blue-50 transition focus:border-blue-400 focus:outline-none focus:ring-4 focus:ring-blue-100"
                                                    required>
                                                <option value="">Select Specialization</option>
                                                <option value="General Practice" {{ old('specialization') == 'General Practice' ? 'selected' : '' }}>General Practice</option>
                                                <option value="Pediatrician" {{ old('specialization') == 'Pediatrician' ? 'selected' : '' }}>Pediatrician</option>
                                                <option value="Obstetrics" {{ old('specialization') == 'Obstetrics' ? 'selected' : '' }}>Obstetrics</option>
                                                <option value="Internal Medicine" {{ old('specialization') == 'Internal Medicine' ? 'selected' : '' }}>Internal Medicine</option>
                                                <option value="Surgery" {{ old('specialization') == 'Surgery' ? 'selected' : '' }}>Surgery</option>
                                            </select>
                                            <x-input-error :messages="$errors->get('specialization')" class="mt-2" />
                                        </div>
                                        <div>
                                            <label for="contact_number" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Contact Number</label>
                                            <input id="contact_number" type="text" name="contact_number" value="{{ old('contact_number') }}"
                                                   class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-blue-50 transition focus:border-blue-400 focus:outline-none focus:ring-4 focus:ring-blue-100"
                                                   required>
                                            <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
                                        </div>
                                        <div>
                                            <label for="email_address" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Email Address</label>
                                            <input id="email_address" type="email" name="email_address" value="{{ old('email_address') }}"
                                                   class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-blue-50 transition focus:border-blue-400 focus:outline-none focus:ring-4 focus:ring-blue-100"
                                                   required>
                                            <x-input-error :messages="$errors->get('email_address')" class="mt-2" />
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label for="verification_code" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Verification Code</label>
                                            <input id="verification_code" type="text" name="verification_code" value="{{ old('verification_code') }}"
                                                   class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-blue-50 transition focus:border-blue-400 focus:outline-none focus:ring-4 focus:ring-blue-100"
                                                   required>
                                            <x-input-error :messages="$errors->get('verification_code')" class="mt-2" />
                                        </div>
                                    </div>
                                </section>

                                <section class="space-y-6 rounded-3xl border border-slate-200/60 bg-white/85 p-6 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h2 class="text-lg font-semibold text-slate-800">Account Security</h2>
                                            <p class="text-sm text-slate-500">Protect your access to sensitive medical information.</p>
                                        </div>
                                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-600">Step 03</span>
                                    </div>
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                        <div class="sm:col-span-2">
                                            <label for="password" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Password</label>
                                            <div class="relative">
                                                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-blue-400">
                                                    <i class="fas fa-lock"></i>
                                                </span>
                                                <input id="password" type="password" name="password"
                                                       class="block w-full rounded-2xl border border-slate-200 bg-white/90 py-3.5 pl-11 pr-4 text-sm text-slate-700 shadow-inner shadow-blue-50 transition focus:border-blue-400 focus:outline-none focus:ring-4 focus:ring-blue-100"
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
                                        <div class="sm:col-span-2">
                                            <label for="password_confirmation" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Confirm Password</label>
                                            <input id="password_confirmation" type="password" name="password_confirmation"
                                                   class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-blue-50 transition focus:border-blue-400 focus:outline-none focus:ring-4 focus:ring-blue-100"
                                                   required autocomplete="new-password">
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                        </div>
                                    </div>
                                </section>

                                <div class="space-y-6">
                                    <div class="flex items-start gap-3 rounded-2xl border border-slate-200/80 bg-white/75 p-4">
                                        <input id="terms" name="terms" type="checkbox"
                                               class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                               required>
                                        <label for="terms" class="text-sm text-slate-600">
                                            I agree to the <a href="#" class="font-semibold text-blue-600 hover:text-blue-500">Terms of Service</a> and <a href="#" class="font-semibold text-blue-600 hover:text-blue-500">Privacy Policy</a> of the Barangay Health Center Monitoring Scheduling System.
                                        </label>
                                    </div>

                                    <div class="flex items-start gap-3 rounded-2xl border border-slate-200/80 bg-white/75 p-4">
                                        <input id="sms_notifications" name="sms_notifications" type="checkbox"
                                               class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                        <label for="sms_notifications" class="text-sm text-slate-600">
                                            Notify me via SMS about patient appointments, barangay missions, and system updates.
                                        </label>
                                    </div>

                                    <input type="hidden" name="role" value="doctor" />

                                    <button type="submit" class="group relative flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-blue-600 via-blue-500 to-blue-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/30 transition hover:scale-[1.01] hover:from-blue-500 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
                                        <span>Register as Doctor</span>
                                        <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                                    </button>

                                    <div class="text-center text-sm text-slate-600">
                                        Already have an account?
                                        <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-500">Sign in here</a>
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
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            strengthBars.forEach((bar, index) => {
                if (index < strength) {
                    bar.className = `h-2 w-1/4 rounded ${getStrengthColor(strength)}`;
                } else {
                    bar.className = 'h-2 w-1/4 rounded bg-slate-200';
                }
            });

            strengthText.textContent = getStrengthText(strength);
            strengthText.className = `text-xs ${getStrengthTextColor(strength)}`;
        });

        function getStrengthColor(strength) {
            if (strength <= 1) return 'bg-red-500';
            if (strength === 2) return 'bg-amber-500';
            if (strength === 3) return 'bg-blue-500';
            return 'bg-emerald-500';
        }

        function getStrengthText(strength) {
            if (strength <= 1) return 'Weak';
            if (strength === 2) return 'Fair';
            if (strength === 3) return 'Good';
            return 'Strong';
        }

        function getStrengthTextColor(strength) {
            if (strength <= 1) return 'text-red-500';
            if (strength === 2) return 'text-amber-500';
            if (strength === 3) return 'text-blue-500';
            return 'text-emerald-600';
        }

        function updateProgress(stepIndex, isHighlighted) {
            const step = progressSteps[stepIndex];
            const label = progressLabels[stepIndex];

            if (!step || !label) return;

            const stepBase = step.dataset.baseClass || '';
            const stepActive = step.dataset.activeClass || '';
            const labelBase = label.dataset.baseClass || '';
            const labelActive = label.dataset.activeClass || '';

            step.className = isHighlighted ? `${stepBase} ${stepActive}` : stepBase;
            label.className = isHighlighted ? `${labelBase} ${labelActive}` : labelBase;
        }

        function renderProgress() {
            const personalFields = ['full_name'];
            const credentialFields = ['professional_license_number', 'specialization', 'contact_number', 'email_address', 'verification_code'];
            const accountFields = ['password', 'password_confirmation'];

            const personalComplete = personalFields.every(field => (document.getElementById(field)?.value || '').trim() !== '');
            const credentialComplete = credentialFields.every(field => (document.getElementById(field)?.value || '').trim() !== '');
            const accountComplete = accountFields.every(field => (document.getElementById(field)?.value || '').trim() !== '');

            const states = [personalComplete, credentialComplete, accountComplete];
            const firstIncomplete = states.findIndex(state => !state);
            const currentIndex = firstIncomplete === -1 ? states.length - 1 : firstIncomplete;

            states.forEach((isComplete, index) => {
                const highlight = isComplete || index === currentIndex;
                updateProgress(index, highlight);
            });
        }

        renderProgress();
        form.addEventListener('input', renderProgress);
    </script>
</x-guest-layout>
