@extends('layouts.app')

@section('title', 'Dorm Listings - NearU')

@push('styles')
<style>
.carousel { position: relative; overflow: hidden; border-radius: 12px; background: #f8f9f7; margin-bottom: 0.9rem; }
.carousel-inner { display: flex; transition: transform .35s ease; width: 100%; }
.carousel-slide { min-width: 100%; flex-shrink: 0; position: relative; }
.carousel-slide img { width: 100%; height: 185px; object-fit: cover; display: block; }
.carousel-btn { position: absolute; top: 50%; transform: translateY(-50%); width: 32px; height: 32px; border-radius: 50%; background: rgba(255,255,255,.88); border: none; box-shadow: 0 2px 8px rgba(0,0,0,.18); cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #1a2e22; z-index: 2; font-weight: 700; }
.carousel-btn.prev { left: 8px; }
.carousel-btn.next { right: 8px; }
.carousel-btn:hover { transform: translateY(-50%) scale(1.06); background: #fff; }
.carousel-counter { position: absolute; bottom: 8px; right: 8px; background: rgba(0,0,0,.52); color: #fff; padding: 4px 9px; border-radius: 999px; font-size: 11px; font-weight: 700; letter-spacing: .02em; }
.dist-overlay { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,.6), transparent); color: #fff; padding: 12px 10px 8px; font-size: 12px; font-weight: 700; }
</style>
@endpush

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <h2>Dorm Listings</h2>
      <form method="POST" action="{{ route('dorms.search') }}" class="search-wrap" style="margin: 1rem 0;">
        @csrf
        <span class="si">🔍</span>
        <input type="text" name="search" placeholder="Search dorms...">
        <button type="submit" style="display: none;"></button>
      </form>

      <div id="dormList">
        @foreach($dormListings as $dorm)
        @php
          $photoPaths = $dorm->images->pluck('path')->filter()->all();
          if (empty($photoPaths) && $dorm->photos) {
              $photoPaths = is_array($dorm->photos)
                  ? $dorm->photos
                  : (json_decode($dorm->photos, true) ?? []);
          }
          $gallery = collect($photoPaths)->filter()->map(fn($path) => asset('storage/' . $path))->values()->all();
          $photoCount = count($gallery) ?: 1;
        @endphp
        <div class="dorm-card">
          <div class="carousel" data-carousel-id="{{ $dorm->id }}">
            <div class="carousel-inner" id="carousel-{{ $dorm->id }}">
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
            @if($photoCount > 1)
              <button type="button" class="carousel-btn prev" onclick="carouselPrev({{ $dorm->id }})">‹</button>
              <button type="button" class="carousel-btn next" onclick="carouselNext({{ $dorm->id }})">›</button>
            @endif
            <div class="carousel-counter" id="counter-{{ $dorm->id }}">1 / {{ $photoCount }}</div>
            <div class="dist-overlay">{{ $dorm->walk_minutes }} min walk</div>
          </div>
          <div class="card-body">
            <div class="rat-row">
              <div class="stars">★★★★★</div>
              <div class="rv">4.8</div>
              <div class="rc">(12 reviews)</div>
            </div>
            <div class="card-top">
              <div>
                <div class="type-badge {{ $dorm->type }}">{{ $dorm->type }}</div>
              </div>
              <div class="heart" onclick="toggleSave({{ $dorm->id }})">
                @if(auth()->check() && auth()->user()->savedListings()->where('dorm_listing_id', $dorm->id)->exists())
                  ❤️
                @else
                  🤍
                @endif
              </div>
            </div>
            <div class="card-street">{{ $dorm->street }}</div>
            <div class="card-price">₱{{ number_format($dorm->price, 0) }}<small>/month</small></div>
            <div class="metas">
              <div class="mpill">{{ $dorm->bathroom }}</div>
              <div class="mpill">{{ $dorm->gender_policy }}</div>
              @if($dorm->wifi_included)
              <div class="mpill ok">WiFi</div>
              @endif
            </div>
            <div class="btn-row">
              <a href="{{ route('dorms.show', $dorm->id) }}" class="btn btn-out">View Details</a>
              @auth
              <button class="btn btn-green" onclick="scheduleVisit({{ $dorm->id }})">Schedule Visit</button>
              @endauth
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  @include('partials.footer')
</div>
@endsection

@push('scripts')
<script>
const carouselStates = {};

function initCarousel(dormId, photoCount) {
  if (!carouselStates[dormId]) {
    carouselStates[dormId] = {
      index: 0,
      total: photoCount
    };
  }
}

function carouselPrev(dormId) {
  initCarousel(dormId, document.querySelectorAll(`#carousel-${dormId} .carousel-slide`).length);
  const state = carouselStates[dormId];
  state.index = (state.index - 1 + state.total) % state.total;
  updateCarousel(dormId);
}

function carouselNext(dormId) {
  initCarousel(dormId, document.querySelectorAll(`#carousel-${dormId} .carousel-slide`).length);
  const state = carouselStates[dormId];
  state.index = (state.index + 1) % state.total;
  updateCarousel(dormId);
}

function updateCarousel(dormId) {
  const inner = document.getElementById(`carousel-${dormId}`);
  const counter = document.getElementById(`counter-${dormId}`);
  const state = carouselStates[dormId];

  if (inner) {
    inner.style.transform = `translateX(-${state.index * 100}%)`;
  }
  if (counter) {
    counter.textContent = `${state.index + 1} / ${state.total}`;
  }
}

function toggleSave(dormId) {
  fetch(`/dorms/${dormId}/save`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      'Content-Type': 'application/json',
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
  document.querySelectorAll('[data-carousel-id]').forEach(carousel => {
    const dormId = carousel.getAttribute('data-carousel-id');
    const slides = carousel.querySelectorAll('.carousel-slide').length;
    initCarousel(dormId, slides);
  });
});
</script>
@endpush
