@extends('layouts.app')

@section('title', 'Settings - Admin')

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <div class="page-header">
        <div class="header-top">
          <h2>⚙️ System Settings</h2>
          <a href="{{ route('admin.profile') }}" class="btn-back">← Back to Profile</a>
        </div>
      </div>
      
      <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="settings-grid">
          <div class="settings-section">
            <h3>🌐 Site Settings</h3>
            
            <div class="form-group">
              <label for="site_name">Site Name</label>
              <input type="text" id="site_name" name="site_name" value="{{ $settings['site_name'] }}" required>
            </div>

            <div class="form-group">
              <label for="site_description">Site Description</label>
              <textarea id="site_description" name="site_description" rows="3">{{ $settings['site_description'] }}</textarea>
            </div>

            <div class="form-group">
              <label for="contact_email">Contact Email</label>
              <input type="email" id="contact_email" name="contact_email" value="{{ $settings['contact_email'] }}" required>
            </div>
          </div>

          <div class="settings-section">
            <h3>🔧 System Configuration</h3>
            
            <div class="form-group">
              <label for="max_listings_per_owner">Max Listings Per Owner</label>
              <input type="number" id="max_listings_per_owner" name="max_listings_per_owner" value="{{ $settings['max_listings_per_owner'] }}" min="1" max="100" required>
            </div>

            <div class="form-group">
              <div class="checkbox-group">
                <input type="checkbox" id="require_verification" name="require_verification" {{ $settings['require_verification'] ? 'checked' : '' }}>
                <label for="require_verification">Require Owner Verification</label>
              </div>
            </div>

            <div class="form-group">
              <div class="checkbox-group">
                <input type="checkbox" id="auto_approve_listings" name="auto_approve_listings" {{ $settings['auto_approve_listings'] ? 'checked' : '' }}>
                <label for="auto_approve_listings">Auto Approve Listings</label>
              </div>
            </div>

            <div class="form-group">
              <div class="checkbox-group">
                <input type="checkbox" id="maintenance_mode" name="maintenance_mode" {{ $settings['maintenance_mode'] ? 'checked' : '' }}>
                <label for="maintenance_mode">Maintenance Mode</label>
              </div>
            </div>
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-green">Save Settings</button>
        </div>
      </form>

      <div class="maintenance-section">
        <h3>🔧 System Maintenance</h3>
        <div class="maintenance-actions">
          <form action="{{ route('admin.settings.backup') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-blue">Create Backup</button>
          </form>
          
          <form action="{{ route('admin.settings.clearCache') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-gray">Clear Cache</button>
          </form>
          
          <a href="{{ route('admin.settings.storage') }}" class="btn btn-orange">Storage Management</a>
        </div>
      </div>
    </div>
  </div>

  @include('partials.footer')
</div>
@endsection

@push('styles')
<style>
.settings-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 1rem;
  margin-bottom: 1rem;
}

.settings-section {
  background: white;
  border: 2px solid #2D7D4F;
  border-radius: 8px;
  padding: 0.875rem;
  box-shadow: 0 2px 6px rgba(45, 125, 79, 0.15);
}

.settings-section h3 {
  margin: 0 0 1rem 0;
  color: #374151;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid #e5e7eb;
}

.form-group {
  margin-bottom: 0.875rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #374151;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 0.625rem;
  border: 2px solid #e5e7eb;
  border-radius: 6px;
  font-size: 0.95rem;
  transition: border-color 0.2s, box-shadow 0.2s;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #2D7D4F;
  box-shadow: 0 0 0 3px rgba(45, 125, 79, 0.1);
}

.checkbox-group {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.checkbox-group input[type="checkbox"] {
  width: auto;
  margin: 0;
}

.checkbox-group label {
  margin: 0;
  cursor: pointer;
}

.form-actions {
  text-align: center;
  margin-bottom: 2rem;
}

.maintenance-section {
  background: white;
  border: 2px solid #2D7D4F;
  border-radius: 10px;
  padding: 1.25rem;
  box-shadow: 0 3px 12px rgba(45, 125, 79, 0.15);
}

.maintenance-section h3 {
  margin: 0 0 1rem 0;
  color: #374151;
}

.maintenance-actions {
  display: flex;
  gap: 1rem;
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

.btn-green {
  background: #16a34a;
  color: white;
}

.btn-green:hover {
  background: #15803d;
}

.btn-blue {
  background: #2563eb;
  color: white;
}

.btn-blue:hover {
  background: #1d4ed8;
}

.btn-gray {
  background: #6b7280;
  color: white;
}

.btn-gray:hover {
  background: #4b5563;
}

.btn-orange {
  background: #ea580c;
  color: white;
}

.btn-orange:hover {
  background: #c2410c;
  text-decoration: none;
  color: white;
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
</style>
@endpush
