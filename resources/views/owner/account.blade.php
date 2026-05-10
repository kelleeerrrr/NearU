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
  padding:1.5rem;
  border-radius:20px;
  background:linear-gradient(135deg, #fef3c7 0%, #fefce8 50%, #fff9e6 100%);
  border:2px solid var(--gold);
  font-weight:700;
  color:#5a4300;
  position:relative;
  overflow:hidden;
  box-shadow:0 8px 25px rgba(242,183,5,0.15);
  transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.warn-box::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 200px;
  height: 200px;
  background: radial-gradient(circle, rgba(242,183,5,0.1) 0%, transparent 70%);
  animation: float 6s ease-in-out infinite;
}

.warn-box::after {
  content: '';
  position: absolute;
  bottom: -30%;
  left: -30%;
  width: 150px;
  height: 150px;
  background: radial-gradient(circle, rgba(45,125,79,0.05) 0%, transparent 70%);
  animation: float 8s ease-in-out infinite reverse;
}

.warn-box:hover {
  transform:translateY(-4px) scale(1.02);
  box-shadow:0 12px 35px rgba(242,183,5,0.25);
  border-color: #f59e0b;
}

@keyframes float {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-20px) rotate(180deg); }
}

.warn-icon {
  font-size: 2rem;
  margin-bottom: 0.5rem;
  display: block;
  animation: bounce 2s infinite;
}

@keyframes bounce {
  0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
  40% { transform: translateY(-10px); }
  60% { transform: translateY(-5px); }
}

.warn-text {
  font-size: 0.95rem;
  line-height: 1.5;
  margin-bottom: 1rem;
  position: relative;
  z-index: 2;
}

.warn-action {
  display: inline-block;
  background: linear-gradient(135deg, var(--gold), #f59e0b);
  color: #5a4300;
  padding: 0.6rem 1.2rem;
  border-radius: 15px;
  text-decoration: none;
  font-weight: 800;
  font-size: 0.85rem;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(242,183,5,0.2);
  position: relative;
  z-index: 2;
}

.warn-action:hover {
  transform: translateY(-2px) scale(1.05);
  box-shadow: 0 6px 20px rgba(242,183,5,0.3);
  background: linear-gradient(135deg, #f59e0b, #d4a200);
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
      @if($user->profile_photo_path)
        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
      @else
        {{ strtoupper(substr($user->name, 0, 1)) }}
      @endif
    </div>
    <div style="margin-bottom: 1rem;">
      <h2>{{ $user->name }}</h2>
      <p>{{ $user->email }}</p>
      <p>{{ $user->phone ?? 'No phone number' }}</p>
      <span class="badge">🏠 Owner Account</span>
    </div>
  </div>

  {{-- EDIT PROFILE BUTTON --}}
  <div class="section">
    <button class="icon-btn green-btn" style="width: 100%; justify-content: center;" onclick="window.location.href='{{ route('owner.profile.edit') }}'">
      ✏️ Edit Profile
    </button>
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
