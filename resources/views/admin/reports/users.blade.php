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

/* Statistics Cards Container */
.stats-container {
  display: grid !important;
  grid-template-columns: repeat(2, 1fr) !important;
  gap: 1rem !important;
  width: 100% !important;
  margin-bottom: 1.5rem !important;
  align-items: stretch !important;
}

/* Override parent container constraints */
.screen.active .cs .stats-container {
  max-width: none !important;
  width: 100% !important;
}

.stats-container .stat-card {
  min-width: 0 !important;
  width: 100% !important;
}

.stat-card {
  background: linear-gradient(135deg, #ffffff, #f8fafc) !important;
  border: 2px solid #2D7D4F !important;
  border-radius: 20px !important;
  padding: 1.5rem !important;
  display: flex !important;
  align-items: center !important;
  gap: 1rem !important;
  box-shadow: 0 8px 32px rgba(45, 125, 79, 0.15) !important;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
  position: relative !important;
  overflow: hidden !important;
  min-height: 100px !important;
}

.stat-card::before {
  content: '' !important;
  position: absolute !important;
  top: -50% !important;
  right: -50% !important;
  width: 100% !important;
  height: 100% !important;
  background: linear-gradient(45deg, rgba(45, 125, 79, 0.03), rgba(45, 125, 79, 0.08)) !important;
  border-radius: 50% !important;
  transition: all 0.4s ease !important;
}

.stat-card::after {
  content: '' !important;
  position: absolute !important;
  top: 10px !important;
  right: 10px !important;
  width: 8px !important;
  height: 8px !important;
  background: linear-gradient(135deg, #6ee7b7, #34d399) !important;
  border-radius: 50% !important;
  box-shadow: 0 2px 8px rgba(45, 125, 79, 0.3) !important;
  transition: all 0.3s ease !important;
}

.stat-card:hover::before {
  top: -30% !important;
  right: -30% !important;
}

.stat-card:hover::after {
  transform: scale(1.5) !important;
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.5) !important;
}

.stat-card:hover {
  transform: translateY(-4px) scale(1.02) !important;
  box-shadow: 0 16px 40px rgba(45, 125, 79, 0.25) !important;
  border-color: #1f5c38 !important;
}

.stat-icon {
  font-size: 2rem !important;
  width: 50px !important;
  height: 50px !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  background: linear-gradient(135deg, #2D7D4F, #16a34a) !important;
  border-radius: 16px !important;
  box-shadow: 0 6px 16px rgba(45, 125, 79, 0.3) !important;
  flex-shrink: 0 !important;
  position: relative !important;
  z-index: 1 !important;
}

.stat-content {
  flex: 1 !important;
  z-index: 1 !important;
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
  display: flex;
  flex-direction: column;
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
