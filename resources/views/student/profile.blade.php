@extends('layouts.app')

@section('title', 'Student Profile - NearU')

@push('styles')
<style>
/* DARK MODE SUPPORT */
body.dark {
  --card: #1e1e1e;
  --bg: #121212;
  --border: #2a2a2a;
  --t2: #aaa;
}

/* HEADER ACTIONS */
.top-actions{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:1rem;
}

.icon-btn{
  padding:.5rem .8rem;
  border-radius:10px;
  border:1.5px solid var(--border);
  background:var(--card);
  cursor:pointer;
  font-weight:700;
  font-size:.8rem;
}

.logout-btn{
  background:#c0392b;
  color:#fff;
  border:none;
}

.green-btn{
  background:transparent;
  color:var(--green);
  border:1.5px solid var(--green);
  transition:all 0.2s ease;
}

.green-btn:hover{
  background:var(--green);
  color:#fff;
  transform:translateY(-1px);
}

/* PAGE */
.page{padding:1rem 1.2rem; max-width: 480px;}

/* PROFILE */
.profile-card{
  background:var(--card);
  border:1.5px solid var(--border);
  border-radius:18px;
  padding:1rem;
  box-shadow:var(--sh);
  display:flex;
  gap:1rem;
  align-items:center;
}

.avatar{
  width:70px;height:70px;border-radius:50%;
  background:linear-gradient(135deg,#2D7D4F,#1f5c38);
  display:flex;align-items:center;justify-content:center;
  color:#fff;font-size:1.6rem;font-weight:800;
  overflow:hidden;
}

.avatar img{
  width:100%;height:100%;object-fit:cover;
}

.badge{
  display:inline-block;
  margin-top:.3rem;
  padding:.25rem .6rem;
  border-radius:20px;
  font-size:.7rem;
  font-weight:800;
  background:var(--green-lt);
  color:var(--green);
}

/* SECTION */
.section{
  margin-top:1rem;
  background:var(--card);
  border:1.5px solid var(--border);
  border-radius:18px;
  padding:1rem;
  box-shadow:var(--sh);

}

.section-title{
  font-weight:800;
  margin-bottom:.7rem;
  font-size:.9rem;
}

/* MENU ITEMS */
.menu-item{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:1rem 0;
  cursor:pointer;
  border-bottom:1px solid var(--border);
  transition:background 0.2s;
}

.menu-item:hover{
  background:var(--bg);
  margin:0 -1rem;
  padding:1rem;
}

.menu-item:last-child{
  border-bottom:none;
}

.menu-item.logout{
  color:#c0392b;
}

/* EDIT FORM */
.edit-form{
  display:flex;
  flex-direction:column;
  gap:.6rem;
}

.input{
  padding:.7rem;
  border-radius:12px;
  border:1.5px solid var(--border);
  font-size:.85rem;
  background:transparent;
  color:inherit;
}

.save-btn{
  background:var(--green);
  color:#fff;
  padding:.7rem;
  border:none;
  border-radius:12px;
  font-weight:800;
  cursor:pointer;
  transition:all 0.2s ease;
}

.save-btn:hover{
  background:var(--green-dk);
  transform:translateY(-1px);
}

/* PHOTO UPLOAD */
.photo-upload{
  margin-top:1rem;
  padding-top:1rem;
  border-top:1px solid var(--border);
}

.photo-preview{
  width:80px;height:80px;border-radius:50%;
  background:var(--bg);
  display:flex;align-items:center;justify-content:center;
  margin-top:.5rem;
  overflow:hidden;
}

.photo-preview img{
  width:100%;height:100%;object-fit:cover;
}
</style>
@endpush

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">

    <div class="cs">
      {{-- TOP ACTIONS --}}
      <div class="top-actions">

        {{-- DARK MODE --}}
        <button class="icon-btn green-btn" onclick="toggleDark()">
          🌙 Dark Mode
        </button>

        {{-- LOGOUT --}}
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="icon-btn logout-btn">
            🚪 Logout
          </button>
        </form>

      </div>

      {{-- PROFILE --}}
      <div class="profile-card">
    <div class="avatar">
      @if(auth()->user()->profile_photo_path)
        <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Profile">
      @else
        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
      @endif
    </div>

    <div>
      <h2>{{ auth()->user()->name }}</h2>
      <p>{{ auth()->user()->email }}</p>
      <p>{{ auth()->user()->phone ?? 'No phone number' }}</p>

      <span class="badge">🎓 Student Account</span>
    </div>
  </div>

  {{-- EDIT PROFILE BUTTON --}}
  <div class="section">
    <button class="icon-btn green-btn" style="width: 100%; justify-content: center;" onclick="window.location.href='/profile/edit'">
      ✏️ Edit Profile
    </button>
  </div>

  {{-- QUICK LINKS --}}
  <div class="section" style="margin-bottom: 4.5rem;">
    <div class="section-title">⚡ Quick Links</div>

    <div class="menu-item" onclick="window.location.href='/saved'">
      <div>❤️ Saved Listings</div>
      <div>→</div>
    </div>

    <div class="menu-item" onclick="window.location.href='/visits'">
      <div>📅 Scheduled Visits</div>
      <div>→</div>
    </div>

    <div class="menu-item" onclick="window.location.href='{{ route('checklist') }}'">
      <div>📋 Move-in Checklist</div>
      <div>→</div>
    </div>

      </div>{{-- /.cs --}}
  </div>{{-- /.screen --}}

  {{-- BOTTOM NAV --}}
  <div class="bot-nav">
    <div class="nav-i" id="nav-home" onclick="window.location.href='/'"><span>🏠</span><div>Home</div></div>
    <div class="nav-i" id="nav-map" onclick="window.location.href='{{ route('student.map') }}'"><span>📍</span><div>Map</div></div>
    <div class="nav-i" id="nav-messages" onclick="window.location.href='/messages'"><span>💬</span><div>Messages</div></div>
    <div class="nav-i active" id="nav-profile" onclick="window.location.href='/profile'"><span>👤</span><div>Profile</div></div>
  </div>

</div>{{-- /.wrap --}}

@endsection

@push('scripts')
<script>
// Use global dark mode function from app layout
// Comprehensive fix for floating footer glitch on profile page
document.addEventListener('DOMContentLoaded', function() {
  // Ensure floating footer is properly positioned
  const botNav = document.querySelector('.bot-nav');
  if (botNav) {
    // Force proper positioning
    botNav.style.position = 'fixed';
    botNav.style.bottom = '20px';
    botNav.style.left = '50%';
    botNav.style.transform = 'translateX(-50%)';
    botNav.style.zIndex = '1500';
    
    // Prevent conflicts with screen animations
    const screen = document.querySelector('.screen');
    if (screen) {
      screen.style.overflow = 'visible';
    }
    
    // Monitor for any DOM changes that might affect positioning
    const observer = new MutationObserver(function(mutations) {
      mutations.forEach(function(mutation) {
        if (botNav && botNav.style.position !== 'fixed') {
          botNav.style.position = 'fixed';
          botNav.style.bottom = '20px';
          botNav.style.left = '50%';
          botNav.style.transform = 'translateX(-50%)';
        }
      });
    });
    
    if (document.body) {
      observer.observe(document.body, {
        attributes: true,
        childList: true,
        subtree: true
      });
    }
  }
});
</script>
@endpush