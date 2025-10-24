<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $typeLabels[$maternal->type] ?? 'Record Details' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-8">
                <div class="mb-6 flex justify-between items-center border-b border-gray-200 pb-4">
                    <h3 class="text-2xl font-semibold text-gray-900">{{ $typeLabels[$maternal->type] ?? 'Record Details' }}</h3>
                    @can('update', $maternal)
                        <a href="{{ route('midwife.maternal.edit', ['maternal' => $maternal->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
                            Edit
                        </a>
                    @endcan
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-gray-700">
                    <div>
                        <h4 class="text-lg font-semibold mb-2">Patient</h4>
                        <p>{{ $maternal->patient->full_name ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold mb-2">Visit Date</h4>
                        <p>{{ $maternal->visit_date ? $maternal->visit_date->format('M d, Y') : 'N/A' }}</p>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold mb-2">Visit Time</h4>
                        <p>{{ $maternal->visit_time ? $maternal->visit_time->format('h:i A') : 'N/A' }}</p>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold mb-2">Notes</h4>
                        <p>{{ $maternal->notes ?? 'N/A' }}</p>
                    </div>

                    @if($maternal->blood_pressure_systolic || $maternal->blood_pressure_diastolic)
                        <div>
                            <h4 class="text-lg font-semibold mb-2">Blood Pressure</h4>
                            <p>
                                {{ $maternal->blood_pressure_systolic ? $maternal->blood_pressure_systolic . '/' : '' }}{{ $maternal->blood_pressure_diastolic ?? '' }} mmHg
                            </p>
                        </div>
                    @endif

                    @if($maternal->weight)
                        <div>
                            <h4 class="text-lg font-semibold mb-2">Weight</h4>
                            <p>{{ $maternal->weight }} kg</p>
                        </div>
                    @endif

                    @if($maternal->height)
                        <div>
                            <h4 class="text-lg font-semibold mb-2">Height</h4>
                            <p>{{ $maternal->height }} cm</p>
                        </div>
                    @endif

                    @if($maternal->heart_rate)
                        <div>
                            <h4 class="text-lg font-semibold mb-2">Heart Rate</h4>
                            <p>{{ $maternal->heart_rate }} bpm</p>
                        </div>
                    @endif

                    @if($maternal->temperature)
                        <div>
                            <h4 class="text-lg font-semibold mb-2">Temperature</h4>
                            <p>{{ $maternal->temperature }} °C</p>
                        </div>
                    @endif

                    @if($maternal->additional_findings)
                        <div class="md:col-span-2">
                            <h4 class="text-lg font-semibold mb-2">Additional Findings</h4>
                            <p>{{ $maternal->additional_findings }}</p>
                        </div>
                    @endif

                    @if($maternal->next_visit_date)
                        <div>
                            <h4 class="text-lg font-semibold mb-2">Next Visit Date</h4>
                            <p>{{ $maternal->next_visit_date->format('M d, Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
