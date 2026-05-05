@extends('layouts.app')

@section('title', 'Owner Profile – ' . $owner->name)

@section('content')
<div class="wrap owner-page">

  {{-- ── TOP BAR ── --}}
  <div class="top-bar">
    <button class="tb-btn" onclick="history.back()">
      <span class="material-symbols-outlined">arrow_back</span>
    </button>
    <div class="tb-title">Owner Profile</div>
  </div>

  {{-- ── OWNER INFO ── --}}
  <div class="cs owner-card">
    <div class="owner-profile">
      
      {{-- Avatar --}}
      <div class="owner-avatar">
        @if($owner->profile_photo_path)
          <img src="{{ Storage::url($owner->profile_photo_path) }}" alt="{{ $owner->name }}">
        @else
          <div class="avatar-placeholder">
            <span class="material-symbols-outlined">person</span>
          </div>
        @endif
      </div>

      {{-- Details --}}
      <div class="owner-details">
        <h1>{{ $owner->name }}</h1>

        <div class="owner-meta">
          <span class="material-symbols-outlined">location_on</span>
          <span>{{ $owner->address ?? 'Location not specified' }}</span>
        </div>

        @if($owner->verification_status === 'verified')
          <div class="verification-badge">
            <span class="material-symbols-outlined">verified</span>
            Verified Owner
          </div>
        @else
          <div class="unverified-badge">
            <span class="material-symbols-outlined">warning</span>
            Not Verified
          </div>
        @endif
      </div>

    </div>
  </div>

  {{-- ── LISTINGS ── --}}
  <div class="cs">
    <div class="sec-lbl">
      <span class="material-symbols-outlined">home</span>
      Listings by {{ $owner->name }}
    </div>

    @if($listings->count())
      <div class="listing-grid">
        @foreach($listings as $listing)
        <div class="dorm-card owner-listing-card"
             onclick="window.location.href='{{ route('dorms.show', $listing) }}'">

          {{-- Image --}}
          <div class="listing-images">
            @if($listing->images->count())
              <img src="{{ Storage::url($listing->images->first()->image_path) }}"
                   alt="{{ $listing->title }}"
                   class="listing-main-img">

              @if($listing->images->count() > 1)
                <div class="image-count">
                  +{{ $listing->images->count() - 1 }}
                </div>
              @endif
            @else
              <div class="no-image">
                <span class="material-symbols-outlined">image</span>
              </div>
            @endif
          </div>

          {{-- Content --}}
          <div class="listing-content">

            <div class="listing-header">
              <h3>{{ $listing->title }}</h3>
              <div class="listing-price">
                ₱{{ number_format($listing->price) }}
                <span>/{{ $listing->price_type }}</span>
              </div>
            </div>

            <div class="listing-meta">
              <div class="mpill">
                <span class="material-symbols-outlined">location_on</span>
                {{ $listing->address }}
              </div>
            </div>

            {{-- Reviews --}}
            @php
              $avg = $listing->reviews->avg('rating');
              $count = $listing->reviews->count();
            @endphp

            @if($count > 0)
              <div class="listing-reviews">
                <div class="stars">
                  @for($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= round($avg) ? 'star filled' : 'star' }}">★</span>
                  @endfor
                  <span class="rating-text">
                    {{ number_format($avg, 1) }} ({{ $count }})
                  </span>
                </div>
              </div>
            @endif

            <div class="type-badge {{ strtolower($listing->type) }}">
              {{ $listing->type }}
            </div>

          </div>
        </div>
        @endforeach
      </div>
    @else
      <div class="empty">
        <div class="empty-ic">🏠</div>
        <p>No listings yet from this owner.</p>
      </div>
    @endif
  </div>

</div>

<style>

/* 🌅 PAGE BACKGROUND */
.owner-page {
  background: linear-gradient(180deg, #fff8cc 0%, #ffffff 60%);
  min-height: 100vh;
}

/* OWNER CARD */
.owner-card {
  background: white;
  border-radius: var(--rad);
  padding: 1.5rem;
  box-shadow: var(--sh);
  border: 1px solid var(--border);
}

/* PROFILE */
.owner-profile {
  display: flex;
  gap: 1.2rem;
  align-items: center;
}

.owner-avatar {
  width: 85px;
  height: 85px;
  border-radius: 50%;
  overflow: hidden;
}

.owner-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.avatar-placeholder {
  width: 100%;
  height: 100%;
  background: #f5f5f5;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
}

/* DETAILS */
.owner-details h1 {
  font-family: 'Syne', sans-serif;
  font-size: 1.5rem;
  font-weight: 800;
}

.owner-meta {
  display: flex;
  gap: .4rem;
  font-size: .85rem;
  color: var(--t2);
}

/* BADGES */
.verification-badge {
  background: #e6f7f0;
  color: #1a7f5a;
}

.unverified-badge {
  background: #fff3e0;
  color: #e65100;
}

.verification-badge,
.unverified-badge {
  display: inline-flex;
  gap: .3rem;
  padding: .3rem .6rem;
  border-radius: 20px;
  font-size: .75rem;
  font-weight: 700;
  margin-top: .4rem;
}

/* GRID */
.listing-grid {
  display: grid;
  gap: 1rem;
}

/* CARD */
.owner-listing-card {
  background: white;
  border-radius: 16px;
  padding: 1rem;
  cursor: pointer;
  transition: .25s ease;
  border: 1px solid #eee;
}

.owner-listing-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

/* IMAGE */
.listing-main-img {
  width: 100%;
  height: 180px;
  border-radius: 12px;
  object-fit: cover;
}

.no-image {
  height: 180px;
  border-radius: 12px;
  background: #fafafa;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* TEXT */
.listing-header {
  display: flex;
  justify-content: space-between;
}

.listing-price {
  font-weight: 800;
  color: #1a7f5a;
}

/* STARS */
.star {
  color: #ddd;
}

.star.filled {
  color: #ffc107;
}

.rating-text {
  font-size: .75rem;
  margin-left: .3rem;
  color: var(--t2);
}

</style>
@endsection