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
          <h3>📊 Storage Overview</h3>
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
.page-header { margin-bottom: 1.5rem; }
.back-link { color: #2D7D4F; text-decoration: none; font-weight: 600; margin-bottom: 0.5rem; display: inline-block; }
.back-link:hover { text-decoration: underline; }

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

.page-header {
  margin-bottom: 1.5rem;
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

.storage-overview { margin-bottom: 2rem; }
.storage-card { background: white; border: 2px solid #2D7D4F; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 16px rgba(45, 125, 79, 0.1); }
.storage-card h3 { margin: 0 0 1rem 0; color: #374151; }
.storage-stats { display: flex; flex-direction: column; gap: 0.75rem; }
.storage-item { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: #f8fafc; border-radius: 8px; border: 1px solid #e5e7eb; }
.storage-label { color: #6b7280; font-weight: 600; }
.storage-value { color: #2D7D4F; font-weight: 700; }
</style>
@endpush
