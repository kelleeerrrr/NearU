@extends('layouts.owner')

@section('title', 'Visit Requests')

@section('content')

<div style="padding:1rem;">
    <h3>📅 Visit Requests</h3>
</div>

@forelse($visits as $visit)

<div style="background:#fff;margin:1rem;padding:1rem;border-radius:12px;border:1px solid #ddd;">

    <div style="font-weight:700;font-size:1rem;">
        👤 {{ $visit->user->name ?? 'Student' }}
    </div>

    <div style="margin-top:.3rem;font-size:.85rem;color:#555;">
        📍 {{ $visit->dormListing->street ?? 'N/A' }}
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
        <strong>
            {{ ucfirst($visit->status) }}
        </strong>
    </div>

    <!-- ACTIONS -->
    <div style="margin-top:.8rem;display:flex;gap:.5rem;flex-wrap:wrap;">

        <form method="POST" action="{{ route('owner.visits.status.update', $visit->id) }}">
            @csrf
            <input type="hidden" name="status" value="approved">
            <button style="padding:.4rem .7rem;background:#2D7D4F;color:#fff;border:none;border-radius:8px;">
                ✅ Confirm
            </button>
        </form>

        <form method="POST" action="{{ route('owner.visits.status.update', $visit->id) }}">
            @csrf
            <input type="hidden" name="status" value="rejected">
            <button style="padding:.4rem .7rem;background:#C8102E;color:#fff;border:none;border-radius:8px;">
                ❌ Cancel
            </button>
        </form>

        <form method="POST" action="{{ route('owner.visits.status.update', $visit->id) }}">
            @csrf
            <input type="hidden" name="status" value="completed">
            <button style="padding:.4rem .7rem;background:#3B82F6;color:#fff;border:none;border-radius:8px;">
                📩 Done
            </button>
        </form>

    </div>

</div>

@empty

<div style="padding:2rem;text-align:center;color:#666;">
    No visit requests yet.
</div>

@endforelse

@endsection