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

      <div class="stats-container">
        <div class="stats-grid">
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
      </div>

        <div class="data-container">
          <div class="data-section">
            <h3>📊 Listings by Type</h3>
            <div class="type-grid">
              @foreach($listingsByType as $type)
                <div class="type-card">
                  <div class="type-content">
                    <span class="type-icon">
  @if($type->type === 'Room')
    🏠
  @elseif($type->type === 'Bedspace')
    🛏️
  @elseif($type->type === 'Unit')
    🏬
  @else
    📁
  @endif
</span>
                    <div class="type-info">
                      <div class="type-name">{{ $type->type }}</div>
                      <div class="type-count">{{ $type->count }}</div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>

        <div class="data-container">
          <div class="data-section">
            <h3>💵 Pricing Analysis</h3>
            <div class="data-list">
              <div class="data-row">
                <span class="data-label">Minimum Price:</span>
                <span class="data-value price">₱{{ number_format($minPrice, 0) }}</span>
              </div>
              <div class="data-row">
                <span class="data-label">Maximum Price:</span>
                <span class="data-value price">₱{{ number_format($maxPrice, 0) }}</span>
              </div>
              <div class="data-row">
                <span class="data-label">Average Price:</span>
                <span class="data-value price">₱{{ number_format($avgPrice, 0) }}</span>
              </div>
            </div>
          </div>
        </div>

        <div class="data-container">
          <div class="data-section">
            <h3>📅 Recent Activity</h3>
            <div class="data-list">
              <div class="data-row">
                <span class="data-label">New Listings This Month:</span>
                <span class="data-value">{{ $newListingsThisMonth }}</span>
              </div>
              <div class="data-row">
                <span class="data-label">New Listings This Week:</span>
                <span class="data-value">{{ $newListingsThisWeek }}</span>
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

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 0.75rem;
  padding: 1rem;
}

.data-container {
  margin: 0 1rem 1rem 1rem;
}

.data-section {
  background: white;
  border: 2px solid #2D7D4F;
  border-radius: 8px;
  padding: 1rem;
  margin-bottom: 1rem;
}

.data-section h3 {
  margin: 0 0 0.75rem 0;
  color: #374151;
  font-size: 1rem;
  font-weight: 700;
}

.data-section h3 {
  margin: 0 0 0.75rem 0;
  color: #374151;
  font-size: 1.1rem;
  font-weight: 700;
}

.stats-container {
  margin: 0 1rem 1.5rem 1rem;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 0.75rem;
  padding: 1rem;
}

@media (max-width: 430px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5rem;
    padding: 0.75rem;
  }
}

.stat-card {
  background: white;
  border: 2px solid #2D7D4F;
  border-radius: 8px;
  padding: 1rem;
  text-align: center;
  transition: all 0.2s ease;
}

.stat-card:hover {
  background: #f8fafc;
  border-color: #2D7D4F;
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(45, 125, 79, 0.1);
}

.stat-number {
  font-size: 1.5rem;
  font-weight: 700;
  color: #2D7D4F;
  margin-bottom: 0.5rem;
}

.stat-label {
  color: #6b7280;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.75rem;
  letter-spacing: 0.5px;
}

.type-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 0.75rem;
  padding: 0.75rem;
}

@media (max-width: 430px) {
  .type-grid {
    grid-template-columns: 1fr;
    gap: 0.5rem;
    padding: 0.5rem;
  }
}

.type-card {
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  padding: 1rem;
  text-align: center;
  transition: all 0.2s ease;
}

.type-card:hover {
  border-color: #2D7D4F;
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(45, 125, 79, 0.1);
}

.type-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
}

.type-icon {
  font-size: 2rem;
  line-height: 1;
}

.type-info {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.25rem;
}

.type-name {
  font-weight: 600;
  color: #374151;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.type-count {
  font-size: 1.5rem;
  font-weight: 700;
  color: #2D7D4F;
}

.data-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.625rem 0.75rem;
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  transition: all 0.2s ease;
}

.data-item:hover {
  background: #f8fafc;
  border-color: #2D7D4F;
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(45, 125, 79, 0.1);
}

.data-list {
  padding: 1rem;
}

.data-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem 0;
  border-bottom: 1px solid #f3f4f6;
  transition: all 0.2s ease;
}

.data-row:last-child {
  border-bottom: none;
}

.data-row:hover {
  background: #f8fafc;
  padding-left: 0.75rem;
}

.data-label {
  color: #6b7280;
  font-weight: 600;
  font-size: 0.875rem;
}

.data-value {
  color: #2D7D4F;
  font-weight: 700;
  font-size: 0.9rem;
}

.data-value.price {
  font-size: 1rem;
  background: linear-gradient(135deg, #2D7D4F, #1f5c38);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

@media (max-width: 430px) {
  .data-row {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.25rem;
    padding: 0.625rem 0;
  }
  
  .data-label {
    font-size: 0.8rem;
  }
  
  .data-value {
    font-size: 0.85rem;
  }
}
</style>
@endpush
