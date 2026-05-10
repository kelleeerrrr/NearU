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

/* Improved saved listings styling */
.dorm-card {
  background: #FFF9E6;
  border: 2px solid var(--gold);
  border-radius: 16px;
  padding: 1rem;
  box-shadow: 0 2px 8px rgba(242, 183, 5, 0.15);
  transition: all 0.2s ease;
}

.dorm-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(242, 183, 5, 0.25);
  border-color: #E6A800;
}

.card-street {
  font-family: 'Syne', sans-serif;
  font-weight: 700;
  font-size: 1.1rem;
  color: var(--t1);
  line-height: 1.3;
  margin-bottom: 0.5rem;
}

.card-price {
  font-family: 'DM Sans', sans-serif;
  font-weight: 600;
  font-size: 0.95rem;
  color: var(--green);
}

.type-badge {
  font-family: 'DM Sans', sans-serif;
  font-weight: 600;
  font-size: 0.75rem;
  padding: 0.3rem 0.8rem;
  border-radius: 20px;
  display: inline-block;
}

.type-badge.Room {
  background: rgba(45, 125, 79, 0.1);
  color: var(--green);
}

.type-badge.Bedspace {
  background: rgba(242, 183, 5, 0.1);
  color: var(--gold);
}

.type-badge.Unit {
  background: rgba(99, 102, 241, 0.1);
  color: #6366f1;
}

.metas {
  display: flex;
  flex-wrap: wrap;
  gap: 0.4rem;
  margin-bottom: 1rem;
}

.mpill {
  font-family: 'DM Sans', sans-serif;
  font-weight: 500;
  font-size: 0.7rem;
  padding: 0.25rem 0.6rem;
  border-radius: 12px;
  background: rgba(94, 110, 94, 0.08);
  color: var(--t2);
}

.mpill.ok {
  background: rgba(45, 125, 79, 0.1);
  color: var(--green);
}

.carousel {
  position: relative;
  border-radius: 12px;
  overflow: hidden;
  margin-bottom: 0.8rem;
}

.carousel img {
  width: 100%;
  height: 185px;
  object-fit: cover;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.carousel img:hover {
  transform: scale(1.02);
}

.heart {
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 1.5rem;
  cursor: pointer;
  color: rgba(255, 255, 255, 0.9);
  text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
  transition: all 0.2s ease;
}

.heart:hover {
  color: #ff4757;
  transform: scale(1.1);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 0.5rem;
}

.btn-row {
  margin-top: 0.5rem;
}

.btn-out {
  width: 100%;
  padding: 0.6rem;
  text-align: center;
  text-decoration: none;
  font-family: 'DM Sans', sans-serif;
  font-weight: 600;
  background: var(--green);
  color: white;
  border: none;
  border-radius: 25px;
  transition: all 0.2s ease;
}

.btn-out:hover {
  background: var(--green-dk);
  transform: translateY(-1px);
  text-decoration: none;
}

/* Mobile viewport fixes */
@media (max-width: 480px) {
  .cs {
    padding: 1rem 0.8rem;
  }
  
  .dorm-card {
    padding: 0.8rem;
    margin-bottom: 0.8rem;
  }
  
  .carousel img {
    height: 150px !important;
  }
  
  .heart {
    font-size: 1.2rem !important;
    top: 8px !important;
    right: 8px !important;
  }
  
  .card-street {
    font-size: 0.85rem;
    margin: 0.2rem 0;
  }
  
  .card-price {
    font-size: 0.9rem;
  }
  
  .mpill {
    font-size: 0.65rem;
    padding: 0.2rem 0.5rem;
  }
  
  .btn-row {
    margin-top: 0.5rem;
  }
  
  .metas {
    display: flex;
    flex-wrap: wrap;
    gap: 0.3rem;
    align-items: center;
  }
  
  .card-street {
    line-height: 1.3;
    word-wrap: break-word;
  }
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
        <h2 class="owner-name">Saved Listings</h2>
      </div>

      @if($savedListings->isEmpty())
        <div class="empty">
          <div class="empty-ic">💔</div>
          <p>No saved listings yet</p>
          <p>Visit the home page to save your favorite dorms!</p>
        </div>
      @else
        <div id="savedList" style="display: flex; flex-direction: column; gap: 0.5rem;">
          @foreach($savedListings as $saved)
            @php
              $dorm = $saved->dormListing;
              // Get cover image using the images relationship
              $cover = $dorm->images->where('is_cover', true)->first()
                  ? asset('storage/' . $dorm->images->where('is_cover', true)->first()->path)
                  : ($dorm->images->first()
                      ? asset('storage/' . $dorm->images->first()->path)
                      : 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=400');
            @endphp
            <div class="dorm-card">
              <div class="carousel">
                <img src="{{ $cover }}" alt="{{ $dorm->street }}" onclick="window.location.href='/dorms/{{ $dorm->id }}'">
                <span class="heart" onclick="removeSaved({{ $dorm->id }})">♥</span>
              </div>
              <div class="card-header">
                <div class="card-street">{{ $dorm->street }}</div>
                <div class="card-price">₱{{ number_format($dorm->price, 0) }}<small>/month</small></div>
              </div>
              
              <div class="type-badge {{ $dorm->type }}">{{ $dorm->type }}</div>
              
              <div class="metas" style="margin-top: 0.5rem;">
                <span class="mpill">🚶 {{ $dorm->walk_minutes }}-min walk</span>
                <span class="mpill">👥 {{ $dorm->gender_policy }}</span>
                <span class="mpill ok">✅ {{ $dorm->status }}</span>
                @if($dorm->wifi_included) <span class="mpill ok">📶 WiFi</span> @endif
              </div>
              
              <div class="btn-row">
                <a href="/dorms/{{ $dorm->id }}" class="btn btn-out">View Details →</a>
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
