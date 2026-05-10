@extends('layouts.app')

@section('title', $listing->street . ' - NearU')

@push('styles')
<style>
/* Price and Type Section */
.price-type-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: linear-gradient(135deg, var(--green-lt), #e8f7ee);
  border: 2px solid var(--green);
  border-top: none;
  border-radius: 0 0 18px 18px;
  padding: 1.25rem 1.5rem;
  margin-bottom: 1.5rem;
  box-shadow: 0 6px 24px rgba(45, 125, 79, 0.15);
}

.enhanced-price {
  font-family: 'Syne', sans-serif;
  font-size: 2.2rem;
  font-weight: 800;
  color: var(--green);
  display: flex;
  align-items: baseline;
  gap: 0.25rem;
}

.enhanced-price small {
  font-size: 0.9rem;
  color: var(--t2);
  font-weight: 600;
}

.type-badge {
  padding: 0.5rem 1.2rem;
  border-radius: 25px;
  font-family: 'Syne', sans-serif;
  font-size: 0.85rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transition: all 0.2s ease;
}

.type-badge.Room {
  background: linear-gradient(135deg, var(--green), var(--green-dk));
  color: white;
}

.type-badge.Bedspace {
  background: linear-gradient(135deg, var(--blue), #2563eb);
  color: white;
}

.type-badge.Unit {
  background: linear-gradient(135deg, var(--gold), var(--gold-dk));
  color: #1F2933;
}

.type-badge:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
}
/* OWNER STYLE IMAGES - IMPROVED */
.listing-header {
  background: #FFFACD;
  border: 3px solid #F2B705;
  color: #000;
  padding: 1.5rem;
  border-radius: 16px;
  margin-bottom: 1.5rem;
  box-shadow: 0 8px 32px rgba(45,125,79,0.12);
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
}

.property-title {
  font-family: 'Syne', sans-serif;
  font-size: 1.8rem;
  font-weight: 800;
  color: var(--text-primary);
  margin: 0;
  line-height: 1.2;
}

.listing-title {
  font-family: 'Syne', sans-serif;
  font-size: 1.8rem;
  font-weight: 800;
  color: var(--text-primary);
  margin: 0;
  line-height: 1.2;
}



.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  border-radius: 25px;
  font-family: 'DM Sans', sans-serif;
  font-weight: 600;
  font-size: 0.85rem;
  margin-top: 0.5rem;
}

.status-badge.available {
  background: linear-gradient(135deg, rgba(45, 125, 79, 0.15), rgba(45, 125, 79, 0.05));
  color: var(--green);
  border: 1px solid rgba(45, 125, 79, 0.2);
}

.status-badge.taken {
  background: linear-gradient(135deg, rgba(200, 16, 46, 0.15), rgba(200, 16, 46, 0.05));
  color: var(--red);
  border: 1px solid rgba(200, 16, 46, 0.2);
}

.status-badge::before {
  content: '';
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: currentColor;
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}



.listing-images {
  position: relative;
  border-radius: 18px 18px 0 0;
  overflow: hidden;
  margin-bottom: 0;
  background: linear-gradient(135deg, #f8f9f7, #e8f5e8);
  box-shadow: 0 8px 32px rgba(45,125,79,0.12);
  border: 2px solid var(--border);
  border-bottom: none;
}

.listing-main-img {
  width: 100%;
  height: 320px;
  border-radius: 16px;
  object-fit: cover;
  cursor: pointer;
  transition: all 0.3s ease;
}


.listing-main-img:hover {
  transform: scale(1.02);
  box-shadow: 0 12px 40px rgba(45, 125, 79, 0.2);
}

.image-count {
  position: absolute;
  top: 16px;
  right: 16px;
  background: linear-gradient(135deg, rgba(45, 125, 79, 0.9), rgba(45, 125, 79, 0.7));
  color: #fff;
  padding: 8px 14px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 700;
  backdrop-filter: blur(10px);
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.3);
  border: 1px solid rgba(255, 255, 255, 0.2);
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.image-count::before {
  content: '📷';
  font-size: 14px;
}

.no-image {
  height: 320px;
  border-radius: 16px;
  background: linear-gradient(135deg, #f8f9fa, #e9ecef);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: var(--t2);
  font-size: 1rem;
  font-weight: 600;
  border: 2px dashed var(--border);
}

.no-image .material-symbols-outlined {
  font-size: 3rem;
  margin-bottom: 0.5rem;
  opacity: 0.5;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
}

.property-title {
  font-family: 'Syne', sans-serif;
  font-size: 1.8rem;
  font-weight: 800;
  color: var(--text-primary);
  margin: 0;
  line-height: 1.2;
}
.enhanced-price small { font-size: 0.9rem; opacity: 0.8; }

.metas { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem; }
.mpill { padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600; border-radius: 12px; background: #f8f9fa; border: 1px solid #e9ecef; transition: all 0.2s ease; }
.mpill:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
.mpill.ok { background: linear-gradient(135deg, #2D7D4F, #4a9d6a); color: white; border: none; }

/* Button Styles */
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.75rem 1.5rem;
  border-radius: 12px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  border: none;
  text-decoration: none;
  min-height: 44px;
  white-space: nowrap;
}

/* Enhanced Details Grid */
.details-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 0.5rem;
  padding: 0.75rem;
  background: linear-gradient(135deg, var(--green-lt), #e8f7ee);
  border-radius: 18px;
  border: 2px solid var(--green);
  margin-bottom: 1rem;
  box-shadow: 0 6px 24px rgba(45, 125, 79, 0.15);
}

.detail-item {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.4rem 0.6rem;
  background: rgba(45, 125, 79, 0.1);
  border-radius: 10px;
  border: 1px solid rgba(45, 125, 79, 0.3);
  transition: all 0.3s ease;
}


.detail-item:hover {
  background: rgba(255, 255, 255, 1);
  transform: translateY(-3px);
  box-shadow: 0 8px 24px rgba(45, 125, 79, 0.2);
  border-color: var(--green);
}

.detail-icon {
  font-size: 0.95rem;
  flex-shrink: 0;
  filter: drop-shadow(0 2px 4px rgba(45, 125, 79, 0.2));
}

.detail-text {
  font-size: 0.75rem;
  font-weight: 700;
  color: var(--green);
  font-family: 'DM Sans', sans-serif;
}

.info-section, .owner-section {
  background: linear-gradient(135deg, var(--green-lt), #e8f7ee);
  border: 2px solid var(--green);
  border-radius: 16px;
  padding: 0.75rem;
  margin-bottom: 1rem;
  box-shadow: 0 6px 24px rgba(45, 125, 79, 0.15);
}

.section-title {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 0.5rem;
  padding-bottom: 0.5rem;
  border-bottom: 2px solid rgba(45, 125, 79, 0.3);
}

.section-icon {
  font-size: 1.2rem;
  flex-shrink: 0;
}

.section-text {
  font-size: 1rem;
  font-weight: 700;
  color: var(--green);
}

.rule-item {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  padding: 0.4rem 0.6rem;
  background: rgba(45, 125, 79, 0.1);
  border-radius: 8px;
  border: 1px solid rgba(45, 125, 79, 0.3);
  transition: all 0.3s ease;
}

.rule-item:hover {
  background: rgba(255, 255, 255, 0.95);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.1);
}

.rule-icon {
  font-size: 0.95rem;
  flex-shrink: 0;
}

.rule-text {
  font-size: 0.75rem;
  font-weight: 600;
  color: var(--green);
}

.owner-info {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.owner-name, .owner-contact {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  padding: 0.4rem 0.6rem;
  background: rgba(45, 125, 79, 0.1);
  border-radius: 8px;
  border: 1px solid rgba(45, 125, 79, 0.3);
  transition: all 0.2s ease;
}


.owner-name:hover, .owner-contact:hover {
  background: rgba(255, 255, 255, 0.95);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.1);
}

.owner-icon {
  font-size: 0.95rem;
  flex-shrink: 0;
}

.owner-text {
  font-size: 0.75rem;
  font-weight: 600;
  color: var(--green);
}

.owner-avatar {
  width: 64px;
  height: 64px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid var(--gold);
  box-shadow: 0 4px 12px rgba(242, 183, 5, 0.3);
}

/* Enhanced Inclusions Section */
.inclusions-section {
  background: linear-gradient(135deg, var(--green-lt), #e8f7ee);
  border-radius: 16px;
  padding: 0.75rem;
  border: 2px solid var(--green);
  margin-bottom: 1rem;
  box-shadow: 0 6px 24px rgba(45, 125, 79, 0.15);
}

.inclusions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
  gap: 0.5rem;
}

.inclusion-item {
  display: flex;
  align-items: center;
  gap: 0.3rem;
  padding: 0.3rem 0.5rem;
  background: rgba(45, 125, 79, 0.1);
  border-radius: 8px;
  border: 1px solid rgba(45, 125, 79, 0.3);
  transition: all 0.2s ease;
}


.inclusion-item:hover {
  background: rgba(255, 255, 255, 0.95);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.1);
}

.inclusion-icon {
  font-size: 0.85rem;
  flex-shrink: 0;
}

.inclusion-text {
  font-size: 0.7rem;
  font-weight: 600;
  color: var(--green);
}

/* Enhanced Button Styles */
.btn-green {
  background: linear-gradient(135deg, var(--green), var(--green-dk));
  color: white;
  box-shadow: 0 6px 20px rgba(45, 125, 79, 0.3);
  border: none;
  position: relative;
  overflow: hidden;
}

.btn-green::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s ease;
}

.btn-green:hover::before {
  left: 100%;
}

.btn-blue {
  background: linear-gradient(135deg, var(--blue), #2563eb);
  color: white;
  box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
  border: none;
  position: relative;
  overflow: hidden;
}

.btn-blue::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s ease;
}

.btn-blue:hover::before {
  left: 100%;
}

.btn-full {
  width: 100%;
}

/* Enhanced Action Button Row */
.btn-row {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1rem;
  margin-bottom: 5rem;
}

.btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
}
</style>
@endpush

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <!-- Back Button and Owner Name -->
      <div style="margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
        <h2 style="margin: 0; font-size: 1rem; font-weight: 800; color: #000; font-family: 'Syne', sans-serif; letter-spacing: 0.5px;">👤 {{ $listing->owner->name }}'s Listings</h2>
        <button class="icon-btn back-btn" onclick="history.back()" style="background: var(--green); color: white; border: none; padding: 0.6rem 1rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s ease; white-space: nowrap;">
          ← Back
        </button>
      </div>

      <!-- Listing Header -->
      <div class="listing-header">
        <h1 class="listing-title">{{ $listing->street }}</h1>
        <div class="listing-subtitle">{{ $listing->type }} • {{ $listing->gender_policy }} • {{ $listing->walk_minutes }} min walk to university</div>
      </div>

      @php
        $images = $listing->images ?? collect([]);
        
        // Create gallery using proper storage path
        $gallery = $images->map(function($image) {
            return asset('storage/' . $image->path);
        })->values()->all();
      @endphp

      <!-- Image Gallery -->
      <div class="listing-images">
        @if($listing->images->count())
          <img src="{{ asset('storage/' . $listing->images->first()->path) }}"
               alt="{{ $listing->street }}"
               class="listing-main-img"
               loading="lazy"
               onclick="UI.openLb(this.src)">

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

      <!-- Price and Type -->
      <div class="price-type-row">
        <div class="enhanced-price">₱{{ number_format($listing->price, 0) }}<small>/month</small></div>
        <div class="type-badge {{ $listing->type }}">{{ $listing->type }}</div>
      </div>

      <!-- Details -->
      <div class="details-grid" style="margin-bottom: 1.5rem;">
        <div class="detail-item">
          <span class="detail-icon">🚿</span>
          <span class="detail-text">{{ $listing->bathroom }}</span>
        </div>
        <div class="detail-item">
          <span class="detail-icon">👥</span>
          <span class="detail-text">{{ $listing->gender_policy }}</span>
        </div>
        <div class="detail-item">
          <span class="detail-icon">🚶</span>
          <span class="detail-text">{{ $listing->walk_minutes }} min walk</span>
        </div>
        @if($listing->wifi_included)
        <div class="detail-item">
          <span class="detail-icon">📶</span>
          <span class="detail-text">WiFi Included</span>
        </div>
        @endif
        @if($listing->pets_allowed)
        <div class="detail-item">
          <span class="detail-icon">🐾</span>
          <span class="detail-text">Pets Allowed</span>
        </div>
        @endif
      </div>

      <!-- What's Included -->
      @if($listing->furnishings || $listing->appliances || $listing->bills_included)
      <div class="inclusions-section" style="margin-bottom: 1.5rem;">
        <div class="section-title">
          <span class="section-icon">📦</span>
          <span class="section-text">What's Included</span>
        </div>
        <div class="inclusions-grid">
          @if($listing->furnishings)
            @php
              $furnishings = is_array($listing->furnishings) ? $listing->furnishings : (json_decode($listing->furnishings, true) ?: []);
            @endphp
            @foreach($furnishings as $furnishing)
              @if(!empty($furnishing))
              <div class="inclusion-item">
                <span class="inclusion-icon">🛋️</span>
                <span class="inclusion-text">{{ $furnishing }}</span>
              </div>
              @endif
            @endforeach
          @endif
          
          @if($listing->appliances)
            @php
              $appliances = is_array($listing->appliances) ? $listing->appliances : (json_decode($listing->appliances, true) ?: []);
            @endphp
            @foreach($appliances as $appliance)
              @if(!empty($appliance))
              <div class="inclusion-item">
                <span class="inclusion-icon">🔌</span>
                <span class="inclusion-text">{{ $appliance }}</span>
              </div>
              @endif
            @endforeach
          @endif
          
          @if($listing->bills_included)
            @php
              $bills = is_array($listing->bills_included) ? $listing->bills_included : (json_decode($listing->bills_included, true) ?: []);
            @endphp
            @foreach($bills as $bill)
              @if(!empty($bill))
              <div class="inclusion-item">
                <span class="inclusion-icon">💡</span>
                <span class="inclusion-text">{{ $bill }}</span>
              </div>
              @endif
            @endforeach
          @endif
        </div>
      </div>
      @endif

      <!-- House Rules -->
      @if($listing->curfew)
      <div class="info-section" style="margin-bottom: 1.5rem;">
        <div class="section-title">
          <span class="section-icon">🕐</span>
          <span class="section-text">House Rules</span>
        </div>
        <div class="rule-item">
          <span class="rule-icon">🌙</span>
          <span class="rule-text">Curfew: {{ $listing->curfew }}</span>
        </div>
      </div>
      @endif

      <!-- Owner Info -->
      <div class="owner-section" style="margin-bottom: 1.5rem;">
        <div class="section-title">
          <span class="section-icon">👤</span>
          <span class="section-text">Owner Information</span>
        </div>
        <div class="owner-info">
          <div class="owner-name">{{ $listing->owner->name }}</div>
          <div class="owner-contact">
            @if($listing->owner->phone) 📞 {{ $listing->owner->phone }} @endif
            @if($listing->owner->email) ✉️ {{ $listing->owner->email }} @endif
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="btn-row" style="margin-bottom: 5rem;">
        
        @auth
        <a href="{{ route('messages.show', [$listing->id, $listing->owner->id]) }}" style="background: var(--green); color: white; border: none; padding: 0.6rem 1rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s ease; white-space: nowrap; text-decoration: none; width: 100%;">💬 Message Owner</a>
        @endauth
      </div>
    </div>
  </div>

  @include('partials.footer')
</div>

<!-- Add bottom spacing for floating nav bar -->
<div style="height: 6rem;"></div>
@endsection

@push('scripts')
<script>

function scheduleVisit(dormId) {
  const date = prompt('Enter visit date (YYYY-MM-DD):');
  const time = prompt('Enter visit time (HH:MM):');
  if (date && time) {
    fetch(`/dorms/${dormId}/schedule-visit`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `visit_date=${date}&visit_time=${time}`
    }).then(() => alert('Visit scheduled!'));
  }
}

// No carousel JavaScript needed - using owner-style photo display
</script>
@endpush