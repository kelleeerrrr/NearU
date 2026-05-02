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
    | STUDENT HOME
    */
    public function indexStudent()
    {
        $dormListings = DormListing::where('status', 'Available')
            ->with(['owner', 'images']) // ✅ include images if available
            ->latest()
            ->get();

        $dormsDataJson = $dormListings->map(function ($dorm) {

            // ✅ SAFE IMAGE HANDLING (NO CRASH)
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
    | SCHEDULE VISIT
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
    | COMPARE
    */
    public function compare(Request $request)
    {
        $ids = array_filter(explode(',', $request->query('ids', '')));

        return view('dorms.compare', [
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