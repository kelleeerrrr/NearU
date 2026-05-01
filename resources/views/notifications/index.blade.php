@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="wrap">

    @include('partials.navbar')

    <div class="screen active" style="max-width:600px;margin:auto;padding:1.5rem;">

        <h2>Notifications 🔔</h2>

        <div style="margin-top:1rem;">

            @forelse($notifications as $notif)
                <div style="
                    padding:12px;
                    border-bottom:1px solid #eee;
                    margin-bottom:10px;
                ">
                    <strong>{{ $notif->title }}</strong><br>
                    <small>{{ $notif->message }}</small><br>
                    <small style="color:gray;">
                        {{ $notif->created_at->diffForHumans() }}
                    </small>
                </div>
            @empty
                <p>No notifications yet.</p>
            @endforelse

        </div>

    </div>

    @include('partials.footer')

</div>
@endsection