<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VerificationDocument;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'type' => 'required|string'
        ]);

        $user = Auth::user();

        // ❌ BLOCK IF ALREADY SUBMITTED OR VERIFIED
        if ($user->verification_status !== 'not_verified') {
            return response()->json([
                'success' => false,
                'message' => 'Your verification is already under review.'
            ], 403);
        }

        $path = $request->file('file')->store('verifications', 'public');

        VerificationDocument::updateOrCreate(
            [
                'user_id' => $user->id,
                'type' => $request->type,
            ],
            [
                'file_path' => $path
            ]
        );

        return response()->json([
            'success' => true,
            'path' => $path
        ]);
    }

    public function submit()
    {
        $user = Auth::user();

        // ❌ already submitted
        if ($user->verification_status !== 'not_verified') {
            return back()->with('error', 'Already submitted.');
        }

        // check required docs
        $required = 6;
        $count = VerificationDocument::where('user_id', $user->id)->count();

        if ($count < $required) {
            return back()->with('error', 'Please complete all required documents.');
        }

        // ✅ SET UNDER REVIEW
        $user->update([
            'verification_status' => 'under_review'
        ]);

        return redirect()->route('owner.dashboard')
            ->with('success', 'Submitted successfully. Your account is now under review.');
    }
}