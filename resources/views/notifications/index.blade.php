@extends('layouts.app')

@section('title', 'Notifications')

@php
    function getNotificationRedirectUrl($notification) {
        // Debug: Log notification type
        \Log::info('Notification type: ' . $notification->type . ' - Title: ' . $notification->title);
        
        switch($notification->type) {
            case 'message':
                if ($notification->listing && $notification->listing->owner_id) {
                    return route('messages.show', [$notification->listing->id, $notification->listing->owner_id]);
                }
                return route('student.home');
            case 'inquiry':
                if ($notification->listing) {
                    return route('dorms.show', $notification->listing->id);
                }
                return route('owner.dashboard');
            case 'verification':
                return route('owner.dashboard');
            case 'visit':
            case 'visit_confirmed':
            case 'visit_requested':
            case 'visit_cancelled':
                return route('visits.index');
            case 'saved':
                if ($notification->listing) {
                    return route('dorms.show', $notification->listing->id);
                }
                return route('student.home');
            default:
                // Check if title contains visit-related keywords
                if (stripos($notification->title, 'visit') !== false || 
                    stripos($notification->message, 'visit') !== false) {
                    return route('visits.index');
                }
                return route('student.home');
        }
    }
@endphp

@section('content')
<div class="wrap">

    @include('partials.navbar')

    <div class="screen active" style="max-width:600px;margin:auto;padding:1.5rem;">

        <h2>Notifications 🔔</h2>

        <div style="margin-top:1rem; display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; flex-wrap:wrap;">

        <div>
            <p style="font-size:0.95rem; color:#556064;">
                <span id="notification-count">{{ $notifications->where('is_read', false)->count() }}</span> unread notification{{ $notifications->where('is_read', false)->count() === 1 ? '' : 's' }}
            </p>
        </div>

        @if($notifications->where('is_read', false)->count() > 0)
            <form action="{{ route('notifications.markAllRead') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" style="background:#2D7D4F; color:#fff; border:none; padding:0.55rem 1rem; border-radius:999px; cursor:pointer;">
                    Mark all read
                </button>
            </form>
        @endif

    </div>

    <div style="margin-top:1rem;">

        @forelse($notifications as $notif)
            <div class="notification-item" 
                 data-notification-id="{{ $notif->id }}"
                 data-redirect-url="{{ getNotificationRedirectUrl($notif) }}"
                 style="
                padding:14px;
                border-radius:16px;
                border:1px solid #e5e7eb;
                margin-bottom:12px;
                background:#fff;
                cursor: pointer;
                transition: all 0.2s ease;
                {{ !$notif->is_read ? 'border-left: 4px solid #2D7D4F;' : '' }}
            " onclick="handleNotificationClick(this)">
                <div style="display:flex; justify-content:space-between; gap:1rem; align-items:flex-start;">
                    <strong style="{{ $notif->is_read ? 'font-weight: 600;' : 'font-weight: 800;' }}">{{ $notif->title }}</strong>
                    <span style="font-size:0.8rem; color:#6b7280;">{{ $notif->created_at->diffForHumans() }}</span>
                </div>
                <p style="margin:0.45rem 0 0; color:#4b5563; {{ $notif->is_read ? 'font-weight: 400;' : 'font-weight: 600;' }}">{{ $notif->message }}</p>

                @if($notif->listing)
                    <p style="margin:0.75rem 0 0; font-size:0.9rem; color:#1f5c38;">
                        Listing: {{ $notif->listing->street ?? $notif->listing->title ?? 'Listing #' . $notif->listing->id }}
                    </p>
                @endif

                <div style="margin-top:0.8rem; display:flex; gap:0.5rem; flex-wrap:wrap;">
                    @if(!$notif->is_read)
                        <span class="unread-badge" style="background:#E8F7EE; color:#176f3a; padding:0.25rem 0.6rem; border-radius:999px; font-size:0.75rem;">Unread</span>
                    @endif
                </div>
            </div>
        @empty
            <p>No notifications yet.</p>
        @endforelse

    </div>

    </div>

    @include('partials.footer')

</div>

<script>
function handleNotificationClick(element) {
    const notificationId = element.dataset.notificationId;
    const redirectUrl = element.dataset.redirectUrl;
    
    // Mark as read via AJAX
    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update UI to show as read
        element.style.borderLeft = 'none';
        element.querySelector('strong').style.fontWeight = '600';
        element.querySelector('p').style.fontWeight = '400';
        
        // Remove unread badge
        const unreadBadge = element.querySelector('.unread-badge');
        if (unreadBadge) {
            unreadBadge.remove();
        }
        
        // Update notification count
        const countElement = document.getElementById('notification-count');
        if (countElement) {
            const currentCount = parseInt(countElement.textContent);
            if (currentCount > 0) {
                countElement.textContent = currentCount - 1;
                countElement.nextSibling.textContent = currentCount - 1 === 1 ? ' unread notification' : ' unread notifications';
            }
        }
        
        // Redirect to the appropriate page
        window.location.href = redirectUrl;
    })
    .catch(error => {
        console.error('Error marking notification as read:', error);
        // Still redirect even if marking as read fails
        window.location.href = redirectUrl;
    });
}

// Add hover effect
document.querySelectorAll('.notification-item').forEach(item => {
    item.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px)';
        this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.1)';
    });
    
    item.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = 'none';
    });
});
</script>
@endsection