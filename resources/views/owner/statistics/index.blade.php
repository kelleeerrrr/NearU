@extends('layouts.owner')

@section('title', 'Statistics — NearU')

@section('content')

<style>

/* HEADER */
.header{
    display:flex;
    align-items:center;
    padding:1rem;
    font-weight:800;
}

.back{
    background: #2D7D4F;
    color: white;
    text-decoration:none;
    padding: 8px 10px;
    border-radius: 8px;
    font-weight:600;
    transition: background 0.3s ease;
}

/* GRID */
.grid-2{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:.6rem;
    padding:0 1rem;
}

/* CARDS */
.card, .stat{
    background:#fff;
    border:1px solid var(--border);
    border-radius:14px;
}

.card{
    margin:.6rem 1rem;
    padding:1rem;
}

.stat{
    padding:1rem;
}

/* TEXT */
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

/* PROGRESS */
.progress{
    height:8px;
    background:#eee;
    border-radius:50px;
    overflow:hidden;
    margin-top:.5rem;
}

.bar{
    height:100%;
    background:var(--green);
}

.small{
    font-size:1rem;
    color: #000;;
}
</style>

<!-- HEADER -->
<div class="header">
    <a href="{{ route('owner.dashboard') }}" class="back">← Back</a>
    <div style="margin-left: 10px;">📊 Statistics</div>
    <div></div>
</div>

<!-- OVERVIEW -->
<div class="section">Account Overview</div>

<div class="grid-2">

    <div class="stat" style="background: linear-gradient(135deg, var(--green) 0%, #1e5a3a 100%); border: 1px solid var(--green);">
        <div class="big" style="color: #fff !important; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">{{ number_format($avgRating ?? 0, 1) }}</div>
        <div class="label" style="color: rgba(255,255,255,0.9) !important;">⭐ Average Rating</div>
    </div>

    <div class="stat" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); border: 1px solid #fbbf24;">
        <div class="big" style="color: #fff !important; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">{{ $totalMessages ?? 0 }}</div>
        <div class="label" style="color: rgba(255,255,255,0.9) !important;">💬 Total Messages</div>
    </div>

    <div class="stat" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); border: 1px solid #fbbf24;">
        <div class="big" style="color: #fff !important; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">{{ $totalVisits ?? 0 }}</div>
        <div class="label" style="color: rgba(255,255,255,0.9) !important;">📅 Total Visits</div>
    </div>

    <div class="stat" style="background: linear-gradient(135deg, var(--green) 0%, #1e5a3a 100%); border: 1px solid var(--green);">
        <div class="big" style="color: #fff !important; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">{{ number_format($avgResponseTime ?? 0, 1) }}h</div>
        <div class="label" style="color: rgba(255,255,255,0.9) !important;">⚡ Avg Response Time</div>
    </div>

</div>

<!-- PERFORMANCE -->
<div class="section">📊 Performance</div>

<div class="grid-2">

    <div class="stat" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border: 1px solid #3b82f6;">
        <div class="big" style="color: #fff !important; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">{{ $conversionRate ?? 0 }}%</div>
        <div class="label" style="color: rgba(255,255,255,0.9) !important;">Message → Visit Conversion</div>
    </div>

    <div class="stat" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border: 1px solid #3b82f6;">
        <div class="big" style="color: #fff !important; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">{{ $dropOffRate ?? 0 }}%</div>
        <div class="label" style="color: rgba(255,255,255,0.9) !important;">Drop-off Rate</div>
    </div>

</div>

<!-- RESPONSE RATE -->
<div class="card" style="border: 2px solid #0e8833;">

    @php
        $responseRate = ($totalMessages ?? 0) > 0
            ? round((($totalMessages - ($unreadMessages ?? 0)) / $totalMessages) * 100)
            : 0;
    @endphp

    <div class="small">Response Rate</div>

    <div style="font-size:1.2rem;font-weight:800;">
        {{ $responseRate }}%
    </div>

    <div class="progress">
        <div class="bar" style="width: {{ $responseRate }}%"></div>
    </div>

</div>

<!-- VISITS -->
<div class="section">📅 Visit Status</div>

<div class="grid-2">

    <div class="stat" style="background: linear-gradient(135deg, var(--green) 0%, #1e5a3a 100%); border: 1px solid var(--green);">
        <div class="big" style="color: #fff !important; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">{{ $totalVisits ?? 0 }}</div>
        <div class="label" style="color: rgba(255,255,255,0.9) !important;">Requested</div>
    </div>

    <div class="stat" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); border: 1px solid #fbbf24;">
        <div class="big" style="color: #fff !important; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">{{ $approvedVisits ?? 0 }}</div>
        <div class="label" style="color: rgba(255,255,255,0.9) !important;">Approved</div>
    </div>

    <div class="stat" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); border: 1px solid #fbbf24;">
        <div class="big" style="color: #fff !important; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">{{ $pendingVisits ?? 0 }}</div>
        <div class="label" style="color: rgba(255,255,255,0.9) !important;">Pending</div>
    </div>

    <div class="stat" style="background: linear-gradient(135deg, var(--green) 0%, #1e5a3a 100%); border: 1px solid var(--green);">
        <div class="big" style="color: #fff !important; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">{{ $completedVisits ?? 0 }}</div>
        <div class="label" style="color: rgba(255,255,255,0.9) !important;">Completed</div>
    </div>

</div>

<!-- LISTINGS -->
<div class="section">🏠 Listings Overview</div>

@php
    $occupancy = ($totalListings ?? 0) > 0
        ? round(($takenListings / $totalListings) * 100)
        : 0;
@endphp

<div class="card" style="border: 2px solid #0e8833;">

    <div class="small">Occupancy Rate</div>

    <div style="font-size:1.2rem;font-weight:800;">
        {{ $takenListings ?? 0 }} / {{ $totalListings ?? 0 }}
    </div>

    <div class="progress">
        <div class="bar" style="width: {{ $occupancy }}%"></div>
    </div>

    <div class="small" style="margin-top:.4rem;">
        {{ $activeListings ?? 0 }} Available · {{ $takenListings ?? 0 }} Taken
    </div>

</div>

<!-- TOP LISTING -->
<div class="section">🏆 Top Performing Listing</div>

<div class="card" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); border: 1px solid #fbbf24;">

    <div style="font-weight:800;">
        {{ $topListing->street ?? 'No data yet' }}
    </div>

    <div class="small">
        Score: {{ $topListing->score ?? 0 }}
    </div>

</div>

<!-- CHART -->
<div class="section">🔥 Weekly Message Trend</div>

<div class="card">
    <canvas id="messageChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(document.getElementById('messageChart'), {
    type: 'line',
    data: {
        labels: @json($chartLabels ?? []),
        datasets: [{
            label: 'Messages',
            data: @json($chartData ?? []),
            borderWidth: 2,
            tension: 0.4
        }]
    }
});
</script>

@endsection