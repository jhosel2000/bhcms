<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Get all conversations for the user
        $conversations = Message::where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->with(['sender', 'receiver'])
            ->get()
            ->groupBy(function ($message) use ($user) {
                // Group by the other user's ID
                return $message->sender_id == $user->id ? $message->receiver_id : $message->sender_id;
            })
            ->map(function ($group) use ($user) {
                $otherUserId = $group->first()->sender_id == $user->id ? $group->first()->receiver_id : $group->first()->sender_id;
                return (object)[
                    'id' => $otherUserId,
                    'messages' => $group->sortBy('created_at'),
                    'participants' => collect([$group->first()->sender, $group->first()->receiver])->unique('id')->values(),
                ];
            })
            ->sortByDesc(function ($conversation) {
                return $conversation->messages->max('created_at');
            })
            ->values();

        $recipients = $this->getAllowedRecipients($user);
        $initialChatUser = null;

        return view('messages.chat', compact('conversations', 'recipients', 'initialChatUser'));
    }

    public function chat($userId)
    {
        $user = Auth::user();
        $otherUser = User::findOrFail($userId);

        // Check if user can chat with this user
        if (!$this->canSendTo($user, $otherUser)) {
            abort(403, 'You are not allowed to chat with this user.');
        }

        // Get all conversations for the user
        $conversations = Message::where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('receiver_id', $user->id);
            })
            ->with(['sender', 'receiver'])
            ->get()
            ->groupBy(function ($message) use ($user) {
                // Group by the other user's ID
                return $message->sender_id == $user->id ? $message->receiver_id : $message->sender_id;
            })
            ->map(function ($group) use ($user) {
                $otherUserId = $group->first()->sender_id == $user->id ? $group->first()->receiver_id : $group->first()->sender_id;
                return (object)[
                    'id' => $otherUserId,
                    'messages' => $group->sortBy('created_at'),
                    'participants' => collect([$group->first()->sender, $group->first()->receiver])->unique('id')->values(),
                ];
            })
            ->sortByDesc(function ($conversation) {
                return $conversation->messages->max('created_at');
            })
            ->values();

        // Get initial messages for the selected conversation (or empty) - limit to last 20 for Messenger-like experience
        $initialConversation = $conversations->firstWhere('id', $userId) ?? $conversations->first();
        if ($initialConversation) {
            $initialMessages = $initialConversation->messages
                ->sortByDesc('created_at')
                ->take(20)
                ->sortBy('created_at');
        } else {
            $initialMessages = collect();
        }
        $initialChatUser = $initialConversation ? $initialConversation->participants->firstWhere('id', '!=', $user->id) : $otherUser;

        // Mark messages as read for the receiver
        if ($initialMessages->isNotEmpty() && $initialChatUser) {
            Message::where('receiver_id', $user->id)
                ->where('sender_id', $initialChatUser->id)
                ->where('status', 'unread')
                ->update(['status' => 'read']);
        }

        $canSend = $this->canSendTo($user, $otherUser);

        return view('messages.chat', compact('conversations', 'initialMessages', 'initialChatUser', 'canSend'));
    }

    public function create()
    {
        $user = Auth::user();
        $recipients = $this->getAllowedRecipients($user);

        return view('messages.create', compact('recipients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $user = Auth::user();
        $receiver = User::findOrFail($request->receiver_id);

        // Check if user can send to this receiver
        if (!$this->canSendTo($user, $receiver)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'You are not allowed to send messages to this user.'], 403);
            }
            return back()->withErrors(['receiver_id' => 'You are not allowed to send messages to this user.']);
        }

        $message = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $request->receiver_id,
            'role_sender' => $user->role,
            'role_receiver' => $receiver->role,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'unread',
        ]);

        // Fire the event for real-time updates
        broadcast(new MessageSent($message))->toOthers();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message->load(['sender', 'receiver'])]);
        }

        return redirect()->route('messages.index')->with('success', 'Message sent successfully.');
    }

    public function show($id)
    {
        $user = Auth::user();
        $message = Message::with(['sender', 'receiver', 'replies.sender', 'replies.receiver'])
            ->where(function ($query) use ($user) {
                $query->where('receiver_id', $user->id)
                      ->orWhere('sender_id', $user->id);
            })
            ->findOrFail($id);

        // Mark as read if receiver
        if ($message->receiver_id === $user->id && !$message->isRead()) {
            $message->markAsRead();
        }

        $canReply = $this->canReply($user);

        return view('messages.show', compact('message', 'canReply'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $user = Auth::user();

        if (!$this->canReply($user)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'You are not allowed to reply to messages.'], 403);
            }
            abort(403, 'You are not allowed to reply to messages.');
        }

        $parentMessage = Message::findOrFail($id);

        // Check if user is part of the conversation
        if ($parentMessage->sender_id !== $user->id && $parentMessage->receiver_id !== $user->id) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'You are not part of this conversation.'], 403);
            }
            abort(403);
        }

        $receiverId = $parentMessage->sender_id === $user->id ? $parentMessage->receiver_id : $parentMessage->sender_id;
        $receiver = User::findOrFail($receiverId);

        $message = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'role_sender' => $user->role,
            'role_receiver' => $receiver->role,
            'subject' => 'Re: ' . $parentMessage->subject,
            'message' => $request->message,
            'status' => 'unread',
            'parent_id' => $parentMessage->id,
        ]);

        // Fire the event for real-time updates
        broadcast(new MessageSent($message))->toOthers();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message->load(['sender', 'receiver'])]);
        }

        return redirect()->route('messages.show', $parentMessage->id)->with('success', 'Reply sent successfully.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $message = Message::where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                  ->orWhere('receiver_id', $user->id);
        })->findOrFail($id);

        $message->delete();

        return redirect()->route('messages.index')->with('success', 'Message deleted successfully.');
    }

    private function canReply(User $user)
    {
        return in_array($user->role, ['doctor', 'midwife']);
    }

    private function getAllowedRecipients(User $user)
    {
        $query = User::where('id', '!=', $user->id);

        switch ($user->role) {
            case 'doctor':
                $query->whereIn('role', ['patient', 'midwife', 'bhw']);
                break;
            case 'midwife':
                $query->whereIn('role', ['doctor', 'patient', 'bhw']);
                break;
            case 'bhw':
                $query->whereIn('role', ['doctor', 'midwife']);
                break;
            case 'patient':
                $query->whereIn('role', ['doctor', 'midwife']);
                break;
        }

        return $query->get();
    }

    private function canSendTo(User $sender, User $receiver)
    {
        $allowed = [
            'doctor' => ['patient', 'midwife', 'bhw'],
            'midwife' => ['doctor', 'patient', 'bhw'],
            'bhw' => ['doctor', 'midwife'],
            'patient' => ['doctor', 'midwife'],
        ];

        return in_array($receiver->role, $allowed[$sender->role] ?? []);
    }
}
