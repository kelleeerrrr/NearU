<!DOCTYPE html>
<html lang="en">
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

<title>@yield('title', 'Owner Dashboard')</title>

<link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">

<style>
*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  -webkit-tap-highlight-color:transparent;
}

:root{
  --bg:var(--green);
  --surface:#fff;

  --t1:#141F14;
  --t2:#5E6E5E;
  --border:#D6E8DC;

  --green:#2D7D4F;
  --green-lt:#E8F7EE;

  --gold:#F2B705;
  --gold-dk:#c99200;
  --gold-lt:#FFFBEB;

  --sh:0 2px 14px rgba(45,125,79,.08);
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

body{
  font-family:'DM Sans',sans-serif;
  background:var(--bg);
  color:var(--t1);
  position: relative;
  transition: background .3s, color .3s;
}


.wrap{
  max-width:480px;
  margin:0 auto;
  background:var(--surface);
  min-height:100vh;
  padding-bottom:85px;
}

/* TOP BAR */
.top{
  background:linear-gradient(135deg,#0f2d0f,#2D7D4F);
  padding:1rem;
  display:flex;
  justify-content:space-between;
  align-items:center;
  color:#fff;
  position:sticky;
  top:0;
  z-index:50;
}

.logo{
  font-family:'Syne',sans-serif;
  font-weight:800;
  font-size:1.2rem;
}

.logo em{color:var(--gold);font-style:normal;}

/* BOTTOM NAV */
.bot-nav{
  position:fixed;
  bottom:20px;
  left:50%;
  transform:translateX(-50%);
  width:calc(100% - 40px);
  max-width:440px;
  background: rgba(255, 251, 235, 0.9);
  backdrop-filter:blur(10px);
  border-radius:25px;
  display:flex;
  justify-content:space-around;
  padding:8px 16px;
  z-index:1500;
  box-shadow:0 4px 20px rgba(242, 183, 5, 0.25);
  border:2px solid #F2B705;
}

body.dark .bot-nav{
  background: rgba(45, 35, 20, 0.9);
  border:2px solid rgba(242, 183, 5, 0.5);
  box-shadow:0 4px 20px rgba(0, 0, 0, 0.3);
}

.nav-i{
  text-decoration:none;
  color:var(--t1);
  font-size:.7rem;
  font-weight:700;
  display:flex;
  flex-direction:column;
  align-items:center;
  gap:.2rem;
  padding:8px 12px;
  border-radius:14px;
  transition:all 0.2s ease;
}

.nav-i span{
  font-size:1.25rem;
  transition:transform .2s;
}

.nav-i.active{
  color:var(--t1);
  background:rgba(255, 255, 255, 0.3);
}

.nav-i.active span{
  transform:scale(1.15);
}

.nav-i:hover:not(.active){
  color:var(--t1);
  transform:translateY(-2px);
}

</style>

@stack('styles')
</head>
@stack('scripts')
<body>

<div class="wrap">

  <!-- TOP BAR -->
  <div class="top" style="justify-content:space-between; gap:1rem;">
    <div class="logo" style = "margin-left: -2rem;">
    <img src="{{ asset('storage/nearu-logo.png') }}" alt="NearU Logo" style="height: 50px;width: 100px; margin-right: -2rem; vertical-align: middle;">
    Near<em>U</em>
  </div>
    <div style="display:flex; align-items:center; gap:0.75rem;">
      <a href="{{ route('notifications.owner') }}" style="color:#fff; text-decoration:none; position:relative;">
        🔔
        @if(isset($unreadCount) && $unreadCount > 0)
          <span style="position:absolute; top:-4px; right:-8px; background:red; color:#fff; font-size:0.7rem; padding:2px 6px; border-radius:999px;">
            {{ $unreadCount }}
          </span>
        @endif
      </a>
      <div>👤 {{ auth()->user()->name }}</div>
    </div>
  </div>

  @yield('content')

</div>

<!-- ✅ UPDATED BOTTOM NAV -->
<div class="bot-nav">

  <a href="/owner/dashboard"
     class="nav-i {{ request()->is('owner/dashboard') ? 'active' : '' }}">
     <span>📊</span>Dashboard
  </a>

  <a href="/owner/listings"
     class="nav-i {{ request()->is('owner/listings*') ? 'active' : '' }}">
     <span>🏠</span>Listings
  </a>

  <!-- FIXED: OWNER INQUIRIES -->
  <a href="/owner/inquiries"
     class="nav-i {{ request()->is('owner/inquiries*') ? 'active' : '' }}">
     <span>💬</span>Inquiries
  </a>

  <a href="/owner/account"
     class="nav-i {{ request()->is('owner/account*') ? 'active' : '' }}">
     <span>👤</span>Profile
  </a>

</div>

  {{-- Global JS helpers (dark mode) --}}
  <script>
    // ── Dark mode: restore saved preference ──────────────────
    if (localStorage.getItem('dm') === 'true') {
      document.body.classList.add('dark');
    }

    // ── Dark mode toggle (called from owner account toggle) ─────────
    window.toggleDark = function() {
      document.body.classList.toggle('dark');
      localStorage.setItem('dm', document.body.classList.contains('dark'));
    };

    // ── CSRF token for fetch() calls ─────────────────────────
    window.csrfToken = '{{ csrf_token() }}';
  </script>

</body>
</html>