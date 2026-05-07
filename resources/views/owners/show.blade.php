@extends('layouts.app')

@section('title', 'Owner Profile – ' . $owner->name)

@section('content')
<div class="wrap owner-page">

  {{-- ── NAVIGATION BAR ── --}}
  @include('partials.navbar')

  {{-- ── OWNER PROFILE SCREEN ── --}}
  <div id="ownerProfile" class="screen active">

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
          <span class="material-symbols-outlined">Email:</span>
          <span>{{ $owner->email }}</span>
        </div>

        <div class="owner-meta">
          <span class="material-symbols-outlined">Phone:</span>
          <span>{{ $owner->phone ?? 'No phone number' }}</span>
        </div>

        <div class="verification-badge">
          Fully Verified
        </div>
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
      <div class="listings-container">
        @foreach($listings as $listing)
        <div class="listing-card owner-listing-card"
             onclick="window.location.href='{{ route('dorms.show', $listing) }}'">

          {{-- Image --}}
          <div class="listing-images">
            @if($listing->images->count())
              <img src="{{ asset('storage/' . $listing->images->first()->path) }}"
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
                <span class="material-symbols-outlined"></span>
                {{ $listing->street }}
              </div>
            </div>

            {{-- Status/Availability --}}
            <div class="listing-status">
              <div class="status-badge {{ $listing->status === 'available' ? 'available' : 'occupied' }}">
                @if($listing->status === 'available')
                  <span class="material-symbols-outlined">check_circle</span>
                  Available
                @else
                  Occupied
                @endif
              </div>
            </div>

            <div class="type-badge {{ strtolower($listing->type) }}">
              {{ $listing->type }}
            </div>

            {{-- MINI REVIEWS SECTION --}}
            @if($listing->reviews->count() > 0)
            <div class="mini-reviews">
              <div class="mini-reviews-header">
                <span class="material-symbols-outlined">star</span>
                <span>Reviews ({{ $listing->reviews->count() }})</span>
              </div>
              
              @foreach($listing->reviews->take(2) as $review)
              <div class="mini-review">
                <div class="mini-review-header">
                  <div class="mini-reviewer">{{ $review->user->name ?? 'Anonymous' }}</div>
                  <div class="mini-rating">
                    @for($i = 1; $i <= 5; $i++)
                      @if($i <= $review->rating)
                        ⭐
                      @else
                        ☆
                      @endif
                    @endfor
                  </div>
                </div>
                <div class="mini-review-text">{{ Str::limit($review->comment ?? 'No comment', 50) }}</div>
              </div>
              @endforeach
              
              @if($listing->reviews->count() > 2)
              <div class="more-reviews">+{{ $listing->reviews->count() - 2 }} more reviews</div>
              @endif
            </div>
            @endif

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

  {{-- ── STATISTICS ── --}}
  @if(isset($totalListings) || isset($totalReviews) || isset($averageRating))
  <div class="cs">
    <div class="sec-lbl">
      <span class="material-symbols-outlined">analytics</span>
      Statistics
    </div>
    
    <div class="stats-grid">
      @if(isset($totalListings))
        <div class="stat-card">
          <div class="stat-number">{{ $totalListings }}</div>
          <div class="stat-label">Total Listings</div>
        </div>
      @endif
      
      @if(isset($totalReviews))
        <div class="stat-card">
          <div class="stat-number">{{ $totalReviews }}</div>
          <div class="stat-label">Total Reviews</div>
        </div>
      @endif
      
      @if(isset($averageRating))
        <div class="stat-card">
          <div class="stat-number">{{ number_format($averageRating, 1) }}</div>
          <div class="stat-label">Average Rating</div>
        </div>
      @endif
      
      @if(isset($responseRate))
        <div class="stat-card">
          <div class="stat-number">{{ number_format($responseRate, 0) }}%</div>
          <div class="stat-label">Response Rate</div>
        </div>
      @endif
    </div>
  </div>
  @endif

  </div>

  {{-- ── FOOTER ── --}}
  @include('partials.footer')

</div>

<style>

/* 🌅 PAGE BACKGROUND */
.owner-page {
  background: linear-gradient(180deg, #3dab33 0%, #ffffff 60%);
  min-height: 100vh;
}

/* OWNER CARD */
.owner-card {
  margin-top: 10px;
  border-radius: var(--rad);
  padding: 1.5rem;
  box-shadow: var(--sh);
  border: #000;
}

/* OWNER PROFILE STYLING */
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
  background: white;
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

/* OWNER DETAILS */
.owner-details h1 {
  font-family: 'Syne', sans-serif;
  font-size: 1.5rem;
  font-weight: 800;
}

.owner-meta {
  display: flex;
  gap: .4rem;
  align-items: center;
  color: #000;
  margin-bottom: 0.5rem;
  font-size: .1rem;
}

.owner-meta .material-symbols-outlined {
  font-size: 1rem;
}

.verification-badge {
  background: linear-gradient(135deg, #2D7D4F, #1f5c38);
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  display: inline-block;
  box-shadow: 0 2px 8px rgba(45, 125, 79, 0.3);
  margin-top: 0.5rem;
  margin-bottom: 0.5rem;
}

/* STATISTICS GRID */
.stats-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-bottom: 5rem;
}

.stat-card {
  background: linear-gradient(135deg, #2D7D4F, #1f5c38);
  color: white;
  padding: 1rem;
  border-radius: 12px;
  text-align: center;
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.2);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(45, 125, 79, 0.3);
}

.stat-number {
  font-size: 2rem;
  font-weight: 800;
  margin-bottom: 0.5rem;
}

.stat-label {
  font-size: 0.9rem;
  opacity: 0.9;
}

/* REVIEWS LIST */
.reviews-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.review-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: var(--sh);
}

.review-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.reviewer-name {
  font-weight: 600;
  color: var(--t1);
  font-size: 1.1rem;
}

.rating-stars {
  color: var(--gold);
  font-size: 1rem;
}

.review-content {
  color: var(--t2);
  line-height: 1.6;
  margin-bottom: 0.5rem;
  font-size: 0.95rem;
}

.review-date {
  font-size: 0.8rem;
  color: var(--t2);
}

/* DARK MODE SUPPORT */
body.dark .stat-card {
  background: linear-gradient(135deg, #1e1e1e, #2a2a2a);
}

body.dark .review-card {
  background: var(--card);
  border-color: var(--border);
}

body.dark .reviewer-name {
  color: var(--t1);
}

body.dark .review-content {
  color: var(--t2);
}

body.dark .review-date {
  color: var(--t2);
}


/* LISTINGS CONTAINER */
.listings-container {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.listing-card {
  background: linear-gradient(135deg, #ffd52b, #df1b1b);
  border: 1px solid #e9ecef;
  border-radius: 14px;
  overflow: hidden;
  cursor: pointer;
  transition: transform 0.2s ease;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.listing-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(45, 125, 79, 0.2);
  border-color: #2D7D4F;
}

/* MINI REVIEWS SECTION */
.mini-reviews {
  margin-top: 1rem;
  padding: 0.75rem;
  background: linear-gradient(135deg, #f8f9fa, #e9ecef);
  border-radius: 8px;
  border-top: 2px solid #2D7D4F;
}

.mini-reviews-header {
  display: flex;
  align-items: center;
  gap: 0.3rem;
  margin-bottom: 0.5rem;
  font-size: 0.85rem;
  font-weight: 600;
  color: #2D7D4F;
}

.mini-review {
  margin-bottom: 0.5rem;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid rgba(45, 125, 79, 0.1);
}

.mini-review:last-child {
  margin-bottom: 0;
  padding-bottom: 0;
  border-bottom: none;
}

.mini-review-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.25rem;
}

.mini-reviewer {
  font-size: 0.8rem;
  font-weight: 500;
  color: var(--t1);
}

.mini-rating {
  font-size: 0.7rem;
}

.mini-review-text {
  font-size: 0.75rem;
  color: var(--t2);
  line-height: 1.3;
}

.more-reviews {
  font-size: 0.75rem;
  color: #2D7D4F;
  font-weight: 500;
  text-align: center;
  padding-top: 0.25rem;
  border-top: 1px solid rgba(45, 125, 79, 0.1);
  margin-top: 0.5rem;
}

/* DARK MODE FOR LISTINGS */
body.dark .listing-card {
  background: var(--card);
  border-color: var(--border);
}

body.dark .mini-reviews {
  background: rgba(255, 255, 255, 0.05);
  border-top-color: var(--border);
}

body.dark .mini-review {
  border-bottom-color: var(--border);
}

body.dark .mini-reviewer {
  color: var(--t1);
}

body.dark .mini-review-text {
  color: var(--t2);
}

body.dark .more-reviews {
  border-top-color: var(--border);
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
  background: linear-gradient(180deg, #dfb622 0%, #ffffff 60%);
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

/* STATUS BADGE */
.listing-status {
  margin: 0.5rem 0;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.3rem;
  padding: 0.3rem 0.8rem;
  border-radius: 12px;
  font-size: 0.8rem;
  font-weight: 600;
}

.status-badge.available {
  background: #E8F7EE;
  color: #1B5E20;
}

.status-badge.occupied {
  background: #FEF2F2;
  color: #991B1B;
}

.status-badge .material-symbols-outlined {
  font-size: 16px;
}

/* DARK MODE FOR STATUS */
body.dark .status-badge.available {
  background: rgba(27, 94, 32, 0.2);
  color: #4CAF50;
}

body.dark .status-badge.occupied {
  background: rgba(153, 27, 27, 0.2);
  color: #F44336;
}

</style>

@endsection