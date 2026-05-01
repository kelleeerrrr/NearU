<?php

namespace App\Http\Controllers;

use App\Models\DormListing;
use App\Models\SavedListing;
use App\Models\VisitSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DormController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STUDENT HOME (✅ FULLY FIXED)
    |--------------------------------------------------------------------------
    */
    public function indexStudent()
    {
        $dormListings = DormListing::where('status', 'Available')
            ->with('owner')
            ->latest()
            ->get();

        // ✅ FIX: Prepare JSON for JS (map, frontend usage)
        $dormsDataJson = $dormListings->map(function ($dorm) {

            $photos = is_array($dorm->photos)
                ? $dorm->photos
                : (json_decode($dorm->photos, true) ?? []);

            return [
                'id' => $dorm->id,
                'street' => $dorm->street,
                'price' => $dorm->price,
                'type' => $dorm->type,
                'status' => $dorm->status,
                'lat' => $dorm->latitude,
                'lng' => $dorm->longitude,
                'photo' => count($photos)
                    ? asset('storage/' . $photos[0])
                    : 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=400',
            ];

        });

        return view('student.home', [
            'dormListings' => $dormListings,
            'dormsDataJson' => $dormsDataJson->toJson()
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PUBLIC DORMS PAGE
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $dormListings = DormListing::where('status', 'Available')
            ->with('owner')
            ->latest()
            ->get();

        return view('dorms.index', compact('dormListings'));
    }

    /*
    |--------------------------------------------------------------------------
    | SINGLE DORM VIEW
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $dorm = DormListing::with('owner')->findOrFail($id);
        return view('dorms.show', compact('dorm'));
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH FILTER
    |--------------------------------------------------------------------------
    */
    public function search(Request $request)
    {
        $query = DormListing::with('owner');

        if ($request->filled('search')) {
            $query->where('street', 'like', '%' . $request->search . '%');
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

        $dormListings = $query
            ->where('status', 'Available')
            ->latest()
            ->get();

        return view('dorms.index', compact('dormListings'));
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE LISTING
    |--------------------------------------------------------------------------
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
    |--------------------------------------------------------------------------
    | UNSAVE LISTING
    |--------------------------------------------------------------------------
    */
    public function unsave($id)
    {
        SavedListing::where('user_id', Auth::id())
            ->where('dorm_listing_id', $id)
            ->delete();

        return back()->with('success', 'Dorm removed from saved list!');
    }

    /*
    |--------------------------------------------------------------------------
    | SCHEDULE VISIT
    |--------------------------------------------------------------------------
    */
    public function scheduleVisit(Request $request, $id)
    {
        $request->validate([
            'visit_date' => 'required|date|after:today',
            'visit_time' => 'required',
            'notes' => 'nullable|string|max:500',
        ]);

        VisitSchedule::create([
            'user_id' => Auth::id(),
            'dorm_listing_id' => $id,
            'visit_date' => $request->visit_date,
            'visit_time' => $request->visit_time,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Visit scheduled successfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | COMPARE LISTINGS
    |--------------------------------------------------------------------------
    */
    public function compare(Request $request)
    {
        $ids = array_filter(explode(',', $request->query('ids', '')));

        $dormListings = DormListing::with('owner')
            ->whereIn('id', $ids)
            ->get();

        return view('dorms.compare', compact('dormListings'));
    }

    /*
    |--------------------------------------------------------------------------
    | MAP VIEW
    |--------------------------------------------------------------------------
    */
    public function map()
    {
        $dormListings = DormListing::where('status', 'Available')
            ->with('owner')
            ->get();

        return view('student.dorms.map', compact('dormListings'));
    }
}