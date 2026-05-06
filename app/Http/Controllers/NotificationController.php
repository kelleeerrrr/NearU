<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // STUDENT
    public function index()
    {
        $user = Auth::user();

        // safety: redirect owners away
        if ($user->user_type === 'owner') {
            return redirect()->route('notifications.owner');
        }

        $notifications = Notification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    // OWNER
    public function owner()
    {
        $user = Auth::user();

        $notifications = Notification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('notifications.owner', compact('notifications'));
    }

    public function markRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);
        
        // Return JSON response for AJAX requests
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }
        
        return back();
    }

    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back();
    }
}