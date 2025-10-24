<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Prescriptions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="fas fa-prescription-bottle-alt mr-2 text-blue-600"></i> Prescriptions
                    </h3>
                    @if($prescriptions->count() > 0)
                        <!-- Mobile view -->
                        <div class="block md:hidden space-y-4">
                            @foreach($prescriptions as $prescription)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="text-sm font-medium text-gray-900">{{ Str::limit($prescription->medication_name, 30) }}</h4>
                                    <span class="text-xs text-gray-500">{{ $prescription->created_at->format('M d, Y') }}</span>
                                </div>
                                @if($prescription->doctor)
                                <p class="text-xs text-gray-600 mb-2">Prescribed by: {{ $prescription->doctor->full_name }}</p>
                                @endif
                                <div class="text-sm text-gray-700 mb-2">
                                    Dosage: {{ $prescription->dosage }}<br>
                                    Frequency: {{ $prescription->frequency }}<br>
                                    Duration: {{ $prescription->duration }}<br>
                                    Instructions: {{ $prescription->instructions }}
                                </div>
                                <a href="{{ route('patient.prescriptions.show', $prescription) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                    View Details
                                </a>
                            </div>
                            @endforeach
                        </div>
                        <!-- Desktop table view -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Medication
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Doctor
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Dosage
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Frequency
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Duration
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Created
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">View</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($prescriptions as $prescription)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ Str::limit($prescription->medication_name, 50) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $prescription->doctor ? $prescription->doctor->full_name : 'Unknown' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $prescription->dosage }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $prescription->frequency }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $prescription->duration }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $prescription->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('patient.prescriptions.show', $prescription) }}" class="text-blue-600 hover:text-blue-900">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $prescriptions->links() }}
                        </div>
                    @else
                        <p class="text-gray-500">No prescriptions found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
