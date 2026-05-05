<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Notification;
use App\Models\Message;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DormController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\OwnerDashboardController;
use App\Http\Controllers\VisitScheduleController;
use App\Http\Controllers\Admin\OwnerVerificationController;
use App\Http\Controllers\StatisticsController;

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

            $user = Auth::user();

            // ✅ ALL OWNERS CAN LOGIN - NO VERIFICATION RESTRICTIONS
            return $user->user_type === 'owner'
                ? redirect()->route('owner.dashboard')
                : redirect()->route('student.home');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    })->name('login.post');
});

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

    Route::middleware(['auth'])->group(function () {

        // OWNER VISITS
        Route::get('/owner/visits', function () {

            // ✅ VERIFICATION CHECK: Only approved owners can access visits
            if (auth()->user()->verification_status !== 'approved') {
                return redirect()->route('owner.dashboard')
                    ->with('error', 'You must be verified to access visit requests. Please complete your verification first.');
            }

            return app(VisitScheduleController::class)->index();
        })
            ->name('owner.visits.index');

        // STUDENT VISITS (optional but recommended)
        Route::get('/visits', [VisitScheduleController::class, 'studentIndex'])
            ->name('visits.student');

        Route::post('/visits', [VisitScheduleController::class, 'store'])
            ->name('visits.store');

        Route::post('/owner/visits/{id}/status', [VisitScheduleController::class, 'updateStatus'])
        ->name('owner.visits.status.update');


    });

    Route::get('/student/home', [DormController::class, 'indexStudent'])
        ->name('student.home');

    Route::get('/saved', function () {

        $savedListings = \App\Models\SavedListing::with('dormListing')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('student.saved', compact('savedListings'));

    })->name('saved.listings');

    Route::post('/dorms/{id}/save', [DormController::class, 'toggleSave']);
    
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

    Route::get('/visits', [VisitScheduleController::class, 'studentIndex'])
        ->name('visits.index');
    Route::post('/visits', [VisitScheduleController::class, 'store'])->name('visits.store');
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
        Route::get('/', function (Request $request) {

            $query = \App\Models\DormListing::with('images')
                ->where('owner_id', auth()->id());

            // FILTER
            if ($request->status) {
                $query->where('status', $request->status);
            }

            $dormListings = $query->latest()->get();

            return view('owner.listings.index', compact('dormListings'));

        })->name('index');


        /*
        |-----------------------------------------
        | CREATE PAGE
        | URL: /owner/listings/create
        |-----------------------------------------
        */
        Route::get('/create', function () {

            // ✅ VERIFICATION CHECK: Only approved owners can create listings
            if (auth()->user()->verification_status !== 'approved') {
                return redirect()->route('owner.dashboard')
                    ->with('error', 'You must be verified to create listings. Please complete your verification first.');
            }

            return view('owner.listings.create');
        })->name('create');


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

            // ✅ VERIFICATION CHECK: Only approved owners can edit listings
            if (auth()->user()->verification_status !== 'approved') {
                return redirect()->route('owner.dashboard')
                    ->with('error', 'You must be verified to edit listings. Please complete your verification first.');
            }

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

            // ✅ VERIFICATION CHECK: Only approved owners can update listings
            if (auth()->user()->verification_status !== 'approved') {
                return redirect()->route('owner.dashboard')
                    ->with('error', 'You must be verified to update listings. Please complete your verification first.');
            }

            $listing = \App\Models\DormListing::where('owner_id', auth()->id())
                ->findOrFail($id);

            $listing->update($request->only([
                'street',
                'price',
                'type',
                'latitude',
                'longitude',
                'status'
            ]));

            return redirect()
                ->route('owner.listings.index')
                ->with('success', 'Listing updated successfully');

        })->name('update');
        Route::post('/{id}/available', function ($id) {

            $listing = \App\Models\DormListing::where('owner_id', auth()->id())
                ->findOrFail($id);

            $listing->status = 'available';
            $listing->save();

            return back();

        })->name('available');


        Route::post('/{id}/unavailable', function ($id) {

            $listing = \App\Models\DormListing::where('owner_id', auth()->id())
                ->findOrFail($id);

            $listing->status = 'unavailable';
            $listing->save();

            return back();

        })->name('unavailable');

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
    Route::post('/messages/send/{listingId}/{userId}', [MessageController::class, 'send'])->name('messages.send');
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{listingId}/{userId}', [MessageController::class, 'show'])->name('messages.show');

// STUDENT
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    // OWNER
    Route::get('/owner/notifications', [NotificationController::class, 'owner'])
        ->name('notifications.owner');

    // SHARED ACTIONS
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])
        ->name('notifications.markAllRead');

    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])
        ->name('notifications.read');
    Route::get('/owner/inquiries', [MessageController::class, 'ownerInquiries'])
        ->name('owner.inquiries.index');

    Route::get('/owner/inquiries/{listingId}/{userId}', [MessageController::class, 'ownerConversation'])
        ->name('owner.inquiries.show');

    Route::post('/owner/inquiries/{listingId}/{userId}', [MessageController::class, 'ownerReply'])
        ->name('owner.inquiries.reply');
    /*
    |--------------------------------------------------------------------------
    | ✅ OWNER STATISTICS (NEW)
    |--------------------------------------------------------------------------
    */
    Route::get('/owner/statistics', [StatisticsController::class, 'index'])
    ->name('owner.statistics.index');

});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware('auth')->group(function () {

    // LIST
    Route::get('/owner-verifications', [OwnerVerificationController::class, 'index'])
        ->name('admin.owner-verifications.index');

    // REVIEW
    Route::get('/owner-verifications/{id}/review', [OwnerVerificationController::class, 'review'])
        ->name('admin.owner-verifications.review');

    // APPROVE
    Route::post('/owner-verifications/{id}/approve', [OwnerVerificationController::class, 'approve'])
        ->name('admin.owner-verifications.approve');

    // REJECT
    Route::post('/owner-verifications/{id}/reject', [OwnerVerificationController::class, 'reject'])
        ->name('admin.owner-verifications.reject');
});

/*
|--------------------------------------------------------------------------
| DEBUG
|--------------------------------------------------------------------------
*/

Route::get('/debug-user', fn () => auth()->user()->user_type);