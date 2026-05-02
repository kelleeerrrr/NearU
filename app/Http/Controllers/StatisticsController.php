<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DormListing;
use App\Models\Message;
use App\Models\Review;
use App\Models\VisitSchedule;

class StatisticsController extends Controller
{
    public function index()
    {
        $owner = Auth::user();

        // 🏠 Listings stats
        $totalListings = DormListing::where('user_id', $owner->id)->count();

        $activeListings = DormListing::where('user_id', $owner->id)
            ->where('status', 'available')
            ->count();

        $takenListings = DormListing::where('user_id', $owner->id)
            ->where('status', 'taken')
            ->count();

        // 💬 Messages stats
        $totalMessages = Message::where('receiver_id', $owner->id)->count();

        $unreadMessages = Message::where('receiver_id', $owner->id)
            ->where('is_read', 0)
            ->count();

        // 📅 Visits stats
        $totalVisits = VisitSchedule::where('user_id', $owner->id)->count();

        $pendingVisits = VisitSchedule::where('user_id', $owner->id)
            ->where('status', 'pending')
            ->count();

        $approvedVisits = VisitSchedule::where('user_id', $owner->id)
            ->where('status', 'approved')
            ->count();

        // ⭐ Ratings
        $avgRating = Review::whereIn(
                'listing_id',
                DormListing::where('user_id', $owner->id)->pluck('id')
            )
            ->avg('rating') ?? 0;

        return view('owner.statistics', compact(
            'totalListings',
            'activeListings',
            'takenListings',
            'totalMessages',
            'unreadMessages',
            'totalVisits',
            'pendingVisits',
            'approvedVisits',
            'avgRating'
        ));
    }
}