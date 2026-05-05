<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VisitSchedule;

class VisitScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $visits = VisitSchedule::with(['user', 'dormListing'])
            ->whereHas('dormListing', fn ($q) => $q->where('owner_id', Auth::id()))
            ->latest()
            ->get();

        return view('owner.visits.index', compact('visits'));
    }

    public function studentIndex()
    {
        $visits = VisitSchedule::with('dormListing')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('student.visits', compact('visits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dorm_listing_id' => 'required|exists:dorm_listings,id',
            'visit_date'      => 'required|date',
            'visit_time'      => 'required',
            'notes'           => 'nullable|string',
        ]);

        $listing = \App\Models\DormListing::findOrFail($request->dorm_listing_id);

        VisitSchedule::create([
            'user_id'         => Auth::id(),
            'dorm_listing_id' => $request->dorm_listing_id,
            'visit_date'      => $request->visit_date,
            'visit_time'      => $request->visit_time,
            'notes'           => $request->notes,
            'status'          => 'Pending',
        ]);

        // ✅ ENHANCED NOTIFICATIONS: Include listing context
        \App\Models\Notification::create([
            'user_id' => $listing->owner_id,
            'dorm_listing_id' => $listing->id,
            'title' => 'New Visit Request',
            'message' => Auth::user()->name . ' requested a visit for ' . $listing->street,
            'type' => 'visit_request',
            'is_read' => false,
        ]);

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $visit = VisitSchedule::with(['user', 'dormListing'])
            ->whereHas('dormListing', fn ($q) => $q->where('owner_id', Auth::id()))
            ->findOrFail($id);

        return view('owner.visits.show', compact('visit'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Confirmed,Completed,Cancelled'
        ]);

        $visit = VisitSchedule::with('dormListing')
            ->whereHas('dormListing', fn ($q) => $q->where('owner_id', Auth::id()))
            ->findOrFail($id);

        $oldStatus = $visit->status;
        $visit->update(['status' => $request->status]);

        // ✅ NOTIFICATIONS: Notify student of status change
        $statusMessages = [
            'Confirmed' => 'confirmed your visit request',
            'Completed' => 'marked your visit as completed',
            'Cancelled' => 'cancelled your visit'
        ];

        if (isset($statusMessages[$request->status])) {
            \App\Models\Notification::create([
                'user_id' => $visit->user_id,
                'dorm_listing_id' => $visit->dorm_listing_id,
                'title' => 'Visit ' . ucfirst($request->status),
                'message' => $visit->dormListing->street . ': ' . Auth::user()->name . ' ' . $statusMessages[$request->status],
                'type' => 'visit_' . strtolower($request->status),
                'is_read' => false,
            ]);
        }

        return back()->with('success', 'Visit updated successfully.');
    }

    public function destroy($id)
    {
        $visit = VisitSchedule::where('user_id', Auth::id())
            ->orWhereHas('dormListing', fn ($q) => $q->where('owner_id', Auth::id()))
            ->findOrFail($id);

        $visit->delete();

        return back()->with('success', 'Visit cancelled successfully.');
    }
}