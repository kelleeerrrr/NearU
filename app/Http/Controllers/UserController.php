<?php

namespace App\Http\Controllers;

use App\Models\SavedListing;
use App\Models\VisitSchedule;
use App\Models\DormListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $savedListings = SavedListing::where('user_id', Auth::id())
            ->with('dormListing.owner')
            ->get();
        $visitSchedules = VisitSchedule::where('user_id', Auth::id())
            ->with('dormListing.owner')
            ->orderBy('visit_date')
            ->get();

        return view('student.profile', compact('user', 'savedListings', 'visitSchedules'));
    }

    public function saved()
    {
        $savedListings = SavedListing::where('user_id', Auth::id())
            ->with('dormListing.owner', 'dormListing.images')
            ->get();

        return view('student.saved', compact('savedListings'));
    }

    public function visits()
    {
        $visits = VisitSchedule::where('user_id', Auth::id())
            ->with('dormListing.owner')
            ->orderBy('visit_date')
            ->get();

        return view('student.visits', compact('visits'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        Auth::user()->update($request->only(['name', 'phone', 'email']));

        return back()->with('success', 'Profile updated successfully!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password changed successfully!');
    }

    // Method for updating profile photo
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|max:2048', // max 2MB
        ]);

        $user = Auth::user();

        $uploadedFile = $request->file('profile_photo');
        if ($request->hasFile('profile_photo') && $uploadedFile && $uploadedFile->isValid()) {
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                Storage::delete($user->profile_photo_path);
            }

            // Store new photo in 'public/profile-photos'
            $path = $request->file('profile_photo')->store('profile-photos', 'public');

            // Save path to user
            $user->update(['profile_photo_path' => $path]);
        }

        return back()->with('success', 'Profile photo updated successfully.');
    }
}