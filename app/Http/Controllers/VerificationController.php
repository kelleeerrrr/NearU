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
            'file' => 'required|file|max:5120',
            'type' => 'required|string'
        ]);

        // Additional validation to ensure file is readable
        $uploadedFile = $request->file('file');
        if (!$uploadedFile || !$uploadedFile->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid file upload.'
            ], 400);
        }

        $user = Auth::user();

        // ❌ BLOCK if already under review or verified
        if ($user->verification_status !== 'not_verified') {
            return response()->json([
                'success' => false,
                'message' => 'Your verification is already under review.'
            ], 403);
        }

        // 📁 STORE FILE
        $path = $request->file('file')->store('verifications', 'public');

        // 💾 SAVE / UPDATE DOCUMENT
        VerificationDocument::updateOrCreate(
            [
                'user_id' => $user->id,
                'type' => $request->type,
            ],
            [
                'file_path' => $path
            ]
        );

        // 🔥 CHECK IF ALL REQUIRED DOCUMENTS ARE UPLOADED
        $requiredTypes = ['id', 'selfie', 'birth', 'property', 'utility', 'barangay'];

        $uploadedTypes = VerificationDocument::where('user_id', $user->id)
            ->pluck('type')
            ->toArray();

        $complete = count(array_intersect($requiredTypes, $uploadedTypes)) === count($requiredTypes);

        // 🚀 AUTO CHANGE STATUS TO UNDER REVIEW
        if ($complete) {
            $user->update([
                'verification_status' => 'under_review'
            ]);
        }

        return response()->json([
            'success' => true,
            'path' => $path,
            'status' => $user->fresh()->verification_status,
            'complete' => $complete
        ]);
    }

    /**
     * OPTIONAL (no longer required but kept for safety)
     */
    public function submit()
    {
        $user = Auth::user();

        if ($user->verification_status !== 'not_verified') {
            return back()->with('error', 'Already submitted.');
        }

        $requiredTypes = ['id', 'selfie', 'birth', 'property', 'utility', 'barangay'];

        $uploadedTypes = VerificationDocument::where('user_id', $user->id)
            ->pluck('type')
            ->toArray();

        if (count(array_intersect($requiredTypes, $uploadedTypes)) !== 6) {
            return back()->with('error', 'Please complete all required documents.');
        }

        $user->update([
            'verification_status' => 'under_review'
        ]);

        return redirect()->route('owner.dashboard')
            ->with('success', 'Your account is now under review!');
    }
}