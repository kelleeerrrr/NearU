@extends('layouts.owner')

@section('title', 'Notifications')

@section('content')
<div class="wrap">

    @include('partials.navbar')

    <div class="screen active" style="max-width:600px;margin:auto;padding:1.5rem;">

        <h2>Notifications 🔔</h2>

        <div style="margin-top:1rem; display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; flex-wrap:wrap;">

        <div>
            <p style="font-size:0.95rem; color:#556064;">
                {{ $notifications->count() }} notification{{ $notifications->count() === 1 ? '' : 's' }}
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
            <div style="
                padding:14px;
                border-radius:16px;
                border:1px solid #e5e7eb;
                margin-bottom:12px;
                background:#fff;
            ">
                <div style="display:flex; justify-content:space-between; gap:1rem; align-items:flex-start;">
                    <strong>{{ $notif->title }}</strong>
                    <span style="font-size:0.8rem; color:#6b7280;">{{ $notif->created_at->diffForHumans() }}</span>
                </div>
                <p style="margin:0.45rem 0 0; color:#4b5563;">{{ $notif->message }}</p>

                @if($notif->listing)
                    <p style="margin:0.75rem 0 0; font-size:0.9rem; color:#1f5c38;">
                        Listing: {{ $notif->listing->street ?? $notif->listing->title ?? 'Listing #' . $notif->listing->id }}
                    </p>
                @endif

                <div style="margin-top:0.8rem; display:flex; gap:0.5rem; flex-wrap:wrap;">
                    @if(!$notif->is_read)
                        <span style="background:#E8F7EE; color:#176f3a; padding:0.25rem 0.6rem; border-radius:999px; font-size:0.75rem;">Unread</span>
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
@endsection