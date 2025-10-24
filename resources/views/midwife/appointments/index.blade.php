<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Midwife - Appointments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Appointments</h3>
                        <button onclick="openCreateModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Book New Appointment
                        </button>
                    </div>

                    <!-- Filters -->
                    <div class="mb-6 flex flex-wrap gap-4">
                        <form method="GET" class="flex flex-wrap gap-4">
                            <select name="status" class="border border-gray-300 rounded px-3 py-2">
                                <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="declined" {{ $status == 'declined' ? 'selected' : '' }}>Declined</option>
                                <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            <label class="flex items-center">
                                <input type="checkbox" name="urgent" value="1" {{ $urgent == '1' ? 'checked' : '' }} class="mr-2">
                                Urgent Only
                            </label>
                            <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">Filter</button>
                        </form>
                    </div>

                    @if($appointments->count() > 0)
                        <!-- Mobile Card Layout -->
                        <div class="block md:hidden space-y-4">
                            @foreach($appointments as $appointment)
                                <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-semibold text-gray-900">{{ $appointment->patient->full_name ?? 'N/A' }}</h4>
                                        <div class="flex flex-col items-end space-y-1">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                @if($appointment->status == 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($appointment->status == 'approved') bg-green-100 text-green-800
                                                @elseif($appointment->status == 'declined') bg-red-100 text-red-800
                                                @elseif($appointment->status == 'completed') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                            @if($appointment->urgency_level === 'urgent')
                                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Urgent</span>
                                            @elseif($appointment->urgency_level === 'maternal')
                                                <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">Maternal Care</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-sm text-gray-600 mb-2">
                                        <p><strong>Provider:</strong>
                                            @if($appointment->doctor) Doctor {{ $appointment->doctor->user->name }}
                                            @elseif($appointment->midwife) Midwife {{ $appointment->midwife->user->name }}
                                            @elseif($appointment->bhw) BHW {{ $appointment->bhw->user->name }}
                                            @endif
                                        </p>
                                        <p><strong>Date & Time:</strong> {{ $appointment->appointment_date->format('M d, Y') }} at {{ $appointment->appointment_time->format('H:i') }}</p>
                                        <p><strong>Reason:</strong> {{ $appointment->reason }}</p>
                                        @if($appointment->uploaded_files)
                                            <p><strong>Files:</strong> {{ count($appointment->uploaded_files) }}</p>
                                        @endif
                                    </div>
                                    <div class="flex space-x-2">
                                        <button onclick="openViewModal({{ $appointment->id }})" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View Details</button>
                                        @if($appointment->status == 'pending')
                                            <button onclick="openEditModal({{ $appointment->id }})" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</button>
                                            <form method="POST" action="{{ route('midwife.appointments.destroy', $appointment) }}" class="inline" onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">Cancel</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Desktop Table Layout -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urgency</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($appointments as $appointment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->patient->full_name ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($appointment->doctor) Doctor {{ $appointment->doctor->user->name }}
                                                @elseif($appointment->midwife) Midwife {{ $appointment->midwife->user->name }}
                                                @elseif($appointment->bhw) BHW {{ $appointment->bhw->user->name }}
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->appointment_date->format('M d, Y') }} {{ $appointment->appointment_time->format('H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($appointment->status == 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($appointment->status == 'approved') bg-green-100 text-green-800
                                                    @elseif($appointment->status == 'declined') bg-red-100 text-red-800
                                                    @elseif($appointment->status == 'completed') bg-blue-100 text-blue-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($appointment->urgency_level === 'urgent')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Urgent</span>
                                                @elseif($appointment->urgency_level === 'maternal')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Maternal Care</span>
                                                @else
                                                    <span class="text-gray-500">Normal</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button onclick="openViewModal({{ $appointment->id }})" class="text-indigo-600 hover:text-indigo-900">View Details</button>
                                                @if($appointment->status == 'pending')
                                                    <button onclick="openEditModal({{ $appointment->id }})" class="ml-2 text-indigo-600 hover:text-indigo-900">Edit</button>
                                                    <form method="POST" action="{{ route('midwife.appointments.destroy', $appointment) }}" class="inline ml-2" onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Cancel</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $appointments->appends(request()->query())->links() }}
                        </div>
                    @else
                        <p class="text-gray-500">No appointments found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Create Appointment Modal -->
    <div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-4 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Book New Appointment</h3>
                    <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('midwife.appointments.store') }}" id="midwife-appointment-form" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="patient_id" :value="__('Patient')" />
                            <select name="patient_id" id="patient_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select a patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>{{ $patient->full_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('patient_id')" class="mt-2" />
                        </div>

                        <div>
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

                        <div>
                            <x-input-label for="appointment_date" :value="__('Appointment Date')" />
                            <x-text-input id="appointment_date" name="appointment_date" type="date" class="mt-1 block w-full" :value="old('appointment_date')" required />
                            <p class="mt-1 text-sm text-gray-600">Appointments can only be booked on Wednesdays.</p>
                            <x-input-error :messages="$errors->get('appointment_date')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="appointment_time" :value="__('Appointment Time')" />
                            <x-text-input id="appointment_time" name="appointment_time" type="time" class="mt-1 block w-full" :value="old('appointment_time')" required />
                            <x-input-error :messages="$errors->get('appointment_time')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="reason" :value="__('Reason for Visit')" />
                            <x-text-input id="reason" name="reason" type="text" class="mt-1 block w-full" :value="old('reason')" placeholder="e.g., Prenatal checkup, Vaccination" required />
                            <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="urgency_level" :value="__('Urgency Level')" />
                            <select name="urgency_level" id="urgency_level" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="normal" {{ old('urgency_level', 'normal') == 'normal' ? 'selected' : '' }}>Normal</option>
                                <option value="urgent" {{ old('urgency_level') == 'urgent' ? 'selected' : '' }}>🚨 Urgent</option>
                                <option value="maternal" {{ old('urgency_level') == 'maternal' ? 'selected' : '' }}>🤰 Maternal Care</option>
                            </select>
                            <p class="mt-1 text-sm text-gray-600">Select urgency level for this appointment.</p>
                            <x-input-error :messages="$errors->get('urgency_level')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="uploaded_files" :value="__('Supporting Files (Optional)')" />
                            <input type="file" name="uploaded_files[]" id="uploaded_files" class="mt-1 block w-full" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
                            <p class="mt-1 text-sm text-gray-600">Upload lab results, referral letters, etc. Allowed: PDF, DOC, DOCX, JPG, JPEG, PNG. Max 5MB each.</p>
                            <x-input-error :messages="$errors->get('uploaded_files')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3" placeholder="Additional notes or reasons for the appointment">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6 space-x-3">
                        <x-secondary-button onclick="closeCreateModal()">
                            {{ __('Cancel') }}
                        </x-secondary-button>

                        <x-primary-button>
                            {{ __('Book Appointment') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Appointment Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-4 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Edit Appointment</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="editModalContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- View Appointment Modal -->
    <div id="viewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-4 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Appointment Details</h3>
                    <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="viewModalContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        function openEditModal(appointmentId) {
            fetch(`/midwife/appointments/${appointmentId}/edit`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('editModalContent').innerHTML = html;
                    document.getElementById('editModal').classList.remove('hidden');
                })
                .catch(error => console.error('Error loading edit form:', error));
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function openViewModal(appointmentId) {
            fetch(`/midwife/appointments/${appointmentId}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('viewModalContent').innerHTML = html;
                    document.getElementById('viewModal').classList.remove('hidden');
                })
                .catch(error => console.error('Error loading appointment details:', error));
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const createModal = document.getElementById('createModal');
            const editModal = document.getElementById('editModal');
            const viewModal = document.getElementById('viewModal');

            if (event.target == createModal) {
                createModal.classList.add('hidden');
            }
            if (event.target == editModal) {
                editModal.classList.add('hidden');
            }
            if (event.target == viewModal) {
                viewModal.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
