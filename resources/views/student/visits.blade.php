@extends('layouts.app')

@section('title', 'Scheduled Visits - NearU')

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div style="padding: 1.2rem;">
      <h2 style="font-size: 1.3rem; font-weight: 800; margin-bottom: 1rem;">📅 Scheduled Visits</h2>

      @if($visits->isEmpty())
        <div class="empty">
          <div class="empty-ic">📅</div>
          <p>No scheduled visits yet</p>
          <p>Schedule a visit to any dorm from the details page!</p>
        </div>
      @else
        <div id="visitsList" style="display: flex; flex-direction: column; gap: 1rem;">
          @foreach($visits as $visit)
            @php
              $dorm = $visit->dormListing;
              $photos = is_array($dorm->photos) ? $dorm->photos : (json_decode($dorm->photos, true) ?? []);
              $cover = count($photos) ? asset('storage/' . $photos[0]) : 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=400';
              $visitDate = $visit->visit_date->format('M d, Y');
              $visitTime = $visit->visit_time;
              $isPast = $visit->visit_date->isPast();
            @endphp
            <div class="dorm-card" style="{{ $isPast ? 'opacity: 0.6;' : '' }}">
              <div class="carousel" style="border-radius: 12px; overflow: hidden; margin-bottom: 0.9rem;">
                <img src="{{ $cover }}" alt="{{ $dorm->street }}" style="width:100%;height:185px;object-fit:cover;cursor:pointer;">
                <div class="dist-overlay" style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,.55); color: #fff; padding: .24rem .7rem; border-radius: 20px; font-size: .72rem; font-weight: 700;">
                  {{ $isPast ? '✓ Completed' : '⏳ Upcoming' }}
                </div>
              </div>

              <div class="card-street">{{ $dorm->street }}</div>
              <div class="card-price">₱{{ number_format($dorm->price, 0) }}<small>/month</small></div>

              <div style="background: var(--green-lt); border: 1.5px solid var(--green); border-radius: 12px; padding: 0.75rem; margin: 0.8rem 0;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                  <span style="font-size: 1rem;">📅</span>
                  <strong>{{ $visitDate }}</strong>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                  <span style="font-size: 1rem;">🕐</span>
                  <strong>{{ $visitTime }}</strong>
                </div>
                @if($visit->notes)
                <div style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--t2);">
                  📝 {{ $visit->notes }}
                </div>
                @endif
              </div>

              <div class="btn-row">
                <a href="/dorms/{{ $dorm->id }}" class="btn btn-out" style="width: 100%; padding: 0.68rem;">View Property →</a>
              </div>

              @if(!$isPast)
                <div style="text-align: center; padding-top: 0.5rem; border-top: 1px solid var(--border);">
                  <button onclick="cancelVisit({{ $visit->id }})" style="color: var(--red); font-size: 0.85rem; font-weight: 600; background: none; border: none; cursor: pointer;">Cancel Visit</button>
                </div>
              @endif
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>

  @include('partials.footer')
</div>
@endsection

@push('scripts')
<script>
function cancelVisit(visitId) {
  if (confirm('Cancel this visit?')) {
    // TODO: Implement cancel visit API call
    alert('Visit cancellation coming soon!');
  }
}
</script>
@endpush
