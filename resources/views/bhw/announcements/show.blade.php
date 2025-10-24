<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Announcement Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">{{ $announcement->title }}</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('bhw.announcements.edit', $announcement) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Edit
                            </a>
                            <a href="{{ route('bhw.announcements.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to List
                            </a>
                        </div>
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
                                <p><strong>Status:</strong>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $announcement->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $announcement->is_active ? 'Active' : 'Inactive' }}
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

                    <div class="border-t pt-6">
                        <div class="flex justify-end space-x-2">
                            <form method="POST" action="{{ route('bhw.announcements.destroy', $announcement) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to delete this announcement?')">
                                    Delete Announcement
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
