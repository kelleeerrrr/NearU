@extends('layouts.owner')

@section('title', 'Statistics — NearU')

@section('content')

<style>
:root{
  --bg:#F0F7F2;
  --card:#fff;
  --t1:#141F14;
  --t2:#5E6E5E;
  --border:#D6E8DC;
  --green:#2D7D4F;
  --gold:#F2B705;
  --blue:#3B82F6;
  --red:#C8102E;
  --green-lt:#E8F7EE;
  --gold-lt:#FFFBEB;
  --blue-lt:#EFF6FF;
  --red-lt:#FFF0F2;
}

body{background:var(--bg);font-family:system-ui;}

.header{
    display:flex;justify-content:space-between;
    padding:1rem;
    font-weight:800;
}

.card{
    background:#fff;
    margin:.6rem 1rem;
    padding:1rem;
    border:1px solid var(--border);
    border-radius:14px;
}

.grid-2{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:.6rem;
    padding:0 1rem;
}

.stat{
    background:#fff;
    border:1px solid var(--border);
    padding:1rem;
    border-radius:14px;
}

.big{
    font-size:1.4rem;
    font-weight:800;
}

.label{
    font-size:.75rem;
    color:var(--t2);
}

.section{
    padding:1rem 1rem .3rem;
    font-weight:800;
}
.progress{
    height:8px;
    background:#eee;
    border-radius:50px;
    overflow:hidden;
}
.bar{
    height:100%;
    background:var(--green);
}
.small{
    font-size:.8rem;
    color:var(--t2);
}
</style>

<!-- HEADER -->
<div class="header">
    <a href="{{ url()->previous() }}">← Back</a>
    <div>📊 Statistics</div>
    <div>🔔</div>
</div>

<!-- OVERVIEW -->
<div class="section">Account Overview</div>

<div class="grid-2">

    <div class="stat">
        <div class="big">{{ number_format($avgRating ?? 0,1) }}</div>
        <div class="label">⭐ Avg Rating</div>
    </div>

    <div class="stat">
        <div class="big">{{ $totalMessages ?? 0 }}</div>
        <div class="label">💬 Total Inquiries</div>
    </div>

    <div class="stat">
        <div class="big">{{ $totalVisits ?? 0 }}</div>
        <div class="label">📅 Total Visits</div>
    </div>

</div>

<!-- INQUIRY PERFORMANCE -->
<div class="section">💬 Inquiry Performance</div>

<div class="grid-2">

    <div class="stat">
        <div class="big">{{ $totalMessages ?? 0 }}</div>
        <div class="label">📨 Total Received</div>
    </div>

    <div class="stat">
        <div class="big">{{ $unreadMessages ?? 0 }}</div>
        <div class="label">🔴 Unread</div>
    </div>

</div>

<div class="card">
    <div class="small">
        Response Rate
    </div>

    <div style="font-weight:700;margin:.3rem 0;">
        {{ round((($totalMessages - ($unreadMessages ?? 0)) / max($totalMessages,1)) * 100) }}%
    </div>

    <div class="progress">
        <div class="bar" style="width:
        {{ round((($totalMessages - ($unreadMessages ?? 0)) / max($totalMessages,1)) * 100) }}%">
        </div>
    </div>
</div>

<!-- VISITS -->
<div class="section">📅 Visit Statistics</div>

<div class="grid-2">

    <div class="stat">
        <div class="big">{{ $totalVisits ?? 0 }}</div>
        <div class="label">📋 Requested</div>
    </div>

    <div class="stat">
        <div class="big">{{ $approvedVisits ?? 0 }}</div>
        <div class="label">✅ Confirmed</div>
    </div>

    <div class="stat">
        <div class="big">{{ $pendingVisits ?? 0 }}</div>
        <div class="label">⏳ Pending</div>
    </div>

</div>

<div class="card">
    <div class="small">Inquiry → Visit Rate</div>
    <div style="font-weight:700;margin:.3rem 0;">
        {{ $totalMessages ? round(($totalVisits / $totalMessages) * 100) : 0 }}%
    </div>
</div>

<!-- OCCUPANCY -->
<div class="section">🏠 Listing Occupancy</div>

<div class="card">
    <div class="small">Occupancy Rate</div>

    <div style="font-size:1.2rem;font-weight:800;">
        {{ $takenListings ?? 0 }} / {{ $totalListings ?? 0 }}
    </div>

    <div class="progress">
        <div class="bar" style="width:
        {{ $totalListings ? round(($takenListings / $totalListings) * 100) : 0 }}%">
        </div>
    </div>

    <div class="small" style="margin-top:.4rem;">
        {{ $activeListings ?? 0 }} Available · {{ $takenListings ?? 0 }} Taken
    </div>
</div>

<!-- REVIEWS -->
<div class="section">⭐ Ratings & Reviews</div>

<div class="card">
    <div style="font-size:1.6rem;font-weight:800;">
        {{ number_format($avgRating ?? 0,1) }} ★
    </div>

    <div class="small">Reviews coming from listings</div>
</div>

@endsection