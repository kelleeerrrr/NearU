@extends('layouts.app')

@section('title', $listing->street . ' - NearU')

@push('styles')
<style>
.carousel { position: relative; overflow: hidden; border-radius: 14px; background: #f8f9f7; }
.carousel-inner { display: flex; transition: transform .35s ease; width: 100%; }
.carousel-slide { min-width: 100%; flex-shrink: 0; position: relative; }
.carousel-slide img { width: 100%; height: 320px; object-fit: cover; display: block; }
.carousel-arrow { position: absolute; top: 50%; transform: translateY(-50%); width: 38px; height: 38px; border-radius: 50%; background: rgba(255,255,255,.92); border: none; box-shadow: 0 3px 12px rgba(0,0,0,.16); cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #1a2e22; z-index: 2; }
.carousel-arrow.prev { left: 12px; }
.carousel-arrow.next { right: 12px; }
.carousel-arrow:hover { transform: translateY(-50%) scale(1.05); }
.carousel-indicator { position: absolute; bottom: 12px; right: 12px; background: rgba(0,0,0,.58); color: #fff; padding: 6px 11px; border-radius: 999px; font-size: 12px; font-weight: 700; letter-spacing: .02em; }
</style>
@endpush

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <h2>{{ $listing->street }}</h2>

      @php
        // Get photos from the images relationship (this is the correct way)
        $images = $listing->images;
        
        // Create gallery using direct file serving route
        $gallery = $images->map(function($image) {
            $filename = basename($image->path);
            return url('/photos/' . $filename);
        })->values()->all();
        
        // Debug: Show raw data for troubleshooting
        $rawImages = $listing->images->toArray();
        
        // Debug: Log gallery paths
        // Uncomment the line below for debugging
        @php logger()->info('Gallery paths for listing ' . $listing->id . ': ' . json_encode($gallery)); @endphp
        
        <!-- Temporary debug to see URLs -->
        <div style="background: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 5px; font-size: 12px;">
          <strong>Generated URLs:</strong><br>
          @foreach($gallery as $url)
            {{ $url }}<br>
          @endforeach
        </div>
        
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
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <div class="card-price" style="margin: 0;">₱{{ number_format($listing->price, 0) }}<small>/month</small></div>
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

@endsection