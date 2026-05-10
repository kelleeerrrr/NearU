@extends('layouts.app')

@section('head')
<meta name="format-detection" content="telephone=no">
@endsection

@section('title', 'Owner Profile – ' . $owner->name)

@section('content')
<div class="wrap owner-page">

  {{-- ── NAVIGATION BAR ── --}}
  @include('partials.navbar')

  {{-- ── OWNER PROFILE SCREEN ── --}}
  <div id="ownerProfile" class="screen active">

    {{-- BACK BUTTON AND OWNER NAME --}}
    <div style="margin: 0.8rem 1.5rem 0.8rem 1.5rem; position: relative; min-height: 120px; padding-top: 1rem; padding-bottom: 0.5rem;">
      <button class="icon-btn back-btn" onclick="history.back()" style="position: absolute; top: 5rem; right: 1.5rem; background: linear-gradient(135deg, var(--green), #1e5a3a); color: white; border: none; padding: 0.6rem 1rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(45,125,79,0.3); transform: translateY(-2px); z-index: 10; pointer-events: auto; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">
    ← Back
  </button>
      <h2 style="margin: 0; font-size: 1.6rem; font-weight: 900; color: #000; font-family: 'Syne', sans-serif; text-shadow: 0 2px 4px rgba(0,0,0,0.1); letter-spacing: 0.5px; animation: title-glow 3s ease-in-out infinite; position: relative; z-index: 2;">👤 {{ $owner->name }}'s Profile</h2>
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

      {{-- OWNER INFO --}}
      <div class="owner-details">
        <h1 class="owner-name" style="font-size: 1.8rem; font-weight: 900; margin-bottom: 0.5rem;">{{ $owner->name }}</h1>

        <div class="owner-meta">
          <span class="owner-email" style="font-size: 1.2rem; font-weight: 500; color: var(--text);">{{ $owner->email }}</span>
        </div>

        <div class="owner-meta">
          <span class="owner-phone" style="font-size: 1.2rem; font-weight: 500; color: var(--text) !important; text-decoration: none !important; pointer-events: none;">{{ $owner->phone ?? 'No phone number' }}</span>
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
              <div class="status-badge {{ $listing->status === 'Available' ? 'available' : 'occupied' }}">
                @if($listing->status === 'Available')
                  <span class="material-symbols-outlined"></span>
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
                        ⭐
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
  min-height: 100vh;
}

/* OWNER CARD */
.owner-card {
    margin-top: 10px;
    border-radius: 20px;
    padding: 0.8rem;
    box-shadow: 0 12px 32px rgba(45,125,79,0.15);
    border: 2px solid var(--gold);
    border-bottom: 4px solid var(--gold) !important;
    transition: all .4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #fffef8 0%, rgba(242,183,5,0.05) 100%) !important;
    transform-style: preserve-3d;
}

.owner-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -60%;
    width: 120px;
    height: 120px;
    background: radial-gradient(circle, rgba(242,183,5,0.1) 0%, transparent 70%);
    animation: float-bubble 7s ease-in-out infinite;
}

.owner-card::after {
    content: '';
    position: absolute;
    bottom: -40%;
    left: -40%;
    width: 80px;
    height: 80px;
    background: radial-gradient(circle, rgba(45,125,79,0.08) 0%, transparent 70%);
    animation: float-bubble 9s ease-in-out infinite reverse;
}

@keyframes float-bubble {
    0%, 100% { transform: translateY(0px) rotate(0deg) scale(1); }
    50% { transform: translateY(-20px) rotate(180deg) scale(1.15); }
}

.owner-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -60%;
    width: 120px;
    height: 120px;
    background: radial-gradient(circle, rgba(242,183,5,0.1) 0%, transparent 70%);
    animation: float-bubble 6s ease-in-out infinite;
}

.owner-card::after {
    content: '';
    position: absolute;
    bottom: -40%;
    left: -40%;
    width: 80px;
    height: 80px;
    background: radial-gradient(circle, rgba(45,125,79,0.08) 0%, transparent 70%);
    animation: float-bubble 8s ease-in-out infinite reverse;
}

@keyframes float-bubble {
    0%, 100% { transform: translateY(0px) rotate(0deg) scale(1); }
    50% { transform: translateY(-20px) rotate(180deg) scale(1.15); }
}

.owner-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 48px rgba(45,125,79,0.25);
    border-color: var(--gold);
}

.owner-card:hover::before {
    top: -40%;
    right: -40%;
    transform: scale(1.3);
}

.owner-card:hover::after {
    bottom: -25%;
    left: -25%;
    transform: scale(1.4);
}

/* OWNER PROFILE STYLING */
.owner-profile {
    display: flex;
    gap: 1rem;
    align-items: center;
    position: relative;
}

.owner-avatar {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    overflow: hidden;
    background: linear-gradient(135deg, var(--gold), #d4a200);
    box-shadow: 0 4px 12px rgba(242,183,5,0.2);
    transition: all .3s ease;
    flex-shrink: 0;
}

.owner-avatar img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
    border-radius: 50% !important;
    aspect-ratio: 1/1 !important;
    display: block !important;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #f8fdf9, #e8f5e8);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: var(--green);
    font-weight: 700;
}

/* OWNER DETAILS */
.owner-details h1 {
    font-family: 'Syne', sans-serif;
    font-size: 1.1rem;
    font-weight: 900;
    color: var(--text);
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    position: relative;
    z-index: 2;
    margin-bottom: 0.4rem;
    display: inline-block;
        padding-bottom: 0.3rem;
}

.owner-details h1::after {
    content: '';
    position: absolute;
    bottom: -6px;
    left: 0;
    width: 50px;
    height: 2px;
    background: var(--gold);
    border-radius: 2px;
    z-index: 3;
}

.owner-card .owner-details h1::after {
    content: '';
    position: absolute;
    bottom: -6px;
    left: 0;
    width: 50px;
    height: 2px;
    background: var(--gold);
    border-radius: 2px;
    z-index: 3;
}

.owner-meta {
    display: flex;
    gap: 0.2rem;
    align-items: center;
    color: var(--text);
    margin-bottom: 0.15rem;
    font-size: 0.55rem;
    font-weight: 500;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
    border-radius: 6px;
    padding: 0.1rem 0.3rem;
    transition: all .3s ease;
}

.owner-meta .material-symbols-outlined {
    font-size: 1.2rem;
    margin-right: 0.5rem;
}

/* VERIFICATION BADGE */
.verification-badge {
    background: linear-gradient(135deg, var(--green), #1e5a3a);
    color: white;
    padding: 0.6rem 1.2rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 700;
    display: inline-block;
    position: relative;
    box-shadow: 0 6px 20px rgba(45,125,79,0.3);
    border: 2px solid rgba(255,255,255,0.3);
    text-transform: uppercase;
    letter-spacing: 0.8px;
    animation: pulse-glow 3s ease-in-out infinite;
}

@keyframes pulse-glow {
    0%, 100% { transform: scale(1); box-shadow: 0 6px 20px rgba(45,125,79,0.3); }
    50% { transform: scale(1.05); box-shadow: 0 8px 24px rgba(45,125,79,0.5); }
}

.verification-badge::before {
    content: '';
    position: absolute;
    top: -10px;
    right: -10px;
    width: 20px;
    height: 20px;
    background: radial-gradient(circle, rgba(242,183,5,0.4) 0%, transparent 70%);
    animation: float-bubble 4s ease-in-out infinite;
}

.verification-badge::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: -8px;
    width: 16px;
    height: 16px;
    background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
    animation: float-bubble 5s ease-in-out infinite reverse;
}

/* STATISTICS GRID */
.stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 5rem;
}

.stat-card {
    background: linear-gradient(135deg, #f8fdf9 0%, rgba(45,125,79,0.05) 100%);
    color: var(--text);
    padding: 1rem;
    border-radius: 16px;
    text-align: center;
    box-shadow: 0 6px 20px rgba(45, 125, 79, 0.2);
    border: 3px solid var(--green);
    transition: all .4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    transform-style: preserve-3d;
    backdrop-filter: blur(10px);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: -40%;
    right: -40%;
    width: 100px;
    height: 100px;
    background: radial-gradient(circle, rgba(242, 183, 5, 0.15) 0%, transparent 70%);
    animation: float-bubble 6s ease-in-out infinite;
}

.stat-card::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -30%;
    width: 80px;
    height: 80px;
    background: radial-gradient(circle, rgba(45, 125, 79, 0.1) 0%, transparent 70%);
    animation: float-bubble 8s ease-in-out infinite reverse;
}

.stat-card:hover {
    transform: translateY(-6px) scale(1.05) rotateX(2deg) rotateY(2deg);
    box-shadow: 0 12px 32px rgba(45, 125, 79, 0.3);
    border-color: var(--gold);
    filter: brightness(1.05);
}

.stat-card:hover::before {
    top: -30%;
    right: -30%;
    transform: scale(1.3) rotate(45deg);
}

.stat-card:hover::after {
    bottom: -20%;
    left: -20%;
    transform: scale(1.4) rotate(-45deg);
}

.stat-number {
    font-size: 2.2rem;
    font-weight: 900;
    font-family: 'Syne', sans-serif;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    position: relative;
    z-index: 2;
    animation: pulse-number 3s ease-in-out infinite;
}

@keyframes pulse-number {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.stat-label {
    font-size: 0.95rem;
    opacity: 0.9;
    font-weight: 600;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
    border-radius: 12px;
    padding: 0.3rem 0.6rem;
    display: inline-block;
    margin-top: 0.5rem;
    position: relative;
    z-index: 2;
}

/* REVIEWS LIST */
.reviews-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.review-card {
    background: white;
    border: 2px solid var(--border);
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: var(--sh);
    transition: all .3s ease;
    position: relative;
    overflow: hidden;
}

.review-card::before {
    content: '';
    position: absolute;
    top: -30%;
    right: -30%;
    width: 80px;
    height: 80px;
    background: radial-gradient(circle, rgba(242, 183, 5, 0.08) 0%, transparent 70%);
    animation: float-bubble 6s ease-in-out infinite;
}

.review-card::after {
    content: '';
    position: absolute;
    bottom: -20%;
    left: -20%;
    width: 60px;
    height: 60px;
    background: radial-gradient(circle, rgba(45, 125, 79, 0.05) 0%, transparent 70%);
    animation: float-bubble 8s ease-in-out infinite reverse;
}

.review-card:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 12px 32px rgba(45, 125, 79, 0.25);
    border-color: var(--gold);
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
  background: linear-gradient(135deg, #258100, #fffb10);
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
  background: #ffc53d;
  border-radius: var(--rad);
  padding: 1.5rem;
  box-shadow: var(--sh);
  border: 1px solid var(--border);
  margin-left: 1rem;
  margin-right:1rem;

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
  background: linear-gradient(180deg, #217238 0%, #ffffff 60%);
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