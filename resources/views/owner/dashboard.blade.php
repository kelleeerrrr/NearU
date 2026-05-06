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
  margin-top:-20px;
  box-shadow:0 -4px 20px rgba(0,0,0,0.1);
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

/* BANNER */
.verification-banner{
  margin:1rem;
  padding:1.2rem;
  border-radius:16px;
  font-weight:600;
  text-align:center;
}

.verification-banner.not-verified{
  background:#FFF7CC;
  border:2px solid var(--gold);
  color:#5A4500;
}

.verification-banner.under-review{
  background:#FFF7CC;
  border:2px solid var(--gold);
  color:#5A4500;
}

/* STATS */
.dash-stats{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:.7rem;
  padding:1rem;
}

.dash-stat{
  background:rgba(46,125,50,0.1);
  border:2px solid var(--green);
  border-radius:16px;
  padding:1rem;
  display:flex;
  gap:.8rem;
  text-decoration:none;
  color:inherit;
  transition:all .2s ease;
  position:relative;
}

.dash-stat:hover{
  transform:translateY(-3px);
  box-shadow:0 6px 14px rgba(0,0,0,0.15);
}

.ds-ico{
  width:42px;height:42px;
  border-radius:12px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:1.2rem;
}

.gold{background:var(--gold-lt);}

.ds-v{font-family:'Syne';font-weight:800;font-size:1.2rem;}
.ds-l{font-size:.7rem;color:var(--t2);}

/* BADGE */
.stat-badge{
  position:absolute;
  top:-6px;
  right:-6px;
  background:#d32f2f;
  color:#fff;
  font-size:.65rem;
  font-weight:800;
  padding:.2rem .45rem;
  border-radius:10px;
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
  padding:.8rem;
  border-radius:14px;
  text-align:center;
  font-weight:700;
  text-decoration:none;
  transition:all .2s ease;
}

.q-btn:hover{
  transform:translateY(-2px);
}

.q-btn.green{background:var(--green);color:#fff;}
.q-btn.gold{background:var(--gold);color:#111;}
.q-btn.blue{border:2px solid var(--gold);color:var(--gold);}
.q-btn.outline{border:2px solid var(--green);color:var(--green);}

.q-btn.green:hover{background:#1b5e20;}
.q-btn.gold:hover{background:#d4a200;}
.q-btn.blue:hover{background:var(--gold);color:#fff;}
.q-btn.outline:hover{background:var(--green);color:#fff;}

.q-btn.disabled{
  background:#ccc !important;
  color:#666 !important;
  cursor:not-allowed;
  pointer-events:none;
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
      <div class="ds-v">{{ $unreadMessages }}</div>
      <div class="ds-l">
        @if($unreadMessages > 0)
          <span style="color:#d32f2f;font-weight:700;">
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
      <div class="ds-v">{{ $pendingVisits }}</div>

      <div class="ds-l">
        @if($pendingVisits > 0)
          <span style="color:#d32f2f;font-weight:700;">
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