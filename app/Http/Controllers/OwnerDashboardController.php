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
        $owner = Auth::user();

        // 🏠 Listings
        $listings = DormListing::where('owner_id', $owner->id)->get();
        $activeListings = $listings->where('status', 'Available')->count();

        // ⭐ Rating
        $avgRating = Review::whereIn('listing_id', $listings->pluck('id'))
            ->avg('rating') ?? 0;

        // 💬 Messages
        $messagesQuery = Message::where('receiver_id', $owner->id);
        $totalMessages = $messagesQuery->count();

        $unreadMessages = Message::where('receiver_id', $owner->id)
            ->where('is_read', 0)
            ->count();

        // 📅 Visits (FIXED LOGIC)
        $pendingVisits = VisitSchedule::whereIn('listing_id', $listings->pluck('id'))
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