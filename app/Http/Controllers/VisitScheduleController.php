<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VisitSchedule;

class VisitScheduleController extends Controller
{
    /**
     * OWNER: view visits
     */
    public function index()
    {
        $ownerId = Auth::id();

        $visits = VisitSchedule::with(['user', 'dormListing'])
            ->whereHas('dormListing', function ($q) use ($ownerId) {
                $q->where('owner_id', $ownerId);
            })
            ->latest()
            ->get();

        return view('owner.visits.index', compact('visits'));
    }

    /**
     * STORE visit (FIXED for your JS)
     */
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
            'dorm_id' => 'required|exists:dorm_listings,id',
            'date'    => 'required|date',
            'time'    => 'required',
            'notes'   => 'nullable|string',
        ]);

        $visit = VisitSchedule::create([
            'user_id'         => Auth::id(),
            'dorm_listing_id' => $request->dorm_id,
            'visit_date'      => $request->date,
            'visit_time'      => $request->time,
            'notes'           => $request->notes,
            'status'          => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Visit scheduled successfully',
            'visit'   => $visit
        ]);
    }

    /**
     * SHOW
     */
    public function show($id)
    {
        $visit = VisitSchedule::with(['user', 'dormListing'])->findOrFail($id);
        return view('owner.visits.show', compact('visit'));
    }

    /**
     * UPDATE STATUS
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed'
        ]);

        $visit = VisitSchedule::findOrFail($id);
        $visit->update(['status' => $request->status]);

        return back()->with('success', 'Visit updated successfully.');
    }

    /**
     * DELETE
     */
    public function destroy($id)
    {
        VisitSchedule::findOrFail($id)->delete();

        return back()->with('success', 'Visit cancelled successfully.');
    }
}