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
        
        <div class="settings-container">
          <div class="settings-section">
            <div class="section-header">
              <h3>🌐 Site Settings</h3>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label for="site_name">Site Name</label>
                <input type="text" id="site_name" name="site_name" value="{{ $settings['site_name'] }}" required>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="site_description">Site Description</label>
                <textarea id="site_description" name="site_description" rows="2">{{ $settings['site_description'] }}</textarea>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="contact_email">Contact Email</label>
                <input type="email" id="contact_email" name="contact_email" value="{{ $settings['contact_email'] }}" required>
              </div>
            </div>
          </div>

          <div class="settings-section">
            <div class="section-header">
              <h3>🔧 System Configuration</h3>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label for="max_listings_per_owner">Max Listings Per Owner</label>
                <input type="number" id="max_listings_per_owner" name="max_listings_per_owner" value="{{ $settings['max_listings_per_owner'] }}" min="1" max="100" required>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <div class="checkbox-group">
                  <input type="checkbox" id="require_verification" name="require_verification" {{ $settings['require_verification'] ? 'checked' : '' }}>
                  <label for="require_verification">Require Owner Verification</label>
                </div>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <div class="checkbox-group">
                  <input type="checkbox" id="auto_approve_listings" name="auto_approve_listings" {{ $settings['auto_approve_listings'] ? 'checked' : '' }}>
                  <label for="auto_approve_listings">Auto Approve Listings</label>
                </div>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <div class="checkbox-group">
                  <input type="checkbox" id="maintenance_mode" name="maintenance_mode" {{ $settings['maintenance_mode'] ? 'checked' : '' }}>
                  <label for="maintenance_mode">Maintenance Mode</label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">💾 Save Settings</button>
        </div>
      </form>

      <div class="maintenance-section">
        <div class="section-header">
          <h3>🔧 System Maintenance</h3>
        </div>
        <div class="maintenance-grid">
          <form action="{{ route('admin.settings.backup') }}" method="POST" class="maintenance-form">
            @csrf
            <button type="submit" class="btn btn-primary">💿 Create Backup</button>
          </form>
          
          <form action="{{ route('admin.settings.clearCache') }}" method="POST" class="maintenance-form">
            @csrf
            <button type="submit" class="btn btn-primary">🗑️ Clear Cache</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  @include('partials.footer')
</div>
@endsection

@push('styles')
<style>
.settings-container {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

@media (max-width: 430px) {
  .settings-container {
    gap: 0.75rem;
    margin-bottom: 1rem;
  }
}

.settings-section {
  background: white;
  border: 2px solid #2D7D4F;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.1);
}

.section-header {
  background: linear-gradient(135deg, #2D7D4F, #1f5c38);
  padding: 0.75rem 1rem;
  border-bottom: 2px solid #2D7D4F;
}

@media (max-width: 430px) {
  .section-header {
    padding: 0.625rem 0.75rem;
  }
}

.section-header h3 {
  margin: 0;
  color: white;
  font-size: 1rem;
  font-weight: 700;
  letter-spacing: 0.3px;
}

@media (max-width: 430px) {
  .section-header h3 {
    font-size: 0.9rem;
  }
}

.form-row {
  padding: 0.75rem 1rem;
  border-bottom: 1px solid #f3f4f6;
}

@media (max-width: 430px) {
  .form-row {
    padding: 0.625rem 0.75rem;
  }
}

.form-row:last-child {
  border-bottom: none;
}

.form-group {
  margin: 0;
}

.form-group label {
  display: block;
  margin-bottom: 0.375rem;
  font-weight: 600;
  color: #374151;
  font-size: 0.875rem;
  letter-spacing: 0.2px;
}

@media (max-width: 430px) {
  .form-group label {
    font-size: 0.8rem;
    margin-bottom: 0.25rem;
  }
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 0.5rem 0.75rem;
  border: 2px solid #e5e7eb;
  border-radius: 8px;
  font-size: 0.875rem;
  transition: all 0.2s ease;
  background: #fafafa;
  -webkit-appearance: none;
  -webkit-tap-highlight-color: transparent;
}

@media (max-width: 430px) {
  .form-group input,
  .form-group textarea {
    padding: 0.625rem 0.75rem;
    font-size: 16px; /* Prevents zoom on iOS */
    min-height: 44px; /* Touch target size */
  }
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #2D7D4F;
  background: white;
  box-shadow: 0 0 0 3px rgba(45, 125, 79, 0.1);
}

.checkbox-group {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.5rem 0;
}

@media (max-width: 430px) {
  .checkbox-group {
    padding: 0.625rem 0;
    gap: 0.625rem;
  }
}

.checkbox-group input[type="checkbox"] {
  width: 18px;
  height: 18px;
  margin: 0;
  accent-color: #2D7D4F;
  cursor: pointer;
  -webkit-appearance: none;
  -webkit-tap-highlight-color: transparent;
}

@media (max-width: 430px) {
  .checkbox-group input[type="checkbox"] {
    width: 20px;
    height: 20px;
    min-width: 44px;
    min-height: 44px;
  }
}

.checkbox-group label {
  margin: 0;
  cursor: pointer;
  font-size: 0.875rem;
  line-height: 1.4;
}

@media (max-width: 430px) {
  .checkbox-group label {
    font-size: 0.8rem;
    line-height: 1.3;
  }
}

.form-actions {
  text-align: center;
  margin-bottom: 1.5rem;
}

.maintenance-section {
  background: white;
  border: 2px solid #2D7D4F;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.1);
}

.maintenance-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 0.5rem;
  padding: 1rem;
}

@media (min-width: 768px) {
  .maintenance-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 430px) {
  .maintenance-grid {
    gap: 0.375rem;
    padding: 0.75rem;
  }
}

.maintenance-form {
  margin: 0;
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.625rem 1rem;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.875rem;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
  white-space: nowrap;
  -webkit-tap-highlight-color: transparent;
  -webkit-appearance: none;
}

@media (max-width: 430px) {
  .btn {
    padding: 0.75rem 1rem;
    font-size: 0.8rem;
    min-height: 44px; /* Touch target size */
    border-radius: 10px;
  }
}

.maintenance-grid .btn {
  width: 100%;
  min-height: 48px;
  justify-content: center;
}

.btn-primary {
  background: linear-gradient(135deg, #2D7D4F, #1f5c38);
  color: white;
  border: 2px solid #2D7D4F;
  box-shadow: 0 2px 8px rgba(45, 125, 79, 0.3);
}

.btn-primary:hover {
  background: linear-gradient(135deg, #1f5c38, #163d29);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.4);
}

.btn-secondary {
  background: linear-gradient(135deg, #6b7280, #4b5563);
  color: white;
  border: 2px solid #6b7280;
  box-shadow: 0 2px 8px rgba(107, 114, 128, 0.3);
}

.btn-secondary:hover {
  background: linear-gradient(135deg, #4b5563, #374151);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(107, 114, 128, 0.4);
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
