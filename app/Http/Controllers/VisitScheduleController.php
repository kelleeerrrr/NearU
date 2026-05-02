<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VisitSchedule;

class VisitScheduleController extends Controller
{
    public function index()
    {
        $owner = Auth::user();

        $visits = VisitSchedule::where('user_id', $owner->id)
            ->with('dormListing')
            ->latest()
            ->get();

        return view('owner.visits.index', compact('visits'));
    }

    public function show($id)
    {
        $visit = VisitSchedule::with(['user', 'dormListing'])->findOrFail($id);

        return view('owner.visits.show', compact('visit'));
    }

    public function updateStatus(Request $request, $id)
    {
        $visit = VisitSchedule::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed'
        ]);

        $visit->status = $request->status;
        $visit->save();

        return back()->with('success', 'Visit updated successfully.');
    }
}