@extends('layouts.app')

@section('title', 'User Statistics - Admin')

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <div class="page-header">
        <a href="{{ route('admin.reports.index') }}" class="back-link">← Back to Reports</a>
        <h2>👥 User Statistics</h2>
      </div>

      <div class="stats-overview">
        <div class="stat-row">
          <div class="stat-card">
            <div class="stat-number">{{ $totalUsers }}</div>
            <div class="stat-label">Total Users</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">{{ $students }}</div>
            <div class="stat-label">Students</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">{{ $userTypeDistribution['admins'] }}</div>
            <div class="stat-label">Admins</div>
          </div>
          <div class="stat-card">
            <div class="stat-number">{{ $owners }}</div>
            <div class="stat-label">Owners</div>
          </div>
        </div>
      </div>

      <div class="charts-section">
        <div class="chart-card">
          <h3>📈 User Growth (Last 30 Days)</h3>
          <div class="chart-placeholder">
            <p>User registration trend over the past month</p>
            <small>Chart integration would go here</small>
          </div>
        </div>
      </div>

      <div class="detailed-stats">
        <div class="detail-section">
          <h3>🔍 Verification Status</h3>
          <div class="verification-stats">
            <div class="verification-card verified">
              <div class="verification-number">{{ $verifiedOwners }}</div>
              <div class="verification-label">Verified Owners</div>
            </div>
            <div class="verification-card pending">
              <div class="verification-number">{{ $verificationDistribution['under_review'] ?? 0 }}</div>
              <div class="verification-label">Pending Verification</div>
            </div>
          </div>
        </div>

        <div class="detail-section">
          <h3>📅 Recent Activity</h3>
          <div class="activity-stats">
            <div class="activity-item">
              <span class="activity-label">New Users This Month:</span>
              <span class="activity-value">{{ $userGrowth->where('date', '>=', now()->startOfMonth())->sum('count') }}</span>
            </div>
            <div class="activity-item">
              <span class="activity-label">New Users This Week:</span>
              <span class="activity-value">{{ $userGrowth->where('date', '>=', now()->startOfWeek())->sum('count') }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('partials.footer')
</div>
@endsection

@push('styles')
<style>
.page-header {
  margin-bottom: 1.5rem;
}

.back-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  background: #2D7D4F;
  color: #fff;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 700;
  text-decoration: none;
  border: 2px solid #2D7D4F;
  box-shadow: 0 2px 8px rgba(45, 125, 79, 0.3);
  transition: all 0.2s;
  white-space: nowrap;
  margin-bottom: 1rem;
}

.back-link:hover {
  background: #1f5c38;
  border-color: #1f5c38;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.4);
  text-decoration: none;
}

.stats-overview {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.stats-overview:first-child {
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.stats-overview:last-child {
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.stat-card {
  background: white;
  border: 2px solid #2D7D4F;
  border-radius: 12px;
  padding: 1rem;
  text-align: center;
  box-shadow: 0 4px 16px rgba(45, 125, 79, 0.1);
}

.stat-number {
  font-size: 1.8rem;
  font-weight: 800;
  color: #2D7D4F;
  margin-bottom: 0.5rem;
}

.stat-label {
  color: #6b7280;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.8rem;
}

.charts-section {
  margin-bottom: 1.5rem;
}

.chart-card {
  background: white;
  border: 2px solid #2D7D4F;
  border-radius: 12px;
  padding: 1rem;
  box-shadow: 0 4px 16px rgba(45, 125, 79, 0.1);
}

.chart-card h3 {
  margin: 0 0 0.75rem 0;
  color: #374151;
}

.chart-placeholder {
  background: #f8fafc;
  border: 2px dashed #e5e7eb;
  border-radius: 8px;
  padding: 1.5rem;
  text-align: center;
  color: #6b7280;
}

.detailed-stats {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
}

.detail-section {
  background: white;
  border: 2px solid #2D7D4F;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 4px 16px rgba(45, 125, 79, 0.1);
}

.detail-section h3 {
  margin: 0 0 1rem 0;
  color: #374151;
}

.verification-stats {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.verification-card {
  padding: 1rem;
  border-radius: 8px;
  text-align: center;
}

.verification-card.verified {
  background: #dcfce7;
  border: 2px solid #16a34a;
}

.verification-card.pending {
  background: #fef3c7;
  border: 2px solid #f59e0b;
}

.verification-number {
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.verification-card.verified .verification-number {
  color: #16a34a;
}

.verification-card.pending .verification-number {
  color: #f59e0b;
}

.verification-label {
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
}

.verification-card.verified .verification-label {
  color: #16a34a;
}

.verification-card.pending .verification-label {
  color: #f59e0b;
}

.activity-stats {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.activity-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem;
  background: #f8fafc;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
}

.activity-label {
  color: #6b7280;
  font-weight: 600;
}

.activity-value {
  color: #2D7D4F;
  font-weight: 700;
  font-size: 1.2rem;
}
</style>
@endpush
