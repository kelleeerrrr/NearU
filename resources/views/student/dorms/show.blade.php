@extends('layouts.app')

@section('title', $listing->street . ' - NearU')

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <h2>{{ $listing->street }}</h2>

      <!-- Image Carousel -->
      <div class="carousel" style="margin-bottom: 1rem;">
        <div class="carousel-inner">
          @if($listing->photos)
            @foreach($listing->photos as $photo)
            <div class="carousel-slide">
              <img src="{{ asset('storage/' . $photo) }}" alt="Dorm image">
            </div>
            @endforeach
          @else
            <div class="carousel-slide">
              <img src="https://via.placeholder.com/400x200?text=Dorm+Image" alt="Dorm image">
            </div>
          @endif
        </div>
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
          <div class="inc-i">🕐 Curfew: {{ $dorm->curfew }}</div>
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
</script>
@endpush

@endsection