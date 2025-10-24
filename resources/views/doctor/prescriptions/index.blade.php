<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Prescription Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4">Manage Prescriptions</h3>
                    <p class="text-gray-600">Create and manage patient prescriptions connected to the database.</p>
                </div>

                <div class="mb-6">
                    <a href="{{ route('doctor.prescriptions.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Create New Prescription
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-4 border rounded-lg shadow-sm">
                    <h4 class="font-semibold mb-2">Prescriptions</h4>
                    <div class="space-y-3">
                        @foreach ($prescriptions as $prescription)
                        <div class="p-3 bg-gray-50 rounded">
                            <p class="font-medium">{{ $prescription->patient->full_name }} - {{ $prescription->medication_name }}</p>
                            <p class="text-sm text-gray-600">{{ $prescription->dosage }}, {{ $prescription->frequency }} for {{ $prescription->duration }}</p>
                            <p class="text-xs text-gray-500">Prescribed: {{ $prescription->created_at->format('M j, Y') }}</p>
                            <a href="{{ route('doctor.prescriptions.show', $prescription->id) }}" class="mt-2 bg-blue-500 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm inline-block">
                                View Details
                            </a>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $prescriptions->links() }}
                    </div>
                </div>
                    <div class="p-4 border rounded-lg shadow-sm">
                        <h4 class="font-semibold mb-2">Prescription Statistics</h4>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span>This Month:</span>
                                <span class="font-semibold">{{ $monthlyPrescriptions }} prescriptions</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Most Common:</span>
                                <span class="font-semibold">{{ $mostCommonMedication ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Pending Refills:</span>
                                <span class="font-semibold text-orange-600">{{ $pendingRefills }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
