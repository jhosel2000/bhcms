<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Prescription Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-4">
                    <p><strong>Medication Name:</strong> {{ $prescription->medication_name }}</p>
                    <p><strong>Dosage:</strong> {{ $prescription->dosage }}</p>
                    <p><strong>Frequency:</strong> {{ $prescription->frequency }}</p>
                    <p><strong>Duration:</strong> {{ $prescription->duration }}</p>
                    <p><strong>Instructions:</strong> {{ $prescription->instructions }}</p>
                    <p><strong>Status:</strong> {{ $prescription->status }}</p>
                    <p><strong>Prescribed At:</strong> {{ $prescription->created_at->format('M d, Y \a\t H:i') }}</p>
                    @if($prescription->doctor)
                        <p><strong>Prescribed by Doctor:</strong> {{ $prescription->doctor->full_name }}</p>
                    @endif
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('patient.prescriptions.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Back to Prescriptions</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
