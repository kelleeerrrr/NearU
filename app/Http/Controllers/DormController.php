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
    /*
    |----------------------------------------
    | VISIT SCHEDULING
    |----------------------------------------
    */
    public function scheduleVisit(Request $request, $id)
    {
        if (Auth::user()->user_type !== 'student') {
            return response()->json([
                'success' => false,
                'message' => 'Only students can schedule visits.'
            ], 403);
        }

        $request->validate([
            'date'    => 'required|date|after_or_equal:today',
            'time'    => 'required',
            'notes'   => 'nullable|string|max:500',
        ]);

        $dorm = DormListing::with('owner')->findOrFail($id);

        if ($dorm->owner_id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot schedule a visit to your own listing.'
            ], 403);
        }

        $exists = VisitSchedule::where('user_id', Auth::id())
            ->where('dorm_listing_id', $dorm->id)
            ->where('visit_date', $request->date)
            ->whereIn('status', ['Pending', 'Confirmed'])
            ->first();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'You already scheduled a visit on this date.'
            ]);
        }

        $visit = VisitSchedule::create([
            'user_id' => Auth::id(),
            'dorm_listing_id' => $dorm->id,
            'visit_date' => $request->date,
            'visit_time' => $request->time,
            'notes' => $request->notes,
            'status' => 'Pending',
        ]);

        if ($dorm->owner) {
            $dorm->owner->notify(new \App\Notifications\NewVisitScheduled($visit));
        }

        return response()->json([
            'success' => true,
            'message' => 'Visit request sent to owner',
            'visit' => $visit
        ]);
    }

    /*
    |----------------------------------------
    | SHOW LISTING
    |----------------------------------------
    */
    public function show($id)
    {
        $listing = DormListing::with(['images', 'reviews.user'])->findOrFail($id);

        return view('student.dorms.show', compact('listing'));
    }

    /*
    |----------------------------------------
    | SAVE / UNSAVE
    |----------------------------------------
    */
    public function toggleSave(Request $request, $id)
    {
        $userId = auth()->id();

        if (!$userId) {
            return response()->json(['error' => 'unauthenticated'], 401);
        }

        $saved = SavedListing::where('user_id', $userId)
            ->where('dorm_listing_id', $id)
            ->first();

        if ($saved) {
            $saved->delete();

            return response()->json([
                'saved' => false,
                'message' => 'Removed'
            ]);
        }

        SavedListing::create([
            'user_id' => $userId,
            'dorm_listing_id' => $id
        ]);

        return response()->json([
            'saved' => true,
            'message' => 'Saved'
        ]);
    }

    /*
    |----------------------------------------
    | REVIEWS
    |----------------------------------------
    */
    public function storeReview(Request $request, $dormId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $existing = Review::where('user_id', Auth::id())
            ->where('dorm_listing_id', $dormId)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You already reviewed this listing.'
            ]);
        }

        $review = Review::create([
            'user_id' => Auth::id(),
            'dorm_listing_id' => $dormId,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        $avg = Review::where('dorm_listing_id', $dormId)->avg('rating');
        $count = Review::where('dorm_listing_id', $dormId)->count();

        DormListing::find($dormId)->update([
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
        return Review::with('user')
            ->where('dorm_listing_id', $dormId)
            ->latest()
            ->get()
            ->map(function ($r) {
                return [
                    'user_name' => $r->user->name,
                    'rating' => $r->rating,
                    'comment' => $r->comment,
                    'created_at' => $r->created_at->format('M d, Y'),
                    'is_verified' => $r->user->is_verified ?? false,
                ];
            });
    }

    /*
    |----------------------------------------
    | CREATE LISTING
    |----------------------------------------
    */
    public function store(Request $request)
    {
        if (Auth::user()->verification_status !== 'approved') {
            return redirect()->route('owner.dashboard')
                ->with('error', 'You must be verified to create listings.');
        }

        $request->validate([
            'street' => 'required|string|max:255',
            'price' => 'required|numeric',
            'type' => 'required|string',
            'photos' => 'array|max:10',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $dorm = DormListing::create([
            'owner_id' => Auth::id(),
            'street' => $request->street,
            'price' => $request->price,
            'type' => $request->type,
            'gender_policy' => $request->gender_policy,
            'walk_minutes' => (int) $request->walk_minutes,
            'bathroom' => $request->bathroom,
            'furnishings' => json_encode($request->furnishings ?? []),
            'appliances' => json_encode($request->appliances ?? []),
            'bills_included' => json_encode($request->bills ?? []),
            'curfew' => is_numeric($request->curfew) ? $request->curfew . 'PM' : $request->curfew,
            'wifi' => $request->has('wifi') ? 1 : 0,
            'pets' => $request->has('pets') ? 1 : 0,
            'nearby_landmarks' => $request->nearby_landmarks,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'available',
        ]);

        /*
        |----------------------------------------
        | IMAGE UPLOAD (MULTIPLE + COVER LOGIC)
        |----------------------------------------
        */
        if ($request->hasFile('photos')) {
            $files = $request->file('photos');

            foreach ($files as $index => $file) {
                $path = $file->store('dorms', 'public');

                DormListingImage::create([
                    'dorm_listing_id' => $dorm->id,
                    'path' => $path,
                    'is_cover' => $index === 0, // FIRST IMAGE = COVER
                ]);
            }
        }

        return redirect()->route('owner.listings.index')
            ->with('success', 'Listing created successfully!');
    }

    /*
    |----------------------------------------
    | STUDENT HOME
    |----------------------------------------
    */
    public function indexStudent()
    {
        $dormListings = DormListing::where('status', 'Available')
            ->with(['owner', 'images'])
            ->latest()
            ->get();

        $dormsDataJson = $dormListings->map(function ($dorm) {

            // ✅ always use cover first
            $cover = $dorm->images->where('is_cover', true)->first()
                ?? $dorm->images->first();

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
    |----------------------------------------
    | PUBLIC LIST
    |----------------------------------------
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
    |----------------------------------------
    | SEARCH
    |----------------------------------------
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
            'dormListings' => $query->where('status', 'available')->latest()->get()
        ]);
    }

    /*
    |----------------------------------------
    | SAVE / UNSAVE
    |----------------------------------------
    */
    public function save($id)
    {
        SavedListing::firstOrCreate([
            'user_id' => Auth::id(),
            'dorm_listing_id' => $id,
        ]);

        return back()->with('success', 'Saved successfully!');
    }

    public function unsave($id)
    {
        SavedListing::where('user_id', Auth::id())
            ->where('dorm_listing_id', $id)
            ->delete();

        return back()->with('success', 'Removed successfully!');
    }

    /*
    |----------------------------------------
    | COMPARE
    |----------------------------------------
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
    |----------------------------------------
    | MAP
    |----------------------------------------
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