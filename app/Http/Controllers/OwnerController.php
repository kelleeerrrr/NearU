<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DormListing;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function show($id)
    {
        // Find the owner (must be user_type = 'owner')
        $owner = User::where('id', $id)
            ->where('user_type', 'owner')
            ->firstOrFail();

        // Get all listings owned by this user with eager loading
        $listings = DormListing::where('owner_id', $owner->id)
            ->with(['images', 'reviews.user'])
            ->get();

        // Get all reviews for this owner's listings
        $reviews = \App\Models\Review::whereIn('dorm_listing_id', $listings->pluck('id'))
            ->with('user')
            ->latest()
            ->get();

        // Calculate statistics
        $totalListings = $listings->count();
        $totalReviews = $reviews->count();
        $averageRating = $reviews->avg('rating') ?? 0;
        
        // Calculate response rate (simplified - could be enhanced with actual message data)
        $totalInquiries = \App\Models\VisitSchedule::whereIn('dorm_listing_id', $listings->pluck('id'))->count();
        $respondedInquiries = \App\Models\VisitSchedule::whereIn('dorm_listing_id', $listings->pluck('id'))
            ->whereNotNull('status')
            ->count();
        $responseRate = $totalInquiries > 0 ? ($respondedInquiries / $totalInquiries) * 100 : 0;

        return view('owners.show', compact('owner', 'listings', 'reviews', 'totalListings', 'totalReviews', 'averageRating', 'responseRate'));
    }
}