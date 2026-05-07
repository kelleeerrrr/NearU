@extends('layouts.app')

@section('title', 'Activity Reports - Admin')

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <div class="page-header">
        <a href="{{ route('admin.reports.index') }}" class="back-link">← Back to Reports</a>
        <h2>📈 Activity Reports</h2>
      </div>

      <div class="stats-overview">
        <div class="stat-card">
          <div class="stat-number">{{ $totalMessages }}</div>
          <div class="stat-label">Total Messages</div>
        </div>
        <div class="stat-card">
          <div class="stat-number">{{ $totalVisits }}</div>
          <div class="stat-label">Total Visits</div>
        </div>
        <div class="stat-card">
          <div class="stat-number">{{ $completedVisits }}</div>
          <div class="stat-label">Completed</div>
        </div>
        <div class="stat-card">
          <div class="stat-number">{{ $totalNotifications }}</div>
          <div class="stat-label">Notifications</div>
        </div>
      </div>

      <div class="detailed-stats">
        <div class="detail-section">
          <h3>📨 Message Activity</h3>
          <div class="activity-stats">
            <div class="activity-item">
              <span class="activity-label">Messages This Month:</span>
              <span class="activity-value">{{ $messagesThisMonth }}</span>
            </div>
            <div class="activity-item">
              <span class="activity-label">Messages This Week:</span>
              <span class="activity-value">{{ $messagesThisWeek }}</span>
            </div>
          </div>
        </div>

        <div class="detail-section">
          <h3>📅 Visit Statistics</h3>
          <div class="activity-stats">
            <div class="activity-item">
              <span class="activity-label">Visits This Month:</span>
              <span class="activity-value">{{ $visitsThisMonth }}</span>
            </div>
            <div class="activity-item">
              <span class="activity-label">Pending Visits:</span>
              <span class="activity-value">{{ $pendingVisits }}</span>
            </div>
            <div class="activity-item">
              <span class="activity-label">Cancelled Visits:</span>
              <span class="activity-value">{{ $cancelledVisits }}</span>
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
.page-header { margin-bottom: 1.5rem; }
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
.stats-overview { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
.stat-card { background: white; border: 2px solid #2D7D4F; border-radius: 12px; padding: 1.5rem; text-align: center; box-shadow: 0 4px 16px rgba(45, 125, 79, 0.1); }
.stat-number { font-size: 2rem; font-weight: 800; color: #2D7D4F; margin-bottom: 0.5rem; }
.stat-label { color: #6b7280; font-weight: 600; text-transform: uppercase; font-size: 0.8rem; }
.detailed-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
.detail-section { background: white; border: 2px solid #2D7D4F; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 16px rgba(45, 125, 79, 0.1); }
.detail-section h3 { margin: 0 0 1rem 0; color: #374151; }
.activity-stats { display: flex; flex-direction: column; gap: 0.75rem; }
.activity-item { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: #f8fafc; border-radius: 8px; border: 1px solid #e5e7eb; }
.activity-label { color: #6b7280; font-weight: 600; }
.activity-value { color: #2D7D4F; font-weight: 700; font-size: 1.1rem; }
</style>
@endpush
