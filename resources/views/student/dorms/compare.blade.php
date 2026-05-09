@extends('layouts.app')

@section('title', 'Compare Dorms')

@section('content')
<div class="wrap">

  {{-- MAIN TOP BAR --}}
  <div class="top-bar">
    <div class="tb-logo"><em>Near</em>U</div>
    <div class="tb-right">
      <button class="ib">🔔</button>
    </div>
  </div>

  {{-- SECONDARY BAR WITH BACK AND TITLE --}}
  <div style="display:flex;align-items:center;gap:1rem;padding:1rem 1.4rem;background:var(--surface);border-bottom:1px solid var(--border);">
    <button class="bb" onclick="history.back()" style="background:var(--green);">← Back</button>
    <div class="tb-title">⚖️ Compare Dorms</div>
  </div>

  <div class="screen active" style="padding-bottom:90px;">
    <div class="cs">

      @if($dormListings->count() >= 2)

        {{-- HINT --}}
        <p style="font-size:.76rem;color:var(--t2);margin-bottom:.7rem;">
        </p>

        @php
          $minPrice = $dormListings->min('price');
          $minWalk  = $dormListings->min('walk_minutes');
        @endphp

        {{-- CUTE COMPARE TABLE --}}
        <style>
          .modern-compare-table {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(45, 125, 79, 0.12);
            border: 2px solid var(--border);
            margin-bottom: 2rem;
            position: relative;
          }
          
          .modern-compare-table::before {
            content: '✨';
            position: absolute;
            top: -8px;
            right: 15px;
            font-size: 1.2rem;
            transform: rotate(15deg);
            z-index: 10;
            color: var(--gold);
          }
          
          .compare-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px;
            font-size: 0.75rem;
          }
          
          .compare-table th {
            background: linear-gradient(135deg, var(--green) 0%, #1f5c38 100%);
            color: white;
            padding: 0.6rem 0.4rem;
            text-align: center;
            font-weight: 800;
            position: relative;
            vertical-align: middle;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(45, 125, 79, 0.15);
            min-height: 85px;
            width: 120px;
          }
          
          .compare-table th:first-child {
            background: linear-gradient(135deg, var(--gold) 0%, #c99200 100%);
            color: #1F2933;
            text-align: center;
            min-width: 95px;
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(242, 183, 5, 0.15);
          }
          
          .dorm-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.3rem;
            height: 100%;
            justify-content: center;
          }
          
          .dorm-header-img {
            width: 38px;
            height: 30px;
            border-radius: 8px;
            object-fit: cover;
            background: linear-gradient(135deg, var(--gold-lt) 0%, #fef3c7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            color: var(--gold-dk);
            box-shadow: 0 2px 4px rgba(242, 183, 5, 0.12);
          }
          
          .dorm-header-name {
            font-family: 'Syne', sans-serif;
            font-size: 0.65rem;
            font-weight: 800;
            line-height: 1.0;
            color: white;
          }
          
          .dorm-header-type {
            display: inline-block;
            padding: 0.15rem 0.3rem;
            background: rgba(255, 255, 255, 0.25);
            color: white;
            border-radius: 6px;
            font-size: 0.5rem;
            font-weight: 700;
          }
          
          .compare-table td {
            padding: 0.5rem 0.4rem;
            text-align: center;
            vertical-align: middle;
            transition: all 0.3s ease;
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
            border: 2px solid var(--border);
            min-height: 42px;
            width: 120px;
          }
          
          .compare-table td:first-child {
            background: linear-gradient(135deg, var(--gold-lt) 0%, #fef3c7 100%);
            font-weight: 700;
            color: var(--gold-dk);
            text-align: center;
            text-transform: uppercase;
            font-size: 0.55rem;
            letter-spacing: 0.3px;
            min-width: 95px;
            border-radius: 8px;
            border: 2px solid var(--gold);
            box-shadow: 0 1px 4px rgba(242, 183, 5, 0.1);
          }
          
          .compare-table tr:hover td:not(:first-child) {
            background: var(--green-lt);
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 6px 20px rgba(45, 125, 79, 0.2);
            border-color: var(--green);
          }
          
          .feature-icon-cell {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            background: white;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(45, 125, 79, 0.15);
            margin-right: 0.5rem;
            font-size: 0.95rem;
          }
          
          .price-cell {
            font-family: 'Syne', sans-serif;
            font-size: 1rem;
            font-weight: 800;
            color: var(--t1);
          }
          
          .best-price {
            background: linear-gradient(135deg, var(--gold) 0%, #c99200 100%) !important;
            color: white !important;
            position: relative;
            box-shadow: 0 4px 12px rgba(242, 183, 5, 0.25) !important;
            border-color: var(--gold) !important;
          }
          
          .best-price::after {
            content: '⭐';
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.9rem;
            background: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
          }
          
          .best-value {
            background: linear-gradient(135deg, var(--green) 0%, #1f5c38 100%) !important;
            color: white !important;
            font-weight: 800;
            position: relative;
            box-shadow: 0 4px 12px rgba(45, 125, 79, 0.25) !important;
            border-color: var(--green) !important;
          }
          
          .best-value::after {
            content: '⭐';
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.9rem;
            background: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
          }
          
          .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            padding: 0.3rem 0.6rem;
            border-radius: 15px;
            font-size: 0.65rem;
            font-weight: 700;
          }
          
          .status-available {
            background: var(--green-lt);
            color: var(--green);
          }
          
          .status-unavailable {
            background: var(--red-lt);
            color: var(--red);
          }
          
          .yes-no-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.6rem;
            font-weight: 700;
          }
          
          .yes-badge {
            background: var(--green-lt);
            color: var(--green);
          }
          
          .no-badge {
            background: var(--bg);
            color: var(--t2);
          }
          
          .feature-row-icon {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
          }
          
          @media (max-width: 768px) {
            .modern-compare-table {
              overflow-x: auto;
              display: block;
              -webkit-overflow-scrolling: touch;
              scrollbar-width: thin;
            }
            
            .compare-table {
              min-width: 600px;
              display: table;
            }
          }
          
          @media (min-width: 769px) {
            .modern-compare-table {
              overflow-x: auto;
              display: block;
              max-width: 100%;
            }
            
            .compare-table {
              min-width: 600px;
              display: table;
            }
          }
        </style>

        <div class="modern-compare-table">
          <table class="compare-table">
            <thead>
              <tr>
                <th>Feature</th>
                @foreach($dormListings as $dorm)
                  <th>
                    <div class="dorm-header">
                      @if(!empty($dorm->photos) && count($dorm->photos))
                        <img src="{{ asset('storage/' . $dorm->photos[0]) }}" class="dorm-header-img" alt="{{ $dorm->street }}">
                      @else
                        <div class="dorm-header-img">🏠</div>
                      @endif
                      <div class="dorm-header-name">{{ $dorm->street }}</div>
                      <div class="dorm-header-type">{{ $dorm->type }}</div>
                    </div>
                  </th>
                @endforeach
              </tr>
            </thead>
            
            <tbody>
              {{-- Price --}}
              <tr>
                <td>
                  <div class="feature-row-icon">
                    <span class="feature-icon-cell">💰</span>
                    Price
                  </div>
                </td>
                @foreach($dormListings as $dorm)
                  <td class="price-cell {{ $dorm->price == $minPrice ? 'best-price' : '' }}">
                    ₱{{ number_format($dorm->price) }}
                  </td>
                @endforeach
              </tr>

              {{-- Walk --}}
              <tr>
                <td>
                  <div class="feature-row-icon">
                    <span class="feature-icon-cell">🚶</span>
                    Walking
                  </div>
                </td>
                @foreach($dormListings as $dorm)
                  <td class="{{ $dorm->walk_minutes == $minWalk ? 'best-value' : '' }}">
                    {{ $dorm->walk_minutes }} min
                  </td>
                @endforeach
              </tr>

              {{-- Type --}}
              <tr>
                <td>
                  <div class="feature-row-icon">
                    <span class="feature-icon-cell">🏠</span>
                    Type
                  </div>
                </td>
                @foreach($dormListings as $dorm)
                  <td>
                    @php
                      $typeCls = match($dorm->type) {
                        'Room'     => 'type-badge Room',
                        'Bedspace' => 'type-badge Bedspace',
                        'Unit'     => 'type-badge Unit',
                        default    => 'type-badge Room',
                      };
                    @endphp
                    <span class="{{ $typeCls }}">{{ $dorm->type }}</span>
                  </td>
                @endforeach
              </tr>

              {{-- Gender --}}
              <tr>
                <td>
                  <div class="feature-row-icon">
                    <span class="feature-icon-cell">👥</span>
                    Gender
                  </div>
                </td>
                @foreach($dormListings as $dorm)
                  <td>{{ $dorm->gender_policy }}</td>
                @endforeach
              </tr>

              {{-- Bathroom --}}
              <tr>
                <td>
                  <div class="feature-row-icon">
                    <span class="feature-icon-cell">🚿</span>
                    Bathroom
                  </div>
                </td>
                @foreach($dormListings as $dorm)
                  <td>{{ $dorm->bathroom ?? '—' }}</td>
                @endforeach
              </tr>

              {{-- WiFi --}}
              <tr>
                <td>
                  <div class="feature-row-icon">
                    <span class="feature-icon-cell">📶</span>
                    WiFi
                  </div>
                </td>
                @foreach($dormListings as $dorm)
                  <td>
                    @if($dorm->wifi_included)
                      <span class="yes-no-badge yes-badge">✅ Yes</span>
                    @else
                      <span class="yes-no-badge no-badge">❌ No</span>
                    @endif
                  </td>
                @endforeach
              </tr>

              {{-- Appliances --}}
              <tr>
                <td>
                  <div class="feature-row-icon">
                    <span class="feature-icon-cell">🔌</span>
                    Appliances
                  </div>
                </td>
                @foreach($dormListings as $dorm)
                  <td style="font-size:0.8rem;">
                    @php
                      $appliances = is_array($dorm->appliances) ? $dorm->appliances : (json_decode($dorm->appliances, true) ?: []);
                    @endphp
                    @if(!empty($appliances))
                      {{ implode(', ', array_slice($appliances, 0, 3)) }}{{ count($appliances) > 3 ? '...' : '' }}
                    @else
                      —
                    @endif
                  </td>
                @endforeach
              </tr>

              {{-- Furnishings --}}
              <tr>
                <td>
                  <div class="feature-row-icon">
                    <span class="feature-icon-cell">🛋️</span>
                    Furnishings
                  </div>
                </td>
                @foreach($dormListings as $dorm)
                  <td style="font-size:0.8rem;">
                    @php
                      $furnishings = is_array($dorm->furnishings) ? $dorm->furnishings : (json_decode($dorm->furnishings, true) ?: []);
                    @endphp
                    @if(!empty($furnishings))
                      {{ implode(', ', array_slice($furnishings, 0, 3)) }}{{ count($furnishings) > 3 ? '...' : '' }}
                    @else
                      —
                    @endif
                  </td>
                @endforeach
              </tr>

              {{-- Bills --}}
              <tr>
                <td>
                  <div class="feature-row-icon">
                    <span class="feature-icon-cell">💡</span>
                    Bills
                  </div>
                </td>
                @foreach($dormListings as $dorm)
                  <td style="font-size:0.8rem;">
                    @php
                      $bills = is_array($dorm->bills_included) ? $dorm->bills_included : (json_decode($dorm->bills_included, true) ?: []);
                    @endphp
                    @if(!empty($bills))
                      {{ implode(', ', array_slice($bills, 0, 3)) }}{{ count($bills) > 3 ? '...' : '' }}
                    @else
                      —
                    @endif
                  </td>
                @endforeach
              </tr>

              {{-- Pets --}}
              <tr>
                <td>
                  <div class="feature-row-icon">
                    <span class="feature-icon-cell">🐾</span>
                    Pets
                  </div>
                </td>
                @foreach($dormListings as $dorm)
                  <td>
                    @if($dorm->pets_allowed)
                      <span class="yes-no-badge yes-badge">✅ Yes</span>
                    @else
                      <span class="yes-no-badge no-badge">❌ No</span>
                    @endif
                  </td>
                @endforeach
              </tr>

              {{-- Curfew --}}
              <tr>
                <td>
                  <div class="feature-row-icon">
                    <span class="feature-icon-cell">🕐</span>
                    Curfew
                  </div>
                </td>
                @foreach($dormListings as $dorm)
                  <td>{{ $dorm->curfew ?? 'No curfew' }}</td>
                @endforeach
              </tr>

              {{-- Status --}}
              <tr>
                <td>
                  <div class="feature-row-icon">
                    <span class="feature-icon-cell">🏷️</span>
                    Status
                  </div>
                </td>
                @foreach($dormListings as $dorm)
                  <td>
                    <span class="status-badge {{ $dorm->status === 'Available' ? 'status-available' : 'status-unavailable' }}">
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
          
            href="{{ url('/students/dorms') }}"
            style="display:inline-block;margin-top:1rem;padding:.72rem 1.8rem;background:var(--green);color:#fff;border-radius:50px;font-weight:700;font-size:.88rem;text-decoration:none;"
          >← Browse Listings</a>
        </div>

      @endif

    </div>
  </div>

  {{-- BOTTOM NAV --}}
  <div class="bot-nav">
    <div class="nav-i" onclick="window.location.href='{{ url('/student/dorms') }}'">
      <span>🏠</span><div>Home</div>
    </div>
    <div class="nav-i" onclick="window.location.href='{{ url('/student/map') }}'">
      <span>📍</span><div>Map</div>
    </div>
    <div class="nav-i" onclick="window.location.href='{{ url('/student/messages') }}'">
      <span>💬</span><div>Messages</div>
    </div>
    <div class="nav-i" onclick="window.location.href='{{ url('/student/profile') }}'">
      <span>👤</span><div>Profile</div>
    </div>
  </div>

</div>
@endsection