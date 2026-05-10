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
    background:var(--card);
    border:5px solid var(--green);
    border-radius:28px;
    transition:all .4s cubic-bezier(0.4, 0, 0.2, 1);
    position:relative;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(45,125,79,0.18), inset 0 0 0 2px rgba(242,183,5,0.4), 0 0 0 1px rgba(45,125,79,0.2);
    background-origin: border-box;
    background-clip: padding-box;
    backdrop-filter: blur(10px);
    transform-style: preserve-3d;
    perspective: 1000px;
    animation:border-pulse 3s ease-in-out infinite;
}

@keyframes border-pulse {
    0%, 100% { 
        box-shadow:0 15px 40px rgba(45,125,79,0.18), inset 0 0 0 2px rgba(242,183,5,0.2), 0 0 0 1px rgba(45,125,79,0.2);
    }
    25% { 
        box-shadow:0 15px 40px rgba(242,183,5,0.15), inset 0 0 0 2px rgba(242,183,5,0.3), 0 0 0 1px rgba(242,183,5,0.15);
    }
    50% { 
        box-shadow:0 15px 40px rgba(242,183,5,0.18), inset 0 0 0 2px rgba(242,183,5,0.4), 0 0 0 1px rgba(242,183,5,0.2);
    }
    75% { 
        box-shadow:0 15px 40px rgba(242,183,5,0.15), inset 0 0 0 2px rgba(242,183,5,0.3), 0 0 0 1px rgba(242,183,5,0.15);
    }
}

.card::before {
    content:'';
    position:absolute;
    top:-60%;
    right:-60%;
    width:140px;
    height:140px;
    background:radial-gradient(circle, rgba(242,183,5,0.12) 0%, transparent 70%);
    animation:float-bubble 7s ease-in-out infinite;
}

.card::after {
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

.card:hover {
    transform:translateY(-12px) rotateX(2deg) rotateY(2deg) scale(1.05);
    box-shadow:0 25px 60px rgba(45,125,79,0.35);
    border-color:var(--gold);
    filter: brightness(1.05);
}

.card:hover::before {
    top:-35%;
    right:-35%;
    transform:scale(1.4) rotate(45deg);
}

.card:hover::after {
    bottom:-20%;
    left:-20%;
    transform:scale(1.5) rotate(-45deg);
}

.card:hover .big {
    transform:scale(1.1);
    text-shadow:0 4px 8px rgba(0,0,0,0.3);
}

.card:hover .label {
    transform:translateY(-2px);
    color:var(--green) !important;
}

.card{
    margin:.6rem 1rem;
    padding:1rem;
}

.stat{
    padding:1rem;
    position:relative;
    z-index:2;
}

/* TEXT */
.big{
    font-size:1.6rem;
    font-weight:900;
    color:var(--text) !important;
    text-shadow:0 3px 6px rgba(0,0,0,0.2);
    position:relative;
    z-index:2;
    background:linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.08) 100%);
    border-radius:16px;
    padding:0.4rem 0.6rem;
    animation:pulse-glow 3s ease-in-out infinite;
    display:inline-block;
    min-width:90px;
    text-align:center;
    letter-spacing:0.5px;
}

@keyframes pulse-glow {
    0%, 100% { transform:scale(1); }
    50% { transform:scale(1.05); }
}

.label{
    font-size:.8rem;
    color:var(--t2) !important;
    font-weight:600;
    text-transform:uppercase;
    letter-spacing:0.8px;
    margin-top:0.3rem;
    display:block;
    text-align:center;
    position:relative;
    z-index:2;
}

.label::before {
    content:'';
    position:absolute;
    bottom:-2px;
    left:50%;
    transform:translateX(-50%);
    width:20px;
    height:2px;
    background:linear-gradient(90deg, transparent, var(--gold), transparent);
    border-radius:1px;
    animation:shimmer-line 2s ease-in-out infinite;
}

@keyframes shimmer-line {
    0%, 100% { opacity:0.3; transform:translateX(-50%) scaleX(0.5); }
    50% { opacity:1; transform:translateX(-50%) scaleX(1); }
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
    <div style="margin-left: 10px; font-family: 'Syne', sans-serif; font-weight: 800;">📊 Statistics</div>
    <div></div>
</div>

<!-- OVERVIEW -->
<div class="section">Account Overview</div>

<div class="grid-2">

    <div class="stat" style="background: var(--card); border: 2px solid var(--border);">
        <div class="big" style="color: var(--text) !important; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">{{ number_format($avgRating ?? 0, 1) }}</div>
        <div class="label" style="color: var(--t2) !important;">⭐ Average Rating</div>
    </div>

    <div class="stat" style="background: var(--card); border: 1.5px solid var(--border);">
        <div class="big" style="color: var(--text) !important;">{{ $totalMessages ?? 0 }}</div>
        <div class="label" style="color: var(--t2) !important;">💬 Total Messages</div>
    </div>

    <div class="stat" style="background: var(--card); border: 1.5px solid var(--border);">
        <div class="big" style="color: var(--text) !important;">{{ $totalVisits ?? 0 }}</div>
        <div class="label" style="color: var(--t2) !important;">📅 Total Visits</div>
    </div>

    <div class="stat" style="background: var(--card); border: 1.5px solid var(--border);">
        <div class="big" style="color: var(--text) !important;">{{ number_format($avgResponseTime ?? 0, 1) }}h</div>
        <div class="label" style="color: var(--t2) !important;">⚡ Avg Response Time</div>
    </div>

</div>

<!-- PERFORMANCE -->
<div class="section">📊 Performance</div>

<div class="grid-2">

    <div class="stat" style="background: var(--green); border: 1.5px solid var(--green);">
        <div class="big" style="color: #fff !important;">{{ $conversionRate ?? 0 }}%</div>
        <div class="label" style="color: rgba(255,255,255,0.9) !important;">📈 Conversion Rate</div>
    </div>

    <div class="stat" style="background: var(--green); border: 1.5px solid var(--green);">
        <div class="big" style="color: #fff !important;">{{ $dropOffRate ?? 0 }}%</div>
        <div class="label" style="color: rgba(255,255,255,0.9) !important;">📉 Drop-off Rate</div>
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

    <div class="stat" style="background: var(--card); border: 1.5px solid var(--border);">
        <div class="big" style="color: var(--text) !important;">{{ $totalVisits ?? 0 }}</div>
        <div class="label" style="color: var(--t2) !important;">📊 Total Visits</div>
    </div>

    <div class="stat" style="background: var(--card); border: 1.5px solid var(--border);">
        <div class="big" style="color: var(--text) !important;">{{ $approvedVisits ?? 0 }}</div>
        <div class="label" style="color: var(--t2) !important;">✅ Approved</div>
    </div>

    <div class="stat" style="background: linear-gradient(to top, #fef3c7 0%, #fefce8 50%, var(--card) 100%) !important; border: 1.5px solid var(--border);">
        <div class="big" style="color: var(--text) !important;">{{ $pendingVisits ?? 0 }}</div>
        <div class="label" style="color: var(--t2) !important;">⏳ Pending</div>
    </div>

    <div class="stat" style="background: linear-gradient(to top, #fef3c7 0%, #fefce8 50%, var(--card) 100%) !important; border: 1.5px solid var(--border);">
        <div class="big" style="color: var(--text) !important;">{{ $completedVisits ?? 0 }}</div>
        <div class="label" style="color: var(--t2) !important;">✅ Completed</div>
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

    <div class="small" style="background: var(--green); color: #fff; padding: 0.2rem 0.4rem; border-radius: 8px; margin-bottom: 0.2rem;">Occupancy Rate</div>

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

<div class="card" style="background: linear-gradient(135deg, #fff9e6 0%, #fef3c7 30%, #f2b705 70%, #fefce8 100%); border: 1px solid var(--gold); box-shadow: 0 4px 12px rgba(242,183,5,0.2);">

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