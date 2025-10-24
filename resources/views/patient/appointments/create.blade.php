 <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Book an Appointment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                @if ($errors->any())
                    <div class="mb-4">
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('patient.appointments.store') }}" id="appointment-form" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="appointment_date" class="block font-medium text-sm text-gray-700">Appointment Date (Wednesdays Only)</label>
                        <input type="date" name="appointment_date" id="appointment_date" value="{{ old('appointment_date') }}" required class="border-gray-300 rounded-md shadow-sm mt-1 block w-full" />
                        <p class="text-sm text-gray-500 mt-1">Appointments are only available on Wednesdays.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Select Doctor</label>
                        <select name="doctor_id" id="doctor_id" class="border-gray-300 rounded-md shadow-sm mt-1 block w-full" required>
                            <option value="">-- Select Doctor --</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    Dr. {{ $doctor->user->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-sm text-gray-500 mt-1">Doctors are available only on Wednesdays.</p>
                    </div>

                    <div class="mb-4">
                        <label for="appointment_time" class="block font-medium text-sm text-gray-700">Appointment Time</label>
                        <select name="appointment_time" id="appointment_time" class="border-gray-300 rounded-md shadow-sm mt-1 block w-full" required>
                            <option value="">-- Select Time --</option>
                        </select>
                        <p class="text-sm text-gray-500 mt-1" id="slots-info">Please select a doctor and date to see available slots.</p>
                    </div>

                    <input type="hidden" name="duration_minutes" id="duration_minutes" value="30" />

                    <div class="mb-4">
                        <label for="reason" class="block font-medium text-sm text-gray-700">Reason for Appointment</label>
                        <select name="reason" id="reason" required class="border-gray-300 rounded-md shadow-sm mt-1 block w-full">
                            <option value="">-- Select Reason --</option>
                            <optgroup label="Feeling Unwell or Managing Symptoms">
                                <option value="Sudden illness (e.g., fever, cough, stomach issues)" {{ old('reason') == 'Sudden illness (e.g., fever, cough, stomach issues)' ? 'selected' : '' }}>Sudden illness (e.g., fever, cough, stomach issues)</option>
                                <option value="Pain or discomfort (e.g., headaches, back pain, joint pain)" {{ old('reason') == 'Pain or discomfort (e.g., headaches, back pain, joint pain)' ? 'selected' : '' }}>Pain or discomfort (e.g., headaches, back pain, joint pain)</option>
                                <option value="New or unexplained symptoms (e.g., dizziness, swelling, rashes)" {{ old('reason') == 'New or unexplained symptoms (e.g., dizziness, swelling, rashes)' ? 'selected' : '' }}>New or unexplained symptoms (e.g., dizziness, swelling, rashes)</option>
                                <option value="Worsening chronic symptoms or flare-ups (e.g., asthma, arthritis)" {{ old('reason') == 'Worsening chronic symptoms or flare-ups (e.g., asthma, arthritis)' ? 'selected' : '' }}>Worsening chronic symptoms or flare-ups (e.g., asthma, arthritis)</option>
                                <option value="Confirming or ruling out serious issues after noticing alarming signs (e.g., blood in stool, chest pain)" {{ old('reason') == 'Confirming or ruling out serious issues after noticing alarming signs (e.g., blood in stool, chest pain)' ? 'selected' : '' }}>Confirming or ruling out serious issues after noticing alarming signs (e.g., blood in stool, chest pain)</option>
                            </optgroup>
                            <optgroup label="Follow-Up or Ongoing Condition Management">
                                <option value="Routine check-ins for chronic diseases (e.g., diabetes, hypertension)" {{ old('reason') == 'Routine check-ins for chronic diseases (e.g., diabetes, hypertension)' ? 'selected' : '' }}>Routine check-ins for chronic diseases (e.g., diabetes, hypertension)</option>
                                <option value="Reviewing lab results, imaging, or adjusting treatment plans" {{ old('reason') == 'Reviewing lab results, imaging, or adjusting treatment plans' ? 'selected' : '' }}>Reviewing lab results, imaging, or adjusting treatment plans</option>
                                <option value="Monitoring recovery after surgery, hospitalization, or a previous illness" {{ old('reason') == 'Monitoring recovery after surgery, hospitalization, or a previous illness' ? 'selected' : '' }}>Monitoring recovery after surgery, hospitalization, or a previous illness</option>
                                <option value="Medication reviews, renewals, or addressing side effects" {{ old('reason') == 'Medication reviews, renewals, or addressing side effects' ? 'selected' : '' }}>Medication reviews, renewals, or addressing side effects</option>
                            </optgroup>
                            <optgroup label="Preventive and Wellness Care">
                                <option value="Annual physicals or routine health check-ups" {{ old('reason') == 'Annual physicals or routine health check-ups' ? 'selected' : '' }}>Annual physicals or routine health check-ups</option>
                                <option value="Immunizations or boosters required by age or travel" {{ old('reason') == 'Immunizations or boosters required by age or travel' ? 'selected' : '' }}>Immunizations or boosters required by age or travel</option>
                                <option value="Screening tests (e.g., mammogram, colonoscopy, blood pressure check)" {{ old('reason') == 'Screening tests (e.g., mammogram, colonoscopy, blood pressure check)' ? 'selected' : '' }}>Screening tests (e.g., mammogram, colonoscopy, blood pressure check)</option>
                                <option value="Lifestyle counseling for diet, exercise, or smoking cessation" {{ old('reason') == 'Lifestyle counseling for diet, exercise, or smoking cessation' ? 'selected' : '' }}>Lifestyle counseling for diet, exercise, or smoking cessation</option>
                                <option value="Health risk assessments due to family medical history" {{ old('reason') == 'Health risk assessments due to family medical history' ? 'selected' : '' }}>Health risk assessments due to family medical history</option>
                            </optgroup>
                            <optgroup label="Specific Life or Health Events">
                                <option value="Pregnancy confirmation, prenatal care, or postpartum follow-up" {{ old('reason') == 'Pregnancy confirmation, prenatal care, or postpartum follow-up' ? 'selected' : '' }}>Pregnancy confirmation, prenatal care, or postpartum follow-up</option>
                                <option value="Family planning, fertility, or contraception discussions" {{ old('reason') == 'Family planning, fertility, or contraception discussions' ? 'selected' : '' }}>Family planning, fertility, or contraception discussions</option>
                                <option value="Pediatric milestones or developmental concerns for children" {{ old('reason') == 'Pediatric milestones or developmental concerns for children' ? 'selected' : '' }}>Pediatric milestones or developmental concerns for children</option>
                                <option value="Managing menopause or other age-related transitions" {{ old('reason') == 'Managing menopause or other age-related transitions' ? 'selected' : '' }}>Managing menopause or other age-related transitions</option>
                            </optgroup>
                            <optgroup label="Mental and Emotional Well-Being">
                                <option value="Feelings of anxiety, depression, stress, or burnout" {{ old('reason') == 'Feelings of anxiety, depression, stress, or burnout' ? 'selected' : '' }}>Feelings of anxiety, depression, stress, or burnout</option>
                                <option value="Sleep issues, mood swings, or behavioral changes" {{ old('reason') == 'Sleep issues, mood swings, or behavioral changes' ? 'selected' : '' }}>Sleep issues, mood swings, or behavioral changes</option>
                                <option value="Seeking therapy referrals or medication for mental health" {{ old('reason') == 'Seeking therapy referrals or medication for mental health' ? 'selected' : '' }}>Seeking therapy referrals or medication for mental health</option>
                            </optgroup>
                            <optgroup label="Injury or Emergency Aftercare">
                                <option value="Accidents (e.g., falls, sports injuries, burns)" {{ old('reason') == 'Accidents (e.g., falls, sports injuries, burns)' ? 'selected' : '' }}>Accidents (e.g., falls, sports injuries, burns)</option>
                                <option value="Follow-up for stitches, fractures, or wound care" {{ old('reason') == 'Follow-up for stitches, fractures, or wound care' ? 'selected' : '' }}>Follow-up for stitches, fractures, or wound care</option>
                                <option value="Assessing for concussion or head injuries" {{ old('reason') == 'Assessing for concussion or head injuries' ? 'selected' : '' }}>Assessing for concussion or head injuries</option>
                            </optgroup>
                            <optgroup label="Administrative or Practical Needs">
                                <option value="Required medical forms for work, school, travel, or insurance" {{ old('reason') == 'Required medical forms for work, school, travel, or insurance' ? 'selected' : '' }}>Required medical forms for work, school, travel, or insurance</option>
                                <option value="Occupational health checks (e.g., fitness for duty, return-to-work clearance)" {{ old('reason') == 'Occupational health checks (e.g., fitness for duty, return-to-work clearance)' ? 'selected' : '' }}>Occupational health checks (e.g., fitness for duty, return-to-work clearance)</option>
                                <option value="Medical certifications, disability assessments, or legal documentation" {{ old('reason') == 'Medical certifications, disability assessments, or legal documentation' ? 'selected' : '' }}>Medical certifications, disability assessments, or legal documentation</option>
                            </optgroup>
                            <optgroup label="Seeking Information or Reassurance">
                                <option value="Questions about an existing diagnosis or treatment plan" {{ old('reason') == 'Questions about an existing diagnosis or treatment plan' ? 'selected' : '' }}>Questions about an existing diagnosis or treatment plan</option>
                                <option value="Second opinion or clarification after seeing another provider" {{ old('reason') == 'Second opinion or clarification after seeing another provider' ? 'selected' : '' }}>Second opinion or clarification after seeing another provider</option>
                                <option value="Understanding genetic risks or planning for future health concerns" {{ old('reason') == 'Understanding genetic risks or planning for future health concerns' ? 'selected' : '' }}>Understanding genetic risks or planning for future health concerns</option>
                            </optgroup>
                            <optgroup label="Accessing Specialized Care">
                                <option value="Need for referral to specialists (e.g., cardiologist, neurologist)" {{ old('reason') == 'Need for referral to specialists (e.g., cardiologist, neurologist)' ? 'selected' : '' }}>Need for referral to specialists (e.g., cardiologist, neurologist)</option>
                                <option value="Preparing for or coordinating with multidisciplinary teams" {{ old('reason') == 'Preparing for or coordinating with multidisciplinary teams' ? 'selected' : '' }}>Preparing for or coordinating with multidisciplinary teams</option>
                                <option value="Managing complex conditions requiring multiple providers" {{ old('reason') == 'Managing complex conditions requiring multiple providers' ? 'selected' : '' }}>Managing complex conditions requiring multiple providers</option>
                            </optgroup>
                            <option value="Other reason not listed" {{ old('reason') == 'Other reason not listed' ? 'selected' : '' }}>Other reason not listed</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="urgency_level" class="block font-medium text-sm text-gray-700">Urgency Level</label>
                        <select name="urgency_level" id="urgency_level" class="border-gray-300 rounded-md shadow-sm mt-1 block w-full">
                            <option value="normal" {{ old('urgency_level', 'normal') == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="urgent" {{ old('urgency_level') == 'urgent' ? 'selected' : '' }}>🚨 Urgent</option>
                            <option value="maternal" {{ old('urgency_level') == 'maternal' ? 'selected' : '' }}>🤰 Maternal Care</option>
                        </select>
                        <p class="text-sm text-gray-500 mt-1">Select urgency level for this appointment.</p>
                    </div>

                    <div class="mb-4">
                        <label for="uploaded_files" class="block font-medium text-sm text-gray-700">Upload Medical Documents (Optional)</label>
                        <input type="file" name="uploaded_files[]" id="uploaded_files" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="border-gray-300 rounded-md shadow-sm mt-1 block w-full" />
                        <p class="text-sm text-gray-500 mt-1">Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max 5MB each)</p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Book Appointment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('appointment_date');
            const doctorSelect = document.getElementById('doctor_id');
            const timeSelect = document.getElementById('appointment_time');
            const slotsInfo = document.getElementById('slots-info');

            // Set min date to today and restrict to Wednesdays
            const today = new Date();
            dateInput.min = today.toISOString().split('T')[0];

            // Function to check if date is Wednesday
            function isWednesday(dateString) {
                const date = new Date(dateString + 'T00:00:00');
                return date.getDay() === 3; // 0 = Sunday, 3 = Wednesday
            }

            // Restrict date picker to Wednesdays
            dateInput.addEventListener('input', function() {
                if (this.value && !isWednesday(this.value)) {
                    alert('Appointments are only available on Wednesdays. Please select a Wednesday.');
                    this.value = '';
                    timeSelect.innerHTML = '<option value="">-- Select Time --</option>';
                    slotsInfo.textContent = 'Please select a doctor and date to see available slots.';
                } else if (this.value && doctorSelect.value) {
                    loadAvailableSlots();
                }
            });

            // Load available slots when doctor or date changes
            function loadAvailableSlots() {
                if (!dateInput.value || !doctorSelect.value) {
                    return;
                }

                slotsInfo.textContent = 'Loading available slots...';
                timeSelect.innerHTML = '<option value="">-- Select Time --</option>';
                timeSelect.disabled = true;

                // Fetch available slots from server
                fetch(`{{ route('patient.appointments.get-available-slots') }}?doctor_id=${doctorSelect.value}&date=${dateInput.value}`)
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.error || 'Failed to load slots');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        timeSelect.disabled = false;

                        if (data.error) {
                            slotsInfo.textContent = data.error;
                            slotsInfo.classList.add('text-red-600');
                            return;
                        }

                        if (data.available_slots && data.available_slots.length > 0) {
                            data.available_slots.forEach(slot => {
                                const option = document.createElement('option');
                                option.value = slot.start_time;
                                option.textContent = `${slot.start_time} - ${slot.end_time}`;
                                timeSelect.appendChild(option);
                            });
                            slotsInfo.textContent = `Found ${data.available_slots.length} available slot(s).`;
                            slotsInfo.classList.remove('text-red-600');
                            slotsInfo.classList.add('text-green-600');
                        } else {
                            slotsInfo.textContent = 'No available slots for this date. Please try another Wednesday.';
                            slotsInfo.classList.remove('text-green-600');
                            slotsInfo.classList.add('text-red-600');
                        }
                    })
                    .catch(error => {
                        console.error('Error loading slots:', error);
                        timeSelect.disabled = false;
                        slotsInfo.textContent = error.message || 'Error loading available slots. Please try again.';
                        slotsInfo.classList.add('text-red-600');
                    });
            }

            doctorSelect.addEventListener('change', function() {
                // Clear time selection when doctor changes
                timeSelect.innerHTML = '<option value="">-- Select Time --</option>';
                slotsInfo.textContent = 'Please select a date to see available slots.';
                slotsInfo.classList.remove('text-red-600', 'text-green-600');

                if (dateInput.value) {
                    loadAvailableSlots();
                }
            });

            // Trigger initial load if date and doctor are pre-selected (e.g., after validation error)
            if (dateInput.value && doctorSelect.value) {
                loadAvailableSlots();
            }
        });
    </script>
</x-app-layout>
