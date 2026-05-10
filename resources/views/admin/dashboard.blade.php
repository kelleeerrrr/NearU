@extends('layouts.app')

@section('title', 'Admin Dashboard - NearU')

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <!-- Admin Welcome -->
      <div class="admin-welcome">
        <span class="welcome-icon">🛡️</span>
        <h3>Welcome back, {{ $admin->name }}!</h3>
        <p>System Administrator Panel</p>
      </div>

      <!-- Statistics Cards -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon">👥</div>
          <div class="stat-info">
            <div class="stat-number">{{ $totalUsers }}</div>
            <div class="stat-label">Total Users</div>
          </div>
        </div>
        
        <div class="stat-card">
          <div class="stat-icon">🎓</div>
          <div class="stat-info">
            <div class="stat-number">{{ $totalStudents }}</div>
            <div class="stat-label">Students</div>
          </div>
        </div>
        
        <div class="stat-card">
          <div class="stat-icon">🏠</div>
          <div class="stat-info">
            <div class="stat-number">{{ $totalOwners }}</div>
            <div class="stat-label">Owners</div>
          </div>
        </div>
        
        <div class="stat-card">
          <div class="stat-icon">🏘️</div>
          <div class="stat-info">
            <div class="stat-number">{{ $totalListings }}</div>
            <div class="stat-label">Total Listings</div>
          </div>
        </div>
      </div>

      <!-- Pending Verifications Alert -->
      @if($pendingVerifications > 0)
      <div class="alert-card pending">
        <div class="alert-icon">⏳</div>
        <div class="alert-content">
          <strong>{{ $pendingVerifications }} Pending Verifications</strong>
          <p>Owner accounts awaiting review</p>
          <a href="{{ route('admin.owner-verifications.index') }}" class="btn btn-yellow">Review Now</a>
        </div>
      </div>
      @endif

      <!-- Manage Users Alert -->
      <div class="alert-card users">
        <div class="alert-icon">👥</div>
        <div class="alert-content">
          <strong>User Management</strong>
          <p>Manage all system users and accounts</p>
          <a href="{{ route('admin.users.index') }}" class="btn btn-green">Manage Users</a>
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
.admin-welcome {
  background: 
    radial-gradient(circle at 25% 25%, rgba(45,125,79,0.2) 10%, transparent 40%),
    radial-gradient(circle at 75% 75%, rgba(45,125,79,0.15) 8%, transparent 35%),
    radial-gradient(circle at 15% 85%, rgba(45,125,79,0.18) 12%, transparent 45%),
    radial-gradient(circle at 85% 15%, rgba(45,125,79,0.12) 15%, transparent 50%),
    radial-gradient(circle at 50% 50%, rgba(45,125,79,0.08) 5%, transparent 30%),
    radial-gradient(circle at 35% 65%, rgba(45,125,79,0.25) 6%, transparent 25%),
    linear-gradient(135deg, #2D7D4F, #1f5c38);
  color: white;
  padding: 1.5rem;
  border-radius: 20px;
  margin-bottom: 1.5rem;
  text-align: center;
  box-shadow: 0 6px 20px rgba(45, 125, 79, 0.15);
  border: 2px solid rgba(255,255,255,0.2);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
}

.admin-welcome::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 200px;
  height: 200px;
  background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
  animation: float 6s ease-in-out infinite;
}

.admin-welcome::after {
  content: '';
  position: absolute;
  bottom: -30%;
  left: -30%;
  width: 150px;
  height: 150px;
  background: radial-gradient(circle, rgba(242,183,5,0.08) 0%, transparent 70%);
  animation: float 8s ease-in-out infinite reverse;
}

@keyframes float {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-20px) rotate(180deg); }
}

.admin-welcome:hover {
  transform: translateY(-6px) scale(1.02);
  box-shadow: 0 16px 48px rgba(45, 125, 79, 0.25);
}

.admin-welcome h3 {
  font-family: 'Syne', sans-serif;
  font-size: 1.8rem;
  font-weight: 800;
  margin: 0 0 0.5rem 0;
  position: relative;
  z-index: 2;
  text-shadow: 0 2px 4px rgba(0,0,0,0.1);
  animation: pulse-text 3s ease-in-out infinite;
}

@keyframes pulse-text {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.02); }
}

.admin-welcome p {
  font-size: 1rem;
  margin: 0;
  opacity: 0.95;
  position: relative;
  z-index: 2;
  font-weight: 500;
}

.welcome-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
  display: block;
  animation: bounce 2s infinite;
  position: relative;
  z-index: 2;
}

@keyframes bounce {
  0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
  40% { transform: translateY(-15px); }
  60% { transform: translateY(-8px); }
}

.stats-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.stat-card {
  background: #fff;
  border: 2px solid #2D7D4F;
  border-top: 3px solid #F2B705;
  border-radius: 20px;
  padding: 1.25rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.15);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.stat-card::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 100%;
  height: 100%;
  background: linear-gradient(45deg, rgba(242,183,5,0.05), rgba(242,183,5,0.1));
  border-radius: 50%;
  transition: all 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-4px) scale(1.02);
  box-shadow: 0 8px 24px rgba(45, 125, 79, 0.25);
  border-color: var(--gold);
}

.stat-card:hover::before {
  top: -30%;
  right: -30%;
  background: linear-gradient(45deg, rgba(242,183,5,0.15), rgba(242,183,5,0.2));
}

.stat-icon {
  font-size: 2.5rem;
  width: 60px;
  height: 60px;
  text-align: center;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #2D7D4F, #1f5c38);
  border-radius: 16px;
  box-shadow: 0 4px 8px rgba(45, 125, 79, 0.2);
  position: relative;
  z-index: 1;
  transition: all 0.3s ease;
}

.stat-icon:hover {
  transform: rotate(10deg) scale(1.1);
  box-shadow: 0 6px 12px rgba(242,183,5,0.4);
}

.stat-number {
  font-size: 1.75rem;
  font-weight: 800;
  color: #2D7D4F;
  position: relative;
  z-index: 1;
}

.stat-label {
  font-size: 0.85rem;
  color: #6b7280;
  font-weight: 600;
  position: relative;
  z-index: 1;
}

.alert-card {
  background: white;
  border-radius: 12px;
  padding: 1rem;
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  min-height: 120px;
}

.alert-card.pending {
  border-left: 4px solid #F2B705;
  background: #fffbeb;
}

.alert-card.users {
  border-left: 4px solid #2D7D4F;
  background: #f0fdf4;
}

.alert-icon {
  font-size: 2rem;
  padding: 0.5rem;
}

.alert-content strong {
  color: #92400E;
}

.alert-content p {
  margin: 0.25rem 0;
  color: #6b7280;
}

.btn-blue {
  background: transparent;
  color: #2D7D4F;
  padding: 0.5rem 1rem;
  border-radius: 1px;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.8rem;
  display: inline-block;
  margin-top: 0.5rem;
  border: 2px solid #2D7D4F;
  box-shadow: 0 2px 4px rgba(45, 125, 79, 0.2);
  transition: all 0.2s;
}

.btn-blue:hover {
  background: #2D7D4F;
  color: white;
  text-decoration: none;
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(45, 125, 79, 0.3);
}

.btn-yellow {
  background: #F2B705;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 1px;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.8rem;
  display: inline-block;
  margin-top: 0.5rem;
  border: 2px solid #F2B705;
  box-shadow: 0 2px 4px rgba(242, 183, 5, 0.2);
  transition: all 0.2s;
}

.btn-yellow:hover {
  background: #d4a003;
  border-color: #d4a003;
  text-decoration: none;
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(242, 183, 5, 0.3);
}

.btn-green {
  background: transparent;
  color: #2D7D4F;
  padding: 0.5rem 1rem;
  border-radius: 1px;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.8rem;
  display: inline-block;
  margin-top: 0.5rem;
  border: 2px solid #2D7D4F;
  box-shadow: 0 2px 4px rgba(45, 125, 79, 0.2);
  transition: all 0.2s;
}

.btn-green:hover {
  background: #2D7D4F;
  color: white;
  text-decoration: none;
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(45, 125, 79, 0.3);
}

.bottom-spacer {
  height: 100px;
  width: 100%;
}
</style>
@endpush
