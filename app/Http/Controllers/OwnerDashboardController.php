<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\DormListing;
use App\Models\Review;
use App\Models\Message;
use App\Models\VisitSchedule;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        // ✅ Fresh owner data
        $owner = \App\Models\User::find(Auth::id());

        if (!$owner) {
            abort(403, 'Owner not found');
        }

        /*
        |--------------------------------------------------------------------------
        | 🏠 LISTINGS
        |--------------------------------------------------------------------------
        */

        $listings = DormListing::where('owner_id', $owner->id)->get();

        $listingIds = $listings->pluck('id');

        $activeListings = $listings->where('status', 'available')->count();

        /*
        |--------------------------------------------------------------------------
        | ⭐ RATING
        |--------------------------------------------------------------------------
        */

        $avgRating = Review::whereIn('dorm_listing_id', $listingIds)
            ->avg('rating') ?? 0;

        /*
        |--------------------------------------------------------------------------
        | 💬 MESSAGES (NEEDS REPLY LOGIC FIXED)
        |--------------------------------------------------------------------------
        */

        $conversations = Message::whereHas('listing', function ($query) use ($owner) {
                $query->where('owner_id', $owner->id);
            })
            ->where(function ($q) use ($owner) {
                $q->where('receiver_id', $owner->id)
                  ->orWhere('sender_id', $owner->id);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) use ($owner) {
                $otherUserId = $message->sender_id === $owner->id
                    ? $message->receiver_id
                    : $message->sender_id;

                return ($message->dorm_listing_id ?? $message->listing_id) . '_' . $otherUserId;
            });

        // ✅ Correct: conversations needing reply
        $unreadMessages = $conversations->filter(function ($messages) use ($owner) {
            $lastMessage = $messages->sortByDesc('created_at')->first();

            // If last message is NOT from owner → needs reply
            return $lastMessage && $lastMessage->sender_id !== $owner->id;
        })->count();

        /*
        |--------------------------------------------------------------------------
        | 📅 VISIT REQUESTS (PENDING ONLY)
        |--------------------------------------------------------------------------
        */

        $pendingVisits = VisitSchedule::whereIn('dorm_listing_id', $listingIds)
            ->where('status', 'Pending')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | 💬 RECENT INQUIRIES (LATEST ONLY)
        |--------------------------------------------------------------------------
        */

        $recentInquiries = Message::whereHas('listing', function ($query) use ($owner) {
                $query->where('owner_id', $owner->id);
            })
            ->latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view('owner.dashboard', compact(
            'owner',
            'listings',
            'activeListings',
            'avgRating',
            'unreadMessages',
            'pendingVisits',
            'recentInquiries'
        ));
    }
}