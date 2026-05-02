namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\DormListing;
use App\Models\Message;
use App\Models\VisitSchedule;
use App\Models\Review;
use App\Models\Notification;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        $owner = Auth::user();

        // listings
        $listings = DormListing::where('owner_id', $owner->id)->get();

        // inquiries (messages)
        $inquiries = Message::whereHas('listing', function ($q) use ($owner) {
            $q->where('owner_id', $owner->id);
        })->get();

        // visits
        $visits = VisitSchedule::whereHas('dormListing', function ($q) use ($owner) {
            $q->where('owner_id', $owner->id);
        })->get();

        // reviews
        $reviews = Review::whereHas('dormListing', function ($q) use ($owner) {
            $q->where('owner_id', $owner->id);
        })->get();

        // notifications
        $notifications = Notification::where('user_id', $owner->id)
            ->latest()
            ->take(10)
            ->get();

        // STATS
        $activeListings = $listings->count();
        $avgRating = $reviews->count()
            ? round($reviews->avg('rating'), 1)
            : 0;

        return view('owner.dashboard', compact(
            'owner',
            'listings',
            'inquiries',
            'visits',
            'reviews',
            'notifications',
            'activeListings',
            'avgRating'
        ));
    }
}