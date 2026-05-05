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

/* VERIFICATION BANNER */
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
  background:#E8F4FD;
  border:2px solid var(--blue);
  color:#0D47A1;
}

.verification-banner.approved{
  background:#E8F7EE;
  border:2px solid var(--green);
  color:#1B5E20;
}

.banner-title{font-weight:800;margin-bottom:.4rem;}
.banner-text{font-size:.9rem;margin-bottom:.8rem;}
.banner-btn{
  display:inline-block;
  padding:.6rem 1rem;
  border-radius:10px;
  font-weight:800;
  text-decoration:none;
  color:#fff;
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
    // ✅ Use the fresh $owner data from controller instead of stale auth()->user()
    $status = $owner->verification_status ?? 'not_verified';
@endphp

<!-- HERO -->
<div class="dash-hero">
  <div class="dash-greeting">Good day,</div>

  <div class="dash-name">
    Welcome back, <em>{{ $owner->name }}</em> 👋
  </div>

  @if($status === 'approved')
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

<!-- ✅ VERIFICATION BANNER (ALWAYS VISIBLE) -->
@if($status === 'under_review')
    <div class="verification-banner under-review">
        <div class="banner-title">⏳ Documents Under Review</div>
        <div class="banner-text">Your verification documents are being reviewed by our admin team. This usually takes 1-3 business days.</div>
    </div>

@elseif($status === 'not_verified' || $status === 'rejected')
     <div class="verification-banner not-verified">
        <div class="banner-title">⚠ Complete Your Verification</div>
        <div class="banner-text">Upload your documents to get verified and unlock all features like creating listings.</div>
        <a href="{{ route('owner.verification.form') }}" class="banner-btn" style="background:var(--gold);color:#000;">Complete Verification →</a>
    <div class="verification-banner not-verified">
        <div class="banner-title">⚠ Complete Your Verification</div>
        <div class="banner-text">Upload your documents to get verified and unlock all features like creating listings.</div>
        <a href="{{ route('owner.verification.form') }}" class="banner-btn" style="background:var(--gold);color:#000;">Complete Verification →</a>
    </div>
@endif

<!-- ✅ DASHBOARD ALWAYS ACCESSIBLE -->

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
  @if($status === 'approved')
    <a href="{{ route('owner.listings.create') }}" class="q-btn green">➕ Add Listing</a>
  @else
    <span class="q-btn green disabled" title="Complete verification to create listings">➕ Add Listing</span>
  @endif

  @if($status === 'approved')
    <a href="{{ route('owner.inquiries.index') }}" class="q-btn gold">💬 Inquiries</a>
  @else
    <span class="q-btn gold disabled" title="Complete verification to access inquiries">💬 Inquiries</span>
  @endif

  @if($status === 'approved')
    <a href="{{ route('owner.visits.index') }}" class="q-btn blue">📅 Visit Requests</a>
  @else
    <span class="q-btn blue disabled" title="Complete verification to access visit requests">📅 Visit Requests</span>
  @endif

  @if($status === 'approved')
    <a href="{{ route('owner.statistics.index') }}" class="q-btn outline">📊 Statistics</a>
  @else
    <span class="q-btn outline disabled" title="Complete verification to access statistics">📊 Statistics</span>
  @endif
</div>

@endsection