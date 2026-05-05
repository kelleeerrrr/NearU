<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DormListing;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function show($id)
    {
        // Find the owner (must be user_type = 'owner')
        $owner = User::where('id', $id)
            ->where('user_type', 'owner')
            ->firstOrFail();

        // Get all listings owned by this user with eager loading
        $listings = DormListing::where('owner_id', $owner->id)
            ->with(['images', 'reviews.user'])
            ->get();

        return view('owners.show', compact('owner', 'listings'));
    }
}