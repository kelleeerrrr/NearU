@extends('layouts.owner')

@section('title', 'My Listings — NearU')

@push('styles')
<style>
/* PAGE TITLE */
.page-title{
    font-family:'Syne';
    font-size:1.1rem;
    font-weight:800;
    margin-bottom:.8rem;
}

/* HEADER */
.header-actions{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:1rem 1.2rem;
}

/* CREATE BUTTON */
.create-btn{
  background:var(--green);
  color:#fff;
  border:none;
  padding:.6rem 1rem;
  border-radius:50px;
  font-weight:800;
  cursor:pointer;
}

.create-btn:disabled{
  background:#ccc !important;
  color:#666 !important;
  cursor:not-allowed;
}

/* VERIFICATION BANNER */
.verification-banner {
  background: linear-gradient(135deg, #FFF3CD, #FFE089);
  border: 2px solid #F2B705;
  border-radius: 12px;
  padding: 15px;
  margin: 0 1.2rem 1rem;
  text-align: center;
  box-shadow: 0 4px 15px rgba(242,183,5,0.2);
}

.verification-banner h4 {
  color: #856404;
  font-size: 16px;
  font-weight: 800;
  margin-bottom: 8px;
}

.verification-banner p {
  color: #856404;
  font-size: 14px;
  margin-bottom: 10px;
}

.verification-link {
  background: linear-gradient(135deg, #2D7D4F, #1e5a3a);
  color: white;
  text-decoration: none;
  padding: 8px 20px;
  border-radius: 6px;
  font-weight: 700;
  font-size: 13px;
  display: inline-block;
  transition: all 0.3s ease;
}

.verification-link:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(45,125,79,0.4);
}

/* FILTER */
.filter-bar{
  display:flex;
  gap:.5rem;
  padding:0 1.2rem 1rem;
}

.filter-bar a{
  padding:.4rem .8rem;
  border-radius:20px;
  text-decoration:none;
  font-size:.8rem;
  border:1px solid var(--border);
  color:#333;
}

.filter-bar a.active{
  background:var(--green);
  color:#fff;
  border:none;
}

/* LISTING CARD */
.listing-card{
  background:var(--card);
  margin:0 1.2rem 1.2rem; /* ✅ bottom spacing */
  padding:1rem;
  border-radius:14px;
  border:1px solid var(--border);
  transition:all .2s ease;
}

.listing-card:hover{
  transform:translateY(-3px);
  box-shadow:0 6px 16px rgba(0,0,0,0.12);
}

/* =========================
   BUTTON SYSTEM (HCI CLEAN)
========================= */

.action-btn{
  padding:.45rem .75rem;
  border-radius:8px;
  font-size:.75rem;
  font-weight:700;
  text-decoration:none;
  border:none;
  cursor:pointer;
  transition:all .2s ease;
  display:inline-block;
}

/* EDIT - BLUE (primary action) */
.btn-edit{
  background:#2563eb;
  color:#fff;
}

.btn-edit:hover{
  background:#1d4ed8;
  transform:translateY(-2px);
}

/* DELETE - RED (danger) */
.btn-delete{
  background:#dc2626;
  color:#fff;
}

.btn-delete:hover{
  background:#b91c1c;
  transform:translateY(-2px);
}

/* STATUS BUTTONS */
.btn-warning{
  background:#f59e0b;
  color:#fff;
}

.btn-success{
  background:var(--green);
  color:#fff;
}

.btn-warning:hover,
.btn-success:hover{
  transform:translateY(-2px);
  opacity:.9;
}

</style>
@endpush

@section('content')

@php
    $statusFilter = request('status');
    $verificationStatus = auth()->user()->verification_status ?? 'not_verified';
@endphp

{{-- HEADER --}}
<div class="header-actions">
  <div class="page-title">🏠 My Listings</div>

  @if($verificationStatus === 'approved')
    <button class="create-btn"
            onclick="location.href='{{ route('owner.listings.create') }}'">
      + Create Listing
    </button>
  @else
    <button class="create-btn" disabled>
      + Create Listing (Verify First)
    </button>
  @endif
</div>

<!-- Success Notification -->
@if(session('success'))
<div id="successNotification" class="success-notification">
    <div class="notification-content">
        <div class="notification-icon">✓</div>
        <div class="notification-message">{{ session('success') }}</div>
    </div>
</div>
@endif

{{-- FILTER --}}
<div class="filter-bar">

    <a href="{{ route('owner.listings.index') }}"
       class="{{ !$statusFilter ? 'active' : '' }}">
        All
    </a>

    <a href="{{ route('owner.listings.index', ['status' => 'available']) }}"
       class="{{ $statusFilter === 'available' ? 'active' : '' }}">
        Available
    </a>

    <a href="{{ route('owner.listings.index', ['status' => 'unavailable']) }}"
       class="{{ $statusFilter === 'unavailable' ? 'active' : '' }}">
        Unavailable
    </a>

</div>

{{-- LISTINGS --}}
@forelse($dormListings as $listing)

<div class="listing-card">

    <div style="font-weight:800;">
        {{ $listing->street }}
    </div>

    <div style="color:var(--green);font-weight:800;">
        ₱{{ number_format($listing->price) }}/mo
    </div>

    <div style="font-size:.8rem;color:#666;">
        Status: {{ ucfirst($listing->status) }}
    </div>

    {{-- ACTIONS --}}
    <div style="display:flex;gap:.5rem;margin-top:.8rem;flex-wrap:wrap;">

        {{-- EDIT --}}
        <a href="{{ route('owner.listings.edit', $listing->id) }}"
           class="action-btn btn-edit">
            Edit
        </a>

        {{-- DELETE --}}
        <form method="POST"
              action="{{ route('owner.listings.delete', $listing->id) }}"
              onsubmit="return confirm('Are you sure you want to delete this listing?')">
            @csrf
            @method('DELETE')

            <button type="submit"
                    class="action-btn btn-delete">
                Delete
            </button>
        </form>

        {{-- STATUS TOGGLE --}}
        @if(strtolower($listing->status) === 'available')

            <form method="POST"
                  action="{{ route('owner.listings.unavailable', $listing->id) }}">
                @csrf
                <button type="submit"
                        class="action-btn btn-warning">
                    Mark Unavailable
                </button>
            </form>

        @else

            <form method="POST"
                  action="{{ route('owner.listings.available', $listing->id) }}">
                @csrf
                <button type="submit"
                        class="action-btn btn-success">
                    Mark Available
                </button>
            </form>

        @endif

    </div>

</div>

@empty
@if($verificationStatus === 'not_verified' || $verificationStatus === 'under_review')
    <div class="verification-banner">
      <h4>🔐 Verification Required</h4>
      <p>You need to complete verification to create and view your listings.</p>
      <a href="{{ route('owner.verification.form') }}" class="verification-link">
        Complete Verification
      </a>
    </div>
@else
    <div style="padding:1rem;color:#666;">
        No listings found.
    </div>
@endif
@endforelse

@endsection

@if(session('success'))
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const notification = document.getElementById('successNotification');
    if (notification) {
        // Show notification
        notification.style.display = 'flex';
        
        // Auto-hide after 3 seconds
        setTimeout(function() {
            notification.style.opacity = '0';
            setTimeout(function() {
                notification.style.display = 'none';
            }, 300);
        }, 3000);
    }
});
</script>

<style>
.success-notification {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 12px 16px;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(16, 185, 129, 0.3);
    margin: 0 1.2rem 1rem;
    display: none;
    align-items: center;
    transition: opacity 0.3s ease;
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 10px;
}

.notification-icon {
    width: 20px;
    height: 20px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 12px;
}

.notification-message {
    font-weight: 600;
    font-size: 14px;
}
</style>
@endpush
@endif