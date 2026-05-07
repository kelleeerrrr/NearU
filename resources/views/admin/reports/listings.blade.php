@extends('layouts.app')

@section('title', 'Listing Analytics - Admin')

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <div class="page-header">
        <a href="{{ route('admin.reports.index') }}" class="back-link">← Back to Reports</a>
        <h2>🏠 Listing Analytics</h2>
      </div>

      <div class="stats-overview">
        <div class="stat-card">
          <div class="stat-number">{{ $totalListings }}</div>
          <div class="stat-label">Total Listings</div>
        </div>
        <div class="stat-card">
          <div class="stat-number">{{ $activeListings }}</div>
          <div class="stat-label">Active</div>
        </div>
        <div class="stat-card">
          <div class="stat-number">{{ $inactiveListings }}</div>
          <div class="stat-label">Inactive</div>
        </div>
        <div class="stat-card">
          <div class="stat-number">₱{{ number_format($avgPrice, 0) }}</div>
          <div class="stat-label">Avg Price</div>
        </div>
      </div>

      <div class="charts-section">
        <div class="chart-row">
          <div class="chart-card">
            <h3>📊 Listings by Type</h3>
            <div class="type-stats">
              @foreach($listingsByType as $type)
                <div class="type-item">
                  <span class="type-label">{{ $type->type }}</span>
                  <span class="type-count">{{ $type->count }}</span>
                </div>
              @endforeach
            </div>
          </div>

          <div class="chart-card">
            <h3>💰 Price Distribution</h3>
            <div class="price-stats">
              @foreach($priceDistribution as $range)
                <div class="price-item">
                  <span class="price-label">{{ $range->price_range }}</span>
                  <span class="price-count">{{ $range->count }}</span>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>

      <div class="detailed-stats">
        
        <div class="detail-section">
          <h3>💵 Pricing Analysis</h3>
          <div class="pricing-stats">
            <div class="pricing-item">
              <span class="pricing-label">Minimum Price:</span>
              <span class="pricing-value">₱{{ number_format($minPrice, 0) }}</span>
            </div>
            <div class="pricing-item">
              <span class="pricing-label">Maximum Price:</span>
              <span class="pricing-value">₱{{ number_format($maxPrice, 0) }}</span>
            </div>
            <div class="pricing-item">
              <span class="pricing-label">Average Price:</span>
              <span class="pricing-value">₱{{ number_format($avgPrice, 0) }}</span>
            </div>
          </div>
        </div>

        <div class="detail-section">
          <h3>📅 Recent Activity</h3>
          <div class="activity-stats">
            <div class="activity-item">
              <span class="activity-label">New Listings This Month:</span>
              <span class="activity-value">{{ $newListingsThisMonth }}</span>
            </div>
            <div class="activity-item">
              <span class="activity-label">New Listings This Week:</span>
              <span class="activity-value">{{ $newListingsThisWeek }}</span>
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
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
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
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.chart-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
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

.type-stats {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.type-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem;
  background: #f8fafc;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
}

.type-label {
  color: #6b7280;
  font-weight: 600;
}

.type-count {
  color: #2D7D4F;
  font-weight: 700;
  font-size: 1.1rem;
}

.price-stats {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.price-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem;
  background: #f8fafc;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
}

.price-label {
  color: #6b7280;
  font-weight: 600;
}

.price-count {
  color: #2D7D4F;
  font-weight: 700;
  font-size: 1.1rem;
}

.detailed-stats {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
}

.detail-section {
  background: white;
  border: 2px solid #2D7D4F;
  border-radius: 12px;
  padding: 1rem;
  box-shadow: 0 4px 16px rgba(45, 125, 79, 0.1);
}

.detail-section h3 {
  margin: 0 0 0.75rem 0;
  color: #374151;
}

.pricing-stats {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.pricing-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem;
  background: #f8fafc;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
}

.pricing-label {
  color: #6b7280;
  font-weight: 600;
}

.pricing-value {
  color: #2D7D4F;
  font-weight: 700;
  font-size: 1.1rem;
}

.activity-stats {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
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
  font-size: 1.1rem;
}
</style>
@endpush
