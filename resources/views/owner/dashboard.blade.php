@extends('layouts.owner')

@section('title', 'Owner Dashboard — NearU')

@push('styles')
<style>
:root{
  --bg:#F0F7F2;--surface:#fff;
  --t1:#141F14;--t2:#5E6E5E;--border:#D6E8DC;
  --green:#2D7D4F;--gold:#F2B705;--blue:#3B82F6;--red:#C8102E;
  --green-lt:#E8F7EE;--gold-lt:#FFFBEB;
}

body{background:var(--bg);}

/* HERO */
.dash-hero{
  background:linear-gradient(145deg,#0a1f0e,#1a4d2e,#0f2d4a);
  padding:1.6rem 1.2rem 2.4rem;
  color:#fff;
  border-radius:0 0 22px 22px;
}

.dash-greeting{font-size:.8rem;opacity:.7;}

.dash-name{
  font-family:'Syne',sans-serif;
  font-size:1.5rem;
  font-weight:800;
  margin-top:.3rem;
}

.dash-name em{color:var(--gold);font-style:normal;}

.dash-ver{
  display:inline-block;
  margin-top:.5rem;
  font-size:.72rem;
  padding:.25rem .6rem;
  border-radius:20px;
  font-weight:700;
}

/* LOCKED */
.locked-box{
  margin:1rem;
  padding:1.2rem;
  border-radius:16px;
  background:#FFF7CC;
  border:2px solid var(--gold);
}

.locked-title{font-weight:800;color:#5A4500;}
.locked-sub{font-size:.8rem;margin-top:.4rem;color:#5A4500;}

.locked-btn{
  display:inline-block;
  margin-top:.8rem;
  background:var(--gold);
  padding:.6rem 1rem;
  border-radius:10px;
  font-weight:800;
  text-decoration:none;
  color:#000;
}

/* STATS */
.dash-stats{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:.7rem;
  padding:1rem;
}

.dash-stat{
  background:#fff;
  border:1px solid var(--border);
  border-radius:16px;
  padding:1rem;
  display:flex;
  gap:.8rem;
}

.ds-ico{
  width:42px;height:42px;
  border-radius:12px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:1.2rem;
}

.green{background:var(--green-lt);}
.gold{background:var(--gold-lt);}
.blue{background:var(--blue-lt);}
.red{background:#FFF0F2;}

.ds-v{font-family:'Syne';font-weight:800;font-size:1.2rem;}
.ds-l{font-size:.7rem;color:var(--t2);}

/* QUICK */
.sec-hdr{padding:.6rem 1rem;font-weight:800;}

.quick-grid{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:.6rem;
  padding:0 1rem 1rem;
}

.q-btn{
  padding:.8rem;
  border-radius:14px;
  text-align:center;
  font-weight:700;
  text-decoration:none;
}

.q-btn.green{background:var(--green);color:#fff;}
.q-btn.gold{background:var(--gold);color:#111;}
.q-btn.blue{background:var(--blue);color:#fff;}
.q-btn.outline{border:2px solid var(--green);color:var(--green);}
</style>
@endpush

@section('content')

@php
    $status = auth()->user()->verification_status ?? 'not_verified';
@endphp

<!-- HERO -->
<div class="dash-hero">
  <div class="dash-greeting">Good day,</div>

  <div class="dash-name">
    Welcome back, <em>{{ auth()->user()->name }}</em> 👋
  </div>

  @if($status === 'verified')
      <div class="dash-ver" style="background:rgba(0,200,100,.2);color:#7CFFB2;">
          ✓ Verified Owner
      </div>

  @elseif($status === 'under_review')
      <div class="dash-ver" style="background:rgba(242,183,5,.2);color:#F2B705;">
          ⏳ Under Review
      </div>

  @elseif($status === 'rejected')
      <div class="dash-ver" style="background:rgba(255,80,80,.2);color:#ff6b6b;">
          ❌ Rejected
      </div>

  @else
      <div class="dash-ver" style="background:rgba(242,183,5,.2);color:#F2B705;">
          ⚠ Not Verified
      </div>
  @endif
</div>

<!-- 🔒 LOCKED STATES -->
@if($status !== 'verified')

<div class="locked-box">

  <div class="locked-title">
    @if($status === 'under_review')
        Your Verification is Under Review
    @elseif($status === 'rejected')
        Verification Rejected
    @else
        Complete Your Verification
    @endif
  </div>

  <div class="locked-sub">
    @if($status === 'under_review')
        Please wait while admin reviews your documents.
    @elseif($status === 'rejected')
        Your submission was rejected. Please resubmit your documents.
    @else
        You must complete verification before accessing dashboard features.
    @endif
  </div>

  @if($status === 'not_verified' || $status === 'rejected')
      <a href="{{ route('owner.verification.form') }}" class="locked-btn">
          Go to Verification →
      </a>
  @endif

</div>

@else

<!-- ✅ FULLY VERIFIED DASHBOARD (COMPLETE RESTORED) -->

<!-- STATS -->
<div class="dash-stats">

  <div class="dash-stat">
    <div class="ds-ico green">🏠</div>
    <div>
      <div class="ds-v">{{ $activeListings ?? 0 }}</div>
      <div class="ds-l">Active Listings</div>
    </div>
  </div>

  <div class="dash-stat">
    <div class="ds-ico gold">⭐</div>
    <div>
      <div class="ds-v">{{ number_format($avgRating ?? 0,1) }}</div>
      <div class="ds-l">Avg Rating</div>
    </div>
  </div>

  <div class="dash-stat">
    <div class="ds-ico red">💬</div>
    <div>
      <div class="ds-v">{{ $totalMessages ?? 0 }}</div>
      <div class="ds-l">{{ $unreadMessages ?? 0 }} unread</div>
    </div>
  </div>

  <div class="dash-stat">
    <div class="ds-ico blue">📅</div>
    <div>
      <div class="ds-v">{{ $pendingVisits ?? 0 }}</div>
      <div class="ds-l">Visit Requests</div>
    </div>
  </div>

</div>

<!-- QUICK ACTIONS -->
<div class="sec-hdr">Quick Actions</div>

<div class="quick-grid">
  <a href="{{ route('owner.listings.create') }}" class="q-btn green">➕ Add Listing</a>
  <a href="{{ route('owner.inquiries.index') }}" class="q-btn gold">💬 Inquiries</a>
  <a href="{{ route('owner.visits.index') }}" class="q-btn blue">📅 Visit Requests</a>
  <a href="{{ route('owner.statistics.index') }}" class="q-btn outline">📊 Statistics</a>
</div>

<!-- OPTIONAL: YOU CAN ADD LISTINGS + INQUIRIES PREVIEW HERE AGAIN -->
<!-- (kept optional so UI stays clean but nothing is lost from system) -->

@endif

@endsection