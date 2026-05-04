<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DormListing;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Debug: Check authentication status
        \Log::info('HomeController index called', [
            'auth_check' => auth()->check(),
            'user_id' => auth()->id(),
            'user_type' => auth()->user()?->user_type,
            'session_id' => session()->getId()
        ]);

        // If user is not authenticated, show welcome screen
        if (!auth()->check()) {
            \Log::info('Showing welcome screen for guest');
            return view('welcome');
        }

        // If user is authenticated, show appropriate dashboard
        $user = auth()->user();
        
        if ($user->user_type === 'owner') {
            // Redirect owner to dashboard
            return redirect()->route('owner.dashboard');
        } else {
            // Show student home page
            $dormListings = DormListing::with('owner')
                ->where('status', 'Available')
                ->latest()
                ->get();

            // Pre-transform data for JS — NO arrow functions in Blade @json()
            $dormsData = $dormListings->map(function ($d) {
                $photos = is_array($d->photos)
                    ? $d->photos
                    : (json_decode($d->photos, true) ?? []);

                return [
                    'id'        => $d->id,
                    'type'      => $d->type,
                    'street'    => $d->street,
                    'price'     => $d->price,
                    'walk'      => $d->walk_minutes,
                    'gender'    => $d->gender_policy,
                    'status'    => $d->status,
                    'rating'    => 0,   // no reviews system yet
                    'revCnt'    => 0,   // no reviews system yet
                    'lat'       => $d->latitude,
                    'lng'       => $d->longitude,
                    'imgs'      => array_map(
                                       function ($p) { return asset('storage/' . $p); },
                                       $photos
                                   ),
                    'wifi'      => (bool) $d->wifi_included,
                    'pets'      => (bool) $d->pets_allowed,
                    'curfew'    => $d->curfew,
                    'ownerId'   => $d->owner?->id,
                    'ownerName' => $d->owner?->name,
                ];
            })->values();

            // Pre-encode to avoid Blade ParseError with | operator inside {!! !!}
            // on PHP 8.4 / Laravel 12
            $dormsDataJson = json_encode(
                $dormsData,
                JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE
            );

            return view('student.home', compact('dormListings', 'dormsData', 'dormsDataJson'));
        }
    }
}