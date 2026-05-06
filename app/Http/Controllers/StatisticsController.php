<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\DormListing;
use App\Models\Message;
use App\Models\Review;
use App\Models\VisitSchedule;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index()
    {
        $owner = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | 🏠 LISTINGS
        |--------------------------------------------------------------------------
        */
        $listings = DormListing::where('owner_id', $owner->id)->get();
        $listingIds = $listings->pluck('id');

        $totalListings = $listings->count();

        // normalize status just in case
        $activeListings = $listings->filter(function ($l) {
            return strtolower($l->status) === 'available';
        })->count();

        $takenListings = $listings->filter(function ($l) {
            return strtolower($l->status) === 'unavailable';
        })->count();

        /*
        |--------------------------------------------------------------------------
        | 💬 MESSAGES
        |--------------------------------------------------------------------------
        */
        $messagesQuery = Message::where('receiver_id', $owner->id)
            ->whereIn('dorm_listing_id', $listingIds);

        $messages = (clone $messagesQuery)
            ->orderBy('created_at')
            ->get();

        $totalMessages = $messages->count();

        $unreadMessages = (clone $messagesQuery)
            ->where('is_read', false)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | ⚡ RESPONSE TIME
        |--------------------------------------------------------------------------
        */
        $responseTimes = [];

        foreach ($messages as $msg) {

            $reply = Message::where('sender_id', $owner->id)
                ->where('receiver_id', $msg->sender_id)
                ->where('created_at', '>', $msg->created_at)
                ->orderBy('created_at')
                ->first();

            if ($reply) {
                $responseTimes[] = $msg->created_at->diffInMinutes($reply->created_at);
            }
        }

        $avgResponseTime = count($responseTimes)
            ? round(array_sum($responseTimes) / count($responseTimes) / 60, 2)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | 📅 VISITS
        |--------------------------------------------------------------------------
        */
        $visits = VisitSchedule::whereIn('dorm_listing_id', $listingIds)->get();

        $totalVisits = $visits->count();

        $pendingVisits = $visits->where('status', 'Pending')->count();
        $approvedVisits = $visits->where('status', 'Confirmed')->count();
        $completedVisits = $visits->where('status', 'Completed')->count();

        /*
        |--------------------------------------------------------------------------
        | 📊 CONVERSION + DROP OFF
        |--------------------------------------------------------------------------
        */
        $conversionRate = $totalMessages > 0
            ? round(($totalVisits / $totalMessages) * 100, 2)
            : 0;

        $dropOffRate = $totalMessages > 0
            ? round((($totalMessages - $totalVisits) / $totalMessages) * 100, 2)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | 🏠 TOP LISTING
        |--------------------------------------------------------------------------
        */
        $messageCounts = Message::whereIn('dorm_listing_id', $listingIds)
            ->selectRaw('dorm_listing_id, count(*) as total')
            ->groupBy('dorm_listing_id')
            ->pluck('total', 'dorm_listing_id');

        $visitCounts = VisitSchedule::whereIn('dorm_listing_id', $listingIds)
            ->selectRaw('dorm_listing_id, count(*) as total')
            ->groupBy('dorm_listing_id')
            ->pluck('total', 'dorm_listing_id');

        $reviewAvg = Review::whereIn('dorm_listing_id', $listingIds)
            ->selectRaw('dorm_listing_id, avg(rating) as avg_rating')
            ->groupBy('dorm_listing_id')
            ->pluck('avg_rating', 'dorm_listing_id');

        $topListing = null;
        $highestScore = 0;

        foreach ($listings as $listing) {

            $msg = $messageCounts[$listing->id] ?? 0;
            $vis = $visitCounts[$listing->id] ?? 0;
            $rev = $reviewAvg[$listing->id] ?? 0;

            $score = ($msg * 1.5) + ($vis * 3) + ($rev * 10);

            if ($score > $highestScore) {
                $highestScore = $score;
                $topListing = $listing;
                $topListing->score = round($score, 2);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | ⭐ AVERAGE RATING
        |--------------------------------------------------------------------------
        */
        $avgRating = Review::whereIn('dorm_listing_id', $listingIds)
            ->avg('rating') ?? 0;

        /*
        |--------------------------------------------------------------------------
        | 🔥 WEEKLY MESSAGE TREND
        |--------------------------------------------------------------------------
        */
        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {

            $date = Carbon::now()->subDays($i);

            $chartLabels[] = $date->format('D');

            $chartData[] = Message::where('receiver_id', $owner->id)
                ->whereIn('dorm_listing_id', $listingIds)
                ->whereDate('created_at', $date)
                ->count();
        }

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */
        return view('owner.statistics.index', compact(
            'totalListings',
            'activeListings',
            'takenListings',
            'totalMessages',
            'unreadMessages',
            'totalVisits',
            'pendingVisits',
            'approvedVisits',
            'completedVisits',
            'avgRating',
            'avgResponseTime',
            'conversionRate',
            'dropOffRate',
            'topListing',
            'chartLabels',
            'chartData'
        ));
    }
}