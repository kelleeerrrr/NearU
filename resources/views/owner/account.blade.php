@extends('layouts.owner')

@section('title', 'Owner Account — NearU')

@push('styles')
<style>


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

.back-btn{
  background:var(--card);
  color:var(--green);
  border:1.5px solid var(--border);
}

body.dark .back-btn{
  background:var(--green);
  color:#fff;
  border:none;
}

/* PAGE */
.page{padding:1rem 1.2rem;}

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

/* WARN */
.warn-box{
  margin-top:1rem;
  padding:1rem;
  border-radius:14px;
  background:#FFF4CC;
  border:1px solid var(--gold);
  font-weight:700;
  color:#5a4300;
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

/* SECURITY */
.security{
  display:flex;
  flex-direction:column;
  gap:.6rem;
}

.input{
  padding:.7rem;
  border-radius:12px;
  border:1.5px solid var(--border);
  font-size:.85rem;
  background:var(--surface);
  color:var(--t1);
}

.input::placeholder{
  color:var(--t2);
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

</style>
@endpush

@section('content')

@php
  // ✅ Use fresh DB data instead of stale session cache
  $user = \App\Models\User::find(auth()->id());
  $status = $user->verification_status ?? 'not_verified';
@endphp

<div class="page">

  {{-- TOP ACTIONS --}}
  <div class="top-actions">

    {{-- DARK MODE --}}
    <button class="icon-btn back-btn" onclick="toggleDark()">
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
      {{ strtoupper(substr($user->name, 0, 1)) }}
    </div>
    <div style="margin-bottom: 1rem;">
      <h2>{{ $user->name }}</h2>
      <p>{{ $user->email }}</p>
      <p>{{ $user->phone ?? 'No phone number' }}</p>
      <span class="badge">🏠 Owner Account</span>
    </div>
  </div>

  {{-- WARNING --}}
  @if($status !== 'approved')
    <div class="warn-box">
      @if($status === 'not_verified')
        ⚠️ Your account is not fully verified yet.
        Listing features are locked until verification is complete.
      @elseif($status === 'under_review')
        ⏳ Your account is under review.
        Listing features are locked until verification is complete.
      @endif
    </div>
  @endif

  {{-- SECURITY --}}
  <div class="section">
    <div class="section-title">🔒 Security</div>
    
    @if(session('success'))
      <div style="background: #E8F7EE; color: #1B5E20; padding: 0.75rem; border-radius: 12px; margin-bottom: 1rem; font-weight: 600;">
        {{ session('success') }}
      </div>
    @endif
    
    @if($errors->any())
      <div style="background: #FEF2F2; color: #991B1B; padding: 0.75rem; border-radius: 12px; margin-bottom: 1rem; font-weight: 600;">
        @foreach($errors->all() as $error)
          {{ $error }} @if(!$loop->last), @endif
        @endforeach
      </div>
    @endif
    
    <form method="POST" action="{{ route('password.update') }}" class="security">
      @csrf
      @method('PUT')
      
      <input type="password" name="current_password" class="input" placeholder="Current Password" required>
      <input type="password" name="password" class="input" placeholder="New Password" required>
      <input type="password" name="password_confirmation" class="input" placeholder="Confirm New Password" required>
      <button type="submit" class="save-btn">Update Password</button>
    </form>
  </div>

</div>

@endsection
