<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Patient Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4">Manage Patients</h3>
                    <p class="text-gray-600">View and manage patient records connected to the database.</p>
                </div>

                <div class="mb-6">
                    <a href="{{ route('bhw.patients.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Add New Patient
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($patients as $patient)
                    <div class="p-4 border rounded-lg shadow-sm">
                        <h4 class="font-semibold mb-2">{{ $patient->full_name }}</h4>
                        <p class="text-sm text-gray-600">Age: {{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->age : 'N/A' }}</p>
                        <p class="text-sm text-gray-600">Last Visit: {{ $patient->updated_at->format('Y-m-d') }}</p>
                        <a href="{{ route('bhw.patients.show', $patient->id) }}" class="mt-2 bg-gray-500 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm inline-block">
                            View Details
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
