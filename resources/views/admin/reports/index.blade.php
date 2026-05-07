@extends('layouts.app')

@section('title', 'System Reports - Admin')

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <h2>📊 System Reports</h2>
      
      <div class="reports-grid">
        <a href="{{ route('admin.reports.users') }}" class="report-card">
          <div class="report-icon">👥</div>
          <div class="report-title">User Statistics</div>
          <div class="report-description">View user growth, demographics, and verification status</div>
        </a>
        
        <a href="{{ route('admin.reports.listings') }}" class="report-card">
          <div class="report-icon">🏠</div>
          <div class="report-title">Listing Analytics</div>
          <div class="report-description">Analyze dorm listings, pricing, and availability</div>
        </a>
        
        <a href="{{ route('admin.reports.activity') }}" class="report-card">
          <div class="report-icon">📈</div>
          <div class="report-title">Activity Reports</div>
          <div class="report-description">Track messages, visits, and system activity</div>
        </a>
      </div>

      <div class="export-section">
        <h3>📥 Export Data</h3>
        <div class="export-buttons">
          <a href="{{ route('admin.reports.exportUsers') }}" class="btn btn-blue">Export Users (CSV)</a>
          <a href="{{ route('admin.reports.exportListings') }}" class="btn btn-green">Export Listings (CSV)</a>
        </div>
      </div>
    </div>
  </div>

  @include('partials.footer')
</div>
@endsection

@push('styles')
<style>
.reports-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 0.75rem;
  margin-bottom: 1rem;
}

.report-card {
  background: white;
  border: 2px solid #2D7D4F;
  border-radius: 8px;
  padding: 1rem 0.75rem;
  text-decoration: none;
  color: inherit;
  text-align: center;
  box-shadow: 0 2px 6px rgba(45, 125, 79, 0.15);
  transition: transform 0.2s, box-shadow 0.2s;
  min-height: 130px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.report-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 32px rgba(45, 125, 79, 0.15);
  text-decoration: none;
  color: inherit;
}

.report-icon {
  font-size: 2.2rem;
  margin-bottom: 0.5rem;
  line-height: 1;
}

.report-title {
  font-size: 0.95rem;
  font-weight: 700;
  color: #2D7D4F;
  margin-bottom: 0.375rem;
}

.report-description {
  color: #6b7280;
  line-height: 1.3;
  font-size: 0.75rem;
}

.export-section {
  background: white;
  border: 2px solid #2D7D4F;
  border-radius: 8px;
  padding: 0.875rem;
  box-shadow: 0 2px 6px rgba(45, 125, 79, 0.15);
}

.export-section h3 {
  margin: 0 0 1rem 0;
  color: #374151;
}

.export-buttons {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.btn {
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-blue {
  background: #2D7D4F;
  color: white;
  border: 2px solid #2D7D4F;
}

.btn-blue:hover {
  background: #1f5c38;
  border-color: #1f5c38;
  text-decoration: none;
  color: white;
}

.btn-green {
  background: #16a34a;
  color: white;
  border: 2px solid #16a34a;
}

.btn-green:hover {
  background: #15803d;
  border-color: #15803d;
  text-decoration: none;
  color: white;
}
</style>
@endpush
