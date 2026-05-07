<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DormListing;
use App\Models\Message;
use App\Models\VisitSchedule;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function users(Request $request)
    {
        // Date range filtering
        $dateFrom = $request->filled('date_from') ? $request->date_from : now()->subDays(30)->format('Y-m-d');
        $dateTo = $request->filled('date_to') ? $request->date_to : now()->format('Y-m-d');

        $query = User::whereDate('created_at', '>=', $dateFrom)
                    ->whereDate('created_at', '<=', $dateTo);

        $totalUsers = $query->count();
        $students = $query->where('user_type', 'student')->count();
        $owners = $query->where('user_type', 'owner')->count();
        $verifiedOwners = $query->where('user_type', 'owner')
            ->where('verification_status', 'approved')->count();

        $recentUsers = User::latest()->limit(10)->get();
        
        // User growth data for charts
        $userGrowth = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Monthly statistics
        $monthlyStats = User::selectRaw('EXTRACT(YEAR FROM created_at) as year, EXTRACT(MONTH FROM created_at) as month, COUNT(*) as count')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        // User type distribution
        $userTypeDistribution = [
            'students' => $students,
            'owners' => $owners,
            'admins' => User::where('user_type', 'admin')->count(),
        ];

        // Verification status distribution for owners
        $verificationDistribution = User::where('user_type', 'owner')
            ->selectRaw('verification_status, COUNT(*) as count')
            ->groupBy('verification_status')
            ->pluck('count', 'verification_status')
            ->toArray();

        return view('admin.reports.users', compact(
            'totalUsers', 'students', 'owners', 'verifiedOwners',
            'recentUsers', 'userGrowth', 'monthlyStats',
            'userTypeDistribution', 'verificationDistribution',
            'dateFrom', 'dateTo'
        ));
    }

    public function listings(Request $request)
    {
        // Date range filtering
        $dateFrom = $request->filled('date_from') ? $request->date_from : now()->subDays(30)->format('Y-m-d');
        $dateTo = $request->filled('date_to') ? $request->date_to : now()->format('Y-m-d');

        $query = DormListing::whereDate('created_at', '>=', $dateFrom)
                           ->whereDate('created_at', '<=', $dateTo);

        $totalListings = $query->count();
        $activeListings = $query->where('status', 'Available')->count();
        $inactiveListings = $query->where('status', 'Unavailable')->count();
        
        $recentListings = DormListing::with('owner')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->latest()
            ->limit(10)
            ->get();

        // Listings by status
        $listingsByStatus = $query->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        // Monthly listing statistics
        $monthlyListings = DormListing::selectRaw('EXTRACT(YEAR FROM created_at) as year, EXTRACT(MONTH FROM created_at) as month, COUNT(*) as count')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        // Top owners by listings
        $topOwners = User::select('users.name', 'users.email')
            ->selectRaw('COUNT(dorm_listings.id) as listing_count')
            ->join('dorm_listings', 'users.id', '=', 'dorm_listings.owner_id')
            ->where('users.user_type', 'owner')
            ->whereDate('dorm_listings.created_at', '>=', $dateFrom)
            ->whereDate('dorm_listings.created_at', '<=', $dateTo)
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('listing_count', 'desc')
            ->limit(10)
            ->get();

        // Price distribution
        $priceDistribution = $query->selectRaw('
            CASE 
                WHEN price < 5000 THEN \'Under 5K\'
                WHEN price < 10000 THEN \'5K - 10K\'
                WHEN price < 15000 THEN \'10K - 15K\'
                WHEN price < 20000 THEN \'15K - 20K\'
                ELSE \'Over 20K\'
            END as price_range,
            COUNT(*) as count
        ')->groupBy('price_range')->get();

        // Average price
        $avgPrice = DormListing::whereDate('created_at', '>=', $dateFrom)
                                ->whereDate('created_at', '<=', $dateTo)
                                ->avg('price');

        // Listings by type
        $listingsByType = DormListing::selectRaw('type, COUNT(*) as count')
                                ->whereDate('created_at', '>=', $dateFrom)
                                ->whereDate('created_at', '<=', $dateTo)
                                ->groupBy('type')
                                ->get();

        // Price statistics
        $minPrice = DormListing::whereDate('created_at', '>=', $dateFrom)
                                ->whereDate('created_at', '<=', $dateTo)
                                ->min('price');
        $maxPrice = DormListing::whereDate('created_at', '>=', $dateFrom)
                                ->whereDate('created_at', '<=', $dateTo)
                                ->max('price');

        // Activity statistics
        $newListingsThisMonth = DormListing::whereDate('created_at', '>=', now()->startOfMonth())
                                ->whereDate('created_at', '<=', $dateTo)
                                ->count();
        $newListingsThisWeek = DormListing::whereDate('created_at', '>=', now()->startOfWeek())
                                ->whereDate('created_at', '<=', $dateTo)
                                ->count();

        return view('admin.reports.listings', compact(
            'totalListings', 'activeListings', 'inactiveListings',
            'recentListings', 'listingsByStatus', 'monthlyListings',
            'topOwners', 'priceDistribution', 'avgPrice', 'listingsByType',
            'minPrice', 'maxPrice', 'newListingsThisMonth', 'newListingsThisWeek',
            'dateFrom', 'dateTo'
        ));
    }

    public function activity(Request $request)
    {
        // Date range filtering
        $dateFrom = $request->filled('date_from') ? $request->date_from : now()->subDays(7)->format('Y-m-d');
        $dateTo = $request->filled('date_to') ? $request->date_to : now()->format('Y-m-d');

        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        // Statistics for different time periods
        $stats = [
            'users_today' => User::whereDate('created_at', $today)->count(),
            'users_week' => User::where('created_at', '>=', $thisWeek)->count(),
            'users_month' => User::where('created_at', '>=', $thisMonth)->count(),
            'listings_today' => DormListing::whereDate('created_at', $today)->count(),
            'listings_week' => DormListing::where('created_at', '>=', $thisWeek)->count(),
            'listings_month' => DormListing::where('created_at', '>=', $thisMonth)->count(),
            'messages_today' => Message::whereDate('created_at', $today)->count(),
            'messages_week' => Message::where('created_at', '>=', $thisWeek)->count(),
            'messages_month' => Message::where('created_at', '>=', $thisMonth)->count(),
            'visits_today' => VisitSchedule::whereDate('created_at', $today)->count(),
            'visits_week' => VisitSchedule::where('created_at', '>=', $thisWeek)->count(),
            'visits_month' => VisitSchedule::where('created_at', '>=', $thisMonth)->count(),
        ];

        // Recent activity within date range
        $recentUsers = User::whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'New User',
                    'description' => "{$user->name} ({$user->user_type}) registered",
                    'time' => $user->created_at->diffForHumans(),
                    'icon' => 'user-plus',
                    'date' => $user->created_at->format('Y-m-d H:i:s')
                ];
            });

        $recentListings = DormListing::with('owner')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($listing) {
                return [
                    'type' => 'New Listing',
                    'description' => "{$listing->title} by {$listing->owner->name}",
                    'time' => $listing->created_at->diffForHumans(),
                    'icon' => 'home',
                    'date' => $listing->created_at->format('Y-m-d H:i:s')
                ];
            });

        $recentMessages = Message::with(['sender', 'receiver'])
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($message) {
                return [
                    'type' => 'New Message',
                    'description' => "Message from {$message->sender->name} to {$message->receiver->name}",
                    'time' => $message->created_at->diffForHumans(),
                    'icon' => 'message',
                    'date' => $message->created_at->format('Y-m-d H:i:s')
                ];
            });

        $recentVisits = VisitSchedule::with(['user', 'dormListing'])
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($visit) {
                return [
                    'type' => 'Visit Scheduled',
                    'description' => "{$visit->user->name} scheduled visit to {$visit->dormListing->title}",
                    'time' => $visit->created_at->diffForHumans(),
                    'icon' => 'calendar',
                    'date' => $visit->created_at->format('Y-m-d H:i:s')
                ];
            });

        $recentActivity = $recentUsers->merge($recentListings)
            ->merge($recentMessages)
            ->merge($recentVisits)
            ->sortByDesc('date')
            ->values();

        // Hourly activity for charts
        $hourlyActivity = Message::selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return view('admin.reports.activity', compact(
            'stats', 'recentActivity', 'hourlyActivity', 'dateFrom', 'dateTo'
        ));
    }

    public function exportUsers(Request $request)
    {
        $dateFrom = $request->filled('date_from') ? $request->date_from : now()->subDays(30)->format('Y-m-d');
        $dateTo = $request->filled('date_to') ? $request->date_to : now()->format('Y-m-d');

        $users = User::whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->get();

        $filename = 'users_report_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'User Type', 'Status', 'Verification Status', 'Created At']);
            
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone,
                    $user->user_type,
                    $user->status,
                    $user->verification_status,
                    $user->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportListings(Request $request)
    {
        $dateFrom = $request->filled('date_from') ? $request->date_from : now()->subDays(30)->format('Y-m-d');
        $dateTo = $request->filled('date_to') ? $request->date_to : now()->format('Y-m-d');

        $listings = DormListing::with('owner')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->get();

        $filename = 'listings_report_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($listings) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['ID', 'Title', 'Owner', 'Price', 'Status', 'Location', 'Created At']);
            
            foreach ($listings as $listing) {
                fputcsv($file, [
                    $listing->id,
                    $listing->title,
                    $listing->owner->name,
                    $listing->price,
                    $listing->status,
                    $listing->location,
                    $listing->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
