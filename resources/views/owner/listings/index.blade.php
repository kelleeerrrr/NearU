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
  background: linear-gradient(135deg, #fef3c7 0%, #fefce8 50%, #fff9e6 100%);
  border: 2px solid var(--gold);
  border-radius: 20px;
  padding: 1.5rem;
  margin: 0 1.2rem 1rem;
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

@keyframes float {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-20px) rotate(180deg); }
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

.verification-banner h4 {
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

.verification-link {
  background: linear-gradient(135deg, var(--gold), #f59e0b);
  color: #5a4300;
  text-decoration: none;
  padding: 0.75rem 2rem;
  border-radius: 15px;
  font-weight: 800;
  font-size: 0.9rem;
  display: inline-block;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(242,183,5,0.2);
  position: relative;
  z-index: 2;
  font-family: 'DM Sans', sans-serif;
}

.verification-link:hover {
  transform: translateY(-2px) scale(1.05);
  box-shadow: 0 6px 20px rgba(242,183,5,0.3);
  background: linear-gradient(135deg, #f59e0b, #d4a200);
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
  margin:0 1.2rem 1.2rem; /* ✅ bottom spacing */
  padding:1rem;
  border-radius:14px;
  border:1px solid #a8b3a7;
  border-top: 3px solid var(--border);
  transition:all .2s ease;
  position:relative;
  overflow:hidden;
  background: #fff;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

/* Status-based accent bars instead of solid colors */
.listing-card[data-status="Available"]{
  border:1px solid #a8b3a7;
  border-top: 3px solid var(--green);
}

.listing-card[data-status="Available"]::before{
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, var(--green), #1e5a3a);
}

.listing-card[data-status="Unavailable"]{
  border:1px solid #f2c94c;
  border-top: 3px solid var(--gold);
}

.listing-card[data-status="Unavailable"]::before{
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, var(--gold), #f59e0b);
}

.listing-card:hover{
  transform:translateY(-3px);
  box-shadow:0 8px 24px rgba(0,0,0,0.12);
  border-color: var(--green);
}

/* Text styling for clean white cards */
.listing-card > div{
  position: relative;
  z-index: 1;
}

.listing-card > div[style*="font-weight:800"]{
  color: var(--text) !important;
  text-shadow: none;
}

.listing-card > div[style*="color:var(--green)"]{
  color: var(--green) !important;
  text-shadow: none;
  font-weight: 800 !important;
}

.listing-card > div[style*="font-size:.8rem"]{
  color: var(--muted) !important;
  font-weight: 600 !important;
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

/* EDIT - GREEN (consistent with theme) */
.btn-edit{
  background:var(--green);
  color:#fff;
  border:1px solid var(--green);
}

.btn-edit:hover{
  background:var(--green-dark);
  transform:translateY(-2px);
  box-shadow:0 4px 12px rgba(45,125,79,0.3);
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

    <a href="{{ route('owner.listings.index', ['status' => 'Available']) }}"
       class="{{ $statusFilter === 'Available' ? 'active' : '' }}">
        Available
    </a>

    <a href="{{ route('owner.listings.index', ['status' => 'Unavailable']) }}"
       class="{{ $statusFilter === 'Unavailable' ? 'active' : '' }}">
        Unavailable
    </a>

</div>

{{-- LISTINGS --}}
@forelse($dormListings as $listing)

<div class="listing-card status-{{ $listing->status }}" data-status="{{ $listing->status }}">

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
      <span class="verification-icon">🔐</span>
      <h4>Verification Required</h4>
      <p>You need to complete verification to create and view your listings.</p>
      <a href="{{ route('owner.verification.form') }}" class="verification-link">
        🎯 Complete Verification Now
      </a>
    </div>
@else
    <div style="padding:1rem;color:#666;">
        No listings found.
    </div>
@endif
@endempty

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