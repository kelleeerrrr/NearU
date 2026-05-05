@extends('layouts.owner')

@section('title', 'Visit Requests')

@section('content')

<div style="padding:1rem;">
    <h3>📅 Visit Requests</h3>
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
        <strong style="color: {{ $visit->status === 'Confirmed' ? '#2D7D4F' : ($visit->status === 'Completed' ? '#3B82F6' : '#F59E0B') }};">
            {{ ucfirst($visit->status) }}
        </strong>
    </div>

    <!-- ACTIONS -->
    <div style="margin-top:.8rem;display:flex;gap:.5rem;flex-wrap:wrap;">

        @if($visit->status === 'Pending')
            <form method="POST" action="{{ route('owner.visits.status.update', $visit->id) }}">
                @csrf
                <input type="hidden" name="status" value="Confirmed">
                <button style="padding:.4rem .7rem;background:#2D7D4F;color:#fff;border:none;border-radius:8px;">
                    ✅ Confirm
                </button>
            </form>
        @endif

        @if($visit->status === 'Confirmed')
            <form method="POST" action="{{ route('owner.visits.status.update', $visit->id) }}">
                @csrf
                <input type="hidden" name="status" value="Completed">
                <button style="padding:.4rem .7rem;background:#3B82F6;color:#fff;border:none;border-radius:8px;">
                    📩 Mark Done
                </button>
            </form>
        @endif

        @if($visit->status !== 'Completed')
            <form method="POST" action="{{ route('owner.visits.status.update', $visit->id) }}">
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