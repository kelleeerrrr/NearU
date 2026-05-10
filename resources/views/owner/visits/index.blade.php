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
    padding:1.5rem;
    border-radius:24px;
    border:3px solid var(--border);
    transition:all .4s cubic-bezier(0.4, 0, 0.2, 1);
    position:relative;
    overflow:hidden;
    box-shadow:0 12px 32px rgba(45,125,79,0.15);
    backdrop-filter: blur(10px);
    transform-style: preserve-3d;
    background:linear-gradient(to top, rgba(45,125,79,0.08) 0%, rgba(45,125,79,0.04) 30%, var(--card) 60%, rgba(255,255,255,0.95) 100%);
    background-origin: border-box;
    background-clip: padding-box;
}

.visit-card::before {
    content:'';
    position:absolute;
    top:-60%;
    right:-60%;
    width:140px;
    height:140px;
    background:radial-gradient(circle, rgba(242,183,5,0.12) 0%, transparent 70%);
    animation:float-bubble 7s ease-in-out infinite;
}

.visit-card::after {
    content:'';
    position:absolute;
    bottom:-40%;
    left:-40%;
    width:90px;
    height:90px;
    background:radial-gradient(circle, rgba(45,125,79,0.08) 0%, transparent 70%);
    animation:float-bubble 9s ease-in-out infinite reverse;
}

@keyframes float-bubble {
    0%, 100% { transform:translateY(0px) rotate(0deg) scale(1); }
    50% { transform:translateY(-20px) rotate(180deg) scale(1.15); }
}

/* Color variations based on status */
.visit-card.status-pending{
    border:3px solid var(--green);
    background:linear-gradient(to top, rgba(45,125,79,0.15) 0%, rgba(45,125,79,0.08) 100%);
}

.visit-card.status-confirmed{
    border:3px solid var(--gold);
    background:linear-gradient(to top, rgba(242,183,5,0.15) 0%, rgba(242,183,5,0.08) 100%);
}

.visit-card.status-completed{
    border:3px solid #3b82f6;
    background:linear-gradient(to top, rgba(59,130,246,0.15) 0%, rgba(59,130,246,0.08) 100%);
}

.visit-card.status-cancelled{
    border:3px solid #6b7280;
    background:linear-gradient(to top, rgba(107,114,128,0.15) 0%, rgba(107,114,128,0.08) 100%);
}

.visit-card:hover{
    transform:translateY(-8px) scale(1.03) rotateX(2deg) rotateY(2deg);
    box-shadow:0 20px 48px rgba(45,125,79,0.25);
    border-color: var(--gold);
    filter: brightness(1.05);
}

.visit-card:hover::before {
    top:-40%;
    right:-40%;
    transform:scale(1.4) rotate(45deg);
}

.visit-card:hover::after {
    bottom:-25%;
    left:-25%;
    transform:scale(1.5) rotate(-45deg);
}

/* Text styling for default cards */
.visit-card .student-name{
    color: var(--text) !important;
    font-weight: 700;
    font-size:1.1rem;
    background:linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
    border-radius:12px;
    padding:0.3rem 0.6rem;
    display:inline-block;
    animation:pulse-glow 3s ease-in-out infinite;
}

@keyframes pulse-glow {
    0%, 100% { transform:scale(1); }
    50% { transform:scale(1.05); }
}

.visit-card .listing-info{
    color: var(--t2) !important;
    font-weight:600;
    font-size:0.9rem;
    margin-top:0.3rem;
}

.visit-card .visit-details{
    color: var(--t2) !important;
    font-weight:600;
    font-size:0.9rem;
    background:linear-gradient(135deg, rgba(45,125,79,0.1) 0%, rgba(45,125,79,0.05) 100%);
    border-radius:10px;
    padding:0.4rem 0.6rem;
    display:inline-block;
    margin-top:0.5rem;
}

.visit-card .notes{
    color: var(--t2) !important;
    font-weight:500;
    font-size:0.85rem;
    background:linear-gradient(135deg, rgba(242,183,5,0.08) 0%, rgba(242,183,5,0.04) 100%);
    border-radius:8px;
    padding:0.3rem 0.5rem;
    display:inline-block;
    margin-top:0.4rem;
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
    margin:0 1.2rem 0.2rem;
    overflow-x: auto;
    padding-bottom: 0.3rem;
}

.filter-bar a{
    padding:.5rem 1rem;
    border-radius:20px;
    font-size:.8rem;
    font-weight:600;
    text-decoration:none;
    color:var(--text);
    background:var(--card);
    border:2px solid var(--border);
    transition:all .3s cubic-bezier(0.4, 0, 0.2, 1);
    position:relative;
    overflow:hidden;
    box-shadow:0 4px 12px rgba(45,125,79,0.08);
    text-transform:uppercase;
    letter-spacing:0.5px;
}

.filter-bar a::before {
    content:'';
    position:absolute;
    top:0;
    left:-100%;
    width:100%;
    height:100%;
    background:linear-gradient(90deg, transparent, rgba(242,183,5,0.2), transparent);
    transition:left 0.5s ease;
}

.filter-bar a:hover::before {
    left:100%;
}

.filter-bar a.active{
    background:linear-gradient(135deg, var(--green), #1e5a3a);
    color:#fff;
    border:2px solid var(--green);
    box-shadow:0 6px 20px rgba(45,125,79,0.25);
    transform:scale(1.05);
}

.filter-bar a:hover:not(.active){
    background:linear-gradient(135deg, var(--card), #f8fdf9);
    border-color: var(--gold);
    transform:translateY(-2px) scale(1.02);
    box-shadow:0 8px 20px rgba(242,183,5,0.15);
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
        <h3 style="margin: 0; margin-left: 10px; font-family: 'Syne', sans-serif; font-weight: 800;">📅 Visit Requests</h3>
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
        <div style="width:50px; height:50px; border-radius:50%; overflow:hidden; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center;">
            @if($visit->user && $visit->user->profile_photo_path)
                <img src="{{ asset('storage/' . $visit->user->profile_photo_path) }}" alt="{{ $visit->user->name }}" style="width:100%; height:100%; object-fit:cover;">
            @else
                <div style="width:100%; height:100%; background:linear-gradient(135deg, var(--green), #1e5a3a); display:flex; align-items:center; justify-content:center; font-size:1.2rem; color:#fff;">
                    👤
                </div>
            @endif
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
        <strong style="color: var(--text); background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 12px; font-size: 0.8rem;">
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