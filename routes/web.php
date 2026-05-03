<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Notification;
use App\Models\Message;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DormController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\OwnerDashboardController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

    Route::get('/', function () {

        if (auth()->check()) {
            return auth()->user()->user_type === 'owner'
                ? redirect()->route('owner.dashboard')
                : redirect()->route('student.home');
        }

        return redirect()->route('login');
    });
/*
|--------------------------------------------------------------------------
| GUEST AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', fn () => view('auth.login'))->name('login');

    Route::post('/login', function (Request $request) {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return Auth::user()->user_type === 'owner'
                ? redirect()->route('owner.dashboard')
                : redirect()->route('student.home');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    })->name('login.post');

    Route::get('/register', fn () => view('auth.register'))->name('register');

    Route::post('/register', function (Request $request) {

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'user_type' => 'required|in:student,owner',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'user_type' => $data['user_type'],
            'password' => Hash::make($data['password']),
            'verification_status' => 'not_verified',
        ]);

        return redirect()->route('login')
            ->with('success', 'Account created successfully.');
    })->name('register.post');
});

    Route::post('/dorms/{id}/reviews', [DormController::class, 'storeReview']);
    Route::get('/dorms/{id}/reviews', [DormController::class, 'getReviews']);

    Route::get('/student/dorms/compare', [DormController::class, 'compare'])
    ->name('dorms.compare');
/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/student/home', [DormController::class, 'indexStudent'])
        ->name('student.home');

    Route::get('/saved', function () {

        $savedListings = \App\Models\SavedListing::with('listing')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('student.saved', compact('savedListings'));

    })->name('saved.listings');
    
    Route::get('/student/map', [DormController::class, 'map'])
        ->name('student.map');

    Route::get('/checklist', function () {
        return view('student.checklist');
    })->name('checklist');

    Route::post('/profile/photo', function (Request $request) {

        $request->validate([
            'photo' => 'required|image|max:2048',
        ]);

        $user = auth()->user();

        // delete old photo if exists
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        // store new photo
        $path = $request->file('photo')->store('profile_photos', 'public');

        $user->update([
            'photo' => $path,
        ]);

        return response()->json([
            'success' => true,
            'photo_url' => asset('storage/' . $path)
        ]);

    })->name('profile.photo.update');

    /*
    |--------------------------------------------------------------------------
    | VISITS (STUDENT)
    |--------------------------------------------------------------------------
    */
Route::get('/visits', function () {

    $visits = \App\Models\VisitSchedule::with(['dormListing.owner'])
        ->where('user_id', auth()->id())
        ->latest()
        ->get();

    return view('student.visits', compact('visits'));

})->name('visit.schedules')->middleware('auth');

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', fn () => view('student.profile', ['user' => auth()->user()]))
        ->name('profile');

    Route::put('/profile', function (Request $request) {

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        auth()->user()->update($data);

        return back()->with('success', 'Profile updated successfully.');

    })->name('profile.update');

    /*
    |--------------------------------------------------------------------------
    | OWNER DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/owner/dashboard', [OwnerDashboardController::class, 'index'])
        ->name('owner.dashboard');

    /*
    |--------------------------------------------------------------------------
    | OWNER INQUIRIES
    |--------------------------------------------------------------------------
    */
    Route::get('/owner/inquiries', function () {

        $messages = Message::with('listing')
            ->where('receiver_id', auth()->id())
            ->latest()
            ->get();

        $grouped = $messages->groupBy('listing_id');

        return view('owner.inquiries.index', compact('grouped'));

    })->name('owner.inquiries.index');

    /*
    |--------------------------------------------------------------------------
    | OWNER VERIFICATION
    |--------------------------------------------------------------------------
    */
    Route::get('/owner/verification', fn () => view('owner.verification.form'))
        ->name('owner.verification.form');

    Route::post('/owner/verification/upload', [VerificationController::class, 'upload'])
        ->name('owner.verification.upload');

    Route::post('/owner/verification/submit', [VerificationController::class, 'submit'])
        ->name('owner.verification.submit');

    /*
    |--------------------------------------------------------------------------
    | OWNER LISTINGS
    |--------------------------------------------------------------------------
    */
    Route::prefix('owner/listings')->name('owner.listings.')->group(function () {

        /*
        |-----------------------------------------
        | INDEX (LISTINGS PAGE)
        | URL: /owner/listings
        |-----------------------------------------
        */
        Route::get('/', function () {

            $dormListings = \App\Models\DormListing::with('images')
                ->where('owner_id', auth()->id())
                ->latest()
                ->get();

            return view('owner.listings.index', compact('dormListings'));
        })->name('index');


        /*
        |-----------------------------------------
        | CREATE PAGE
        | URL: /owner/listings/create
        |-----------------------------------------
        */
        Route::get('/create', fn () => view('owner.listings.create'))
            ->name('create');


        /*
        |-----------------------------------------
        | STORE LISTING
        | URL: POST /owner/listings
        |-----------------------------------------
        */
        Route::post('/', [\App\Http\Controllers\DormController::class, 'store'])
            ->name('store');

        /*
        |-----------------------------------------
        | EDIT
        | URL: /owner/listings/{id}/edit
        |-----------------------------------------
        */
        Route::get('/{id}/edit', function ($id) {

            $listing = \App\Models\DormListing::with('images')
                ->where('owner_id', auth()->id())
                ->findOrFail($id);

            return view('owner.listings.edit', compact('listing'));

        })->name('edit');


        /*
        |-----------------------------------------
        | UPDATE
        |-----------------------------------------
        */
        Route::put('/{id}', function (Request $request, $id) {

            $listing = \App\Models\DormListing::where('owner_id', auth()->id())
                ->findOrFail($id);

            $listing->update($request->only([
                'street',
                'price',
                'type',
                'latitude',
                'longitude',
            ]));

            return redirect()
                ->route('owner.listings.index')
                ->with('success', 'Listing updated successfully');

        })->name('update');


        /*
        |-----------------------------------------
        | DELETE
        |-----------------------------------------
        */
        Route::delete('/{id}', function ($id) {

            $listing = \App\Models\DormListing::where('owner_id', auth()->id())
                ->with('images')
                ->findOrFail($id);

            foreach ($listing->images as $image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($image->path);
                $image->delete();
            }

            $listing->delete();

            return back()->with('success', 'Listing deleted successfully');

        })->name('delete');
    });
    /*
    |--------------------------------------------------------------------------
    | OWNER ACCOUNT
    |--------------------------------------------------------------------------
    */
    Route::get('/owner/account', fn () => view('owner.account'))
        ->name('owner.account');

    /*
    |--------------------------------------------------------------------------
    | MESSAGES
    |--------------------------------------------------------------------------
    */
    Route::get('/messages', [MessageController::class, 'index'])
        ->name('messages.index');

    /*
    |--------------------------------------------------------------------------
    | ✅ OWNER VISITS (NEW)
    |--------------------------------------------------------------------------
    */
    Route::get('/owner/visits', function () {

        $visits = \App\Models\VisitSchedule::with(['user', 'dormListing'])
            ->whereHas('dormListing', function ($q) {
                $q->where('owner_id', auth()->id());
            })
            ->latest()
            ->get();

        return view('owner.visits.index', compact('visits'));

    })->name('owner.visits.index');

    /*
    |--------------------------------------------------------------------------
    | ✅ OWNER STATISTICS (NEW)
    |--------------------------------------------------------------------------
    */
    Route::get('/owner/statistics', function () {

        $ownerId = auth()->id();

        $listings = \App\Models\DormListing::where('owner_id', $ownerId)->get();

        $visits = \App\Models\VisitSchedule::whereHas('dormListing', function ($q) use ($ownerId) {
            $q->where('owner_id', $ownerId);
        })->get();

        $messages = Message::where('receiver_id', $ownerId)->get();

        return view('owner.statistics.index', compact('listings', 'visits', 'messages'));

    })->name('owner.statistics.index');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/admin/owner-verifications', function () {

    abort_unless(auth()->user()->user_type === 'admin', 403);

    $owners = \App\Models\User::where('user_type', 'owner')->latest()->get();

    return view('admin.owner-verifications.index', compact('owners'));

})->name('admin.owner-verifications.index');

/*
|--------------------------------------------------------------------------
| DEBUG
|--------------------------------------------------------------------------
*/

Route::get('/debug-user', fn () => auth()->user()->user_type);