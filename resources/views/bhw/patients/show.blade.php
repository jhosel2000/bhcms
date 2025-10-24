<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Patient Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                <h3 class="text-lg font-semibold mb-4">{{ $patient->full_name }}</h3>
                <p><strong>Age:</strong> {{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->age : 'N/A' }}</p>
                <p><strong>Contact Number:</strong> {{ $patient->contact_number ?? 'N/A' }}</p>
                <p><strong>Address:</strong> {{ $patient->full_address ?? 'N/A' }}</p>
                <p><strong>Gender:</strong> {{ ucfirst($patient->gender) ?? 'N/A' }}</p>
                <p><strong>Civil Status:</strong> {{ ucfirst($patient->civil_status) ?? 'N/A' }}</p>
                <p><strong>Occupation:</strong> {{ $patient->occupation ?? 'N/A' }}</p>
                <p><strong>Religion:</strong> {{ $patient->religion ?? 'N/A' }}</p>

                <h4 class="mt-6 font-semibold">Appointments</h4>
                @if($patient->appointments->count() > 0)
                    <ul class="list-disc list-inside">
                        @foreach($patient->appointments as $appointment)
                            <li>
                                {{ $appointment->appointment_date }} at {{ $appointment->appointment_time }} - {{ ucfirst($appointment->status ?? 'Scheduled') }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>No appointments found.</p>
                @endif

                <div class="mt-6 space-x-2">
                    <a href="{{ route('bhw.patients.edit', $patient->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">Edit Patient</a>
                    <a href="{{ route('bhw.patients.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">Back to Patients</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
