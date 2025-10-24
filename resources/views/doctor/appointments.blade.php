{{--
    Doctor Appointments List View
    List view for appointment management with modal workflows
--}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Appointments') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Appointment Management</h3>
                    </div>

                    <!-- Filters -->
                    <div class="mb-6 flex flex-wrap gap-4">
                        <form method="GET" class="flex flex-wrap gap-4" id="filterForm">
                            <select name="status" class="border border-gray-300 rounded px-3 py-2">
                                <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="declined" {{ $status == 'declined' ? 'selected' : '' }}>Declined</option>
                            </select>
                            <input type="text" name="search" placeholder="Search patients..." value="{{ request('search') }}" class="border border-gray-300 rounded px-3 py-2">
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="border border-gray-300 rounded px-3 py-2">
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="border border-gray-300 rounded px-3 py-2">
                            <select name="urgency" class="border border-gray-300 rounded px-3 py-2">
                                <option value="">All Urgency</option>
                                <option value="urgent" {{ request('urgency') == 'urgent' ? 'selected' : '' }}>Urgent/Maternal</option>
                                <option value="normal" {{ request('urgency') == 'normal' ? 'selected' : '' }}>Normal</option>
                            </select>
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
                                            @if($appointment->status == 'declined' && $appointment->declined_reason)
                                                <span class="text-xs text-gray-500 block mt-1">Reason: {{ $appointment->declined_reason }}</span>
                                            @endif
                                            @if($appointment->urgency_level === 'urgent')
                                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Urgent</span>
                                            @elseif($appointment->urgency_level === 'maternal')
                                                <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">Maternal Care</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-sm text-gray-600 mb-2">
                                        <p><strong>Date & Time:</strong> {{ $appointment->appointment_date->format('M d, Y') }} at {{ $appointment->appointment_time->format('H:i') }}</p>
                                        <p><strong>Reason:</strong> {{ $appointment->reason }}</p>
                                        @if($appointment->uploaded_files)
                                            <p><strong>Files:</strong> {{ count($appointment->uploaded_files) }}</p>
                                        @endif
                                    </div>
                                    <div class="flex space-x-2">
                                        <button onclick="openViewModal({{ $appointment->id }})" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View</button>
                                        @if($appointment->status === 'pending')
                                            <button onclick="approveAppointment({{ $appointment->id }})" class="text-green-600 hover:text-green-900 text-sm font-medium">Approve</button>
                                            <button onclick="declineAppointment({{ $appointment->id }})" class="text-red-600 hover:text-red-900 text-sm font-medium">Decline</button>
                                        @elseif($appointment->status === 'approved')
                                            <button onclick="completeAppointment({{ $appointment->id }})" class="text-blue-600 hover:text-blue-900 text-sm font-medium">Mark Complete</button>
                                        @elseif($appointment->status === 'declined')
                                            <span class="text-xs text-gray-500">Declined</span>
                                        @elseif($appointment->status === 'completed')
                                            <span class="text-xs text-green-600">Completed</span>
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
                                                @if($appointment->status == 'declined' && $appointment->declined_reason)
                                                    <div class="text-xs text-gray-500 mt-1">Reason: {{ $appointment->declined_reason }}</div>
                                                @endif
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
                                                @if($appointment->status === 'pending')
                                                    <button onclick="approveAppointment({{ $appointment->id }})" class="ml-2 text-green-600 hover:text-green-900">Approve</button>
                                                    <button onclick="declineAppointment({{ $appointment->id }})" class="ml-2 text-red-600 hover:text-red-900">Decline</button>
                                                @elseif($appointment->status === 'approved')
                                                    <button onclick="completeAppointment({{ $appointment->id }})" class="ml-2 text-blue-600 hover:text-blue-900">Mark Complete</button>
                                                @elseif($appointment->status === 'declined')
                                                    <span class="text-xs text-gray-500">Declined</span>
                                                @elseif($appointment->status === 'completed')
                                                    <span class="text-xs text-green-600">Completed</span>
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
        function openEditModal(appointmentId) {
            fetch(`/doctor/appointments/${appointmentId}/edit`)
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
            fetch(`/doctor/appointments/${appointmentId}`)
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

        function approveAppointment(appointmentId) {
            if (confirm('Are you sure you want to approve this appointment?')) {
                fetch(`/doctor/appointments/${appointmentId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Appointment approved successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(() => alert('An error occurred. Please try again.'));
            }
        }

        function declineAppointment(appointmentId) {
            const reason = prompt('Please provide a reason for declining this appointment:');
            if (reason !== null) {
                fetch(`/doctor/appointments/${appointmentId}/decline`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ declined_reason: reason })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Appointment declined successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(() => alert('An error occurred. Please try again.'));
            }
        }

        function completeAppointment(appointmentId) {
            if (confirm('Are you sure you want to mark this appointment as completed?')) {
                fetch(`/doctor/appointments/${appointmentId}/complete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Appointment marked as completed!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(() => alert('An error occurred. Please try again.'));
            }
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const editModal = document.getElementById('editModal');
            const viewModal = document.getElementById('viewModal');

            if (event.target == editModal) {
                editModal.classList.add('hidden');
            }
            if (event.target == viewModal) {
                viewModal.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
