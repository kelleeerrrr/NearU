@extends('layouts.app')

@section('title', 'Manage Categories - Admin')

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <h2>🏷️ Manage Categories</h2>
      
      <!-- Categories Overview -->
      <div class="categories-overview">
        <div class="category-card">
          <h3>🎓 Student</h3>
          <div class="category-details">
            <div class="category-info">
              <span class="category-name">Student</span>
              <span class="category-desc">Regular students looking for housing</span>
            </div>
            <div class="category-color" style="background: #3b82f6;"></div>
          </div>
        </div>

        <div class="category-card">
          <h3>🏠 Owner</h3>
          <div class="category-details">
            <div class="category-info">
              <span class="category-name">Owner</span>
              <span class="category-desc">Property owners listing dorms</span>
            </div>
            <div class="category-color" style="background: #f59e0b;"></div>
          </div>
        </div>

        <div class="category-card">
          <h3>🛡️ Admin</h3>
          <div class="category-details">
            <div class="category-info">
              <span class="category-name">Admin</span>
              <span class="category-desc">System administrators</span>
            </div>
            <div class="category-color" style="background: #dc2626;"></div>
          </div>
        </div>
      </div>

      <div class="info-card">
        <h3>ℹ️ About Categories</h3>
        <p>Categories help classify users and provide targeted features. Each user type has specific permissions and access levels within the system.</p>
        <div class="info-list">
          <div class="info-item">
            <span class="info-icon">🎓</span>
            <div class="info-content">
              <strong>Students</strong> can browse listings, save favorites, and contact owners
            </div>
          </div>
          <div class="info-item">
            <span class="info-icon">🏠</span>
            <div class="info-content">
              <strong>Owners</strong> can create listings, manage inquiries, and schedule visits
            </div>
          </div>
          <div class="info-item">
            <span class="info-icon">🛡️</span>
            <div class="info-content">
              <strong>Admins</strong> have full system access and management capabilities
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
.category-card {
  background: white;
  border: 2px solid #2D7D4F;
  border-radius: 16px;
  padding: 1.5rem;
  margin-bottom: 1rem;
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.15);
  transition: transform 0.2s, box-shadow 0.2s;
}

.category-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(45, 125, 79, 0.2);
}

.category-card h3 {
  margin: 0 0 1rem 0;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 1.1rem;
  color: #1f2937;
}

.category-details {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
}

.category-info {
  flex: 1;
}

.category-name {
  font-weight: 700;
  font-size: 1rem;
  color: #1f2937;
  margin-bottom: 0.25rem;
}

.category-desc {
  color: #6b7280;
  font-size: 0.9rem;
}

.category-color {
  width: 40px;
  height: 40px;
  border-radius: 8px;
  border: 2px solid rgba(0,0,0,0.1);
}

.categories-overview {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
}

.info-card {
  background: linear-gradient(135deg, #f8fafc, #ffffff);
  border: 2px solid #2D7D4F;
  border-radius: 12px;
  padding: 1.5rem;
  margin-bottom: 2rem;
}

.info-card h3 {
  margin: 0 0 1rem 0;
  color: #1f2937;
}

.info-card p {
  color: #6b7280;
  line-height: 1.6;
  margin-bottom: 1.5rem;
}

.info-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.info-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem;
  background: rgba(45, 125, 79, 0.05);
  border-radius: 8px;
}

.info-icon {
  font-size: 1.5rem;
}

.info-content strong {
  color: #1f2937;
}
</style>
@endpush
