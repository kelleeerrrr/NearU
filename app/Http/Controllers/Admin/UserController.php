<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by user type
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }

        // Filter by verification status
        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        // Get statistics for filters
        $stats = [
            'total_users' => User::count(),
            'students' => User::where('user_type', 'student')->count(),
            'owners' => User::where('user_type', 'owner')->count(),
            'admins' => User::where('user_type', 'admin')->count(),
            'verified_owners' => User::where('user_type', 'owner')->where('verification_status', 'approved')->count(),
            'pending_verifications' => User::where('user_type', 'owner')->where('verification_status', 'under_review')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function show(User $user)
    {
        $user->load([
            'dormListings' => function ($query) {
                $query->latest();
            },
            'savedListings.dormListing',
            'visitSchedules' => function ($query) {
                $query->latest();
            }
        ]);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'user_type' => 'required|in:student,owner,admin',
            'verification_status' => 'required|in:not_verified,pending,approved,rejected',
            'status' => 'required|in:active,inactive',
        ]);

        $user->update($request->all());

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->user_type === 'admin') {
            return back()->with('error', 'Cannot delete admin users.');
        }

        // Delete related data
        $user->dormListings()->delete();
        $user->savedListings()->delete();
        $user->visitSchedules()->delete();
        $user->messages()->delete();
        $user->notifications()->delete();
        
        // Delete profile photo if exists
        if ($user->profile_photo_path) {
            \Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active'
        ]);

        return back()->with('success', 'User status updated successfully.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password reset successfully.');
    }

    public function verify(User $user)
    {
        if ($user->user_type !== 'owner') {
            return back()->with('error', 'Only owners can be verified.');
        }

        $user->update(['verification_status' => 'approved']);

        return back()->with('success', 'User verified successfully.');
    }

    public function rejectVerification(Request $request, User $user)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        if ($user->user_type !== 'owner') {
            return back()->with('error', 'Only owners can have verification rejected.');
        }

        $user->update([
            'verification_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        return back()->with('success', 'User verification rejected.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,verify,reject',
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
        ]);

        $users = User::whereIn('id', $request->users)->get();
        $action = $request->action;
        $count = 0;

        foreach ($users as $user) {
            // Don't allow actions on self or other admins
            if ($user->id === auth()->id() || ($user->user_type === 'admin' && $action === 'delete')) {
                continue;
            }

            switch ($action) {
                case 'activate':
                    $user->update(['status' => 'active']);
                    $count++;
                    break;
                case 'deactivate':
                    $user->update(['status' => 'inactive']);
                    $count++;
                    break;
                case 'delete':
                    if ($user->user_type !== 'admin') {
                        $user->delete();
                        $count++;
                    }
                    break;
                case 'verify':
                    if ($user->user_type === 'owner') {
                        $user->update(['verification_status' => 'approved']);
                        $count++;
                    }
                    break;
                case 'reject':
                    if ($user->user_type === 'owner') {
                        $user->update(['verification_status' => 'rejected']);
                        $count++;
                    }
                    break;
            }
        }

        return back()->with('success', "Action completed on {$count} user(s).");
    }

    public function export(Request $request)
    {
        $query = User::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }

        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->get();

        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'User Type', 'Status', 'Verification Status', 'Created At']);
            
            // CSV Data
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
}
