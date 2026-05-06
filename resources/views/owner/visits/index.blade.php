@extends('layouts.owner')

@section('title', 'Visit Requests')

@section('content')

<div style="padding:1rem;">
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
        <div style="color: #666; font-size: 12px;">
            <a href="{{ route('owner.dashboard') }}" class="back-btn" style="background: #2D7D4F; color: white; padding: 8px 10px; border-radius: 8px; text-decoration: none; display: inline-block; font-weight: 600; transition: background 0.3s ease;">
                ← Back
            </a>
        </div>
        <h3 style="margin: 0; margin-left: 10px;">📅 Visit Requests</h3>
    </div>
</div>

@forelse($visits as $visit)

<div style="background:#fff;margin:1rem;padding:1rem;border-radius:12px;border:1px solid #ddd;">

    <div style="display:flex; align-items:center; gap:1rem; margin-bottom:0.8rem;">
        <div style="width:50px; height:50px; border-radius:8px; background:#f0f0f0; display:flex; align-items:center; justify-content:center; font-size:1.2rem;">
            👤
        </div>
        <div>
            <div style="font-weight:700;font-size:1rem;">
                {{ $visit->user->name ?? 'Student' }}
            </div>
            <div style="font-size:0.85rem;color:#666;">
                📍 {{ $visit->dormListing->street ?? 'N/A' }}
            </div>
        </div>
    </div>

    <div style="margin-top:.4rem;">
        📅 {{ \Carbon\Carbon::parse($visit->visit_date)->format('F d, Y') }}
        at {{ \Carbon\Carbon::parse($visit->visit_time)->format('h:i A') }}
    </div>

    <div style="margin-top:.4rem;">
        📝 {{ $visit->notes ?? 'No notes' }}
    </div>

    <div style="margin-top:.5rem;">
        Status:
        @php
            $statusLabel = $visit->status === 'Confirmed' ? 'Approved' : ucfirst($visit->status);
            $statusColor = $visit->status === 'Pending' ? '#F59E0B' : ($visit->status === 'Confirmed' ? '#2563EB' : ($visit->status === 'Completed' ? '#16A34A' : '#B91C1C'));
        @endphp
        <strong style="color: {{ $statusColor }};">
            {{ $statusLabel }}
        </strong>
    </div>

    <!-- ACTIONS -->
    <div style="margin-top:.8rem;display:flex;gap:.5rem;flex-wrap:wrap;">

        @if($visit->status === 'Pending')
            <form method="POST" action="{{ route('visits.status.update', $visit->id) }}">
                @csrf
                <input type="hidden" name="status" value="Confirmed">
                <button style="padding:.4rem .7rem;background:#2563EB;color:#fff;border:none;border-radius:8px;">
                    ✅ Approve
                </button>
            </form>

            <form method="POST" action="{{ route('visits.status.update', $visit->id) }}">
                @csrf
                <input type="hidden" name="status" value="Cancelled">
                <button style="padding:.4rem .7rem;background:#C8102E;color:#fff;border:none;border-radius:8px;">
                    ❌ Reject
                </button>
            </form>
        @endif

        @if($visit->status === 'Confirmed')
            <form method="POST" action="{{ route('visits.status.update', $visit->id) }}">
                @csrf
                <input type="hidden" name="status" value="Completed">
                <button style="padding:.4rem .7rem;background:#16A34A;color:#fff;border:none;border-radius:8px;">
                    📩 Mark Completed
                </button>
            </form>

            <form method="POST" action="{{ route('visits.status.update', $visit->id) }}">
                @csrf
                <input type="hidden" name="status" value="Cancelled">
                <button style="padding:.4rem .7rem;background:#C8102E;color:#fff;border:none;border-radius:8px;">
                    ❌ Cancel
                </button>
            </form>
        @endif

    </div>

</div>

@empty

<div style="padding:2rem;text-align:center;color:#666;">
    No visit requests yet.
</div>

@endforelse

@endsection