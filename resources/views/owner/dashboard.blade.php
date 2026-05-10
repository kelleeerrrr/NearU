@extends('layouts.owner')

@section('title', 'Owner Dashboard')

@push('styles')
<style>
/* HERO */
.dash-hero{
  background:linear-gradient(145deg,#0a1f0e,#1a4d2e,#0f2d4a);
  padding:1.6rem 1.2rem 2.4rem;
  color:#fff;
}

.dash-greeting{font-size:.8rem;opacity:.7;}

.dash-name{
  font-family:'Syne',sans-serif;
  font-size:1.5rem;
  font-weight:800;
  margin-top:.3rem;
}

/* MAIN */
.main-content{
  background:var(--surface);
  border-radius:22px 22px 0 0;
  overflow:hidden;
  margin-top:-30px;
  position:relative;
  z-index:10;
  padding-bottom:2rem;
}

.dash-name em{color:var(--gold);}

.dash-ver{
  display:inline-block;
  margin-top:.5rem;
  font-size:.72rem;
  padding:.25rem .6rem;
  border-radius:20px;
  font-weight:700;
}

/* STATS */
.dash-stats{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:1rem;
  padding:1rem;
}

.dash-stat{
  background:linear-gradient(135deg, #ffffff 0%, #f8fdf9 100%);
  border:3px solid var(--green);
  border-radius:24px;
  padding:1.2rem;
  display:flex;
  align-items:center;
  gap:1rem;
  box-shadow:0 6px 20px rgba(45,125,79,0.12), inset 0 0 0 1px rgba(45,125,79,0.2), 0 0 0 1px rgba(45,125,79,0.15);
  transition:all .3s cubic-bezier(0.4, 0, 0.2, 1);
  position:relative;
  overflow:hidden;
  animation:green-glow 3s ease-in-out infinite;
  text-decoration: none !important;
  color: inherit !important;
}

.dash-stat:hover{
  transform:translateY(-8px) scale(1.03);
  box-shadow:0 15px 40px rgba(45,125,79,0.25);
  border-color: var(--gold);
  background:linear-gradient(135deg, #ffffff 0%, #fefce8 100%);
  text-decoration: none !important;
  color: inherit !important;
}

.dash-stat .ds-v,
.dash-stat .ds-l,
.dash-stat span {
  color: var(--text) !important;
  text-decoration: none !important;
}

.dash-stat .ds-l,
.dash-stat .ds-l span {
  color: var(--t2) !important;
  text-decoration: none !important;
}

@keyframes green-glow {
    0%, 100% { 
        box-shadow:0 6px 20px rgba(45,125,79,0.12), 0 0 20px rgba(45,125,79,0.15), inset 0 0 0 2px rgba(45,125,79,0.2);
    }
    25% { 
        box-shadow:0 6px 20px rgba(45,125,79,0.15), 0 0 30px rgba(45,125,79,0.25), inset 0 0 0 2px rgba(45,125,79,0.3);
    }
    50% { 
        box-shadow:0 6px 20px rgba(45,125,79,0.18), 0 0 40px rgba(45,125,79,0.35), inset 0 0 0 2px rgba(45,125,79,0.4);
    }
    75% { 
        box-shadow:0 6px 20px rgba(45,125,79,0.15), 0 0 30px rgba(45,125,79,0.25), inset 0 0 0 2px rgba(45,125,79,0.3);
    }
}

.dash-stat::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 120px;
  height: 120px;
  background: radial-gradient(circle, rgba(242,183,5,0.15) 0%, transparent 70%);
  transition: all 0.4s ease;
  animation: shimmer 3s ease-in-out infinite;
}

.dash-stat::after {
  content: '';
  position: absolute;
  bottom: -30%;
  left: -30%;
  width: 80px;
  height: 80px;
  background: radial-gradient(circle, rgba(45,125,79,0.08) 0%, transparent 70%);
  animation: shimmer 4s ease-in-out infinite reverse;
}

@keyframes shimmer {
  0%, 100% { transform: translateY(0px) rotate(0deg) scale(1); }
  50% { transform: translateY(-15px) rotate(180deg) scale(1.1); }
}

.dash-stat:hover{
  transform:translateY(-8px) scale(1.03);
  box-shadow:0 15px 40px rgba(45,125,79,0.25);
  border-color: var(--gold);
  background:linear-gradient(135deg, #ffffff 0%, #fefce8 100%);
}

.dash-stat:hover::before {
  top: -30%;
  right: -30%;
  transform: scale(1.2);
}

.dash-stat:hover::after {
  bottom: -20%;
  left: -20%;
  transform: scale(1.3);
}

.ds-ico{
  width:56px;height:56px;
  border-radius:18px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:1.6rem;
  position: relative;
  z-index: 2;
  transition: all 0.4s ease;
  box-shadow:0 4px 12px rgba(0,0,0,0.1);
}

.dash-stat:hover .ds-ico {
  transform: rotate(15deg) scale(1.15) translateY(-3px);
  box-shadow:0 8px 20px rgba(0,0,0,0.15);
}

.gold{background:linear-gradient(135deg, var(--gold-lt), #fff9e6);}

.ds-v{font-family:'Syne';font-weight:800;font-size:1.4rem; position: relative; z-index: 2; text-shadow: 0 1px 2px rgba(0,0,0,0.05);}
.ds-l{font-size:.8rem;color:var(--t2); position: relative; z-index: 2; font-weight: 600;}

/* BADGE */
.stat-badge{
  position:absolute;
  top:-10px;
  right:-10px;
  background:linear-gradient(135deg, #ff6b6b, #ee5a24);
  color:#fff;
  font-size:.7rem;
  font-weight:800;
  padding:.4rem .6rem;
  border-radius:15px;
  box-shadow:0 6px 15px rgba(238,90,36,0.4);
  animation: pulse-bounce 2s infinite;
  z-index: 4;
  border: 2px solid rgba(255,255,255,0.3);
}

@keyframes pulse-bounce {
  0%, 100% { transform: scale(1) translateY(0); }
  25% { transform: scale(1.05) translateY(-2px); }
  50% { transform: scale(1.1) translateY(-4px); }
  75% { transform: scale(1.05) translateY(-2px); }
}

/* QUICK */
.sec-hdr{padding:.6rem 1rem;font-weight:800;}

.quick-grid{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:.6rem;
  padding:0 1rem 2rem;
}

.q-btn{
  padding:1rem;
  border-radius:18px;
  text-align:center;
  font-weight:700;
  text-decoration:none;
  transition:all .3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
  font-family: 'DM Sans', sans-serif;
  box-shadow:0 4px 15px rgba(0,0,0,0.1);
}

.q-btn::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: rgba(255,255,255,0.2);
  transform: translate(-50%, -50%);
  transition: width 0.3s ease, height 0.3s ease;
}

.q-btn:hover{
  transform:translateY(-4px) scale(1.02);
  box-shadow:0 8px 25px rgba(0,0,0,0.15);
}

.q-btn:hover::before {
  width: 300px;
  height: 300px;
}

.q-btn.green{background:linear-gradient(135deg, var(--green), #1b5e20);color:#fff;}
.q-btn.gold{background:linear-gradient(135deg, var(--gold), #d4a200);color:#111;}
.q-btn.blue{background:linear-gradient(135deg, #fff, #f8f9fa);border:2px solid var(--gold);color:var(--gold);}
.q-btn.outline{background:linear-gradient(135deg, #fff, #f8f9fa);border:2px solid var(--green);color:var(--green);}

.q-btn.green:hover{background:linear-gradient(135deg, #1b5e20, #0d3d0f);}
.q-btn.gold:hover{background:linear-gradient(135deg, #d4a200, #b89400);}
.q-btn.blue:hover{background:linear-gradient(135deg, var(--gold), #d4a200);color:#fff;}
.q-btn.outline:hover{background:linear-gradient(135deg, var(--green), #1b5e20);color:#fff;}

.q-btn.disabled{
  background:#ccc !important;
  color:#666 !important;
  cursor:not-allowed;
  pointer-events:none;
}

/* VERIFICATION BANNER */
.verification-banner {
  background: linear-gradient(135deg, #fef3c7 0%, #fefce8 50%, #fff9e6 100%);
  border: 2px solid var(--gold);
  border-radius: 20px;
  padding: 1.5rem;
  margin: 1rem 1rem 0.5rem 1rem;
  text-align: center;
  box-shadow: 0 8px 25px rgba(242,183,5,0.15);
  position: relative;
  overflow: hidden;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.verification-banner::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 200px;
  height: 200px;
  background: radial-gradient(circle, rgba(242,183,5,0.1) 0%, transparent 70%);
  animation: float 6s ease-in-out infinite;
}

.verification-banner::after {
  content: '';
  position: absolute;
  bottom: -30%;
  left: -30%;
  width: 150px;
  height: 150px;
  background: radial-gradient(circle, rgba(45,125,79,0.05) 0%, transparent 70%);
  animation: float 8s ease-in-out infinite reverse;
}

.verification-banner:hover {
  transform: translateY(-4px) scale(1.02);
  box-shadow: 0 12px 35px rgba(242,183,5,0.25);
  border-color: #f59e0b;
}

.verification-banner h3 {
  color: #5a4300;
  font-size: 1.2rem;
  font-weight: 800;
  margin-bottom: 0.5rem;
  font-family: 'Syne', sans-serif;
  position: relative;
  z-index: 2;
}

.verification-banner p {
  color: #6b5900;
  font-size: 0.9rem;
  margin-bottom: 1rem;
  line-height: 1.5;
  position: relative;
  z-index: 2;
}

.verification-icon {
  font-size: 2.5rem;
  margin-bottom: 0.5rem;
  display: block;
  animation: bounce 2s infinite;
  position: relative;
  z-index: 2;
}

@keyframes bounce {
  0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
  40% { transform: translateY(-10px); }
  60% { transform: translateY(-5px); }
}

.verification-btn {
  background: linear-gradient(135deg, var(--gold), #f59e0b);
  color: #5a4300;
  border: none;
  padding: 0.75rem 2rem;
  border-radius: 15px;
  font-weight: 800;
  font-size: 0.9rem;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  display: inline-block;
  box-shadow: 0 4px 15px rgba(242,183,5,0.2);
  position: relative;
  z-index: 2;
  font-family: 'DM Sans', sans-serif;
}

.verification-btn:hover {
  transform: translateY(-2px) scale(1.05);
  box-shadow: 0 6px 20px rgba(242,183,5,0.3);
  background: linear-gradient(135deg, #f59e0b, #d4a200);
}

</style>
@endpush

@section('content')

@php
$status = $owner->verification_status ?? 'not_verified';
@endphp

<!-- HERO -->
<div class="dash-hero">
  <div class="dash-greeting">Good day,</div>
  <div class="dash-name">
    Welcome back, <em>{{ $owner->name }}</em> 👋
  </div>

  @if($status === 'approved')
    <div class="dash-ver" style="background:rgba(0,200,100,.2);color:#7CFFB2;">✓ Verified Owner</div>
  @elseif($status === 'under_review')
    <div class="dash-ver" style="background:rgba(242,183,5,.2);color:#F2B705;">⏳ Under Review</div>
  @else
    <div class="dash-ver" style="background:rgba(242,183,5,.2);color:#F2B705;">⚠ Not Verified</div>
  @endif
</div>

<div class="main-content">

<!-- VERIFICATION BANNER -->
@if($status !== 'approved')
<div class="verification-banner">
  <span class="verification-icon">🔐</span>
  <h3>Complete Verification to Access Features</h3>
  <p>Your account needs to be verified to unlock all features including creating listings, managing inquiries, and viewing statistics.</p>
  <a href="{{ route('owner.verification.form') }}" class="verification-btn">
    🎯 Complete Verification Now
  </a>
</div>
@endif

<!-- STATS -->
<div class="dash-stats">

  <!-- Listings -->
  <div class="dash-stat">
    <div class="ds-ico gold">🏠</div>
    <div>
      <div class="ds-v">{{ $activeListings }}</div>
      <div class="ds-l">Active Listings</div>
    </div>
  </div>

  <!-- Rating -->
  <div class="dash-stat">
    <div class="ds-ico gold">⭐</div>
    <div>
      <div class="ds-v">{{ number_format($avgRating,1) }}</div>
      <div class="ds-l">Avg Rating</div>
    </div>
  </div>

  <!-- 💬 MESSAGES (CLICKABLE) -->
  <a href="{{ route('owner.inquiries.index') }}" class="dash-stat">
    <div class="ds-ico gold">💬</div>
    <div>
      <div class="ds-v" style="color: var(--text) !important;">{{ $unreadMessages }}</div>
      <div class="ds-l">
        @if($unreadMessages > 0)
          <span style="color: var(--t2) !important;font-weight:600;">
            {{ $unreadMessages }} need reply
          </span>
        @else
          All caught up 🎉
        @endif
      </div>
    </div>
  </a>

  <!-- 📅 VISITS (CLICKABLE) -->
  <a href="{{ route('owner.visits.index') }}" class="dash-stat">
    <div class="ds-ico gold">📅</div>
    <div>
      <div class="ds-v" style="color: var(--text) !important;">{{ $pendingVisits }}</div>

      <div class="ds-l">
        @if($pendingVisits > 0)
          <span style="color: var(--t2) !important;font-weight:600;">
            Pending visits
          </span>
        @else
          No pending visits 🎉
        @endif
      </div>
    </div>
  </a>

</div>

<!-- QUICK ACTIONS -->
<div class="sec-hdr">Quick Actions</div>

<div class="quick-grid">
  @if($status === 'approved')
    <a href="{{ route('owner.listings.create') }}" class="q-btn green">➕ Add Listing</a>
    <a href="{{ route('owner.inquiries.index') }}" class="q-btn gold">💬 Inquiries</a>
    <a href="{{ route('owner.visits.index') }}" class="q-btn blue">📅 Visit Requests</a>
    <a href="{{ route('owner.statistics.index') }}" class="q-btn outline">📊 Statistics</a>
  @else
    <span class="q-btn green disabled">➕ Add Listing</span>
    <span class="q-btn gold disabled">💬 Inquiries</span>
    <span class="q-btn blue disabled">📅 Visit Requests</span>
    <span class="q-btn outline disabled">📊 Statistics</span>
  @endif
</div>

</div>
@endsection