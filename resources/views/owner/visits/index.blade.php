@extends('layouts.owner')

@section('title', 'Visit Requests')

@push('styles')
<style>
.page-title{
    font-family:'Syne';
    font-size:1.1rem;
    font-weight:800;
    margin-bottom:.8rem;
}

/* VISIT CARD */
.visit-card{
    margin:0 1.2rem 1.2rem;
    padding:1rem;
    border-radius:14px;
    border:1px solid var(--border);
    transition:all .2s ease;
    position:relative;
    overflow:hidden;
}

/* Color variations based on status */
.visit-card.status-pending{
    background: linear-gradient(135deg, var(--green) 0%, #1e5a3a 100%);
    border:1px solid var(--green);
}

.visit-card.status-confirmed{
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    border:1px solid #fbbf24;
}

.visit-card.status-completed{
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border:1px solid #3b82f6;
}

.visit-card.status-cancelled{
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    border:1px solid #6b7280;
}

.visit-card:hover{
    transform:translateY(-3px);
    box-shadow:0 8px 24px rgba(0,0,0,0.15);
}

/* Text styling for colored cards */
.visit-card .student-name{
    color: #fff !important;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.visit-card .listing-info{
    color: rgba(255,255,255,0.9) !important;
}

.visit-card .visit-details{
    color: rgba(255,255,255,0.95) !important;
}

.visit-card .notes{
    color: rgba(255,255,255,0.9) !important;
}

/* BUTTON SYSTEM */
.action-btn{
    padding:.45rem .75rem;
    border-radius:8px;
    font-size:.75rem;
    font-weight:700;
    text-decoration:none;
    border:none;
    cursor:pointer;
    transition:all .2s ease;
    display:inline-block;
}

.btn-approve{
    background:#2563eb;
    color:#fff;
}

.btn-approve:hover{
    background:#1d4ed8;
    transform:translateY(-2px);
}

.btn-reject{
    background:#dc2626;
    color:#fff;
}

.btn-reject:hover{
    background:#b91c1c;
    transform:translateY(-2px);
}

.btn-complete{
    background:var(--green);
    color:#fff;
}

.btn-complete:hover{
    transform:translateY(-2px);
    opacity:.9;
}

.btn-cancel{
    background:#dc2626;
    color:#fff;
}

.btn-cancel:hover{
    background:#b91c1c;
    transform:translateY(-2px);
}

/* FILTER CHIPS */
.filter-bar{
    display:flex;
    gap:.5rem;
    padding:0 1.2rem 1rem;
    flex-wrap:nowrap;
    overflow-x:auto;
    scrollbar-width:none;
    -ms-overflow-style:none;
}

.filter-bar::-webkit-scrollbar{
    display:none;
}

.filter-bar a{
    padding:.4rem .8rem;
    border-radius:20px;
    text-decoration:none;
    font-size:.8rem;
    border:1px solid var(--border);
    color:#333;
    transition:all .2s ease;
    white-space:nowrap;
    flex-shrink:0;
}

.filter-bar a.active{
    background:var(--green);
    color:#fff;
    border:none;
}

.filter-bar a:hover:not(.active){
    background:#f8f9fa;
}

/* Responsive design */
@media (max-width: 768px) {
    .filter-bar a{
        font-size:.7rem;
        padding:.35rem .6rem;
    }
}

@media (max-width: 480px) {
    .filter-bar a{
        font-size:.65rem;
        padding:.3rem .5rem;
    }
}

</style>
@endpush

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

    {{-- FILTER --}}
    <div class="filter-bar">
        <a href="{{ route('owner.visits.index') }}"
           class="{{ !request('status') ? 'active' : '' }}">
            All
        </a>

        <a href="{{ route('owner.visits.index', ['status' => 'Pending']) }}"
           class="{{ request('status') === 'Pending' ? 'active' : '' }}">
            Pending
        </a>

        <a href="{{ route('owner.visits.index', ['status' => 'Confirmed']) }}"
           class="{{ request('status') === 'Confirmed' ? 'active' : '' }}">
            Approved
        </a>

        <a href="{{ route('owner.visits.index', ['status' => 'Completed']) }}"
           class="{{ request('status') === 'Completed' ? 'active' : '' }}">
            Completed
        </a>

        <a href="{{ route('owner.visits.index', ['status' => 'Cancelled']) }}"
           class="{{ request('status') === 'Cancelled' ? 'active' : '' }}">
            Cancelled
        </a>
    </div>
</div>

@forelse($visits as $visit)

<div class="visit-card status-{{ strtolower($visit->status) }}">

    <div style="display:flex; align-items:center; gap:1rem; margin-bottom:0.8rem;">
        <div style="width:50px; height:50px; border-radius:8px; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; font-size:1.2rem;">
            👤
        </div>
        <div>
            <div class="student-name" style="font-weight:700;font-size:1rem;">
                {{ $visit->user->name ?? 'Student' }}
            </div>
            <div class="listing-info" style="font-size:0.85rem;">
                📍 {{ $visit->dormListing->street ?? 'N/A' }}
            </div>
        </div>
    </div>

    <div class="visit-details" style="margin-top:.4rem;">
        📅 {{ \Carbon\Carbon::parse($visit->visit_date)->format('F d, Y') }}
        at {{ \Carbon\Carbon::parse($visit->visit_time)->format('h:i A') }}
    </div>

    <div class="notes" style="margin-top:.4rem;">
        📝 {{ $visit->notes ?? 'No notes' }}
    </div>

    <div style="margin-top:.5rem;">
        Status:
        @php
            $statusLabel = $visit->status === 'Confirmed' ? 'Approved' : ucfirst($visit->status);
        @endphp
        <strong style="color: rgba(255,255,255,0.95); background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 12px; font-size: 0.8rem;">
            {{ $statusLabel }}
        </strong>
    </div>

    <!-- ACTIONS -->
    <div style="margin-top:.8rem;display:flex;gap:.5rem;flex-wrap:wrap;">

        @if($visit->status === 'Pending')
            <form method="POST" action="{{ route('visits.status.update', $visit->id) }}">
                @csrf
                <input type="hidden" name="status" value="Confirmed">
                <button class="action-btn btn-approve">
                    ✅ Approve
                </button>
            </form>

            <form method="POST" action="{{ route('visits.status.update', $visit->id) }}">
                @csrf
                <input type="hidden" name="status" value="Cancelled">
                <button class="action-btn btn-reject">
                    ❌ Reject
                </button>
            </form>
        @endif

        @if($visit->status === 'Confirmed')
            <form method="POST" action="{{ route('visits.status.update', $visit->id) }}">
                @csrf
                <input type="hidden" name="status" value="Completed">
                <button class="action-btn btn-complete">
                    📩 Mark Completed
                </button>
            </form>

            <form method="POST" action="{{ route('visits.status.update', $visit->id) }}">
                @csrf
                <input type="hidden" name="status" value="Cancelled">
                <button class="action-btn btn-cancel">
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