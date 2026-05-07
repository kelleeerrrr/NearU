@extends('layouts.app')

@section('title', 'Storage Management - Admin')

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <div class="page-header">
        <div class="header-top">
          <h2>💾 Storage Management</h2>
          <a href="{{ route('admin.profile') }}" class="btn-back">← Back</a>
        </div>
      </div>

      <div class="storage-overview">
        <div class="storage-card">
          <div class="card-header">
            <h3>📊 Storage Overview</h3>
          </div>
          <div class="storage-stats">
            <div class="storage-item">
              <span class="storage-label">Total Storage Used:</span>
              <span class="storage-value">{{ $storageInfo['total_size'] }}</span>
            </div>
            <div class="storage-item">
              <span class="storage-label">Total Files:</span>
              <span class="storage-value">{{ $storageInfo['files_count'] }}</span>
            </div>
            <div class="storage-item">
              <span class="storage-label">Dorm Images:</span>
              <span class="storage-value">{{ $storageInfo['dorm_images_size'] }}</span>
            </div>
            <div class="storage-item">
              <span class="storage-label">Profile Photos:</span>
              <span class="storage-value">{{ $storageInfo['profile_photos_size'] }}</span>
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

.btn-back {
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
}

.btn-back:hover {
  background: #1f5c38;
  border-color: #1f5c38;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.4);
}

.header-top {
  display: flex;
  align-items: center;
  gap: 1rem;
  justify-content: space-between;
}

.header-top h2 {
  margin: 0;
  color: #374151;
  font-size: 1.5rem;
}

.storage-overview { 
  margin-bottom: 2rem; 
}

.storage-card { 
  background: white; 
  border: 2px solid #2D7D4F; 
  border-radius: 12px; 
  overflow: hidden;
  box-shadow: 0 4px 16px rgba(45, 125, 79, 0.1); 
}

.storage-card .card-header {
  background: linear-gradient(135deg, #2D7D4F, #1f5c38);
  padding: 0.75rem 1rem;
  border-bottom: 2px solid #2D7D4F;
}

.storage-card h3 { 
  margin: 0; 
  color: white;
  font-size: 1rem;
  font-weight: 700;
  letter-spacing: 0.3px;
}

.storage-stats { 
  display: flex;
  flex-direction: column; 
  gap: 0.75rem; 
  padding: 1.5rem;
}

.storage-item { 
  display: flex; 
  justify-content: space-between; 
  align-items: center; 
  padding: 0.75rem 1rem; 
  background: #f8fafc; 
  border-radius: 8px; 
  border: 1px solid #e5e7eb;
  transition: all 0.2s ease;
}

.storage-item:hover {
  background: #f1f5f9;
  border-color: #2D7D4F;
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(45, 125, 79, 0.1);
}

.storage-label { 
  color: #6b7280; 
  font-weight: 600; 
  font-size: 0.875rem;
}

.storage-value { 
  color: #2D7D4F; 
  font-weight: 700; 
  font-size: 0.9rem;
  background: linear-gradient(135deg, #2D7D4F, #1f5c38);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

@media (max-width: 430px) {
  .storage-stats {
    padding: 1rem;
    gap: 0.5rem;
  }
  
  .storage-item {
    padding: 0.625rem 0.75rem;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.25rem;
  }
  
  .storage-label {
    font-size: 0.8rem;
  }
  
  .storage-value {
    font-size: 0.85rem;
  }
}
</style>
@endpush
