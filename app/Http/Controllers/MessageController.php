<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ✅ ADD THIS (notification helper)
use App\Models\Notification;

class MessageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STUDENT MESSAGING SYSTEM
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $conversations = Message::where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) {
                return $message->sender_id == Auth::id()
                    ? $message->receiver_id
                    : $message->sender_id;
            });

        return view('messages.index', compact('conversations'));
    }

    public function show($userId)
    {
        $otherUser = User::findOrFail($userId);

        $messages = Message::where(function ($query) use ($userId) {
                $query->where('sender_id', Auth::id())
                      ->where('receiver_id', $userId);
            })
            ->orWhere(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        Message::where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->update(['is_read' => true]);

        return view('messages.show', compact('messages', 'otherUser'));
    }

    /*
    |--------------------------------------------------------------------------
    | SEND MESSAGE (🔥 UPDATED WITH NOTIFICATION)
    |--------------------------------------------------------------------------
    */

    public function send(Request $request, $userId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $userId,
            'message' => $request->message,
            'listing_id' => $request->listing_id ?? null,
        ]);

        // 🔔 AUTO NOTIFICATION (NEW)
        Notification::create([
            'user_id' => $userId,
            'title' => 'New Message',
            'message' => Auth::user()->name . ' sent you a message.',
            'type' => 'message',
            'is_read' => false,
        ]);

        return back();
    }

    /*
    |--------------------------------------------------------------------------
    | OWNER INQUIRIES
    |--------------------------------------------------------------------------
    */

    public function ownerInquiries()
    {
        $ownerId = Auth::id();

        $messages = Message::where('receiver_id', $ownerId)
            ->with(['sender', 'listing'])
            ->orderBy('created_at', 'desc')
            ->get();

        $grouped = $messages->groupBy('listing_id');

        return view('owner.inquiries.index', [
            'messages' => $messages,
            'grouped' => $grouped
        ]);
    }

    public function ownerConversation($listingId, $userId)
    {
        $ownerId = Auth::id();

        $otherUser = User::findOrFail($userId);

        $messages = Message::where('listing_id', $listingId)
            ->where(function ($query) use ($userId, $ownerId) {
                $query->where(function ($q) use ($userId, $ownerId) {
                    $q->where('sender_id', $ownerId)
                      ->where('receiver_id', $userId);
                })
                ->orWhere(function ($q) use ($userId, $ownerId) {
                    $q->where('sender_id', $userId)
                      ->where('receiver_id', $ownerId);
                });
            })
            ->orderBy('created_at', 'asc')
            ->get();

        Message::where('sender_id', $userId)
            ->where('receiver_id', $ownerId)
            ->update(['is_read' => true]);

        return view('owner.inquiries.show', compact('messages', 'otherUser', 'listingId'));
    }

    /*
    |--------------------------------------------------------------------------
    | OWNER REPLY (🔥 UPDATED WITH NOTIFICATION)
    |--------------------------------------------------------------------------
    */

    public function ownerReply(Request $request, $listingId, $userId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $userId,
            'listing_id' => $listingId,
            'message' => $request->message,
        ]);

        // 🔔 AUTO NOTIFICATION (NEW)
        Notification::create([
            'user_id' => $userId,
            'title' => 'Owner Reply',
            'message' => 'The owner replied to your inquiry.',
            'type' => 'message',
            'is_read' => false,
        ]);

        return back();
    }
}