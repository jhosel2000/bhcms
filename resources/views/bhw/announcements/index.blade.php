<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('BHW - Announcements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Community Health Announcements</h3>
                        <a href="{{ route('bhw.announcements.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create Announcement
                        </a>
                    </div>

                    @if($announcements->count() > 0)
                        <div class="space-y-4">
                            @foreach($announcements as $announcement)
                                <div class="border p-4 rounded shadow-sm {{ $announcement->priority == 'high' ? 'border-red-300 bg-red-50' : ($announcement->priority == 'medium' ? 'border-yellow-300 bg-yellow-50' : 'border-gray-300') }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-semibold text-lg">{{ $announcement->title }}</h4>
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                @if($announcement->type == 'health') bg-green-100 text-green-800
                                                @elseif($announcement->type == 'vaccination') bg-blue-100 text-blue-800
                                                @elseif($announcement->type == 'emergency') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($announcement->type) }}
                                            </span>
                                            <span class="px-2 py-1 text-xs rounded-full
                                                @if($announcement->priority == 'high') bg-red-100 text-red-800
                                                @elseif($announcement->priority == 'medium') bg-yellow-100 text-yellow-800
                                                @else bg-green-100 text-green-800
                                                @endif">
                                                {{ ucfirst($announcement->priority) }}
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-gray-700 mb-2">{{ $announcement->content }}</p>
                                    <div class="flex justify-between items-center text-sm text-gray-500">
                                        <span>Published: {{ $announcement->published_at ? $announcement->published_at->format('M d, Y H:i') : 'Not published' }}</span>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('bhw.announcements.show', $announcement) }}" class="text-blue-600 hover:text-blue-800">View</a>
                                            <a href="{{ route('bhw.announcements.edit', $announcement) }}" class="text-green-600 hover:text-green-800">Edit</a>
                                            <form method="POST" action="{{ route('bhw.announcements.destroy', $announcement) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this announcement?')">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $announcements->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 mb-4">No announcements available.</p>
                            <a href="{{ route('bhw.announcements.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create First Announcement
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
