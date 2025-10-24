<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Appointment Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Appointment Information</h3>
                        <div>
                            <a href="{{ route('midwife.appointments.edit', $appointment) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Edit
                            </a>
                            <a href="{{ route('midwife.appointments.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold mb-2">Patient Information</h4>
                            <p><strong>Name:</strong> {{ $appointment->patient->full_name ?? 'N/A' }}</p>
                            <p><strong>Contact:</strong> {{ $appointment->patient->contact_number ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <h4 class="font-semibold mb-2">Appointment Details</h4>
                            <p><strong>Date:</strong> {{ $appointment->appointment_date->format('M d, Y') }}</p>
                            <p><strong>Time:</strong> {{ $appointment->appointment_time->format('H:i') }}</p>
                            <p><strong>Status:</strong>
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($appointment->status == 'scheduled') bg-yellow-100 text-yellow-800
                                    @elseif($appointment->status == 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($appointment->status == 'completed') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="font-semibold mb-2">Reason for Visit</h4>
                        <p>{{ $appointment->reason }}</p>
                    </div>

                    @if($appointment->notes)
                        <div class="mt-6">
                            <h4 class="font-semibold mb-2">Notes</h4>
                            <p>{{ $appointment->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
