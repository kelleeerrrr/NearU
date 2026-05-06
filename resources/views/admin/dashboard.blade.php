@extends('layouts.app')

@section('title', 'Admin Dashboard - NearU')

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <h2>🛡️ Admin Dashboard</h2>
      
      <!-- Admin Welcome -->
      <div class="admin-welcome">
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
          <a href="{{ route('admin.owner-verifications.index') }}" class="btn btn-blue">Review Now</a>
        </div>
      </div>
      @endif

      <!-- Recent Activity -->
      <div class="activity-section">
        <h3>Recent Activity</h3>
        
        <div class="activity-grid">
          <!-- Recent Users -->
          <div class="activity-card">
            <h4>👤 New Users</h4>
            @foreach($recentUsers as $user)
              <div class="activity-item">
                <span class="activity-name">{{ $user->name }}</span>
                <span class="activity-type">{{ $user->user_type }}</span>
                <span class="activity-time">{{ $user->created_at->diffForHumans() }}</span>
              </div>
            @endforeach
          </div>
          
          <!-- Recent Listings -->
          <div class="activity-card">
            <h4>🏘️ New Listings</h4>
            @foreach($recentListings as $listing)
              <div class="activity-item">
                <span class="activity-name">{{ Str::limit($listing->street, 20) }}</span>
                <span class="activity-type">{{ $listing->type }}</span>
                <span class="activity-time">{{ $listing->created_at->diffForHumans() }}</span>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="actions-section">
        <h3>🚀 Quick Actions</h3>
        <div class="actions-grid">
          <a href="{{ route('admin.owner-verifications.index') }}" class="action-card">
            <div class="action-icon">✅</div>
            <div class="action-title">Owner Verifications</div>
          </a>
          
          <a href="#" class="action-card">
            <div class="action-icon">👥</div>
            <div class="action-title">Manage Users</div>
          </a>
          
          <a href="#" class="action-card">
            <div class="action-icon">📊</div>
            <div class="action-title">System Reports</div>
          </a>
          
          <a href="#" class="action-card">
            <div class="action-icon">⚙️</div>
            <div class="action-title">Settings</div>
          </a>
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
  background: linear-gradient(135deg, #2D7D4F, #1f5c38);
  color: white;
  padding: 1.5rem;
  border-radius: 18px;
  margin-bottom: 1.5rem;
  text-align: center;
  box-shadow: 0 4px 16px rgba(45, 125, 79, 0.2);
  transition: transform 0.2s, box-shadow 0.2s;
}

.admin-welcome:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(45, 125, 79, 0.15);
}

.admin-welcome h3 {
  margin: 0 0 0.5rem 0;
  font-size: 1.3rem;
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
  border-radius: 18px;
  padding: 1rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(45, 125, 79, 0.15);
}

.stat-icon {
  font-size: 2rem;
  width: 50px;
  text-align: center;
}

.stat-number {
  font-size: 1.5rem;
  font-weight: 800;
  color: #2D7D4F;
}

.stat-label {
  font-size: 0.8rem;
  color: #6b7280;
  font-weight: 600;
}

.alert-card {
  background: white;
  border-radius: 12px;
  padding: 1rem;
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.alert-card.pending {
  border-left: 4px solid #F2B705;
  background: #fffbeb;
}

.alert-icon {
  font-size: 2rem;
}

.alert-content strong {
  color: #92400E;
}

.alert-content p {
  margin: 0.25rem 0;
  color: #6b7280;
}

.activity-section h3 {
  margin-bottom: 1rem;
  color: #1f2937;
}

.activity-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.activity-card {
  background: white;
  border-radius: 12px;
  padding: 1rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  border: 1px solid #e5e7eb;
}

.activity-card h4 {
  margin: 0 0 0.75rem 0;
  font-size: 0.9rem;
  color: #374151;
}

.activity-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 0;
  border-bottom: 1px solid #f3f4f6;
  font-size: 0.8rem;
}

.activity-item:last-child {
  border-bottom: none;
}

.activity-name {
  flex: 1;
  font-weight: 600;
  color: #374151;
}

.activity-type {
  background: #f3f4f6;
  padding: 0.2rem 0.5rem;
  border-radius: 6px;
  font-size: 0.7rem;
  color: #6b7280;
  font-weight: 600;
}

.activity-time {
  color: #9ca3af;
  font-size: 0.7rem;
}

.actions-section h3 {
  margin-bottom: 1rem;
  color: #1f2937;
}

.actions-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.75rem;
}

.action-card {
  background: #fff;
  color: #2D7D4F;
  padding: 0.5rem 1rem;
  border-radius: 12px;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.8rem;
  display: inline-block;
  margin-top: 0.5rem;
  border: 2px solid #2D7D4F;
  box-shadow: 0 2px 4px rgba(45, 125, 79, 0.2);
  transition: all 0.2s;
}

.action-card:hover {
  background: #2D7D4F;
  text-decoration: none;
  color: white;
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(45, 125, 79, 0.3);
}

.action-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  text-decoration: none;
  color: inherit;
}

.action-icon {
  font-size: 2rem;
  margin-bottom: 0.5rem;
}

.action-title {
  font-weight: 700;
  color: #374151;
  font-size: 0.9rem;
}

.btn-blue {
  background: #2D7D4F;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.8rem;
  display: inline-block;
  margin-top: 0.5rem;
  border: none;
  box-shadow: 0 2px 4px rgba(45, 125, 79, 0.2);
  transition: all 0.2s;
}

.btn-blue:hover {
  background: #1f5c38;
  text-decoration: none;
  color: white;
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(45, 125, 79, 0.3);
}

.bottom-spacer {
  height: 100px;
  width: 100%;
}
</style>
@endpush
