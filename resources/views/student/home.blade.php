@extends('layouts.app')

@section('title', 'NearU – Find Housing Near Campus')

@section('content')
<div class="wrap">

  {{-- ── NAVIGATION BAR ── --}}
  @include('partials.navbar')

  {{-- ── HOME SCREEN ── --}}
  <div id="home" class="screen active">

    {{-- Hero --}}
    <div class="hero">
      <h1>Find a place <span>near campus</span></h1>
      <p>
        <span class="hero-badge">🛏️ Dorms</span>
        <span class="hero-badge">🛌 Bedspaces</span>
        <span class="hero-badge">🏠 Units</span>
        <span class="hero-badge">🚶 Walking distance</span>
      </p>
    </div>

    {{-- Search --}}
    <div class="search-wrap">
      <span class="si">🔍</span>
      <input
        type="text"
        id="searchInput"
        placeholder="Search by street, type, or owner…"
        autocomplete="off"
        value="{{ request('search') }}"
      >
    </div>

    {{-- Filter chips --}}
    <div class="chips-wrap">
      <div class="chips">
        <div class="chip" onclick="Filters.open('price')">💰 Price</div>
        <div class="chip" onclick="Filters.open('type')">🏠 Type</div>
        <div class="chip" onclick="Filters.open('distance')">🚶 Distance</div>
        <div class="chip" onclick="Filters.open('gender')">👥 Gender</div>
        <div class="chip" onclick="Filters.open('advanced')">⚙️ More</div>
        <div class="chip on" id="availChip" onclick="Filters.toggleAvail()">✅ Available</div>
      </div>
    </div>

    {{-- Active filter tags --}}
    <div id="activeFilters" class="active-filters"></div>

    {{-- Listings --}}
    <div class="listings">
      <div class="sec-lbl">
        All Listings
        <span id="listCount" style="font-weight:600;opacity:.6;font-size:.65rem;margin-left:.3rem;text-transform:none;letter-spacing:0;"></span>
      </div>
      <div id="dormList">
        
        @forelse($dormListings as $dorm)
          @php
            $photos = $dorm->images->pluck('path')->toArray();

            $cover  = count($photos)
              ? asset('storage/' . $photos[0])
              : 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=400';

            $inCmp  = in_array($dorm->id, session('compare', []));
            $saved  = in_array($dorm->id, session('saved', []));
            $rating = $dorm->rating ? round($dorm->rating) : 0;
            $avgRating = $dorm->rating ?? 0;
            $empty  = 5 - $rating;
          @endphp

          {{-- data-* attrs drive client-side search + filter --}}
          <div class="dorm-card{{ $inCmp ? ' cmp-on' : '' }}"
               id="card-{{ $dorm->id }}"
               data-street="{{ strtolower($dorm->street) }}"
               data-type="{{ strtolower($dorm->type) }}"
               data-owner="{{ strtolower($dorm->owner->name ?? '') }}"
               data-price="{{ $dorm->price }}"
               data-walk="{{ $dorm->walk_minutes }}"
               data-gender="{{ strtolower($dorm->gender_policy) }}"
               data-status="{{ strtolower($dorm->status) }}"
               data-wifi="{{ $dorm->wifi_included ? '1' : '0' }}"
               data-pets="{{ $dorm->pets_allowed ? '1' : '0' }}"
               data-curfew="{{ strtolower($dorm->curfew ?? '') }}">

            {{-- IMAGE CAROUSEL --}}
            @if(count($photos) > 1)
              <div class="carousel" id="car-{{ $dorm->id }}">
                <div class="carousel-inner" id="ci-{{ $dorm->id }}">
                  @foreach($photos as $photo)
                    <div class="carousel-slide">
                      <img src="{{ asset('storage/' . $photo) }}"
                          alt="{{ $dorm->street }}"
                          loading="lazy"
                          style="width:100%;height:185px;object-fit:cover;cursor:pointer;border-radius:12px;"
                          onclick="UI.openLb(this.src)">
                    </div>
                  @endforeach
                </div>
                <button class="carousel-btn prev" onclick="Carousel.slide({{ $dorm->id }}, -1)">‹</button>
                <button class="carousel-btn next" onclick="Carousel.slide({{ $dorm->id }},  1)">›</button>
                <div class="carousel-dots">
                  @foreach($photos as $i => $photo)
                    <div class="c-dot{{ $i === 0 ? ' on' : '' }}"
                         onclick="Carousel.goSlide({{ $dorm->id }}, {{ $i }})"></div>
                  @endforeach
                </div>
                <div class="dist-overlay">📍 {{ $dorm->walk_minutes }}-min walk</div>
              </div>
            @else
              <div class="carousel">
              <img src="{{ $cover }}"
                  style="width:100%;height:185px;object-fit:cover;cursor:pointer;border-radius:12px;"
                  alt="{{ $dorm->street }}"
                  loading="lazy"
                  onclick="UI.openLb(this.src)">
                <div class="dist-overlay">📍 {{ $dorm->walk_minutes }}-min walk</div>
              </div>
            @endif

            {{-- RATING ROW --}}
            <div class="rat-row">
              <span class="stars">{!! str_repeat('★', $rating) !!}{!! str_repeat('☆', $empty) !!}</span>
              <span class="rv">{{ number_format($dorm->rating ?? 0, 1) }}</span>
              <span class="rc">({{ $dorm->reviews()->count() }} reviews)</span>
            </div>

            {{-- TYPE BADGE + HEART --}}
            <div class="card-top">
              <div class="type-badge {{ $dorm->type }}">
                @if($dorm->type === 'Room') 🛏️
                @elseif($dorm->type === 'Bedspace') 🛌
                @else 🏠
                @endif
                {{ $dorm->type }}
              </div>
              <span class="heart" id="heart-{{ $dorm->id }}"
                    style="color:{{ $saved ? '#C8102E' : 'var(--border)' }}"
                    onclick="Saved.toggle({{ $dorm->id }}, event)">
                {{ $saved ? '♥' : '♡' }}
              </span>
            </div>

            {{-- STREET & PRICE --}}
            <div class="card-street">{{ $dorm->street }}</div>
            <div class="card-price">₱{{ number_format($dorm->price, 0) }} <small>/ month</small></div>

            {{-- META PILLS --}}
            <div class="metas">
              <span class="mpill">🚶 {{ $dorm->walk_minutes }}-min walk</span>
              <span class="mpill">👥 {{ $dorm->gender_policy }}</span>
              <span class="mpill ok">✅ {{ $dorm->status }}</span>
              @if($dorm->wifi_included) <span class="mpill ok">📶 WiFi</span> @endif
            </div>

            {{-- OWNER CHIP --}}
            @if($dorm->owner)
              <div class="owner-chip" onclick="OwnerProfile.show({{ $dorm->owner->id }})">
                👤 <strong>{{ $dorm->owner->name }}</strong>
                @if($dorm->owner->is_verified ?? false)
                  <span style="color:var(--green);font-size:.65rem;">✓</span>
                @endif
                <span style="opacity:.5;font-size:.7rem;">· View profile →</span>
              </div>
            @endif

            {{-- INCLUSIONS BOX --}}
            <div class="inc-box">
              <div class="inc-ttl">Inclusions</div>
              <div class="inc-grid">
                @if($dorm->bathroom)       <div class="inc-i">🚿 {{ $dorm->bathroom }}</div> @endif
                @if($dorm->furnishings)    <div class="inc-i">🛏️ {{ Str::limit($dorm->furnishings, 18) }}</div> @endif
                @if($dorm->appliances)     <div class="inc-i">🔌 {{ Str::limit($dorm->appliances, 18) }}</div> @endif
                @if($dorm->bills_included) <div class="inc-i">💡 {{ Str::limit($dorm->bills_included, 16) }}</div> @endif
                @if($dorm->curfew)         <div class="inc-i">🕐 {{ $dorm->curfew }}</div> @endif
              </div>
            </div>

            {{-- BUTTONS ROW 1: Reviews + Directions --}}
            <div class="btn-row">
              <button class="btn btn-out"
                onclick="Reviews.show({{ $dorm->id }}, @js($dorm->street))">
                ⭐ Reviews ({{ $dorm->reviews()->count() }})
              </button>
              <button class="btn btn-green"
                onclick="Directions.get({{ $dorm->id }}, {{ $dorm->latitude ?? 0 }}, {{ $dorm->longitude ?? 0 }}, '{{ addslashes($dorm->street) }}')">
                🧭 Directions
              </button>
            </div>

            {{-- BUTTONS ROW 2: Compare + Schedule --}}
            <div class="btn-row">
              <button class="btn btn-cmp{{ $inCmp ? ' on' : '' }}" id="cmp-btn-{{ $dorm->id }}"
                onclick="Compare.toggle({{ $dorm->id }})">
                {{ $inCmp ? '✓ Added' : '⚖️ Compare' }}
              </button>
              <button class="btn btn-gold"
                onclick="Schedule.open({{ $dorm->id }}, '{{ addslashes($dorm->street) }}', '{{ $dorm->type }}', {{ $dorm->price }}, '{{ addslashes($dorm->owner->name ?? '') }}')">
                📅 Schedule
              </button>
            </div>

            {{-- CONTACT OWNER --}}
            <button class="btn btn-blue btn-full"
                onclick="window.location.href='{{ route('messages.show', [$dorm->id, $dorm->owner->id]) }}'">
                📞 Contact Owner
            </button>

          </div>{{-- /.dorm-card --}}

        @empty
          <div class="empty">
            <div class="empty-ic">🔍</div>
            <p>No listings available</p>
          </div>
        @endforelse

        {{-- Shown when JS search/filter hides everything --}}
        <div class="empty" id="noResults" style="display:none;">
          <div class="empty-ic">🔍</div>
          <p>No listings match your search</p>
        </div>

      </div>{{-- /#dormList --}}
    </div>{{-- /.listings --}}

  </div>{{-- /#home --}}

  {{-- BOTTOM NAV --}}
  <div class="bot-nav">
    <div class="nav-i on" id="nav-home"     onclick="window.location.href='/'"><span>🏠</span><div>Home</div></div>
    <div class="nav-i"    id="nav-map"      onclick="window.location.href='{{ route('student.map') }}'"><span>📍</span><div>Map</div></div>
    <div class="nav-i"    id="nav-messages" onclick="window.location.href='/messages'"><span>💬</span><div>Messages</div></div>
    <div class="nav-i"    id="nav-profile"  onclick="window.location.href='/profile'"><span>👤</span><div>Profile</div></div>
  </div>

</div>{{-- /.wrap --}}

{{-- LIGHTBOX --}}
<div id="lb" onclick="UI.closeLb()">
  <button id="lb-cls" onclick="event.stopPropagation();UI.closeLb()">×</button>
  <img id="lbImg" src="" alt="">
</div>

{{-- COMPARE BAR --}}
<div id="cmp-bar" class="hide">
  <span class="cb-t" id="cmpTxt">0 selected</span>
  <button class="cb-btn" onclick="Compare.open()">Compare Now →</button>
</div>

<div id="filterModal" class="modal">
  <div class="msheet">
    <div class="mhandle"></div>
    <div class="m-ttl" id="fTitle">Filter</div>
    <div class="m-sub" id="fSub">Tap to select. Tap again to deselect.</div>
    <div id="fOpts"></div>
    <button class="apply-btn" onclick="Filters.apply()">Apply Filter ✓</button>
  </div>
</div>

<div id="reviewsModal" class="modal">
  <div class="msheet">
    <div class="mhandle"></div>
    <div class="m-ttl" id="revModalTitle">Reviews</div>
    <div id="revModalBody" style="max-height:45vh;overflow-y:auto;"></div>

    @auth
    <div class="add-review-box" style="margin-top:.8rem;">
      <div style="font-family:'Syne',sans-serif;font-size:.88rem;font-weight:800;margin-bottom:.65rem;color:var(--t1);">✍️ Write a Review</div>
      <div class="star-picker" id="starPicker">
        @for($s = 1; $s <= 5; $s++)
          <span class="star-pick" data-val="{{ $s }}" onclick="Reviews.pickStar({{ $s }})">★</span>
        @endfor
      </div>
      <textarea class="rev-input" id="revText" rows="3" placeholder="Share your experience…"></textarea>
      <input type="hidden" id="revDormId" value="">
      <button class="rev-submit-btn" onclick="Reviews.submit()">Submit Review →</button>
    </div>
    @endauth
    @guest
    <div style="text-align:center;padding:1rem 0 .5rem;font-size:.82rem;color:var(--t2);">
      <a href="{{ route('login') }}" style="color:var(--green);font-weight:700;">Login</a> to write a review
    </div>
    @endguest
  </div>
</div>

<div id="directionsModal" class="modal">
  <div class="msheet" style="padding-bottom:1.2rem;">
    <div class="mhandle"></div>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.75rem;">
      <div class="m-ttl" id="dirModalTitle" style="margin-bottom:0;">🧭 Directions</div>
      <button onclick="Directions.close()"
        style="background:none;border:none;font-size:1.3rem;cursor:pointer;color:var(--t2);width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;">✕</button>
    </div>
    <div id="dirStats" style="display:flex;gap:.6rem;margin-bottom:.75rem;"></div>
    <div id="dirMap" style="width:100%;height:240px;border-radius:14px;overflow:hidden;border:2px solid var(--border);background:var(--bg);margin-bottom:.75rem;"></div>
    <div style="font-size:.7rem;font-weight:800;color:var(--t2);text-transform:uppercase;letter-spacing:.6px;margin-bottom:.45rem;">Turn-by-turn</div>
    <div id="dirSteps" style="max-height:180px;overflow-y:auto;"></div>
  </div>
</div>

<div id="schedModal" class="modal">
  <div class="msheet">
    <div class="mhandle"></div>
    <div class="m-ttl">📅 Schedule a Visit</div>
    <div id="schedInfo"
      style="background:var(--bg);padding:.85rem;border-radius:12px;margin-bottom:1rem;font-size:.85rem;border:1px solid var(--border);line-height:1.5;"></div>

    <div class="fg">
      <label>Select Date</label>
      <input type="date" id="vDate" min="{{ now()->addDay()->format('Y-m-d') }}">
    </div>

    <div class="fg">
      <label>Preferred Time</label>
      <div class="tgrid">
        @foreach(['9:00 AM','10:00 AM','11:00 AM','1:00 PM','2:00 PM','3:00 PM','4:00 PM','5:00 PM','6:00 PM'] as $slot)
          <div class="tslot" onclick="Schedule.selTime(this)">{{ $slot }}</div>
        @endforeach
      </div>
    </div>

    <div class="fg">
      <label>Notes <span style="font-weight:400;text-transform:none;">(optional)</span></label>
      <textarea id="vNotes" rows="2" placeholder="Any specific requests…"
        style="width:100%;padding:.82rem;border:1.5px solid var(--border);border-radius:12px;
               font-family:'DM Sans',sans-serif;font-size:.87rem;outline:none;
               background:var(--card);color:var(--t1);resize:none;
               transition:border var(--transition);"></textarea>
    </div>

    <input type="hidden" id="schedDormId" value="">

    @auth
      <button class="sub-btn" onclick="Schedule.submit()">✅ Confirm Visit</button>
    @endauth
    @guest
      <a href="{{ route('login') }}" class="sub-btn"
         style="display:block;text-align:center;text-decoration:none;">Login to Schedule</a>
    @endguest
  </div>
</div>

@endsection

@push('styles')
<style>
  /* HERO */
  .hero { background:linear-gradient(145deg,#0a1f0e,#1a4d2e,#163d60); padding:1.7rem 1.4rem 2.8rem; color:#fff; position:relative; overflow:hidden; }
  .hero::before { content:''; position:absolute; top:-40px; right:-40px; width:200px; height:200px; border-radius:50%; background:rgba(242,183,5,.07); pointer-events:none; }
  .hero::after  { content:''; position:absolute; bottom:-1px; left:0; right:0; height:24px; background:var(--surface); border-radius:24px 24px 0 0; }
  .hero h1 { font-family:'Syne',sans-serif; font-size:1.6rem; font-weight:800; line-height:1.22; margin-bottom:.38rem; }
  .hero h1 span { color:#F2B705; }
  .hero p { opacity:.72; font-size:.84rem; display:flex; align-items:center; gap:.4rem; flex-wrap:wrap; }
  .hero-badge { display:inline-flex; align-items:center; gap:.22rem; background:rgba(255,255,255,.12); padding:.22rem .65rem; border-radius:20px; font-size:.74rem; font-weight:700; border:1px solid rgba(255,255,255,.2); }

  /* SEARCH */
  .search-wrap { margin:-10px 1.2rem .75rem; position:relative; z-index:3; }
  .search-wrap input { width:100%; padding:.9rem 1.2rem .9rem 2.9rem; border:2px solid var(--border); border-radius:50px; font-family:'DM Sans',sans-serif; font-size:.9rem; outline:none; background:var(--card); color:var(--t1); box-shadow:var(--sh); transition:border var(--transition),box-shadow var(--transition); }
  .search-wrap input:focus { border-color:var(--green); box-shadow:0 0 0 3px rgba(45,125,79,.1),var(--sh); }
  .si { position:absolute; left:1rem; top:50%; transform:translateY(-50%); font-size:.95rem; pointer-events:none; }

  /* CHIPS */
  .chips-wrap { padding:0 1.2rem .85rem; overflow-x:auto; }
  .chips-wrap::-webkit-scrollbar { display:none; }
  .chips { display:flex; gap:.5rem; width:max-content; }
  .chip { padding:.46rem 1rem; border:1.5px solid var(--border); border-radius:50px; background:var(--card); font-size:.77rem; font-weight:700; cursor:pointer; white-space:nowrap; transition:all .18s; color:var(--t1); display:flex; align-items:center; gap:.3rem; }
  .chip:hover { border-color:var(--green); color:var(--green); }
  .chip.on    { background:var(--green); color:#fff; border-color:var(--green); }
  .active-filters { padding:0 1.2rem .7rem; display:flex; gap:.42rem; flex-wrap:wrap; }
  .af-tag { display:inline-flex; align-items:center; gap:.3rem; padding:.3rem .75rem; background:var(--green-lt); border:1.5px solid var(--green); border-radius:50px; font-size:.73rem; font-weight:700; color:var(--green); cursor:pointer; }
  .af-tag:hover { background:var(--green); color:#fff; }

  /* LISTINGS */
  .listings { padding:0 1.2rem 1rem; }
  .sec-lbl { font-size:.68rem; font-weight:800; color:var(--t2); text-transform:uppercase; letter-spacing:1.4px; padding:.2rem 0 .7rem; display:flex; align-items:center; gap:.45rem; }
  .sec-lbl::after { content:''; flex:1; height:1px; background:var(--border); }

  /* CARD */
  .dorm-card { background:var(--card); border-radius:var(--rad); padding:1.1rem; margin-bottom:1rem; box-shadow:var(--sh); border:1.5px solid var(--border); transition:transform var(--transition),box-shadow var(--transition),border-color var(--transition); }
  .dorm-card:hover { transform:translateY(-3px); box-shadow:var(--sh2); }
  .dorm-card.cmp-on { border-color:var(--gold); box-shadow:0 0 0 3px rgba(242,183,5,.18); }

  /* CAROUSEL */
  .carousel { position:relative; border-radius:12px; overflow:hidden; margin-bottom:.9rem; background:var(--bg); }
  .carousel-inner { display:flex; transition:transform .35s cubic-bezier(.4,0,.2,1); }
  .carousel-slide { min-width:100%; height:185px; flex-shrink:0; }
  .carousel-slide img { width:100%; height:100%; object-fit:cover; cursor:pointer; transition:transform .3s; }
  .carousel-slide img:hover { transform:scale(1.02); }
  .carousel-dots { position:absolute; bottom:8px; left:50%; transform:translateX(-50%); display:flex; gap:5px; }
  .c-dot { width:6px; height:6px; border-radius:50%; background:rgba(255,255,255,.5); transition:all .2s; cursor:pointer; }
  .c-dot.on { background:#fff; width:18px; border-radius:3px; }
  .carousel-btn { position:absolute; top:50%; transform:translateY(-50%); background:rgba(255,255,255,.85); border:none; border-radius:50%; width:30px; height:30px; font-size:.85rem; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 8px rgba(0,0,0,.2); transition:all .18s; z-index:2; }
  .carousel-btn:hover { background:#fff; transform:translateY(-50%) scale(1.08); }
  .carousel-btn.prev { left:8px; }
  .carousel-btn.next { right:8px; }
  .dist-overlay { position:absolute; top:10px; right:10px; background:rgba(0,0,0,.55); color:#fff; padding:.24rem .7rem; border-radius:20px; font-size:.72rem; font-weight:700; backdrop-filter:blur(4px); }
  .lazy-pending { opacity:0; transition:opacity .35s; }
  .lazy-loaded  { opacity:1; }

  /* CARD BODY */
  .rat-row { display:flex; align-items:center; gap:.38rem; margin-bottom:.52rem; }
  .stars { color:var(--gold); font-size:.82rem; letter-spacing:-.5px; }
  .rv    { font-weight:800; font-size:.82rem; color:var(--t1); }
  .rc    { font-size:.74rem; color:var(--t2); }
  .card-top { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:.45rem; }
  .type-badge { display:inline-flex; align-items:center; gap:.24rem; padding:.24rem .75rem; border-radius:20px; font-size:.72rem; font-weight:800; }
  .type-badge.Room     { background:#e8f5ee; color:#1f5c38; }
  .type-badge.Bedspace { background:var(--blue-lt); color:#1d4ed8; }
  .type-badge.Unit     { background:var(--gold-lt); color:#92400E; }
  .heart { font-size:1.25rem; cursor:pointer; transition:transform .2s,color .2s; line-height:1; }
  .heart:hover { transform:scale(1.25); }
  .card-street { font-weight:800; font-size:1rem; margin-bottom:.1rem; color:var(--t1); }
  .card-price  { font-family:'Syne',sans-serif; font-size:1.32rem; font-weight:800; color:var(--green); margin-bottom:.48rem; }
  .card-price small { font-size:.7rem; font-weight:500; color:var(--t2); font-family:'DM Sans',sans-serif; }
  .metas { display:flex; flex-wrap:wrap; gap:.38rem; margin-bottom:.8rem; }
  .mpill { display:inline-flex; align-items:center; gap:.22rem; padding:.24rem .65rem; background:var(--bg); border-radius:20px; font-size:.72rem; font-weight:600; color:var(--t2); border:1px solid var(--border); }
  .mpill.ok   { background:var(--green-lt); color:var(--green); border-color:#b2dfca; }
  .mpill.blue { background:var(--blue-lt);  color:#1d4ed8;      border-color:#bfdbfe; }
  .owner-chip { display:inline-flex; align-items:center; gap:.3rem; padding:.22rem .65rem; background:var(--bg); border:1.5px solid var(--border); border-radius:20px; font-size:.72rem; font-weight:700; color:var(--t2); cursor:pointer; transition:all .18s; margin-bottom:.5rem; }
  .owner-chip:hover { border-color:var(--green); color:var(--green); background:var(--green-lt); }
  .inc-box  { background:var(--bg); border-radius:13px; padding:.75rem .9rem; margin-bottom:.78rem; border:1px solid var(--border); }
  .inc-ttl  { font-weight:800; font-size:.68rem; color:var(--t2); text-transform:uppercase; letter-spacing:.8px; margin-bottom:.42rem; }
  .inc-grid { display:grid; grid-template-columns:1fr 1fr; gap:.28rem; }
  .inc-i    { font-size:.76rem; color:var(--t1); display:flex; align-items:center; gap:.24rem; }

  /* BUTTONS */
  .btn-row { display:grid; grid-template-columns:1fr 1fr; gap:.45rem; margin-bottom:.45rem; }
  .btn { padding:.68rem .5rem; border-radius:50px; font-family:'DM Sans',sans-serif; font-size:.78rem; font-weight:700; cursor:pointer; border:none; transition:all var(--transition); text-align:center; white-space:nowrap; }
  .btn:active { transform:scale(.97) !important; }
  .btn-green { background:var(--green); color:#fff; box-shadow:0 3px 10px rgba(45,125,79,.28); }
  .btn-green:hover { background:var(--green-dk); transform:translateY(-1px); }
  .btn-out   { background:transparent; border:2px solid var(--green); color:var(--green); }
  .btn-out:hover { background:var(--green); color:#fff; }
  .btn-gold  { background:var(--gold); color:#1F2933; box-shadow:0 3px 10px rgba(242,183,5,.3); }
  .btn-gold:hover { background:var(--gold-dk); transform:translateY(-1px); }
  .btn-cmp   { background:transparent; border:2px solid var(--gold); color:var(--gold-dk); }
  .btn-cmp.on { background:var(--gold); color:#1F2933; }
  .btn-blue  { background:var(--blue); color:#fff; box-shadow:0 3px 10px rgba(59,130,246,.28); }
  .btn-blue:hover { background:#2563eb; transform:translateY(-1px); }
  .btn-full  { width:100%; padding:.84rem; font-size:.9rem; margin-bottom:.42rem; }
  .empty    { text-align:center; padding:3rem 1rem; color:var(--t2); }
  .empty-ic { font-size:3.5rem; margin-bottom:.72rem; }
  .empty p  { font-weight:600; font-size:.88rem; }

  /* COMPARE BAR */
  #cmp-bar { position:fixed; bottom:72px; left:50%; transform:translateX(-50%); max-width:480px; width:100%; background:var(--green); color:#fff; padding:11px 1.3rem; display:flex; justify-content:space-between; align-items:center; z-index:300; box-shadow:0 -2px 12px rgba(45,125,79,.3); }
  #cmp-bar.hide { display:none; }
  #cmp-bar .cb-t { font-size:.84rem; font-weight:700; }
  #cmp-bar .cb-btn { background:var(--gold); color:#1F2933; border:none; border-radius:22px; padding:8px 16px; font-weight:800; font-size:.78rem; cursor:pointer; font-family:'DM Sans',sans-serif; transition:all .18s; }
  #cmp-bar .cb-btn:hover { background:var(--gold-dk); }

  /* LIGHTBOX */
  #lb { display:none; position:fixed; inset:0; background:rgba(0,0,0,.96); z-index:10001; justify-content:center; align-items:center; }
  #lb.active { display:flex; }
  #lb img { max-width:92%; max-height:90vh; object-fit:contain; border-radius:12px; }
  #lb-cls { position:absolute; top:18px; right:18px; font-size:1.8rem; color:#fff; cursor:pointer; background:rgba(255,255,255,.12); border:none; width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; }

  /* MODALS */
  .modal { display:none; position:fixed; inset:0; background:rgba(0,0,0,.55); z-index:10000; align-items:flex-end; justify-content:center; }
  .modal.active { display:flex; }
  .msheet { background:var(--card); width:100%; max-width:480px; border-radius:24px 24px 0 0; padding:1.4rem 1.3rem; max-height:92vh; overflow-y:auto; animation:sUp .28s cubic-bezier(.4,0,.2,1); }
  @keyframes sUp { from{transform:translateY(100%)} to{transform:translateY(0)} }
  .mhandle { width:38px; height:4px; background:var(--border); border-radius:2px; margin:0 auto 1.1rem; }
  .m-ttl { font-family:'Syne',sans-serif; font-size:1.14rem; font-weight:800; margin-bottom:.24rem; color:var(--t1); }
  .m-sub { font-size:.8rem; color:var(--t2); margin-bottom:1rem; }
  .opt-i { padding:.84rem .95rem; border:1.5px solid var(--border); border-radius:12px; margin-bottom:.52rem; cursor:pointer; transition:all .2s; color:var(--t1); font-size:.87rem; font-weight:600; display:flex; align-items:center; gap:.6rem; }
  .opt-i:hover,.opt-i.sel { background:var(--green); color:#fff; border-color:var(--green); }
  .opt-i.multi.sel { background:var(--green-lt); color:var(--green); border-color:var(--green); }
  .apply-btn { width:100%; padding:.92rem; background:var(--gold); color:#1F2933; border:none; border-radius:50px; font-family:'DM Sans',sans-serif; font-size:.92rem; font-weight:800; cursor:pointer; margin-top:.7rem; transition:all .2s; box-shadow:0 3px 12px rgba(242,183,5,.35); }
  .apply-btn:hover { background:var(--gold-dk); }

  /* SCHEDULE */
  .fg { margin-bottom:.95rem; }
  .fg label { display:block; font-weight:700; font-size:.78rem; margin-bottom:.4rem; color:var(--t1); text-transform:uppercase; letter-spacing:.5px; }
  .fg input { width:100%; padding:.82rem .95rem; border:1.5px solid var(--border); border-radius:12px; font-family:'DM Sans',sans-serif; font-size:.87rem; outline:none; background:var(--card); color:var(--t1); transition:border var(--transition); }
  .fg input:focus { border-color:var(--green); }
  .tgrid { display:grid; grid-template-columns:repeat(3,1fr); gap:.45rem; }
  .tslot { padding:.56rem; border:1.5px solid var(--border); border-radius:10px; text-align:center; cursor:pointer; font-size:.76rem; font-weight:700; transition:all .2s; color:var(--t1); background:var(--card); }
  .tslot:hover { border-color:var(--green); color:var(--green); }
  .tslot.sel { background:var(--green); color:#fff; border-color:var(--green); }
  .sub-btn { width:100%; padding:.92rem; background:var(--green); color:#fff; border:none; border-radius:50px; font-weight:700; font-size:.9rem; cursor:pointer; margin-top:.45rem; font-family:'DM Sans',sans-serif; transition:all .2s; box-shadow:0 4px 14px rgba(45,125,79,.3); display:block; text-align:center; text-decoration:none; }
  .sub-btn:hover { background:var(--green-dk); }

  /* REVIEWS */
  .rev-card { background:var(--card); padding:.95rem; border-radius:14px; margin-bottom:.75rem; border:1.5px solid var(--border); }
  .rev-top  { display:flex; justify-content:space-between; align-items:center; margin-bottom:.42rem; }
  .rev-n    { font-weight:700; font-size:.86rem; display:flex; align-items:center; gap:.4rem; }
  .rev-txt  { color:var(--t2); font-size:.82rem; line-height:1.55; }
  .rev-dt   { font-size:.72rem; color:var(--t2); margin-top:.32rem; }
  .add-review-box { background:var(--bg); border:1.5px solid var(--border); border-radius:14px; padding:1rem; }
  .star-picker { display:flex; gap:.4rem; margin-bottom:.7rem; }
  .star-pick   { font-size:1.5rem; cursor:pointer; color:var(--border); transition:color .15s,transform .15s; }
  .star-pick.on,.star-pick:hover { color:var(--gold); }
  .star-pick:hover { transform:scale(1.2); }
  .rev-input   { width:100%; padding:.72rem .9rem; border:1.5px solid var(--border); border-radius:10px; font-family:'DM Sans',sans-serif; font-size:.85rem; outline:none; background:var(--card); color:var(--t1); resize:none; transition:border var(--transition); }
  .rev-input:focus { border-color:var(--green); }
  .rev-submit-btn { margin-top:.65rem; padding:.68rem 1.4rem; background:var(--green); color:#fff; border:none; border-radius:50px; font-family:'DM Sans',sans-serif; font-size:.82rem; font-weight:700; cursor:pointer; transition:all var(--transition); }
  .rev-submit-btn:hover { background:var(--green-dk); }

  /* DIRECTIONS */
  .dir-stat { background:var(--bg); border-radius:11px; padding:.5rem .75rem; flex:1; text-align:center; border:1px solid var(--border); }
  .dir-stat-v { font-family:'Syne',sans-serif; font-weight:800; font-size:1rem; color:var(--green); }
  .dir-stat-l { font-size:.66rem; color:var(--t2); font-weight:700; text-transform:uppercase; letter-spacing:.4px; }
  .dir-step   { display:flex; align-items:flex-start; gap:.7rem; padding:.55rem .5rem; border-bottom:1px solid var(--border); }
  .dir-step:last-child { border:none; }
  .step-ic    { width:28px; height:28px; border-radius:50%; background:var(--green); color:#fff; display:flex; align-items:center; justify-content:center; font-size:.75rem; flex-shrink:0; }
  .step-ic.arr { background:var(--gold); color:#1F2933; }
  .step-txt   { flex:1; font-size:.8rem; color:var(--t1); line-height:1.45; padding-top:.25rem; }
  .step-dist  { font-size:.71rem; color:var(--t2); font-weight:700; margin-top:2px; }
</style>
@endpush

@push('scripts')
<script>
'use strict';

 
const DORMS_DATA = {!! $dormsDataJson !!};

const UI = {
  openLb(src) {
    if (!src) return;
    document.getElementById('lbImg').src = src;
    document.getElementById('lb').classList.add('active');
  },
  closeLb() { document.getElementById('lb').classList.remove('active'); },

  initLazyImages() {
    const imgs = document.querySelectorAll('img[data-src]');
    if (!('IntersectionObserver' in window)) {
      imgs.forEach(img => { img.src = img.dataset.src; img.classList.replace('lazy-pending','lazy-loaded'); });
      return;
    }
    const obs = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          const img = e.target;
          if (img.dataset.src) {
            img.src = img.dataset.src;
            img.classList.replace('lazy-pending','lazy-loaded');
            img.removeAttribute('data-src');
            obs.unobserve(img);
          }
        }
      });
    }, { rootMargin:'200px' });
    imgs.forEach(img => obs.observe(img));
  },
};

const Carousel = {
  _idx: {},
  slide(id, dir) {
    const dorm = DORMS_DATA.find(d => d.id === id);
    if (!dorm?.imgs?.length) return;
    this._idx[id] = ((this._idx[id] ?? 0) + dir + dorm.imgs.length) % dorm.imgs.length;
    this._update(id);
  },
  goSlide(id, idx) { this._idx[id] = idx; this._update(id); },
  _update(id) {
    const ci = document.getElementById('ci-' + id);
    if (ci) ci.style.transform = `translateX(-${this._idx[id] * 100}%)`;
    document.querySelectorAll(`#car-${id} .c-dot`).forEach((dot, i) =>
      dot.classList.toggle('on', i === this._idx[id]));
  },
};

const Saved = {
  _saved: new Set({{ Js::from(session('saved', [])) }}),
  toggle(id, evt) {
    evt.stopPropagation();
    const heart = document.getElementById('heart-' + id);
    fetch('/dorms/' + id + '/save', {
      method:'POST',
      headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN':window.csrfToken },
      body: JSON.stringify({ dorm_id: id }),
    })
    .then(r => r.json())
    .then(data => {
      if (data.saved) {
        this._saved.add(id); heart.textContent = '♥'; heart.style.color = '#C8102E';
        showToast('❤️ Saved!', 'ok');
      } else {
        this._saved.delete(id); heart.textContent = '♡'; heart.style.color = 'var(--border)';
        showToast('💔 Removed');
      }
    })
    .catch(() => showToast('⚠️ Could not save', 'warn'));
  },
};

const Compare = {
  _list: [],
  toggle(id) {
    const i = this._list.indexOf(id);
    if (i > -1) {
      this._list.splice(i, 1);
      document.getElementById('card-' + id)?.classList.remove('cmp-on');
      const btn = document.getElementById('cmp-btn-' + id);
      if (btn) { btn.textContent = '⚖️ Compare'; btn.classList.remove('on'); }
    } else {
      if (this._list.length >= 3) { showToast('⚠️ Max 3 dorms', 'warn'); return; }
      this._list.push(id);
      document.getElementById('card-' + id)?.classList.add('cmp-on');
      const btn = document.getElementById('cmp-btn-' + id);
      if (btn) { btn.textContent = '✓ Added'; btn.classList.add('on'); }
    }
    this._updateBar();
  },
  _updateBar() {
    const bar = document.getElementById('cmp-bar');
    if (!this._list.length) { bar.classList.add('hide'); return; }
    bar.classList.remove('hide');
    document.getElementById('cmpTxt').textContent =
      `${this._list.length} dorm${this._list.length > 1 ? 's' : ''} selected`;
  },
  open() {
    if (this._list.length < 2) { showToast('⚠️ Select at least 2', 'warn'); return; }
  window.location.href = '/student/dorms/compare?ids=' + this._list.join(',');
  },
};

const Search = {
  _q: '',
  run(q) { this._q = q.toLowerCase().trim(); Filters._applyAll(); },
};

const Filters = {
  _state: { price:null, type:null, distance:null, gender:null, avail:true, adv:[] },

  _config: {
    price:    { title:'💰 Filter by Price',  sub:'Select one', single:true,
                opts:[['0-3000','Under ₱3,000'],['3001-4000','₱3,000–₱4,000'],['4001-5000','₱4,000–₱5,000'],['5001-999999','₱5,000+']] },
    type:     { title:'🏠 Filter by Type',   sub:'Select one', single:true,
                opts:[['bedspace','🛌 Bedspace'],['room','🛏️ Room'],['unit','🏠 Unit']] },
    distance: { title:'🚶 Walking Distance', sub:'Select one', single:true,
                opts:[['5','5 min or less'],['7','7 min or less'],['10','10 min or less'],['15','15 min or less']] },
    gender:   { title:'👥 Gender Policy',    sub:'Select one', single:true,
                opts:[['male','👨 Male Only'],['female','👩 Female Only'],['any','👥 Any']] },
    advanced: { title:'⚙️ More Filters',     sub:'Multi-select', single:false,
                opts:[['pets','🐾 Pets Allowed'],['no-curfew','🌙 No Curfew'],['wifi','📶 WiFi Included']] },
  },

  open(type) {
    const c = this._config[type];
    document.getElementById('fTitle').textContent = c.title;
    document.getElementById('fSub').textContent   = c.sub;
    document.getElementById('fOpts').innerHTML = c.opts.map(([v, l]) => {
      const active = c.single ? this._state[type] === v : this._state.adv.includes(v);
      return `<div class="opt-i${!c.single ? ' multi' : ''}${active ? ' sel' : ''}" data-v="${v}">${l}</div>`;
    }).join('');
    const modal = document.getElementById('filterModal');
    modal.setAttribute('data-ft', type);
    modal.classList.add('active');
    modal.querySelectorAll('.opt-i').forEach(item => {
      item.onclick = function() {
        if (this.classList.contains('multi')) this.classList.toggle('sel');
        else {
          modal.querySelectorAll('.opt-i:not(.multi)').forEach(x => x.classList.remove('sel'));
          this.classList.toggle('sel');
        }
      };
    });
  },

  apply() {
    const modal = document.getElementById('filterModal');
    const type  = modal.getAttribute('data-ft');
    if (this._config[type].single) {
      const sel = modal.querySelector('.opt-i:not(.multi).sel');
      this._state[type] = sel ? sel.getAttribute('data-v') : null;
    } else {
      this._state.adv = [...modal.querySelectorAll('.opt-i.multi.sel')].map(x => x.getAttribute('data-v'));
    }
    modal.classList.remove('active');
    this._applyAll();
    this._renderTags();
  },

  toggleAvail() {
    this._state.avail = !this._state.avail;
    document.getElementById('availChip').classList.toggle('on', this._state.avail);
    this._applyAll();
  },

  _applyAll() {
    const f = this._state;
    const q = Search._q;
    let visible = 0;

    document.querySelectorAll('.dorm-card').forEach(card => {
      let show = true;
      const price  = parseInt(card.dataset.price)  || 0;
      const walk   = parseInt(card.dataset.walk)   || 99;
      const type   = card.dataset.type   || '';
      const gender = card.dataset.gender || '';
      const status = card.dataset.status || '';
      const wifi   = card.dataset.wifi   === '1';
      const pets   = card.dataset.pets   === '1';
      const curfew = card.dataset.curfew || '';
      const street = card.dataset.street || '';
      const owner  = card.dataset.owner  || '';

      if (q && !street.includes(q) && !type.includes(q) && !owner.includes(q)) show = false;
      if (f.avail && status !== 'available') show = false;
      if (f.price) {
        const [mn, mx] = f.price.split('-').map(Number);
        if (price < mn || price > mx) show = false;
      }
      if (f.type && type !== f.type) show = false;
      if (f.distance && walk > parseInt(f.distance)) show = false;
      if (f.gender && f.gender !== 'any' && gender !== 'any' && gender !== f.gender) show = false;
      if (f.adv.includes('wifi')      && !wifi)                              show = false;
      if (f.adv.includes('pets')      && !pets)                              show = false;
      if (f.adv.includes('no-curfew') && curfew && curfew !== 'no curfew')  show = false;

      card.style.display = show ? '' : 'none';
      if (show) visible++;
    });

    const noResults = document.getElementById('noResults');
    if (noResults) noResults.style.display = visible === 0 ? 'block' : 'none';

    const countEl = document.getElementById('listCount');
    if (countEl) countEl.textContent = `(${visible} listing${visible !== 1 ? 's' : ''})`;
  },

  _renderTags() {
    const f = this._state;
    const tags = [];
    if (f.price)                         tags.push({ label:`💰 ₱${f.price.replace('-',' – ₱')}`, key:'price' });
    if (f.type)                          tags.push({ label:`🏠 ${f.type}`,   key:'type' });
    if (f.distance)                      tags.push({ label:`🚶 ≤${f.distance}min`, key:'distance' });
    if (f.gender && f.gender !== 'any')  tags.push({ label:`👥 ${f.gender}`, key:'gender' });
    f.adv.forEach((a, i) => {
      const map = { pets:'🐾 Pets','no-curfew':'🌙 No Curfew',wifi:'📶 WiFi' };
      tags.push({ label: map[a] || a, key:`adv-${i}` });
    });
    document.getElementById('activeFilters').innerHTML = tags.map((t, idx) =>
      `<div class="af-tag" onclick="Filters._clearTag(${idx})">${t.label} <span style="opacity:.7">✕</span></div>`
    ).join('');
  },

  _clearTag(idx) {
    const f = this._state;
    const keys = [];
    if (f.price)    keys.push('price');
    if (f.type)     keys.push('type');
    if (f.distance) keys.push('distance');
    if (f.gender && f.gender !== 'any') keys.push('gender');
    f.adv.forEach((_, i) => keys.push(`adv-${i}`));
    const k = keys[idx];
    if (!k) return;
    if (k.startsWith('adv-')) f.adv.splice(parseInt(k.split('-')[1]), 1);
    else f[k] = null;
    this._applyAll();
    this._renderTags();
  },
};

const Reviews = {
  _pickedRating: 0,
  _dormId: null,

  show(dormId, street) {
    this._dormId = dormId;
    this._pickedRating = 0;
    document.getElementById('revModalTitle').textContent = `⭐ ${street}`;
    if (document.getElementById('revDormId')) document.getElementById('revDormId').value = dormId;
    if (document.getElementById('revText'))   document.getElementById('revText').value   = '';
    document.querySelectorAll('.star-pick').forEach(s => s.classList.remove('on'));

    const body = document.getElementById('revModalBody');
    body.innerHTML = '<div style="text-align:center;padding:1.5rem;color:var(--t2);">⏳ Loading…</div>';
    document.getElementById('reviewsModal').classList.add('active');

    fetch('/dorms/' + dormId + '/reviews', {
      headers:{ 'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
      if (!data.length) {
        body.innerHTML = '<div class="empty" style="padding:1.5rem;"><div class="empty-ic">⭐</div><p>No reviews yet. Be the first!</p></div>';
        return;
      }
      body.innerHTML = data.map(r => `
        <div class="rev-card">
          <div class="rev-top">
            <span class="rev-n">${r.user_name}
              ${r.is_verified ? '<span style="background:var(--green-lt);color:var(--green);font-size:.62rem;font-weight:800;padding:.1rem .4rem;border-radius:6px;margin-left:.3rem;">✓</span>' : ''}
            </span>
            <span class="stars">${'★'.repeat(r.rating)}${'☆'.repeat(5-r.rating)}</span>
          </div>
          <div class="rev-txt">${r.comment}</div>
          <div class="rev-dt">${r.created_at}</div>
        </div>`).join('');
    })
    .catch(() => {
      body.innerHTML = '<div style="text-align:center;padding:1.5rem;color:var(--t2);">Could not load reviews.</div>';
    });
  },

  pickStar(n) {
    this._pickedRating = n;
    document.querySelectorAll('.star-pick').forEach(s =>
      s.classList.toggle('on', parseInt(s.dataset.val) <= n));
  },

  submit() {
    const text = document.getElementById('revText').value.trim();
    if (!this._pickedRating) { showToast('⚠️ Pick a star rating', 'warn'); return; }
    if (!text)               { showToast('⚠️ Write your review', 'warn');  return; }

    fetch('/dorms/' + this._dormId + '/reviews', {
      method:'POST',
      headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN':window.csrfToken },
      body: JSON.stringify({ rating:this._pickedRating, comment:text }),
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        showToast('✅ Review submitted!', 'ok');
        const street = document.getElementById('revModalTitle').textContent.replace('⭐ ','');
        this.show(this._dormId, street);
        const rc = document.querySelector(`#card-${this._dormId} .rc`);
        if (rc && data.reviews_count) rc.textContent = `(${data.reviews_count} reviews)`;
      } else {
        showToast('⚠️ ' + (data.message || 'Could not submit'), 'warn');
      }
    })
    .catch(() => showToast('⚠️ Could not submit review', 'warn'));
  },
};

const Directions = {
  _map: null,
  _routeLayer: null,
  _glowLayer: null,
  _destMarker: null,
  _userMarker: null,

  _watchId: null,
  _lastUserLoc: null,
  _destLat: null,
  _destLng: null,
  _lastUpdate: 0,

  // 👉 YOUR WAYPOINT (GCH Gate near 7/11 Alangilan)
  _waypointLat: 13.786612110666772,
  _waypointLng: 121.06926620190735,

  _fmtM: m => m < 1000 ? `${Math.round(m)}m` : `${(m / 1000).toFixed(1)}km`,
  _fmtS: s => {
    const mn = Math.round(s / 60);
    return mn < 60 ? `${mn} min` : `${Math.floor(mn / 60)}h ${mn % 60}m`;
  },

  _turnEmoji(type, mod) {
    if (type === 'arrive') return '🏁';
    if (type === 'depart') return '🚶';
    if (!mod) return '⬆️';
    const m = mod.toLowerCase();
    if (m.includes('uturn')) return '↩️';
    if (m.includes('right')) return '➡️';
    if (m.includes('left')) return '⬅️';
    return '⬆️';
  },

  async get(dormId, destLat, destLng, street) {
    if (!destLat || !destLng) {
      showToast('⚠️ No map location set for this listing', 'warn');
      return;
    }

    this._destLat = destLat;
    this._destLng = destLng;
    this._lastUserLoc = null;
    this._lastUpdate = 0;

    document.getElementById('dirModalTitle').textContent = `🧭 To ${street}`;
    document.getElementById('dirStats').innerHTML =
      '<div style="color:var(--t2);font-size:.82rem;">📍 Waiting for live GPS...</div>';
    document.getElementById('dirSteps').innerHTML = '';
    document.getElementById('directionsModal').classList.add('active');

    const userLoc = await new Promise(resolve => {
      if (!navigator.geolocation) return resolve(null);

      navigator.geolocation.getCurrentPosition(
        pos => resolve({
          lat: pos.coords.latitude,
          lng: pos.coords.longitude
        }),
        () => resolve(null),
        { enableHighAccuracy: true, timeout: 8000 }
      );
    });

    if (!userLoc) {
      document.getElementById('dirStats').innerHTML =
        '<div style="color:var(--red);font-size:.82rem;">⚠️ Enable GPS to continue</div>';
      return;
    }

    await this._buildMap(userLoc, destLat, destLng, street);

    this._watchId = navigator.geolocation.watchPosition(
      pos => {
        const loc = {
          lat: pos.coords.latitude,
          lng: pos.coords.longitude
        };
        this._updateLiveRoute(loc, street);
      },
      () => showToast('⚠️ GPS tracking error', 'warn'),
      { enableHighAccuracy: true, maximumAge: 1000, timeout: 10000 }
    );
  },

  async _updateLiveRoute(userLoc, street) {
    if (!this._map || !this._destLat) return;

    const now = Date.now();
    if (now - this._lastUpdate < 3000) return;
    this._lastUpdate = now;

    if (!this._userMarker) {
      this._userMarker = L.circleMarker([userLoc.lat, userLoc.lng], {
        radius: 8,
        fillColor: '#C8102E',
        color: '#fff',
        weight: 2,
        fillOpacity: 1
      }).addTo(this._map);
    } else {
      this._userMarker.setLatLng([userLoc.lat, userLoc.lng]);
    }

    try {
      // 🔥 SEGMENT 1: USER → WAYPOINT
      const url1 =
        `https://router.project-osrm.org/route/v1/foot/` +
        `${userLoc.lng},${userLoc.lat};${this._waypointLng},${this._waypointLat}` +
        `?steps=false&geometries=geojson&overview=full`;

      // 🔥 SEGMENT 2: WAYPOINT → DESTINATION
      const url2 =
        `https://router.project-osrm.org/route/v1/foot/` +
        `${this._waypointLng},${this._waypointLat};${this._destLng},${this._destLat}` +
        `?steps=false&geometries=geojson&overview=full`;

      const [r1, r2] = await Promise.all([
        fetch(url1).then(r => r.json()),
        fetch(url2).then(r => r.json())
      ]);

      if (!r1.routes?.length || !r2.routes?.length) return;

      const coords1 = r1.routes[0].geometry.coordinates;
      const coords2 = r2.routes[0].geometry.coordinates;

      // 🔥 MERGE ROUTES (LOCKED PATH)
      const merged = [...coords1, ...coords2];
      const pts = merged.map(c => [c[1], c[0]]);

      if (this._routeLayer) this._map.removeLayer(this._routeLayer);
      if (this._glowLayer) this._map.removeLayer(this._glowLayer);

      this._routeLayer = L.polyline(pts, {
        color: '#2D7D4F',
        weight: 6,
        opacity: 0.9
      }).addTo(this._map);

      this._glowLayer = L.polyline(pts, {
        color: '#74C69D',
        weight: 3,
        opacity: 0.5,
        dashArray: '10,8'
      }).addTo(this._map);

      const totalDist = r1.routes[0].distance + r2.routes[0].distance;
      const totalDur = r1.routes[0].duration + r2.routes[0].duration;

      document.getElementById('dirStats').innerHTML = `
        <div class="dir-stat">
          <div class="dir-stat-v">${this._fmtM(totalDist)}</div>
          <div class="dir-stat-l">Remaining</div>
        </div>
        <div class="dir-stat">
          <div class="dir-stat-v">${this._fmtS(totalDur)}</div>
          <div class="dir-stat-l">ETA</div>
        </div>
      `;

    } catch (err) {
      console.log('Route update failed:', err);
    }
  },

  async _buildMap(userLoc, destLat, destLng, street) {
    if (this._map) {
      try { this._map.remove(); } catch (_) {}
      this._map = null;
    }

    await new Promise(r => setTimeout(r, 80));

    this._map = L.map('dirMap', { zoomControl: true })
      .setView([userLoc.lat, userLoc.lng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap',
      maxZoom: 19
    }).addTo(this._map);

    L.circleMarker([userLoc.lat, userLoc.lng], {
      radius: 10,
      fillColor: '#C8102E',
      color: '#fff',
      weight: 3,
      fillOpacity: 1
    }).addTo(this._map).bindPopup('📍 You are here');

    this._destMarker = L.marker([destLat, destLng], {
      icon: L.divIcon({
        className: '',
        html: `<div style="background:#2D7D4F;width:28px;height:28px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 10px rgba(0,0,0,.28)"></div>`,
        iconSize: [28, 28],
        iconAnchor: [14, 28]
      })
    }).addTo(this._map).bindPopup(street);
  },

  stopTracking() {
    if (this._watchId !== null) {
      navigator.geolocation.clearWatch(this._watchId);
      this._watchId = null;
    }
  },

  close() {
    this.stopTracking();
    document.getElementById('directionsModal').classList.remove('active');
  }
};

const Schedule = {
  _selectedTime: null,
  _dormId: null,

  open(dormId, street, type, price, owner) {
    this._dormId = dormId;
    this._selectedTime = null;
    document.querySelectorAll('.tslot').forEach(b => b.classList.remove('sel'));
    document.getElementById('schedDormId').value = dormId;
    document.getElementById('vNotes').value      = '';
    document.getElementById('vDate').value       = '';
    document.getElementById('schedInfo').innerHTML =
      `<strong>${street}</strong> — ${type}<br>
       <span style="color:var(--t2);">₱${Number(price).toLocaleString()}/mo · 👤 ${owner || 'Owner'}</span>`;
    document.getElementById('schedModal').classList.add('active');
  },

  selTime(el) {
    document.querySelectorAll('.tslot').forEach(b => b.classList.remove('sel'));
    el.classList.add('sel');
    this._selectedTime = el.textContent.trim();
  },

  submit() {
    const dt    = document.getElementById('vDate').value;
    const notes = document.getElementById('vNotes').value;
    if (!dt)                 { showToast('⚠️ Select a date', 'warn');      return; }
    if (!this._selectedTime) { showToast('⚠️ Select a time slot', 'warn'); return; }

    fetch('/visits', {
      method:'POST',
      headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN':window.csrfToken },
      body: JSON.stringify({ dorm_listing_id:this._dormId, visit_date:dt, visit_time:this._selectedTime, notes }),
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        document.getElementById('schedModal').classList.remove('active');
        showToast(`✅ Visit confirmed — ${dt} at ${this._selectedTime}!`, 'ok');
      } else {
        showToast('⚠️ ' + (data.message || 'Could not schedule'), 'warn');
      }
    })
    .catch(() => showToast('⚠️ Could not schedule visit', 'warn'));
  },
};

const OwnerProfile = { show(id) { window.location.href = `/owners/${id}`; } };
const ChatModule   = {
  contactOwner(ownerId, street, dormId) {
    if (!ownerId) { showToast('⚠️ Owner info not available', 'warn'); return; }
    window.location.href = `/messages/${ownerId}?dorm=${dormId}`;
  },
};

document.querySelectorAll('.modal').forEach(modal => {
  modal.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('active'); });
});

let _searchTimer;
document.getElementById('searchInput').addEventListener('input', e => {
  clearTimeout(_searchTimer);
  _searchTimer = setTimeout(() => Search.run(e.target.value), 250);
});

document.addEventListener('DOMContentLoaded', () => {
  UI.initLazyImages();
  Filters._applyAll();

  const urlQ = new URLSearchParams(window.location.search).get('search') || '';
  if (urlQ) Search.run(urlQ);
});
</script>
@endpush