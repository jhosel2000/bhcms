<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Messages') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('messages.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Compose New Message
                </a>
            </div>
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                @if($initialChatUser ?? false)
                    <!-- Chat Interface -->
                    <div class="flex flex-col md:flex-row min-h-96">
                        <!-- Conversations Sidebar -->
                        <div class="w-full md:w-1/4 md:border-r md:border-gray-200 p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Conversations</h3>
                            <div class="space-y-2">
                                @forelse ($conversations as $conversation)
                                    @php
                                        $otherUser = $conversation->participants->firstWhere('id', '!=', auth()->id());
                                    @endphp
                                <a href="{{ route('messages.chat', $otherUser->id) }}" class="block p-3 rounded-lg hover:bg-gray-100 {{ $initialChatUser->id == $otherUser->id ? 'bg-blue-100' : '' }}">
                                    <div class="font-medium">{{ $otherUser->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $otherUser->role }}</div>
                                    <div class="text-xs text-gray-400">{{ $conversation->messages->last()->created_at->diffForHumans() }}</div>
                                </a>
                                @empty
                                    <p class="text-gray-500">No conversations yet.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Chat Area -->
                        <div class="w-full md:w-3/4 flex flex-col">
                            <div class="p-4 border-b border-gray-200">
                                <h4 class="text-lg font-medium">{{ $initialChatUser->name }} ({{ ucfirst($initialChatUser->role) }})</h4>
                            </div>
                            <div id="messages" class="flex-1 p-4 overflow-y-auto max-h-96 md:max-h-none">
                                @forelse ($initialMessages as $message)
                                    <div class="mb-4 {{ $message->sender_id == auth()->id() ? 'text-right' : 'text-left' }}">
                                        <div class="inline-block p-3 rounded-lg {{ $message->sender_id == auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800' }}">
                                            <p>{{ $message->message }}</p>
                                            <small class="text-xs opacity-75">{{ $message->created_at->setTimezone('Asia/Manila')->format('M d, H:i') }}</small>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-center">No messages yet. Start the conversation!</p>
                                @endforelse
                            </div>
                            @if($canSend)
                                <div class="p-4 border-t border-gray-200">
                                    <form id="messageForm" method="POST" action="{{ route('messages.store') }}">
                                        @csrf
                                        <input type="hidden" name="receiver_id" value="{{ $initialChatUser->id }}">
                                        <input type="hidden" name="subject" value="Chat">
                                        <div class="flex flex-col md:flex-row">
                                            <input type="text" name="message" id="messageInput" placeholder="Type your message..." class="flex-1 rounded-md md:rounded-r-none md:rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                                            <button type="submit" class="mt-2 md:mt-0 px-4 py-2 bg-blue-600 border border-transparent rounded-md md:rounded-l-none md:rounded-r-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Send</button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Conversations List -->
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Your Conversations</h3>
                        <div class="space-y-4">
                            @forelse ($conversations as $conversation)
                                @php
                                    $otherUser = $conversation->participants->firstWhere('id', '!=', auth()->id());
                                    $lastMessage = $conversation->messages->last();
                                @endphp
                                <a href="{{ route('messages.chat', $otherUser->id) }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <div class="font-medium">{{ $otherUser->name }} ({{ ucfirst($otherUser->role) }})</div>
                                    <div class="text-sm text-gray-600">{{ Str::limit($lastMessage->message, 50) }}</div>
                                    <div class="text-xs text-gray-400">{{ $lastMessage->created_at->diffForHumans() }}</div>
                                </a>
                            @empty
                                <p class="text-gray-500 text-center py-8">No conversations yet. Start by composing a new message.</p>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        @if($initialChatUser)
            window.Echo.private('messages.{{ auth()->id() }}')
                .listen('.message.sent', (e) => {
                    if (e.sender_id == {{ $initialChatUser->id }} || e.receiver_id == {{ $initialChatUser->id }}) {
                        appendMessage(e);
                    }
                });

            function appendMessage(data) {
                const messagesDiv = document.getElementById('messages');
                const isMine = data.sender_id == {{ auth()->id() }};
                const messageDiv = document.createElement('div');
                messageDiv.className = `mb-4 ${isMine ? 'text-right' : 'text-left'}`;
                const phTime = new Date(data.created_at).toLocaleString('en-PH', { timeZone: 'Asia/Manila', month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' });
                messageDiv.innerHTML = `
                    <div class="inline-block p-3 rounded-lg ${isMine ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'}">
                        <p>${data.message}</p>
                        <small class="text-xs opacity-75">${phTime}</small>
                    </div>
                `;
                messagesDiv.appendChild(messageDiv);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            }

            document.getElementById('messageForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const messageText = document.getElementById('messageInput').value;
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('messageInput').value = '';
                        // Append the message locally for the sender
                        appendMessage({
                            message: messageText,
                            sender_id: {{ auth()->id() }},
                            created_at: new Date().toISOString()
                        });
                    } else {
                        alert(data.error || 'Error sending message');
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    alert('Error sending message');
                });
            });
        @endif
    </script>
    @endpush
</x-app-layout>
