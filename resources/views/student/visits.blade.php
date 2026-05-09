@extends('layouts.app')

@section('title', 'Scheduled Visits - NearU')

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
        <h2 style="font-size: 1.3rem; font-weight: 800; margin: 0;">📅 Scheduled Visits</h2>
      </div>

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
              $photos = $dorm->images->pluck('path')->toArray();
              $cover = count($photos)
                ? asset('storage/' . $photos[0])
                : 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=400';
              $visitDate = $visit->visit_date->format('M d, Y');
              $visitTime = $visit->visit_time;
              $isPast = $visit->visit_date->isPast();
            @endphp
            <div class="dorm-card" style="{{ $isPast ? 'opacity: 0.6;' : '' }}">
              <div class="carousel" style="border-radius: 12px; overflow: hidden; margin-bottom: 0.9rem;">
                <img src="{{ $cover }}" alt="{{ $dorm->street }}" style="width:100%;height:185px;object-fit:cover;cursor:pointer;" onclick="window.location.href='/dorms/{{ $dorm->id }}'">
                <div class="dist-overlay" style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,.55); color: #fff; padding: .24rem .7rem; border-radius: 20px; font-size: .72rem; font-weight: 700;">
                  {{ $isPast ? '✓ Completed' : '⏳ Upcoming' }}
                </div>
              </div>

              <div class="card-street" style="font-weight: 600; margin-bottom: 0.3rem;">{{ $dorm->street }}</div>
              <div class="card-price" style="margin-bottom: 0.5rem;">₱{{ number_format($dorm->price, 0) }}<small>/month</small></div>

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
                @php
                  $statusLabel = $visit->status === 'Confirmed' ? 'Approved' : ucfirst($visit->status);
                  $statusColor = $visit->status === 'Pending' ? '#F59E0B' : ($visit->status === 'Confirmed' ? '#2563EB' : ($visit->status === 'Completed' ? '#16A34A' : '#B91C1C'));
                @endphp
                <div style="margin-top: 0.5rem; font-size: 0.8rem; color: var(--t2);">
                  Status: <strong style="color: {{ $statusColor }};">{{ $statusLabel }}</strong>
                </div>
              </div>

              <div class="btn-row">
                <a href="/dorms/{{ $dorm->id }}" class="btn btn-out" style="width: 100%; padding: 0.68rem;">View Property →</a>
              </div>

              @if(in_array($visit->status, ['Pending', 'Confirmed']))
                <form method="POST" action="{{ route('visits.cancel', $visit->id) }}" onsubmit="return confirm('Cancel this visit?');" style="margin-top: 0.8rem;">
                  @csrf
                  <button type="submit" class="btn btn-red" style="width: 100%;">Cancel Visit</button>
                </form>
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
