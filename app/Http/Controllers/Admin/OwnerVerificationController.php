<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class OwnerVerificationController extends Controller
{
    // 📋 LIST OWNERS UNDER REVIEW
    public function index()
    {
        abort_unless(Auth::user()->user_type === 'admin', 403);

        $owners = User::with('verificationDocuments')
            ->where('user_type', 'owner')
            ->where('verification_status', 'under_review')
            ->latest()
            ->get();

        return view('admin.owner-verifications.index', compact('owners'));
    }

    // ✅ APPROVE OWNER
    public function approve($id)
    {
        abort_unless(Auth::user()->user_type === 'admin', 403);

        $user = User::findOrFail($id);

        $user->update([
            'verification_status' => 'approved'
        ]);

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Account Approved',
            'message' => 'Your owner account has been approved.',
            'is_read' => false,
        ]);

        return back()->with('success', 'Owner approved successfully.');
    }

    public function review($id)
    {
        abort_unless(Auth::user()->user_type === 'admin', 403);

        $owner = User::with('verificationDocuments')->findOrFail($id);

        return view('admin.owner-verifications.review', compact('owner'));
    }

    // ❌ REJECT OWNER
    public function reject($id)
    {
        abort_unless(Auth::user()->user_type === 'admin', 403);

        $user = User::findOrFail($id);

        $user->update([
            'verification_status' => 'not_verified'
        ]);

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Verification Rejected',
            'message' => 'Your submitted documents were rejected. Please re-upload.',
            'is_read' => false,
        ]);

        return back()->with('error', 'Owner rejected successfully.');
    }
}