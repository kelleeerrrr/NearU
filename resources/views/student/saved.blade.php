@extends('layouts.app')

@section('title', 'Saved Listings - NearU')

@push('styles')
<style>
.icon-btn{
  padding:.5rem .8rem;
  border-radius:10px;
  border:1.5px solid var(--border);
  background:var(--card);
  cursor:pointer;
  font-weight:700;
  font-size:.8rem;
}

.back-btn{
  background:var(--green);
  color:#fff;
  border:none;
}
</style>
@endpush

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
        <button class="icon-btn back-btn" onclick="window.location.href='/profile'">
          ← Back
        </button>
        <h2 style="font-size: 1.3rem; font-weight: 800; margin: 0;">❤️ Saved Listings</h2>
      </div>

      @if($savedListings->isEmpty())
        <div class="empty">
          <div class="empty-ic">💔</div>
          <p>No saved listings yet</p>
          <p>Visit the home page to save your favorite dorms!</p>
        </div>
      @else
        <div id="savedList" style="display: flex; flex-direction: column; gap: 1rem;">
          @foreach($savedListings as $saved)
            @php
              $dorm = $saved->dormListing;
              $photos = is_array($dorm->photos) ? $dorm->photos : (json_decode($dorm->photos, true) ?? []);
              $cover = count($photos) ? asset('storage/' . $photos[0]) : 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=400';
            @endphp
            <div class="dorm-card">
              <div class="carousel" style="border-radius: 12px; overflow: hidden; margin-bottom: 0.9rem;">
                <img src="{{ $cover }}" alt="{{ $dorm->street }}" style="width:100%;height:185px;object-fit:cover;cursor:pointer;">
              </div>

              <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.45rem;">
                <div class="type-badge {{ $dorm->type }}">{{ $dorm->type }}</div>
                <span class="heart" style="font-size: 1.5rem; cursor: pointer;" onclick="removeSaved({{ $dorm->id }})">♥</span>
              </div>

              <div class="card-street">{{ $dorm->street }}</div>
              <div class="card-price">₱{{ number_format($dorm->price, 0) }}<small>/month</small></div>

              <div class="metas" style="margin: 0.8rem 0;">
                <span class="mpill">🚶 {{ $dorm->walk_minutes }}-min walk</span>
                <span class="mpill">👥 {{ $dorm->gender_policy }}</span>
                @if($dorm->wifi_included)
                  <span class="mpill ok">📶 WiFi</span>
                @endif
              </div>

              <div class="btn-row">
                <a href="/dorms/{{ $dorm->id }}" class="btn btn-out" style="width: 100%; padding: 0.68rem;">View Details →</a>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>

  @include('partials.footer')
</div>

<form id="remove-saved-form" method="POST" style="display: none;">
  @csrf
  @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function removeSaved(dormId) {
  if (confirm('Remove from saved listings?')) {
    const form = document.getElementById('remove-saved-form');
    form.action = `/dorms/${dormId}/unsave`;
    form.submit();
  }
}
</script>
@endpush
