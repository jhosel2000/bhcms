<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Announcement Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold">{{ $announcement->title }}</h3>
                    <a href="{{ route('patient.announcements.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Back to List
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h4 class="font-semibold mb-2">Announcement Information</h4>
                        <div class="space-y-2">
                            <p><strong>Type:</strong>
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($announcement->type == 'health') bg-green-100 text-green-800
                                    @elseif($announcement->type == 'vaccination') bg-blue-100 text-blue-800
                                    @elseif($announcement->type == 'emergency') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($announcement->type) }}
                                </span>
                            </p>
                            <p><strong>Priority:</strong>
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($announcement->priority == 'high') bg-red-100 text-red-800
                                    @elseif($announcement->priority == 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($announcement->priority) }}
                                </span>
                            </p>
                            <p><strong>Published:</strong> {{ $announcement->published_at ? $announcement->published_at->format('M d, Y H:i') : 'Not published' }}</p>
                            <p><strong>Created By:</strong> {{ $announcement->creator->name ?? 'Unknown' }}</p>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold mb-2">Content</h4>
                        <div class="bg-gray-50 p-4 rounded">
                            <p class="whitespace-pre-wrap">{{ $announcement->content }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
