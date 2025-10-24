<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Appointment') }}
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

                <form method="POST" action="{{ route('patient.appointments.update', $appointment) }}" id="appointment-form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="appointment_date" class="block font-medium text-sm text-gray-700">Appointment Date (Wednesdays Only)</label>
                        <input type="date" name="appointment_date" id="appointment_date" value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}" required class="border-gray-300 rounded-md shadow-sm mt-1 block w-full" />
                        <p class="text-sm text-gray-500 mt-1">Appointments are only available on Wednesdays.</p>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Select Doctor</label>
                        <select name="doctor_id" id="doctor_id" class="border-gray-300 rounded-md shadow-sm mt-1 block w-full" required>
                            <option value="">-- Select Doctor --</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ (old('doctor_id', $appointment->doctor_id) == $doctor->id) ? 'selected' : '' }}>
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

                    <div class="mb-4">
                        <label for="reason" class="block font-medium text-sm text-gray-700">Reason for Appointment</label>
                        <textarea name="reason" id="reason" rows="3" required class="border-gray-300 rounded-md shadow-sm mt-1 block w-full">{{ old('reason', $appointment->reason) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="uploaded_files" class="block font-medium text-sm text-gray-700">Upload Additional Medical Documents (Optional)</label>
                        <input type="file" name="uploaded_files[]" id="uploaded_files" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="border-gray-300 rounded-md shadow-sm mt-1 block w-full" />
                        <p class="text-sm text-gray-500 mt-1">Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max 5MB each)</p>
                        @if($appointment->uploaded_files && count($appointment->uploaded_files) > 0)
                            <div class="mt-2">
                                <p class="text-sm font-medium text-gray-700">Current Files:</p>
                                <ul class="list-disc list-inside text-sm text-gray-600">
                                    @foreach($appointment->uploaded_files as $file)
                                        <li>
                                            @if(isset($file['path']))
                                                <a href="{{ Storage::url($file['path']) }}" target="_blank" class="text-blue-600 hover:text-blue-800">{{ $file['original_name'] ?? 'Unknown file' }}</a>
                                            @else
                                                <span class="text-gray-400">{{ $file['original_name'] ?? 'Unknown file' }}</span>
                                            @endif
                                            ({{ number_format(($file['size'] ?? 0) / 1024, 1) }} KB)
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('patient.appointments.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back
                        </a>
                        <div class="flex space-x-2">
                            <form method="POST" action="{{ route('patient.appointments.destroy', $appointment) }}" onsubmit="return confirm('Are you sure you want to cancel this appointment?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Cancel Appointment
                                </button>
                            </form>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Appointment
                            </button>
                        </div>
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
            const currentAppointmentTime = '{{ $appointment->appointment_time->format('H:i') }}';

            // Set min date to today
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
                                // Pre-select current time if it matches
                                if (slot.start_time === currentAppointmentTime) {
                                    option.selected = true;
                                }
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

            // Load slots on page load if date and doctor are set
            if (dateInput.value && doctorSelect.value) {
                loadAvailableSlots();
            }
        });
    </script>
</x-app-layout>
