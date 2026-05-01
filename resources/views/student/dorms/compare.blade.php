@extends('layouts.app')

@section('title', 'Compare Dorms')

@section('content')
<div class="wrap">

  {{-- TOP BAR --}}
  <div class="top-bar">
    <button class="bb" onclick="history.back()">← Back</button>
    <div class="tb-title">⚖️ Compare Dorms</div>
    <div class="tb-right">
      <button class="ib">🔔</button>
      <button class="ib">👤</button>
    </div>
  </div>

  <div class="screen active" style="padding-bottom:90px;">
    <div class="cs">

      @if($dormListings->count() >= 2)

        {{-- HINT --}}
        <p style="font-size:.76rem;color:var(--t2);margin-bottom:.7rem;">
          <span style="color:#065F46;font-weight:800;">Green</span> = best in category
        </p>

        @php
          $minPrice = $dormListings->min('price');
          $minWalk  = $dormListings->min('walk_minutes');
        @endphp

        {{-- TABLE --}}
        <div class="cmp-wrap">
          <table class="cmp-tbl">

            <thead>
              <tr>
                <th>Feature</th>
                @foreach($dormListings as $dorm)
                  <th>
                    @if(!empty($dorm->photos) && count($dorm->photos))
                      <img
                        src="{{ asset('storage/' . $dorm->photos[0]) }}"
                        class="cmp-img"
                        alt="{{ $dorm->street }}"
                        onerror="this.style.display='none'"
                      >
                    @else
                      <div style="width:56px;height:42px;background:rgba(255,255,255,.15);border-radius:8px;margin:0 auto 5px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;">🏠</div>
                    @endif
                    {{ $dorm->street }}<br>
                    <small style="font-weight:400;opacity:.75;font-size:.65rem;">{{ $dorm->type }}</small>
                  </th>
                @endforeach
              </tr>
            </thead>

            <tbody>

              {{-- Price --}}
              <tr>
                <td>💰 Price</td>
                @foreach($dormListings as $dorm)
                  <td class="{{ $dorm->price == $minPrice ? 'cmp-best' : '' }}">
                    ₱{{ number_format($dorm->price) }}
                  </td>
                @endforeach
              </tr>

              {{-- Walk --}}
              <tr>
                <td>🚶 Walk</td>
                @foreach($dormListings as $dorm)
                  <td class="{{ $dorm->walk_minutes == $minWalk ? 'cmp-best' : '' }}">
                    {{ $dorm->walk_minutes }} min
                  </td>
                @endforeach
              </tr>

              {{-- Type --}}
              <tr>
                <td>🏠 Type</td>
                @foreach($dormListings as $dorm)
                  @php
                    $typeCls = match($dorm->type) {
                      'Room'     => 'type-badge Room',
                      'Bedspace' => 'type-badge Bedspace',
                      'Unit'     => 'type-badge Unit',
                      default    => 'type-badge Room',
                    };
                  @endphp
                  <td><span class="{{ $typeCls }}">{{ $dorm->type }}</span></td>
                @endforeach
              </tr>

              {{-- Gender --}}
              <tr>
                <td>👥 Gender</td>
                @foreach($dormListings as $dorm)
                  <td>{{ $dorm->gender_policy }}</td>
                @endforeach
              </tr>

              {{-- Bathroom --}}
              <tr>
                <td>🚿 Bathroom</td>
                @foreach($dormListings as $dorm)
                  <td>{{ $dorm->bathroom ?? '—' }}</td>
                @endforeach
              </tr>

              {{-- WiFi --}}
              <tr>
                <td>📶 WiFi</td>
                @foreach($dormListings as $dorm)
                  <td>
                    @if($dorm->wifi_included)
                      <span class="mpill ok">✅ Yes</span>
                    @else
                      <span class="mpill">❌ No</span>
                    @endif
                  </td>
                @endforeach
              </tr>

              {{-- Appliances --}}
              <tr>
                <td>🔌 Appliances</td>
                @foreach($dormListings as $dorm)
                  <td style="font-size:.7rem;">{{ $dorm->appliances ?? '—' }}</td>
                @endforeach
              </tr>

              {{-- Furnishings --}}
              <tr>
                <td>🛋️ Furnishings</td>
                @foreach($dormListings as $dorm)
                  <td style="font-size:.7rem;">{{ $dorm->furnishings ?? '—' }}</td>
                @endforeach
              </tr>

              {{-- Bills --}}
              <tr>
                <td>💡 Bills</td>
                @foreach($dormListings as $dorm)
                  <td style="font-size:.7rem;">{{ $dorm->bills_included ?? '—' }}</td>
                @endforeach
              </tr>

              {{-- Pets --}}
              <tr>
                <td>🐾 Pets</td>
                @foreach($dormListings as $dorm)
                  <td>
                    @if($dorm->pets_allowed)
                      <span class="mpill ok">✅ Yes</span>
                    @else
                      <span class="mpill">❌ No</span>
                    @endif
                  </td>
                @endforeach
              </tr>

              {{-- Curfew --}}
              <tr>
                <td>🕐 Curfew</td>
                @foreach($dormListings as $dorm)
                  <td>{{ $dorm->curfew ?? 'No curfew' }}</td>
                @endforeach
              </tr>

              {{-- Nearby --}}
              <tr>
                <td>📍 Nearby</td>
                @foreach($dormListings as $dorm)
                  <td style="font-size:.7rem;">{{ $dorm->nearby_landmarks ?? '—' }}</td>
                @endforeach
              </tr>

              {{-- Status --}}
              <tr>
                <td>✅ Status</td>
                @foreach($dormListings as $dorm)
                  <td>
                    <span class="mpill {{ $dorm->status === 'Available' ? 'ok' : 'batstate-lt' }}">
                      {{ $dorm->status === 'Available' ? '✅' : '🔒' }} {{ $dorm->status }}
                    </span>
                  </td>
                @endforeach
              </tr>

            </tbody>
          </table>
        </div>


      @else

        {{-- EMPTY STATE --}}
        <div class="empty">
          <div class="empty-ic">⚖️</div>
          <p>
            @if($dormListings->count() == 1)
              Select at least <strong>2 dorms</strong> to compare.
            @else
              No dorms selected for comparison.
            @endif
          </p>
          
            href="{{ route('dorms.index') }}"
            style="display:inline-block;margin-top:1rem;padding:.72rem 1.8rem;background:var(--green);color:#fff;border-radius:50px;font-weight:700;font-size:.88rem;text-decoration:none;"
          >← Browse Listings</a>
        </div>

      @endif

    </div>
  </div>

  {{-- BOTTOM NAV --}}
  <div class="bot-nav">
    <div class="nav-i" onclick="window.location.href='{{ route('dorms.index') }}'">
      <span>🏠</span><div>Home</div>
    </div>
    <div class="nav-i" onclick="window.location.href='{{ route('map') }}'">
      <span>📍</span><div>Map</div>
    </div>
    <div class="nav-i" onclick="window.location.href='{{ route('messages.index') }}'">
      <span>💬</span><div>Messages</div>
    </div>
    <div class="nav-i" onclick="window.location.href='{{ route('profile') }}'">
      <span>👤</span><div>Profile</div>
    </div>
  </div>

</div>
@endsection