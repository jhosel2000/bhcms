<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $typeLabels[$type] ?? 'Maternal Care Records' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
                        <h3 class="text-lg font-semibold">{{ $typeLabels[$type] ?? 'Records' }}</h3>
                        <a href="{{ route('midwife.maternal.create', ['type' => $type]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full sm:w-auto text-center">
                            Add New Record
                        </a>
                    </div>

                    @if($records->count() > 0)
                        <!-- Desktop Table View -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visit Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($records as $record)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $record->patient->full_name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $record->visit_date ? $record->visit_date->format('M d, Y') : 'N/A' }}
                                                @if($record->visit_time)
                                                    at {{ $record->visit_time->format('H:i') }}
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ Str::limit($record->notes, 50) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @can('view', $record)
                                                    <a href="{{ route('midwife.maternal.show', $record) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                                @endcan
                                                @can('update', $record)
                                                    <a href="{{ route('midwife.maternal.edit', $record) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                                @endcan
                                                @can('delete', $record)
                                                    <form method="POST" action="{{ route('midwife.maternal.destroy', $record) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Card View -->
                        <div class="md:hidden space-y-4">
                            @foreach($records as $record)
                                <div class="bg-gray-50 rounded-lg p-4 border">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="text-lg font-semibold text-gray-900">{{ $record->patient->full_name ?? 'N/A' }}</h4>
                                        <div class="flex space-x-2">
                                            @can('view', $record)
                                                <a href="{{ route('midwife.maternal.show', $record) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">View</a>
                                            @endcan
                                            @can('update', $record)
                                                <a href="{{ route('midwife.maternal.edit', $record) }}" class="text-blue-600 hover:text-blue-900 text-sm">Edit</a>
                                            @endcan
                                            @can('delete', $record)
                                                <form method="POST" action="{{ route('midwife.maternal.destroy', $record) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                    <div class="text-sm text-gray-600 mb-2">
                                        <strong>Visit Date:</strong> {{ $record->visit_date ? $record->visit_date->format('M d, Y') : 'N/A' }}
                                        @if($record->visit_time)
                                            at {{ $record->visit_time->format('H:i') }}
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <strong>Notes:</strong> {{ Str::limit($record->notes, 100) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $records->links() }}
                        </div>
                    @else
                        <p class="text-gray-500">No records found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
