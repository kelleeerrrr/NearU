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
        if (Auth::user()->user_type !== 'student') {
            return response()->json([
                'success' => false,
                'message' => 'Only students can schedule visits.'
            ], 403);
        }

        $request->validate([
            'dorm_listing_id' => 'required|exists:dorm_listings,id',
            'visit_date'      => 'required|date|after_or_equal:today',
            'visit_time'      => 'required',
            'notes'           => 'nullable|string|max:500',
        ]);

        $listing = \App\Models\DormListing::findOrFail($request->dorm_listing_id);

        if ($listing->owner_id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot schedule a visit for your own listing.'
            ], 403);
        }

        $existing = VisitSchedule::where('user_id', Auth::id())
            ->where('dorm_listing_id', $listing->id)
            ->where('visit_date', $request->visit_date)
            ->whereIn('status', ['Pending', 'Confirmed'])
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You already scheduled a visit on this date.'
            ]);
        }

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
            'status' => 'required|in:Pending,Confirmed,Approved,Completed,Cancelled'
        ]);

        $status = $request->status === 'Approved' ? 'Confirmed' : $request->status;

        $visit = VisitSchedule::with('dormListing')
            ->whereHas('dormListing', fn ($q) => $q->where('owner_id', Auth::id()))
            ->findOrFail($id);

        if (in_array($visit->status, ['Completed', 'Cancelled'])) {
            return back()->with('error', 'This visit cannot be updated.');
        }

        $visit->update([
            'status' => $status,
            'cancelled_at' => $status === 'Cancelled' ? now() : null,
        ]);

        // ✅ NOTIFICATIONS: Notify student of status change
        $statusMessages = [
            'Confirmed' => 'approved your visit request',
            'Completed' => 'marked your visit as completed',
            'Cancelled' => 'cancelled your visit'
        ];

        if (isset($statusMessages[$status])) {
            \App\Models\Notification::create([
                'user_id' => $visit->user_id,
                'dorm_listing_id' => $visit->dorm_listing_id,
                'title' => 'Visit ' . ucfirst($status),
                'message' => $visit->dormListing->street . ': ' . Auth::user()->name . ' ' . $statusMessages[$status],
                'type' => 'visit_' . strtolower($status),
                'is_read' => false,
            ]);
        }

        return back()->with('success', 'Visit updated successfully.');
    }

    public function cancel($id)
    {
        $visit = VisitSchedule::with('dormListing')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['Pending', 'Confirmed'])
            ->firstOrFail();

        $visit->update([
            'status' => 'Cancelled',
            'cancelled_at' => now(),
        ]);

        \App\Models\Notification::create([
            'user_id' => $visit->dormListing->owner_id,
            'dorm_listing_id' => $visit->dorm_listing_id,
            'title' => 'Visit Cancelled',
            'message' => Auth::user()->name . ' cancelled the visit request for ' . $visit->dormListing->street,
            'type' => 'visit_cancelled',
            'is_read' => false,
        ]);

        return back()->with('success', 'Visit cancelled successfully.');
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