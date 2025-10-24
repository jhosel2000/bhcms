<x-guest-layout>
    <div class="relative min-h-screen bg-slate-950">
        <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
            <div class="absolute left-1/2 top-[-12%] h-[28rem] w-[28rem] -translate-x-1/2 rounded-full bg-emerald-500/25 blur-3xl"></div>
            <div class="absolute right-[12%] bottom-[-14%] h-[22rem] w-[22rem] rounded-full bg-emerald-400/20 blur-3xl"></div>
            <div class="absolute left-[6%] bottom-[18%] h-64 w-64 rounded-full bg-emerald-600/10 blur-2xl"></div>
        </div>

        <div class="relative mx-auto flex min-h-screen max-w-6xl items-center px-4 py-14 sm:px-8">
            <div class="w-full overflow-hidden rounded-3xl border border-white/10 bg-white/80 shadow-2xl backdrop-blur-2xl">
                <div class="grid lg:grid-cols-12">
                    <aside class="relative overflow-hidden bg-gradient-to-br from-emerald-700 via-emerald-600 to-emerald-800 px-8 py-12 text-white sm:px-10 lg:col-span-5 lg:py-16">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.18),_rgba(0,0,0,0))] opacity-70"></div>
                        <div class="relative z-10 flex h-full flex-col justify-between gap-10">
                            <div>
                                <div class="flex items-center gap-2 text-emerald-100/70 text-xs uppercase tracking-[0.35em]">
                                    <span class="inline-flex h-1.5 w-1.5 rounded-full bg-white/80"></span>
                                    Barangay Services
                                </div>
                                <h1 class="mt-5 text-3xl font-bold sm:text-4xl">Barangay Health Worker Registration</h1>
                                <p class="mt-5 max-w-md text-sm text-emerald-50/90 sm:text-base">
                                    Create your professional profile, manage barangay assignments, and collaborate seamlessly with the health center team. A refreshed, human-centric interface keeps everything organized and secure.
                                </p>
                            </div>

                            <div class="space-y-6">
                                <div class="rounded-3xl border border-white/20 bg-white/10 p-6 shadow-xl shadow-emerald-900/25">
                                    <p class="text-sm font-semibold uppercase tracking-wider text-emerald-50/75">Why you'll love it</p>
                                    <ul class="mt-4 space-y-4 text-sm text-emerald-50/80">
                                        <li class="flex items-start gap-3">
                                            <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/20 text-xs font-semibold">✔</span>
                                            <span><strong class="font-semibold">Real-time visibility</strong> on barangay schedules, duties, and upcoming engagements.</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/20 text-xs font-semibold">✔</span>
                                            <span><strong class="font-semibold">Centralized records</strong> for assignments, verification, and performance tracking.</span>
                                        </li>
                                        <li class="flex items-start gap-3">
                                            <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-white/20 text-xs font-semibold">✔</span>
                                            <span><strong class="font-semibold">Secure authentication</strong> with password strength insights and privacy safeguards.</span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="flex items-center gap-4">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl border border-white/20 bg-white/10 shadow-lg shadow-emerald-900/30">
                                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto">
                                    </div>
                                    <p class="text-sm text-emerald-50/85">Barangay Health Center Monitoring Scheduling System</p>
                                </div>
                            </div>
                        </div>
                    </aside>

                    <div class="bg-white/70 px-8 py-10 sm:px-10 lg:col-span-7 lg:px-12 lg:py-14">
                        <div class="mx-auto w-full max-w-xl space-y-8">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-600">Registration Journey</p>
                                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Guided steps</span>
                                </div>
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between" data-progress>
                                    <div class="flex items-center gap-3" data-progress-group>
                                        <div class="progress-step transition-all duration-300"
                                             data-base-class="progress-step flex h-10 w-10 items-center justify-center rounded-full border border-emerald-100 bg-white text-emerald-500/70 shadow-sm"
                                             data-active-class="bg-emerald-500 text-white border-transparent shadow-xl shadow-emerald-200 ring-4 ring-emerald-100">1</div>
                                        <span class="progress-label text-xs font-medium uppercase tracking-wide text-slate-500"
                                              data-base-class="progress-label text-xs font-medium uppercase tracking-wide text-slate-500"
                                              data-active-class="text-emerald-600">Personal Info</span>
                                    </div>
                                    <div class="flex items-center gap-3" data-progress-group>
                                        <div class="progress-step transition-all duration-300"
                                             data-base-class="progress-step flex h-10 w-10 items-center justify-center rounded-full border border-emerald-100 bg-white text-emerald-500/70 shadow-sm"
                                             data-active-class="bg-emerald-500 text-white border-transparent shadow-xl shadow-emerald-200 ring-4 ring-emerald-100">2</div>
                                        <span class="progress-label text-xs font-medium uppercase tracking-wide text-slate-500"
                                              data-base-class="progress-label text-xs font-medium uppercase tracking-wide text-slate-500"
                                              data-active-class="text-emerald-600">Contact Details</span>
                                    </div>
                                    <div class="flex items-center gap-3" data-progress-group>
                                        <div class="progress-step transition-all duration-300"
                                             data-base-class="progress-step flex h-10 w-10 items-center justify-center rounded-full border border-emerald-100 bg-white text-emerald-500/70 shadow-sm"
                                             data-active-class="bg-emerald-500 text-white border-transparent shadow-xl shadow-emerald-200 ring-4 ring-emerald-100">3</div>
                                        <span class="progress-label text-xs font-medium uppercase tracking-wide text-slate-500"
                                              data-base-class="progress-label text-xs font-medium uppercase tracking-wide text-slate-500"
                                              data-active-class="text-emerald-600">Account Setup</span>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('register.bhw') }}" class="space-y-8">
                                @csrf

                                <section class="space-y-6 rounded-3xl border border-slate-200/60 bg-white/80 p-6 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h2 class="text-lg font-semibold text-slate-800">Personal Information</h2>
                                            <p class="text-sm text-slate-500">Tell us who you are so we can greet you properly.</p>
                                        </div>
                                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-600">Step 01</span>
                                    </div>
                                    <div class="grid grid-cols-1 gap-6">
                                        <div>
                                            <label for="full_name" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Full Name</label>
                                            <div class="relative">
                                                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-emerald-400">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                                <input id="full_name" type="text" name="full_name" value="{{ old('full_name') }}"
                                                       class="block w-full rounded-2xl border border-slate-200 bg-white/90 py-3.5 pl-11 pr-4 text-sm text-slate-700 shadow-inner shadow-emerald-50 transition focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-100"
                                                       required autofocus>
                                                <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div>
                                            <label for="date_of_birth" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Date of Birth</label>
                                            <input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                                                   class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-emerald-50 transition focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-100"
                                                   required>
                                            <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                                        </div>
                                    </div>
                                </section>

                                <section class="space-y-6 rounded-3xl border border-slate-200/60 bg-white/80 p-6 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h2 class="text-lg font-semibold text-slate-800">Contact & Assignment Details</h2>
                                            <p class="text-sm text-slate-500">Help the health center reach you and confirm your credentials.</p>
                                        </div>
                                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-600">Step 02</span>
                                    </div>
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                        <div>
                                            <label for="contact_number" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Contact Number</label>
                                            <input id="contact_number" type="text" name="contact_number" value="{{ old('contact_number') }}"
                                                   class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-emerald-50 transition focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-100"
                                                   required>
                                            <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
                                        </div>
                                        <div>
                                            <label for="email_address" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Email Address</label>
                                            <input id="email_address" type="email" name="email_address" value="{{ old('email_address') }}"
                                                   class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-emerald-50 transition focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-100"
                                                   required>
                                            <x-input-error :messages="$errors->get('email_address')" class="mt-2" />
                                        </div>
                                        <div>
                                            <label for="purok_zone_of_assignment" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Purok/Zone of Assignment</label>
                                            <input id="purok_zone_of_assignment" type="text" name="purok_zone_of_assignment" value="{{ old('purok_zone_of_assignment') }}"
                                                   class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-emerald-50 transition focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-100"
                                                   required>
                                            <x-input-error :messages="$errors->get('purok_zone_of_assignment')" class="mt-2" />
                                        </div>
                                        <div>
                                            <label for="barangay_id_number" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Barangay ID Number</label>
                                            <input id="barangay_id_number" type="text" name="barangay_id_number" value="{{ old('barangay_id_number') }}"
                                                   class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-emerald-50 transition focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-100"
                                                   required>
                                            <x-input-error :messages="$errors->get('barangay_id_number')" class="mt-2" />
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label for="verification_code" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Verification Code</label>
                                            <input id="verification_code" type="text" name="verification_code" value="{{ old('verification_code') }}"
                                                   class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-emerald-50 transition focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-100"
                                                   required>
                                            <x-input-error :messages="$errors->get('verification_code')" class="mt-2" />
                                        </div>
                                    </div>
                                </section>

                                <section class="space-y-6 rounded-3xl border border-slate-200/60 bg-white/80 p-6 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h2 class="text-lg font-semibold text-slate-800">Account Security</h2>
                                            <p class="text-sm text-slate-500">Create a strong password to safeguard your account.</p>
                                        </div>
                                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-600">Step 03</span>
                                    </div>
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                        <div class="sm:col-span-2">
                                            <label for="password" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Password</label>
                                            <div class="relative">
                                                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-emerald-400">
                                                    <i class="fas fa-lock"></i>
                                                </span>
                                                <input id="password" type="password" name="password"
                                                       class="block w-full rounded-2xl border border-slate-200 bg-white/90 py-3.5 pl-11 pr-4 text-sm text-slate-700 shadow-inner shadow-emerald-50 transition focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-100"
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
                                                   class="block w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3.5 text-sm text-slate-700 shadow-inner shadow-emerald-50 transition focus:border-emerald-400 focus:outline-none focus:ring-4 focus:ring-emerald-100"
                                                   required autocomplete="new-password">
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                        </div>
                                    </div>
                                </section>

                                <div class="space-y-6">
                                    <div class="flex items-start gap-3 rounded-2xl border border-slate-200/80 bg-white/75 p-4">
                                        <input id="terms" name="terms" type="checkbox"
                                               class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                               required>
                                        <label for="terms" class="text-sm text-slate-600">
                                            I agree to the <a href="#" class="font-semibold text-emerald-600 hover:text-emerald-500">Terms of Service</a> and <a href="#" class="font-semibold text-emerald-600 hover:text-emerald-500">Privacy Policy</a> of the Barangay Health Center Monitoring Scheduling System.
                                        </label>
                                    </div>

                                    <div class="flex items-start gap-3 rounded-2xl border border-slate-200/80 bg-white/75 p-4">
                                        <input id="sms_notifications" name="sms_notifications" type="checkbox"
                                               class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                        <label for="sms_notifications" class="text-sm text-slate-600">
                                            I would like to receive SMS notifications for appointments and real-time barangay updates.
                                        </label>
                                    </div>

                                    <input type="hidden" name="role" value="bhw" />

                                    <button type="submit" class="group relative flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-600 via-emerald-500 to-emerald-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-emerald-600/30 transition hover:scale-[1.01] hover:from-emerald-500 hover:to-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-200">
                                        <span>Register as Barangay Health Worker</span>
                                        <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                                    </button>

                                    <div class="text-center text-sm text-slate-600">
                                        Already have an account?
                                        <a href="{{ route('login') }}" class="font-semibold text-emerald-600 hover:text-emerald-500">Sign in here</a>
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
            const personalFields = ['full_name', 'date_of_birth'];
            const contactFields = ['contact_number', 'email_address', 'purok_zone_of_assignment', 'barangay_id_number', 'verification_code'];
            const accountFields = ['password', 'password_confirmation'];

            const personalComplete = personalFields.every(field => (document.getElementById(field)?.value || '').trim() !== '');
            const contactComplete = contactFields.every(field => (document.getElementById(field)?.value || '').trim() !== '');
            const accountComplete = accountFields.every(field => (document.getElementById(field)?.value || '').trim() !== '');

            const states = [personalComplete, contactComplete, accountComplete];
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
