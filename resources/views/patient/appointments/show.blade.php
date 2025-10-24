<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Appointment Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                <h3 class="text-lg font-semibold mb-4">
                    Appointment with
                    @if($appointment->doctor)
                        Dr. {{ $appointment->doctor->user->name }}
                    @elseif($appointment->midwife)
                        Midwife {{ $appointment->midwife->user->name }}
                    @endif
                </h3>
                <p><strong>Date:</strong> {{ $appointment->appointment_date->format('M j, Y') }}</p>
                <p><strong>Time:</strong> {{ $appointment->appointment_time->format('g:i A') }}</p>
                <p><strong>Reason:</strong> {{ $appointment->reason }}</p>
                <p><strong>Status:</strong> {{ ucfirst($appointment->status) }}</p>
                @if($appointment->notes)
                <p><strong>Notes:</strong> {{ $appointment->notes }}</p>
                @endif

                @if($appointment->uploaded_files && count($appointment->uploaded_files) > 0)
                <div class="mt-4">
                    <h4 class="text-md font-semibold mb-2">Uploaded Files:</h4>
                    <div class="space-y-2">
                        @foreach($appointment->uploaded_files as $file)
                        <div class="flex items-center space-x-3 p-2 bg-gray-50 rounded">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-medium">{{ $file['original_name'] ?? 'Unknown file' }}</p>
                                <p class="text-xs text-gray-500">{{ number_format(($file['size'] ?? 0) / 1024, 1) }} KB • {{ isset($file['mime_type']) ? strtoupper($file['mime_type']) : 'Unknown type' }}</p>
                            </div>
                            @if(isset($file['path']))
                                <a href="{{ Storage::url($file['path']) }}" download="{{ $file['original_name'] ?? 'download' }}" class="bg-green-500 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                    Download
                                </a>
                            @else
                                <span class="bg-gray-300 text-gray-500 px-3 py-1 rounded text-sm cursor-not-allowed">Download</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('patient.appointments.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Back to Appointments
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
