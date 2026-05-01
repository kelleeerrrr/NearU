<!DOCTYPE html>
<html lang="en">
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

<title>@yield('title', 'Owner Dashboard — NearU')</title>

<link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">

<style>
*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  -webkit-tap-highlight-color:transparent;
}

:root{
  --bg:#F0F7F2;
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

body{
  font-family:'DM Sans',sans-serif;
  background:var(--bg);
  color:var(--t1);
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
  bottom:0;
  left:50%;
  transform:translateX(-50%);
  width:100%;
  max-width:480px;
  background:#fff;
  display:flex;
  justify-content:space-around;
  padding:.6rem 0;
  border-top:2px solid var(--gold);
  box-shadow:0 -6px 20px rgba(0,0,0,.08);
  z-index:100;
}

.nav-i{
  text-decoration:none;
  color:var(--t2);
  font-size:.7rem;
  font-weight:700;
  display:flex;
  flex-direction:column;
  align-items:center;
  gap:.15rem;
  position:relative;
  padding:.3rem .7rem;
  border-radius:14px;
}

.nav-i span{
  font-size:1.25rem;
}

.nav-i.active{
  color:var(--gold-dk);
}

.nav-i.active::before{
  content:'';
  position:absolute;
  top:-4px;
  width:42px;
  height:42px;
  background:var(--gold-lt);
  border-radius:12px;
  z-index:-1;
}
</style>

@stack('styles')
</head>
@stack('scripts')
<body>

<div class="wrap">

  <!-- TOP BAR -->
  <div class="top">
    <div class="logo">Near<em>U</em> OWNER</div>
    <div>👤 {{ auth()->user()->name }}</div>
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

</body>
</html>