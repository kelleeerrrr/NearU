@extends('layouts.owner')

@section('title', 'My Listings — NearU')

@push('styles')
<style>
:root{
  --bg:#F0F7F2;--surface:#fff;--card:#fff;
  --t1:#141F14;--t2:#5E6E5E;--border:#D6E8DC;
  --green:#2D7D4F;--green-lt:#E8F7EE;
  --gold:#F2B705;
  --blue:#3B82F6;
  --red:#C8102E;
  --sh:0 2px 14px rgba(45,125,79,.08);
}

.header-actions{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:1rem 1.2rem .3rem;
}

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

.ver-warning{
  margin:1rem;
  padding:1rem;
  border-radius:14px;
  background:#FFF4CC;
  border:1px solid var(--gold);
  color:#5a4300;
  font-weight:700;
}

.listing-card{
  background:var(--card);
  border-radius:18px;
  margin:0 1.2rem .85rem;
  border:1.5px solid var(--border);
  box-shadow:var(--sh);
  overflow:hidden;
}

.locked{
  opacity:.5;
  pointer-events:none;
}
</style>
@endpush

@section('content')

@php
    $status = auth()->user()->verification_status;
    $isVerified = $status === 'verified';
@endphp

{{-- HEADER --}}
<div class="header-actions">
  <div style="font-weight:800;">🏠 My Listings</div>

  @if($isVerified)
    <button class="create-btn" onclick="location.href='{{ route('owner.listings.create') }}'">
      + Create Listing
    </button>
  @else
    <button class="create-btn disabled" onclick="showVerifyAlert()">
      + Create Listing
    </button>
  @endif
</div>

{{-- STATUS WARNING --}}
@if(!$isVerified)
<div class="ver-warning">
  ⚠️ Your account is currently: <b>{{ ucfirst($status) }}</b><br>
  You must be fully verified to manage listings.
</div>
@endif

{{-- LISTINGS --}}
@if($isVerified)

    @forelse($dormListings as $listing)
        <div class="listing-card">
            <div style="padding:1rem;">
                <div style="font-weight:800;">{{ $listing->street }}</div>

                <div style="color:var(--green);font-weight:800;">
                    ₱{{ number_format($listing->price) }}/mo
                </div>

                <p style="font-size:.8rem;color:#666;">
                    {{ $listing->type }} · {{ $listing->gender ?? 'Any' }}
                </p>

                <span style="font-size:.75rem;color:#5E6E5E;">
                    {{ ucfirst($listing->status) }}
                </span>
            </div>
        </div>
    @empty
        <div style="padding:1rem;color:#666;">
            No listings yet.
        </div>
    @endforelse

@else

<div class="listing-card locked">
  <div style="padding:1.2rem;text-align:center;">
    🔒 Listings are locked<br><br>
    Complete verification to unlock this feature
  </div>
</div>

@endif

@endsection

@push('scripts')
<script>
function showVerifyAlert(){
    alert("⚠️ Please complete your account verification first.");
}
</script>
@endpush