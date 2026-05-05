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
        // ✅ Use fresh database data instead of stale session cache
        $owner = \App\Models\User::find(Auth::id());

        // 🏠 Listings
        $listings = DormListing::where('owner_id', $owner->id)->get();
        $activeListings = $listings->where('status', 'available')->count();

        // ⭐ Rating
        $avgRating = Review::whereIn('dorm_listing_id', $listings->pluck('id'))
            ->avg('rating') ?? 0;

        // 💬 Messages
        $messagesQuery = Message::where('receiver_id', $owner->id);
        $totalMessages = $messagesQuery->count();

        $unreadMessages = Message::where('receiver_id', $owner->id)
            ->where('is_read', 0)
            ->count();

        // 📅 Visits (FIXED LOGIC)
        $listingIds = DormListing::where('owner_id', $owner->id)
            ->pluck('id');

        $pendingVisits = VisitSchedule::whereIn('dorm_listing_id', $listingIds)
            ->where('status', 'pending')
            ->count();

        // 💬 RECENT INQUIRIES ✅ FIX
        $recentInquiries = Message::where('receiver_id', $owner->id)
            ->latest()
            ->take(5)
            ->get();

        return view('owner.dashboard', compact(
            'owner',
            'listings',
            'activeListings',
            'avgRating',
            'totalMessages',
            'unreadMessages',
            'pendingVisits',
            'recentInquiries'
        ));
    }
}