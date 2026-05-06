<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\DormListing;
use App\Models\Notification;

class DashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        
        // Get statistics
        $totalUsers = User::count();
        $totalStudents = User::where('user_type', 'student')->count();
        $totalOwners = User::where('user_type', 'owner')->count();
        $totalListings = DormListing::count();
        $pendingVerifications = User::where('user_type', 'owner')
            ->where('verification_status', 'under_review')
            ->count();
        
        // Get recent activity
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();
        $recentListings = DormListing::orderBy('created_at', 'desc')->take(5)->get();
        
        return view('admin.dashboard', compact(
            'admin',
            'totalUsers',
            'totalStudents',
            'totalOwners',
            'totalListings', 
            'pendingVerifications',
            'recentUsers',
            'recentListings'
        ));
    }

    public function profile()
    {
        $admin = Auth::user();
        
        return view('admin.profile', compact('admin'));
    }
}
