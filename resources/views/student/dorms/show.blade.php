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

.listing-header { background: linear-gradient(135deg, #2D7D4F, #4a9d6a); color: white; padding: 1.5rem; border-radius: 16px; margin-bottom: 1.5rem; box-shadow: 0 6px 24px rgba(45,125,79,0.2); }
.listing-title { font-size: 1.4rem; font-weight: 800; margin-bottom: 0.5rem; }
.listing-subtitle { opacity: 0.9; font-size: 0.95rem; }

.price-type-row { background: white; padding: 1.2rem; border-radius: 14px; margin-bottom: 1rem; box-shadow: 0 2px 12px rgba(0,0,0,0.08); display: flex; justify-content: space-between; align-items: center; }
.enhanced-price { font-size: 1.8rem; font-weight: 800; color: #2D7D4F; }
.enhanced-price small { font-size: 0.9rem; opacity: 0.8; }

.metas { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem; }
.mpill { padding: 0.4rem 0.8rem; font-size: 0.8rem; font-weight: 600; border-radius: 12px; background: #f8f9fa; border: 1px solid #e9ecef; transition: all 0.2s ease; }
.mpill:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
.mpill.ok { background: linear-gradient(135deg, #2D7D4F, #4a9d6a); color: white; border: none; }
</style>
@endpush

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <!-- Back Button -->
      <div style="margin-bottom: 1rem;">
        <button class="icon-btn back-btn" onclick="history.back()" style="background: var(--green); color: white; border: none; padding: 0.6rem 1rem; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; transition: all 0.2s ease;">
          ← Back
        </button>
      </div>

      <!-- Listing Header -->
      <div class="listing-header">
        <h1 class="listing-title">{{ $listing->street }}</h1>
        <div class="listing-subtitle">{{ $listing->type }} • {{ $listing->gender_policy }} • {{ $listing->walk_minutes }} min walk to campus</div>
      </div>

      @php
        $images = $listing->images ?? collect([]);
        
        // Create gallery using direct file serving route
        $gallery = $images->map(function($image) {
            $filename = basename($image->path);
            return url('/photos/' . $filename);
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
      <div class="metas" style="margin-bottom: 1rem;">
        <div class="mpill">{{ $listing->bathroom }}</div>
        <div class="mpill">{{ $listing->gender_policy }}</div>
        <div class="mpill">{{ $listing->walk_minutes }} min walk</div>
        @if($listing->wifi_included)
        <div class="mpill ok">WiFi Included</div>
        @endif
        @if($listing->pets_allowed)
        <div class="mpill">Pets Allowed</div>
        @endif
      </div>

      <!-- Includes -->
      @if($listing->furnishings || $listing->appliances || $listing->bills_included)
      <div class="inc-box" style="margin-bottom: 1rem;">
        <div class="inc-ttl">What's Included</div>
        <div class="inc-grid">
          @if($listing->furnishings)
          <div class="inc-i">🛋️ {{ $listing->furnishings }}</div>
          @endif
          @if($listing->appliances)
          <div class="inc-i">🔌 {{ $listing->appliances }}</div>
          @endif
          @if($listing->bills_included)
          <div class="inc-i">💡 {{ $listing->bills_included }}</div>
          @endif
        </div>
      </div>
      @endif

      <!-- House Rules -->
      @if($listing->curfew)
      <div class="inc-box" style="margin-bottom: 1rem;">
        <div class="inc-ttl">House Rules</div>
        <div class="inc-grid">
          <div class="inc-i">🕐 Curfew: {{ $listing->curfew }}</div>
        </div>
      </div>
      @endif

      <!-- Owner Info -->
      <div class="owner-chip" style="margin-bottom: 1rem;">
        👤 Owner: {{ $listing->owner->name }}
        @if($listing->owner->phone)
        📞 {{ $listing->owner->phone }}
        @endif
      </div>

      <!-- Action Buttons -->
      <div class="btn-row" style="margin-bottom: 1rem;">
        @auth
        <button class="btn btn-out" onclick="toggleSave({{ $listing->id }})">
          @if(auth()->user()->savedListings()->where('dorm_listing_id', $listing->id)->exists())
            ❤️ Saved
          @else
            🤍 Save
          @endif
        </button>
        <button class="btn btn-green" onclick="scheduleVisit({{ $listing->id }})">📅 Schedule Visit</button>
        @endauth
      </div>

      @auth
      <a href="{{ route('messages.show', [$listing->id, $listing->owner->id]) }}" class="btn btn-blue btn-full">💬 Message Owner</a>
      @endauth
    </div>
  </div>

  @include('partials.footer')
</div>

<!-- Add bottom spacing for floating nav bar -->
<div style="height: 6rem;"></div>
@endsection

@push('scripts')
<script>
function toggleSave(dormId) {
  fetch(`/dorms/${dormId}/save`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    },
  }).then(() => location.reload());
}

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