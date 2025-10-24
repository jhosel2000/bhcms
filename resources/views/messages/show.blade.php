<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Message') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="p-6">
                    <div class="border-b border-gray-200 pb-4 mb-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $message->subject }}</h3>
                        <p class="text-sm text-gray-600">
                            From: {{ $message->sender->name }} ({{ ucfirst($message->role_sender) }}) |
                            To: {{ $message->receiver->name }} ({{ ucfirst($message->role_receiver) }}) |
                            {{ $message->created_at->format('M d, Y h:i A') }}
                        </p>
                    </div>

                    <div class="mb-6">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $message->message }}</p>
                    </div>

                    @if($message->replies->count() > 0)
                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Replies</h4>
                            @foreach($message->replies as $reply)
                                <div class="bg-gray-50 p-4 rounded mb-4">
                                    <p class="text-sm text-gray-600">
                                        From: {{ $reply->sender->name }} ({{ ucfirst($reply->role_sender) }}) |
                                        {{ $reply->created_at->format('M d, Y h:i A') }}
                                    </p>
                                    <p class="text-gray-700 whitespace-pre-wrap mt-2">{{ $reply->message }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($canReply)
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-md font-medium text-gray-900 mb-4">Reply</h4>
                        <form method="POST" action="{{ route('messages.reply', $message->id) }}">
                            @csrf
                            <div class="mb-4">
                                <label for="message" class="block text-sm font-medium text-gray-700">Your Reply</label>
                                <textarea id="message" name="message" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"></textarea>
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Send Reply
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('messages.index') }}" class="text-gray-600 hover:text-gray-900">← Back to Inbox</a>
            </div>
        </div>
    </div>
</x-app-layout>
