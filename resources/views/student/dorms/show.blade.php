@extends('layouts.app')

@section('title', $listing->street . ' - NearU')

@push('styles')
<style>
.carousel { position: relative; overflow: hidden; border-radius: 18px; background: linear-gradient(135deg, #f8f9f7, #e8f5e8); box-shadow: 0 8px 32px rgba(45,125,79,0.12); }
.carousel-inner { display: flex; transition: transform .4s cubic-bezier(0.4, 0, 0.2, 1); width: 100%; }
.carousel-slide { min-width: 100%; flex-shrink: 0; position: relative; }
.carousel-slide img { width: 100%; height: 280px; object-fit: cover; display: block; }
.carousel-arrow { position: absolute; top: 50%; transform: translateY(-50%); width: 44px; height: 44px; border-radius: 50%; background: rgba(255,255,255,.95); border: none; box-shadow: 0 4px 20px rgba(0,0,0,.2); cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 22px; color: #2D7D4F; z-index: 2; transition: all 0.3s ease; }
.carousel-arrow.prev { left: 16px; }
.carousel-arrow.next { right: 16px; }
.carousel-arrow:hover { transform: translateY(-50%) scale(1.1); background: #fff; box-shadow: 0 6px 24px rgba(0,0,0,.25); }
.carousel-indicator { position: absolute; bottom: 16px; right: 16px; background: rgba(45,125,79,0.9); color: #fff; padding: 8px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; backdrop-filter: blur(10px); }

.listing-header { background: linear-gradient(135deg, #ffe62a, #ebb540); color: #000; padding: 1.5rem; border-radius: 16px; margin-bottom: 1.5rem; box-shadow: 0 6px 24px rgba(45,125,79,0.2); }
.listing-title { font-size: 1.4rem; font-weight: 800; margin-bottom: 0.5rem; }
.listing-subtitle { opacity: 0.9; font-size: 0.95rem; }

.price-type-row { background: white; padding: 1.2rem; border-radius: 14px; margin-bottom: 1rem; box-shadow: 0 2px 12px rgba(0,0,0,0.08); display: flex; justify-content: space-between; align-items: center; }
.enhanced-price { font-size: 1.8rem; font-weight: 800; color: #2D7D4F; }
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

/* Improved Layout Styles */
.details-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 0.75rem;
  padding: 1rem;
  background: linear-gradient(135deg, #f8f9f7, #e8f5e8);
  border-radius: 16px;
  border: 1px solid #e8f5e8;
}

.detail-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 0.75rem;
  background: rgba(255, 255, 255, 0.8);
  border-radius: 12px;
  border: 1px solid #d4e8d4;
  transition: all 0.2s ease;
}

.detail-item:hover {
  background: rgba(255, 255, 255, 0.95);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.1);
}

.detail-icon {
  font-size: 1.2rem;
  flex-shrink: 0;
}

.detail-text {
  font-size: 0.9rem;
  font-weight: 600;
  color: #2D7D4F;
}

.info-section, .owner-section {
  background: linear-gradient(135deg, #f8f9f7, #e8f5e8);
  border-radius: 16px;
  padding: 1rem;
  border: 1px solid #e8f5e8;
}

.section-title {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 0.75rem;
  padding-bottom: 0.5rem;
  border-bottom: 2px solid #d4e8d4;
}

.section-icon {
  font-size: 1.3rem;
  flex-shrink: 0;
}

.section-text {
  font-size: 1.1rem;
  font-weight: 700;
  color: #2D7D4F;
}

.rule-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem;
  background: rgba(255, 255, 255, 0.8);
  border-radius: 12px;
  border: 1px solid #d4e8d4;
}

.rule-icon {
  font-size: 1.2rem;
  flex-shrink: 0;
}

.rule-text {
  font-size: 0.95rem;
  font-weight: 600;
  color: #2D7D4F;
}

.owner-info {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.owner-name, .owner-phone {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem;
  background: rgba(255, 255, 255, 0.8);
  border-radius: 12px;
  border: 1px solid #d4e8d4;
  transition: all 0.2s ease;
}

.owner-name:hover, .owner-phone:hover {
  background: rgba(255, 255, 255, 0.95);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.1);
}

.owner-icon {
  font-size: 1.2rem;
  flex-shrink: 0;
}

.owner-text {
  font-size: 0.95rem;
  font-weight: 600;
  color: #2D7D4F;
}

/* Inclusions Section Styles */
.inclusions-section {
  background: linear-gradient(135deg, #f8f9f7, #e8f5e8);
  border-radius: 16px;
  padding: 1rem;
  border: 1px solid #e8f5e8;
}

.inclusions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 0.5rem;
}

.inclusion-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 0.75rem;
  background: rgba(255, 255, 255, 0.8);
  border-radius: 12px;
  border: 1px solid #d4e8d4;
  transition: all 0.2s ease;
}

.inclusion-item:hover {
  background: rgba(255, 255, 255, 0.95);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.1);
}

.inclusion-icon {
  font-size: 1.1rem;
  flex-shrink: 0;
}

.inclusion-text {
  font-size: 0.85rem;
  font-weight: 600;
  color: #2D7D4F;
}

.btn-green {
  background: linear-gradient(135deg, #2D7D4F, #1f5c38);
  color: white;
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.2);
}

.btn-blue {
  background: linear-gradient(135deg, #007bff, #0056b3);
  color: white;
  box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
}

.btn-full {
  width: 100%;
}

.btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}
</style>
@endpush

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <!-- Back Button and Owner Name -->
      <div style="margin-bottom: 1rem; display: flex; align-items: center; gap: 1rem;">
        <button class="icon-btn back-btn" onclick="history.back()" style="background: var(--green); color: white; border: none; padding: 0.6rem 1rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; transition: all 0.2s ease;">
          ← Back 
        </button>
        <h2 style="margin: 0; font-size: 1.6rem; font-weight: 900; color: var(--green); font-family: 'Segoe UI', 'Arial', sans-serif; letter-spacing: 0.5px;">{{ $listing->owner->name }}'s Listings</h2>
      </div>

      <!-- Listing Header -->
      <div class="listing-header">
        <h1 class="listing-title">{{ $listing->street }}</h1>
        <div class="listing-subtitle">{{ $listing->type }} • {{ $listing->gender_policy }} • {{ $listing->walk_minutes }} min walk to campus</div>
      </div>

      @php
        $images = $listing->images ?? collect([]);
        
        // Create gallery using proper storage path
        $gallery = $images->map(function($image) {
            return asset('storage/' . $image->path);
        })->values()->all();
      @endphp

      <!-- Image Carousel -->
      <div class="carousel" style="margin-bottom: 1rem;">
        <div class="carousel-inner" id="listing-carousel-inner">
          @forelse($gallery as $photo)
            <div class="carousel-slide">
              <img src="{{ $photo }}" alt="Dorm image">
            </div>
          @empty
            <div class="carousel-slide">
              <img src="https://via.placeholder.com/400x200?text=Dorm+Image" alt="Dorm image">
            </div>
          @endforelse
        </div>

        @if(count($gallery) > 1)
          <button type="button" class="carousel-arrow prev" id="carousel-prev">‹</button>
          <button type="button" class="carousel-arrow next" id="carousel-next">›</button>
        @endif

        <div class="carousel-indicator" id="carousel-indicator">1 / {{ count($gallery) ?: 1 }}</div>
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
          <div class="owner-name">
            <span class="owner-icon">👤</span>
            <span class="owner-text">{{ $listing->owner->name }}</span>
          </div>
          @if($listing->owner->phone)
          <div class="owner-phone">
            <span class="owner-icon">📞</span>
            <span class="owner-text">{{ $listing->owner->phone }}</span>
          </div>
          @endif
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="btn-row" style="margin-bottom: 5rem;">
        
        @auth
        <a href="{{ route('messages.show', [$listing->id, $listing->owner->id]) }}" class="btn btn-blue btn-full">💬 Message Owner</a>
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

document.addEventListener('DOMContentLoaded', function() {
  const inner = document.getElementById('listing-carousel-inner');
  const prevBtn = document.getElementById('carousel-prev');
  const nextBtn = document.getElementById('carousel-next');
  const indicator = document.getElementById('carousel-indicator');
  if (!inner) return;

  const slides = inner.querySelectorAll('.carousel-slide');
  const total = slides.length;
  let index = 0;

  function updateCarousel() {
    inner.style.transform = `translateX(-${index * 100}%)`;
    if (indicator) {
      indicator.textContent = `${index + 1} / ${total}`;
    }
    if (prevBtn) {
      prevBtn.style.display = total > 1 ? 'flex' : 'none';
    }
    if (nextBtn) {
      nextBtn.style.display = total > 1 ? 'flex' : 'none';
    }
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', function() {
      index = (index - 1 + total) % total;
      updateCarousel();
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', function() {
      index = (index + 1) % total;
      updateCarousel();
    });
  }

  updateCarousel();
});
</script>
@endpush