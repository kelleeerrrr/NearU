<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#2D7D4F">

  <title>@yield('title', 'NearU – Find Housing Near Campus')</title>

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

  {{-- Leaflet CSS --}}
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

  {{-- Per-page styles --}}
  @stack('styles')

  <style>
    /* ═══════════════════════════════════════
       RESET & CSS VARIABLES
    ═══════════════════════════════════════ */
    *, *::before, *::after {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      -webkit-tap-highlight-color: transparent;
    }

    :root {
      /* Backgrounds */
      --bg:         #F0F7F2;
      --surface:    #fff;
      --card:       #fff;

      /* Text */
      --t1:         #141F14;
      --t2:         #5E6E5E;

      /* Border */
      --border:     #D6E8DC;

      /* Green palette */
      --green:      #2D7D4F;
      --green-dk:   #1f5c38;
      --green-lt:   #E8F7EE;

      /* Gold palette */
      --gold:       #F2B705;
      --gold-dk:    #c99200;
      --gold-lt:    #FFFBEB;

      /* Blue palette */
      --blue:       #3B82F6;
      --blue-lt:    #EFF6FF;

      /* Red palette */
      --red:        #C8102E;
      --red-lt:     #FFF0F2;

      /* Shadows */
      --sh:         0 2px 14px rgba(45, 125, 79, .08);
      --sh2:        0 6px 28px rgba(45, 125, 79, .16);

      /* Shared tokens */
      --rad:        18px;
      --transition: .2s ease;
    }

    /* ── DARK MODE ── */
    body.dark {
      --bg:       #0a1410;
      --surface:  #1a2a1f;
      --card:     #243429;
      --t1:       #dfeee4;
      --t2:       #6a8a72;
      --border:   #2e4034;
      --green-lt: #0d2219;
      --gold-lt:  #1a1500;
      --blue-lt:  #0d1826;
      --red-lt:   #1a050a;
    }

    /* ── BASE ── */
    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--bg);
      color: var(--t1);
      position: relative;
    }

    
    html {
      overflow-x: hidden;
      transition: background .3s, color .3s;
    }

    /* ── WRAP ── */
    .wrap {
      max-width: 480px;
      margin: 0 auto;
      background: var(--surface);
      min-height: 100vh;
      position: relative;
      padding-bottom: 82px;
    }

    /* ── SCREEN TRANSITIONS ── */
    .screen { display: none; animation: fi .22s ease; }
    .screen.active { display: block; }

    @keyframes fi {
      from { opacity: 0; transform: translateY(5px); }
      to   { opacity: 1; transform: none; }
    }

    /* ── TOP BAR ── */
    .top-bar {
      background: linear-gradient(135deg,#0f2d0f,#2D7D4F);
      padding: .9rem 1.4rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: #fff;
      position: sticky;
      top: 0;
      z-index: 1500;
      box-shadow: 0 2px 12px rgba(45, 125, 79, .25);
    }

    .tb-logo {
      font-family: 'Syne', sans-serif;
      font-size: 1.55rem;
      font-weight: 800;
      letter-spacing: -.6px;
    }
    .tb-logo em { color: #F2B705; font-style: normal; }

    .tb-title {
      font-family: 'Syne', sans-serif;
      font-size: 1.05rem;
      font-weight: 700;
    }

    .tb-right { display: flex; gap: .5rem; }

    .ib, .bb {
      background: rgba(255, 255, 255, .15);
      border: none;
      color: #fff;
      font-size: 1rem;
      cursor: pointer;
      padding: .4rem .55rem;
      border-radius: 10px;
      transition: background var(--transition);
    }
    .ib:hover, .bb:hover { background: rgba(255, 255, 255, .28); }

    /* ── AUTH PAGES ── */
    .auth-wrap {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 2rem;
      background: linear-gradient(150deg, #0a1f0e, #1a4d2e, #0d2e48);
    }

    .auth-box {
      background: var(--surface);
      padding: 2.2rem;
      border-radius: 26px;
      max-width: 400px;
      width: 100%;
      margin: 0 auto;
      box-shadow: 0 20px 60px rgba(0, 0, 0, .25);
    }

    .auth-logo { text-align: center; margin-bottom: 1.6rem; }

    .auth-logo h1 {
      font-family: 'Syne', sans-serif;
      font-size: 2.5rem;
      font-weight: 800;
      color: var(--green);
    }
    .auth-logo h1 em { color: var(--gold); font-style: normal; }
    .auth-logo p { color: #6B7280; font-size: .88rem; margin-top: .3rem; }

    /* Input groups */
    .ig { margin-bottom: 1rem; }

    .ig label {
      display: block;
      margin-bottom: .42rem;
      font-weight: 700;
      font-size: .82rem;
      color: #374151;
      text-transform: uppercase;
      letter-spacing: .5px;
    }

    .ig input {
      width: 100%;
      padding: .88rem 1rem;
      border: 2px solid #E5E7EB;
      border-radius: 12px;
      font-family: 'DM Sans', sans-serif;
      font-size: .94rem;
      outline: none;
      color: var(--t1);
      background: var(--surface);
      transition: border var(--transition), box-shadow var(--transition);
    }
    .ig input:focus {
      border-color: var(--green);
      box-shadow: 0 0 0 3px rgba(45, 125, 79, .12);
    }

    /* Auth button */
    .auth-btn {
      width: 100%;
      padding: .95rem;
      background: var(--green);
      color: #fff;
      border: none;
      border-radius: 50px;
      font-size: .97rem;
      font-weight: 700;
      cursor: pointer;
      margin-top: .6rem;
      font-family: 'DM Sans', sans-serif;
      transition: all var(--transition);
      box-shadow: 0 4px 16px rgba(45, 125, 79, .35);
    }
    .auth-btn:hover {
      background: var(--green-dk);
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(45, 125, 79, .45);
    }
    .auth-btn:active { transform: scale(.97); }

    /* Auth helper text */
    .auth-link {
      text-align: center;
      margin-top: 1.2rem;
      color: #6B7280;
      font-size: .87rem;
    }
    .auth-link a {
      color: var(--green);
      font-weight: 700;
      text-decoration: none;
    }
    .auth-link a:hover { text-decoration: underline; }

    /* Auth alerts */
    .auth-alert {
      border-radius: 12px;
      padding: .78rem 1rem;
      margin-bottom: 1rem;
      font-size: .84rem;
      line-height: 1.5;
      display: flex;
      flex-direction: column;
      gap: .28rem;
    }
    .auth-alert--error {
      background: #FEF2F2;
      border: 1.5px solid #FECACA;
      color: #991B1B;
    }
    .auth-alert--success {
      background: #F0FDF4;
      border: 1.5px solid #BBF7D0;
      color: #3D7A4A;
    }

    /* Demo tip box */
    .auth-demo-tip {
      margin-top: 1rem;
      background: #F0FDF4;
      border-radius: 12px;
      padding: .75rem 1rem;
      font-size: .76rem;
      color: #3D7A4A;
      border: 1px solid #BBF7D0;
      text-align: center;
      line-height: 1.5;
    }

    /* User type toggle (signup) */
    .utype {
      flex: 1;
      padding: 1.1rem .7rem;
      border: 2px solid #E5E7EB;
      border-radius: 14px;
      text-align: center;
      cursor: pointer;
      transition: all .22s;
      background: var(--surface);
    }
    .utype.sel {
      border-color: var(--green);
      background: var(--green);
      color: #fff;
    }
    .utype.sel div { color: #fff !important; }
    .utype:hover:not(.sel) {
      border-color: var(--green);
      transform: translateY(-2px);
    }

    /* ── BOTTOM NAV ── */
    .bot-nav {
      position: fixed;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      width: calc(100% - 40px);
      max-width: 440px;
      background: rgba(255, 251, 235, 0.9);
      backdrop-filter: blur(10px);
      border-radius: 25px;
      display: flex;
      justify-content: space-around;
      padding: 8px 16px;
      z-index: 1500;
      box-shadow: 0 4px 20px rgba(242, 183, 5, 0.25);
      border: 2px solid #F2B705;
    }

    body.dark .bot-nav {
      background: rgba(45, 35, 20, 0.9);
      border: 2px solid rgba(242, 183, 5, 0.5);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .nav-i {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: .15rem;
      color: var(--t1);
      font-size: .62rem;
      font-weight: 700;
      padding: 0.5rem;
      border-radius: 12px;
      cursor: pointer;
      position: relative;
      transition: all 0.2s ease;
      text-decoration: none; {{-- safe for <a> tags --}}
    }

    .nav-i span { font-size: 1.22rem; transition: transform .2s; }

    .nav-i.on { 
      color: var(--t1); 
      background: rgba(255, 255, 255, 0.3);
    }
    .nav-i.on span { transform: scale(1.15); }

    .nav-i:hover:not(.on) {
      color: var(--t1);
      background: rgba(255, 255, 255, 0.2);
      transform: translateY(-2px);
    }

    /* ── TOAST NOTIFICATION ── */
    #toast {
      position: fixed;
      top: 72px;
      left: 50%;
      transform: translateX(-50%);
      background: #0a1f0e;
      color: #fff;
      padding: 10px 22px;
      border-radius: 50px;
      font-size: .83rem;
      font-weight: 700;
      z-index: 20000;
      opacity: 0;
      transition: opacity .28s;
      pointer-events: none;
      white-space: nowrap;
      box-shadow: 0 4px 20px rgba(0, 0, 0, .25);
      max-width: 92%;
      text-align: center;
    }
    #toast.show   { opacity: 1; }
    #toast.ok     { background: var(--green); }
    #toast.warn   { background: #92400E; }

    /* ── HERO ── */
    .hero {
      background: linear-gradient(145deg, #0a1f0e, #1a4d2e, #163d60);
      padding: 1.7rem 1.4rem 2.8rem;
      color: #fff;
      position: relative;
      overflow: hidden;
    }
    .hero::after {
      content: '';
      position: absolute;
      bottom: -1px; left: 0; right: 0;
      height: 24px;
      background: var(--surface);
      border-radius: 24px 24px 0 0;
    }
    .hero h1 {
      font-family: 'Syne', sans-serif;
      font-size: 1.6rem;
      font-weight: 800;
      line-height: 1.22;
      margin-bottom: .38rem;
    }
    .hero h1 span { color: #F2B705; }

    /* ── SECTION SCREEN ── */
    .cs { padding: 1.3rem 1.2rem; }
    .cs h2 {
      font-family: 'Syne', sans-serif;
      font-size: 1.35rem;
      font-weight: 800;
      margin-bottom: 1rem;
      color: var(--t1);
    }

    /* ── CARDS ── */
    .dorm-card {
      background: var(--card);
      border-radius: var(--rad);
      padding: 1.1rem;
      margin-bottom: 1rem;
      box-shadow: var(--sh);
      border: 1.5px solid var(--border);
      transition: transform var(--transition), box-shadow var(--transition);
    }
    .dorm-card:hover { transform: translateY(-3px); box-shadow: var(--sh2); }

    /* ── TYPE BADGES ── */
    .type-badge {
      display: inline-flex;
      align-items: center;
      gap: .24rem;
      padding: .24rem .75rem;
      border-radius: 20px;
      font-size: .72rem;
      font-weight: 800;
      letter-spacing: .2px;
    }
    .type-badge.Room     { background: #e8f5ee; color: #1f5c38; }
    .type-badge.Bedspace { background: var(--blue-lt); color: #1d4ed8; }
    .type-badge.Unit     { background: var(--gold-lt); color: #92400E; }

    /* ── META PILLS ── */
    .mpill {
      display: inline-flex;
      align-items: center;
      gap: .22rem;
      padding: .24rem .65rem;
      background: var(--bg);
      border-radius: 20px;
      font-size: .72rem;
      font-weight: 600;
      color: var(--t2);
      border: 1px solid var(--border);
    }
    .mpill.ok   { background: var(--green-lt); color: var(--green); border-color: #b2dfca; }
    .mpill.blue { background: var(--blue-lt);  color: #1d4ed8;      border-color: #bfdbfe; }

    /* ── BUTTONS ── */
    .btn-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: .45rem;
      margin-bottom: .45rem;
    }
    .btn {
      padding: .68rem .5rem;
      border-radius: 50px;
      font-family: 'DM Sans', sans-serif;
      font-size: .78rem;
      font-weight: 700;
      cursor: pointer;
      border: none;
      transition: all var(--transition);
      text-align: center;
      white-space: nowrap;
    }
    .btn:active { transform: scale(.97) !important; }
    .btn-green  { background: var(--green); color: #fff; box-shadow: 0 3px 10px rgba(45,125,79,.28); }
    .btn-green:hover { background: var(--green-dk); transform: translateY(-1px); }
    .btn-out    { background: transparent; border: 2px solid var(--green); color: var(--green); }
    .btn-out:hover { background: var(--green); color: #fff; }
    .btn-gold   { background: var(--gold); color: #1F2933; box-shadow: 0 3px 10px rgba(242,183,5,.3); }
    .btn-gold:hover { background: var(--gold-dk); transform: translateY(-1px); }
    .btn-blue   { background: var(--blue); color: #fff; box-shadow: 0 3px 10px rgba(59,130,246,.28); }
    .btn-blue:hover { background: #2563eb; transform: translateY(-1px); }
    .btn-full   { width: 100%; padding: .84rem; font-size: .9rem; margin-bottom: .42rem; }
    .btn-red {
      background: var(--red);
      color: #fff;
      width: 100%;
      padding: .88rem;
      font-size: .9rem;
      margin-top: .9rem;
      border-radius: 50px;
      border: none;
      cursor: pointer;
      font-family: 'DM Sans', sans-serif;
      font-weight: 700;
      transition: all var(--transition);
    }
    .btn-red:hover { background: #a00c24; transform: translateY(-1px); }

    /* ── COMPARE TABLE ── */
    .cmp-wrap {
      overflow-x: auto;
      border-radius: 14px;
      border: 1.5px solid var(--border);
    }
    .cmp-tbl {
      width: 100%;
      border-collapse: collapse;
      font-size: .78rem;
    }
    .cmp-tbl th {
      background: var(--green);
      color: #fff;
      padding: 9px 8px;
      text-align: center;
      font-family: 'Syne', sans-serif;
      font-size: .73rem;
      font-weight: 700;
    }
    .cmp-tbl th:first-child {
      text-align: left;
      background: #1f5c38;
      min-width: 90px;
    }
    .cmp-tbl td {
      padding: 8px 8px;
      border-bottom: 1px solid var(--border);
      text-align: center;
      color: var(--t1);
    }
    .cmp-tbl td:first-child {
      text-align: left;
      font-weight: 700;
      color: var(--t2);
      background: var(--bg);
      font-size: .72rem;
    }
    .cmp-tbl tr:last-child td { border-bottom: none; }
    .cmp-best  { color: #065F46; font-weight: 800; }
    .cmp-img {
      width: 56px;
      height: 42px;
      object-fit: cover;
      border-radius: 8px;
      display: block;
      margin: 0 auto 5px;
    }

    /* ── EMPTY STATE ── */
    .empty { text-align: center; padding: 3rem 1rem; color: var(--t2); }
    .empty-ic { font-size: 3.5rem; margin-bottom: .72rem; }
    .empty p { font-weight: 600; font-size: .88rem; }

    /* ── RATINGS ── */
    .stars    { color: var(--gold); font-size: .82rem; letter-spacing: -.5px; }

    /* ── BatState accent ── */
    .batstate-lt { background: var(--red-lt); color: var(--red); border: 1px solid rgba(200,16,46,.2); }

    /* ── SECTION LABEL ── */
    .sec-lbl {
      font-size: .68rem;
      font-weight: 800;
      color: var(--t2);
      text-transform: uppercase;
      letter-spacing: 1.4px;
      padding: .2rem 0 .7rem;
      display: flex;
      align-items: center;
      gap: .45rem;
    }
    .sec-lbl::after { content: ''; flex: 1; height: 1px; background: var(--border); }

    /* ── DARK MODE TOGGLE ── */
    .tog { position: relative; display: inline-block; width: 46px; height: 24px; }
    .tog input { opacity: 0; width: 0; height: 0; }
    .sl {
      position: absolute;
      cursor: pointer;
      inset: 0;
      background: #ccc;
      border-radius: 24px;
      transition: .3s;
    }
    .sl::before {
      position: absolute;
      content: '';
      height: 16px; width: 16px;
      left: 4px; bottom: 4px;
      background: #fff;
      transition: .3s;
      border-radius: 50%;
    }
    input:checked + .sl { background: var(--green); }
    input:checked + .sl::before { transform: translateX(22px); }

    /* ── GRADIENT BACKGROUND ── */
    body {
      background: linear-gradient(135deg, #fefefe 0%, #fff9e6 50%, #fefefe 100%);
      background-attachment: fixed;
      min-height: 100vh;
    }

    /* Dark mode override */
    .dark body {
      background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 50%, #1a1a1a 100%);
    }
  </style>
</head>

<body>

  {{-- Global toast container (available on every page) --}}
  <div id="toast" role="status" aria-live="polite"></div>

  {{-- Main content --}}
  @yield('content')

  {{-- Leaflet JS (deferred so it doesn't block render) --}}
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>

  {{-- Global JS helpers (toast, dark mode) --}}
  <script>
    // ── Dark mode: restore saved preference ──────────────────
    if (localStorage.getItem('dm') === 'true') {
      document.body.classList.add('dark');
    }

    // ── Toast helper ─────────────────────────────────────────
    window.showToast = function(msg, cls = '') {
      const t = document.getElementById('toast');
      t.textContent = msg;
      t.className   = 'show ' + cls;
      clearTimeout(t._timer);
      t._timer = setTimeout(() => { t.className = ''; }, 3000);
    };

    // ── Dark mode toggle (called from profile toggle) ─────────
    window.toggleDark = function() {
      document.body.classList.toggle('dark');
      localStorage.setItem('dm', document.body.classList.contains('dark'));
    };

    // ── CSRF token for fetch() calls ─────────────────────────
    window.csrfToken = '{{ csrf_token() }}';
  </script>

  {{-- Per-page scripts --}}
  @stack('scripts')

</body>
</html>