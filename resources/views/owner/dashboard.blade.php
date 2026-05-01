@extends('layouts.owner')

@section('title', 'Owner Dashboard — NearU')

@push('styles')
<style>

/* HERO */
.dash-hero{
  background:linear-gradient(145deg,#0a1f0e,#1a4d2e,#0f2d4a);
  padding:1.6rem 1.2rem 2.4rem;
  color:#fff;
  position:relative;
  overflow:hidden;
}

.dash-greeting{
  font-size:.8rem;
  opacity:.7;
}

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
  font-size:.7rem;
  padding:.25rem .6rem;
  border-radius:20px;
  font-weight:700;
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
  width:42px;
  height:42px;
  border-radius:12px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:1.2rem;
}

.ds-ico.green{background:var(--green-lt);}
.ds-ico.gold{background:var(--gold-lt);}

.ds-v{
  font-family:'Syne';
  font-weight:800;
  font-size:1.2rem;
}

.ds-l{
  font-size:.7rem;
  color:var(--t2);
}

/* QUICK */
.sec-hdr{
  padding:.5rem 1rem;
  font-weight:800;
}

.quick-grid{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:.6rem;
  padding:0 1rem 1rem;
}

.q-btn{
  padding:.8rem;
  border-radius:14px;
  text-decoration:none;
  text-align:center;
  font-weight:700;
  font-size:.8rem;
}

.q-btn.green{background:var(--green);color:#fff;}
.q-btn.gold{background:var(--gold);color:#1f1f1f;}
.q-btn.blue{background:var(--blue);color:#fff;}
.q-btn.outline{border:2px solid var(--green);color:var(--green);}

/* VERIFICATION BANNER */
.ver-banner{
  margin:0 1rem 1rem;
  padding:1rem;
  border-radius:16px;
  border:2px solid var(--gold);
}

/* LOCKED STATE */
.locked-box{
  margin:1rem;
  padding:1.2rem;
  border-radius:16px;
  background:#FFF7CC;
  border:2px solid #F2B705;
}

.locked-title{
  font-weight:800;
  color:#5A4500;
}

.locked-sub{
  font-size:.8rem;
  margin-top:.4rem;
  color:#5A4500;
}

.locked-btn{
  display:inline-block;
  margin-top:.8rem;
  background:#F2B705;
  padding:.6rem 1rem;
  border-radius:10px;
  font-weight:800;
  text-decoration:none;
  color:#000;
}

</style>
@endpush

@section('content')

@php
    $status = auth()->user()->verification_status;
@endphp

<!-- HERO -->
<div class="dash-hero">
  <div class="dash-greeting">Good day,</div>

  <div class="dash-name">
    Welcome back, <em>{{ auth()->user()->name }}</em> 👋
  </div>

  {{-- STATUS BADGE --}}
  @if($status === 'verified')
      <div class="dash-ver" style="background:rgba(0,200,100,.2); color:#7CFFB2;">
          ✓ Verified Owner
      </div>
  @elseif($status === 'under_review')
      <div class="dash-ver" style="background:rgba(242,183,5,.2); color:#F2B705;">
          ⏳ Under Review
      </div>
  @elseif($status === 'rejected')
      <div class="dash-ver" style="background:rgba(255,80,80,.2); color:#ff6b6b;">
          ❌ Rejected
      </div>
  @else
      <div class="dash-ver" style="background:rgba(242,183,5,.2); color:#F2B705;">
          ⚠ Not Verified
      </div>
  @endif
</div>

<!-- 🚨 IF NOT VERIFIED OR UNDER REVIEW -->
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
      Please wait while we review your submitted documents.
    @elseif($status === 'rejected')
      Your submission was rejected. Please resubmit your documents.
    @else
      You must complete verification before accessing listing features.
    @endif
  </div>

  @if($status === 'not_verified' || $status === 'rejected')
  <a href="{{ route('owner.verification.form') }}" class="locked-btn">
    Go to Verification →
  </a>
  @endif
</div>

@else

<!-- ✅ VERIFIED DASHBOARD -->

<div class="dash-stats">

  <div class="dash-stat">
    <div class="ds-ico green">🏠</div>
    <div>
      <div class="ds-v">3</div>
      <div class="ds-l">Active Listings</div>
    </div>
  </div>

  <div class="dash-stat">
    <div class="ds-ico gold">📊</div>
    <div>
      <div class="ds-v">4.7★</div>
      <div class="ds-l">Avg Rating</div>
    </div>
  </div>

</div>

<div class="sec-hdr">Quick Actions</div>

<div class="quick-grid">
  <a class="q-btn green" href="{{ route('owner.listings.create') }}">➕ Add Listing</a>
  <a class="q-btn gold" href="{{ route('owner.inquiries.index') }}">💬 Inquiries</a>
  <a class="q-btn blue" href="#">📅 Visits</a>
  <a class="q-btn outline" href="{{ route('owner.verification.form') }}">📋 Verification</a>
</div>

<div class="ver-banner">
  <div class="ver-title">Verification Complete</div>
  <div class="ver-sub">All documents approved</div>

  <div style="height:6px;background:#eee;border-radius:50px;margin-top:8px;">
    <div style="width:100%;height:100%;background:#2D7D4F;border-radius:50px;"></div>
  </div>
</div>

@endif

@endsection