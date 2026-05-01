@extends('layouts.owner')

@section('title', 'My Listings — NearU')

@push('styles')
<style>
:root{
  --bg:#F0F7F2;--surface:#fff;--card:#fff;
  --t1:#141F14;--t2:#5E6E5E;--border:#D6E8DC;
  --green:#2D7D4F;--green-lt:#E8F7EE;
  --gold:#F2B705;
  --blue:#3B82F6;--red:#C8102E;
  --sh:0 2px 14px rgba(45,125,79,.08);
  --sh2:0 6px 28px rgba(45,125,79,.16);
}

/* HEADER */
.header-actions{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:1rem 1.2rem .3rem;
}

/* CREATE BUTTON */
.create-btn{
  background:var(--green);
  color:#fff;
  border:none;
  padding:.6rem 1rem;
  border-radius:50px;
  font-weight:800;
  font-size:.8rem;
  cursor:pointer;
}

.create-btn.disabled{
  background:#ccc;
  cursor:not-allowed;
}

/* WARNING BOX */
.ver-warning{
  margin:1rem;
  padding:1rem;
  border-radius:14px;
  background:#FFF4CC;
  border:1px solid var(--gold);
  color:#5a4300;
  font-weight:700;
}

/* FILTER */
.filter-bar{padding:.75rem 1.2rem .5rem;}
.chips{display:flex;gap:.5rem;}
.chip{
  padding:.46rem 1rem;
  border:1.5px solid var(--border);
  border-radius:50px;
  font-size:.77rem;
  font-weight:700;
  cursor:pointer;
}
.chip.on{background:var(--green);color:#fff;border-color:var(--green);}

/* LISTINGS */
.listing-card{
  background:var(--card);
  border-radius:18px;
  margin:0 1.2rem .85rem;
  border:1.5px solid var(--border);
  box-shadow:var(--sh);
  overflow:hidden;
}

/* DISABLED OVERLAY */
.locked{
  opacity:.5;
  pointer-events:none;
}
</style>
@endpush

@section('content')

@php
  // You can replace this with real DB verification field later
  $isVerified = auth()->user()->is_verified ?? false;
@endphp

{{-- HEADER --}}
<div class="header-actions">
  <div style="font-weight:800;">🏠 My Listings</div>

  @if($isVerified)
    <button class="create-btn" onclick="location.href='/owner/listings/create'">
      + Create Listing
    </button>
  @else
    <button class="create-btn disabled" onclick="showVerifyAlert()">
      + Create Listing
    </button>
  @endif
</div>

{{-- WARNING IF NOT VERIFIED --}}
@if(!$isVerified)
<div class="ver-warning">
  ⚠️ You are not fully verified yet.  
  Complete your verification to unlock listing features.
</div>
@endif

{{-- FILTER --}}
<div class="filter-bar">
  <div class="chips">
    <div class="chip on">All</div>
    <div class="chip">Available</div>
    <div class="chip">Taken</div>
  </div>
</div>

{{-- LISTINGS --}}
@if($isVerified)

<div class="listing-card">
  <div style="padding:1rem;">
    <div style="font-weight:800;">Jupiter Street</div>
    <div style="color:var(--green);font-weight:800;">₱3,200 /mo</div>
    <p style="font-size:.8rem;color:#666;">🛏️ Room · 👥 Female · 🚶 9-min walk</p>
  </div>
</div>

@else

{{-- LOCKED STATE --}}
<div class="listing-card locked">
  <div style="padding:1rem;text-align:center;">
    🔒 Listings locked  
    <br><br>
    Complete verification to access your listings
  </div>
</div>

@endif

@endsection

@push('scripts')
<script>

function showVerifyAlert(){
  alert("⚠️ Please complete your verification first to unlock all features.");
}

</script>
@endpush