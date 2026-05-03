<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\DormListing;
use App\Models\DormListingImage;
use App\Models\SavedListing;
use App\Models\VisitSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DormController extends Controller
{
    public function scheduleVisit(Request $request)
    {
        $request->validate([
            'dorm_id' => 'required|exists:dorm_listings,id',
            'date'    => 'required|date|after:today',
            'time'    => 'required',
            'notes'   => 'nullable|string|max:500',
        ]);

        $dorm = DormListing::with('owner')->findOrFail($request->dorm_id);

        // 🚫 prevent duplicate same-day booking
        $exists = VisitSchedule::where('user_id', Auth::id())
            ->where('dorm_listing_id', $dorm->id)
            ->where('visit_date', $request->date)
            ->first();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'You already scheduled a visit on this date.'
            ]);
        }

        $visit = VisitSchedule::create([
            'user_id'          => Auth::id(),
            'dorm_listing_id'  => $dorm->id,
            'visit_date'       => $request->date,
            'visit_time'       => $request->time,
            'notes'            => $request->notes,
            'status'           => 'pending', // 🔥 important
        ]);

        // 🔔 NOTIFY OWNER (if you already created notification class)
        if ($dorm->owner) {
            $dorm->owner->notify(new \App\Notifications\NewVisitScheduled($visit));
        }

        return response()->json([
            'success' => true,
            'message' => 'Visit request sent to owner',
            'visit' => $visit
        ]);
    }

    public function storeReview(Request $request, $dormId)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $dorm = DormListing::findOrFail($dormId);

        // Prevent duplicate review (optional but recommended)
        $existing = Review::where('user_id', Auth::id())
            ->where('dorm_listing_id', $dormId)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You already reviewed this listing.'
            ]);
        }

        // Save review
        $review = Review::create([
            'user_id'          => Auth::id(),
            'dorm_listing_id'  => $dormId,
            'rating'           => $request->rating,
            'comment'          => $request->comment,
        ]);

        // Recalculate rating
        $avg = Review::where('dorm_listing_id', $dormId)->avg('rating');
        $count = Review::where('dorm_listing_id', $dormId)->count();

        $dorm->update([
            'rating' => round($avg, 1)
        ]);

        return response()->json([
            'success' => true,
            'reviews_count' => $count,
            'avg_rating' => round($avg, 1),
            'review' => $review->load('user')
        ]);
    }
    public function getReviews($dormId)
    {
        $reviews = Review::with('user')
            ->where('dorm_listing_id', $dormId)
            ->latest()
            ->get()
            ->map(function ($r) {
                return [
                    'user_name'   => $r->user->name,
                    'rating'      => $r->rating,
                    'comment'     => $r->comment,
                    'created_at'  => $r->created_at->format('M d, Y'),
                    'is_verified' => $r->user->is_verified ?? false,
                ];
            });

        return response()->json($reviews);
    }

    /*
    | STORE LISTING (WITH IMAGES)
    */
    public function store(Request $request)
    {

        $request->validate([
            'street' => 'required|string|max:255',
            'price' => 'required|numeric',
            'type' => 'required|string',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        // Create listing
        $dorm = DormListing::create([
            'owner_id' => Auth::id(),
            'street' => $request->street,
            'price' => $request->price,
            'type' => $request->type,
            'status' => 'Available',
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        // Save images
        if ($request->hasFile('photos')) {

            foreach ($request->file('photos') as $index => $file) {

                // store in public storage
                $path = $file->store('dorms', 'public');

                // save in DB
                DormListingImage::create([
                    'dorm_listing_id' => $dorm->id,
                    'path' => $path,
                    'is_cover' => $index === 0,
                ]);
            }
        } else {
            dd('NO FILES RECEIVED');
        }

    return redirect()->route('owner.listings.index')
        ->with('success', 'Dorm listing created successfully!');
    }

    /*
    | STUDENT HOME
    */
    public function indexStudent()
    {
        $dormListings = DormListing::where('status', 'Available')
            ->with(['owner', 'images'])
            ->latest()
            ->get();

        $dormsDataJson = $dormListings->map(function ($dorm) {

            $cover = $dorm->images->first();

            return [
                'id' => $dorm->id,
                'street' => $dorm->street,
                'price' => $dorm->price,
                'type' => $dorm->type,
                'status' => $dorm->status,
                'lat' => $dorm->latitude,
                'lng' => $dorm->longitude,

                'photo' => $cover
                    ? asset('storage/' . $cover->path)
                    : 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=400',
            ];
        });

        return view('student.home', [
            'dormListings' => $dormListings,
            'dormsDataJson' => $dormsDataJson->toJson()
        ]);
    }

    /*
    | PUBLIC LIST
    */
    public function index()
    {
        $dormListings = DormListing::where('status', 'Available')
            ->with(['owner', 'images'])
            ->latest()
            ->get();

        return view('dorms.index', compact('dormListings'));
    }

    /*
    | SINGLE VIEW
    */
    public function show($id)
    {
        $dorm = DormListing::with(['owner', 'images'])->findOrFail($id);
        return view('dorms.show', compact('dorm'));
    }

    /*
    | SEARCH
    */
    public function search(Request $request)
    {
        $query = DormListing::with(['owner', 'images']);

        if ($request->filled('search')) {
            $query->where('street', 'like', "%{$request->search}%");
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        return view('dorms.index', [
            'dormListings' => $query->where('status', 'Available')->latest()->get()
        ]);
    }

    /*
    | SAVE
    */
    public function save($id)
    {
        DormListing::findOrFail($id);

        SavedListing::firstOrCreate([
            'user_id' => Auth::id(),
            'dorm_listing_id' => $id,
        ]);

        return back()->with('success', 'Dorm saved successfully!');
    }

    /*
    | UNSAVE
    */
    public function unsave($id)
    {
        SavedListing::where('user_id', Auth::id())
            ->where('dorm_listing_id', $id)
            ->delete();

        return back()->with('success', 'Removed from saved list!');
    }

    /*
    | COMPARE
    */
    public function compare(Request $request)
    {
        $ids = array_filter(explode(',', $request->query('ids', '')));

        return view('student.dorms.compare', [
            'dormListings' => DormListing::with(['owner', 'images'])
                ->whereIn('id', $ids)
                ->get()
        ]);
    }

    /*
    | MAP
    */
    public function map()
    {
        return view('student.dorms.map', [
            'dormListings' => DormListing::where('status', 'Available')
                ->with(['owner', 'images'])
                ->get()
        ]);
    }
}