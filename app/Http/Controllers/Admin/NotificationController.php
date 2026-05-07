<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')
            ->latest()
            ->paginate(20);
            
        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $users = \App\Models\User::where('user_type', '!=', 'admin')->get();
        return view('admin.notifications.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,success,error',
            'user_id' => 'nullable|exists:users,id',
            'send_to_all' => 'boolean',
        ]);

        if ($request->boolean('send_to_all')) {
            $users = \App\Models\User::where('user_type', '!=', 'admin')->get();
            
            foreach ($users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => $request->title,
                    'message' => $request->message,
                    'type' => $request->type,
                    'is_read' => false,
                ]);
            }
        } else {
            Notification::create([
                'user_id' => $request->user_id,
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'is_read' => false,
            ]);
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification sent successfully.');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification deleted successfully.');
    }

    public function markAsRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }
}
