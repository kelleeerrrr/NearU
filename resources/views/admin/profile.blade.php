@extends('layouts.app')

@section('title', 'Admin Profile - NearU')

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
          {{ strtoupper(substr($admin->name, 0, 1)) }}
        </div>

        <div>
          <h2>{{ $admin->name }}</h2>
          <p>{{ $admin->email }}</p>
          <p>System Administrator</p>

          <span class="badge">🛡️ Admin Account</span>
        </div>
      </div>

      
      {{-- ADMIN INFO --}}
      <div class="section">
        <div class="section-title">ℹ️ Account Information</div>
        
        <div style="padding: 1rem 0;">
          <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--border);">
            <span style="color: var(--t2); font-size: 0.8rem;">Account Status</span>
            <span class="badge" style="background: #10b981; color: white;">✅ Active</span>
          </div>
          <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--border);">
            <span style="color: var(--t2); font-size: 0.8rem;">Member Since</span>
            <span style="font-size: 0.8rem;">{{ $admin->created_at->format('F j, Y') }}</span>
          </div>
          <div style="display: flex; justify-content: space-between; padding: 0.5rem 0;">
            <span style="color: var(--t2); font-size: 0.8rem;">Last Updated</span>
            <span style="font-size: 0.8rem;">{{ $admin->updated_at->format('F j, Y') }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bottom spacing for floating nav -->
  <div class="bottom-spacer"></div>

  @include('partials.footer')
</div>
@endsection

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

.bottom-spacer {
  height: 100px;
  width: 100%;
}
</style>
@endpush
