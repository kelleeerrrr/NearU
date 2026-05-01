@extends('layouts.app')

@section('title', 'Dorm Listings - NearU')

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
        <div class="dorm-card">
          <div class="carousel">
            <div class="carousel-inner">
              <div class="carousel-slide">
                <img src="{{ $dorm->photos ? asset('storage/' . $dorm->photos[0]) : 'https://via.placeholder.com/400x200?text=Dorm+Image' }}" alt="Dorm image">
              </div>
            </div>
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
  // Open schedule modal
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
</script>
@endpush