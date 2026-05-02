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

Route::get('/', [HomeController::class, 'index'])->name('home');

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

    /*
    |--------------------------------------------------------------------------
    | VISITS (STUDENT)
    |--------------------------------------------------------------------------
    */
    Route::get('/visits', function () {

        $visits = \App\Models\VisitSchedule::with('dormListing')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('student.visits', compact('visits'));

    })->name('visit.schedules');

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

        Route::get('/', function () {

            $dormListings = \App\Models\DormListing::where('owner_id', auth()->id())
                ->latest()
                ->get();

            return view('owner.listings.index', compact('dormListings'));
        })->name('index');

        Route::get('/create', fn () => view('owner.listings.create'))
            ->name('create');

        Route::post('/', function (Request $request) {

            $listing = \App\Models\DormListing::create([
                'owner_id' => auth()->id(),
                'street' => $request->street,
                'price' => $request->price,
                'type' => $request->type,
                'status' => 'Available',
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            Notification::create([
                'user_id' => auth()->id(),
                'title' => 'Listing Published',
                'message' => "Your listing '{$listing->street}' is now live.",
                'is_read' => false,
            ]);

            return redirect()->route('owner.listings.index');

        })->name('store');
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

        // LISTINGS
        $listings = \App\Models\DormListing::where('owner_id', $ownerId)->get();

        $totalListings = $listings->count();
        $activeListings = $listings->where('status', 'Available')->count();
        $takenListings = $listings->where('status', '!=', 'Available')->count();

        // VISITS
        $visits = \App\Models\VisitSchedule::whereHas('dormListing', function ($q) use ($ownerId) {
            $q->where('owner_id', $ownerId);
        })->get();

        // MESSAGES
        $messages = \App\Models\Message::where('receiver_id', $ownerId)->get();

        $totalMessages = $messages->count();
        $unreadMessages = $messages->where('is_read', 0)->count();

        return view('owner.statistics.index', compact(
            'listings',
            'visits',
            'messages',
            'totalMessages',
            'unreadMessages',
            'totalListings',
            'activeListings',
            'takenListings'
        ));

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