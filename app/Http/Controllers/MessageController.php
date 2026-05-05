<?php

namespace App\Http\Controllers;

use App\Models\DormListing;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STUDENT MESSAGES - LISTING-BASED
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $conversations = Message::where(function ($q) {
                $q->where('sender_id', Auth::id())
                  ->orWhere('receiver_id', Auth::id());
            })
            ->with(['sender', 'receiver', 'listing', 'legacyListing'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) {
                $otherUserId = $message->sender_id == Auth::id() ? $message->receiver_id : $message->sender_id;
                return ($message->dorm_listing_id ?? $message->listing_id) . '_' . $otherUserId;
            });

        return view('messages.index', compact('conversations'));
    }

    public function show($listingId, $userId)
    {
        $listing = DormListing::findOrFail($listingId);
        $otherUser = User::findOrFail($userId);

        $messages = Message::where(function ($q) use ($listingId) {
                $q->where('dorm_listing_id', $listingId)
                  ->orWhere('listing_id', $listingId);
            })
            ->where(function ($q) use ($userId) {
                $q->where(function ($sub) use ($userId) {
                    $sub->where('sender_id', Auth::id())
                        ->where('receiver_id', $userId);
                })->orWhere(function ($sub) use ($userId) {
                    $sub->where('sender_id', $userId)
                        ->where('receiver_id', Auth::id());
                });
            })
            ->with(['sender', 'receiver', 'listing', 'legacyListing'])
            ->orderBy('created_at', 'asc')
            ->get();

        Message::where(function ($q) use ($listingId) {
                $q->where('dorm_listing_id', $listingId)
                  ->orWhere('listing_id', $listingId);
            })
            ->where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->update(['is_read' => true]);

        return view('messages.show', compact('messages', 'otherUser', 'listing'));
    }

    public function send(Request $request, $userId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'listing_id' => 'required|exists:dorm_listings,id',
        ]);

        $listing = DormListing::findOrFail($request->listing_id);

        // ✅ ALWAYS use listing owner as receiver
        $receiverId = $listing->owner_id;

        // ❗ Safety check (prevents your exact crash)
        if (!User::where('id', $receiverId)->exists()) {
            return back()->with('error', 'Listing owner does not exist.');
        }

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiverId,
            'dorm_listing_id' => $listing->id,
            'listing_id' => $listing->id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        Notification::create([
            'user_id' => $receiverId,
            'dorm_listing_id' => $listing->id,
            'title' => 'New Message',
            'message' => Auth::user()->name . ' sent you a message about ' . $listing->street,
            'type' => 'message',
            'is_read' => false,
        ]);

        return back()->with('success', 'Message sent!');
    }

    /*
    |--------------------------------------------------------------------------
    | OWNER INQUIRIES - LISTING-BASED
    |--------------------------------------------------------------------------
    */

    public function ownerInquiries()
    {
        // if (Auth::user()->verification_status !== 'approved') {
        //     return redirect()->route('owner.dashboard')
        //         ->with('error', 'You must be verified to access inquiries. Please complete your verification first.');
        // }

        $ownerId = Auth::id();

        $grouped = Message::whereHas('listing', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->where(function ($q) use ($ownerId) {
                $q->where('receiver_id', $ownerId)
                  ->orWhere('sender_id', $ownerId);
            })
            ->with(['sender', 'listing', 'legacyListing'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) use ($ownerId) {
                $otherUserId = $message->sender_id === $ownerId ? $message->receiver_id : $message->sender_id;
                return ($message->dorm_listing_id ?? $message->listing_id) . '_' . $otherUserId;
            });

        return view('owner.inquiries.index', compact('grouped'));
    }

    public function ownerConversation($listingId, $userId)
    {
        // if (Auth::user()->verification_status !== 'approved') {
        //     return redirect()->route('owner.dashboard')
        //         ->with('error', 'You must be verified to access inquiries. Please complete your verification first.');
        // }

        // ✅ LISTING-BASED: Show conversation for specific listing + user
        $listing = \App\Models\DormListing::where('owner_id', Auth::id())
            ->findOrFail($listingId);
        $student = User::findOrFail($userId);

        $messages = Message::where(function ($q) use ($listingId) {
                $q->where('dorm_listing_id', $listingId)
                  ->orWhere('listing_id', $listingId);
            })
            ->where(function ($q) use ($userId) {
                $q->where(function ($sub) use ($userId) {
                    $sub->where('sender_id', Auth::id())
                        ->where('receiver_id', $userId);
                })->orWhere(function ($sub) use ($userId) {
                    $sub->where('sender_id', $userId)
                        ->where('receiver_id', Auth::id());
                });
            })
            ->with(['sender', 'receiver', 'listing', 'legacyListing'])
            ->orderBy('created_at', 'asc')
            ->get();

        Message::where(function ($q) use ($listingId) {
                $q->where('dorm_listing_id', $listingId)
                  ->orWhere('listing_id', $listingId);
            })
            ->where('sender_id', $userId)
            ->where('receiver_id', Auth::id())
            ->update(['is_read' => true]);

        return view('owner.inquiries.show', compact('messages', 'student', 'listing'));
    }

    public function ownerReply(Request $request, $listingId, $userId)
    {
        // if (Auth::user()->verification_status !== 'approved') {
        //     return redirect()->route('owner.dashboard')
        //         ->with('error', 'You must be verified to access inquiries. Please complete your verification first.');
        // }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Verify ownership
        $listing = \App\Models\DormListing::where('owner_id', Auth::id())
            ->findOrFail($listingId);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $userId,
            'dorm_listing_id' => $listingId,
            'listing_id' => $listingId,
            'message' => $request->message,
            'is_read' => false,
        ]);

        // ✅ ENHANCED NOTIFICATIONS: Include listing context
        Notification::create([
            'user_id' => $userId,
            'dorm_listing_id' => $listingId,
            'title' => 'New Reply',
            'message' => $listing->street . ': ' . Auth::user()->name . ' replied to your inquiry',
            'type' => 'message',
            'is_read' => false,
        ]);

        return back()->with('success', 'Reply sent!');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE MESSAGE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $message = Message::findOrFail($id);

        if ($message->sender_id !== Auth::id() && $message->receiver_id !== Auth::id()) {
            abort(403);
        }

        $message->delete();

        return back()->with('success', 'Message deleted.');
    }
}