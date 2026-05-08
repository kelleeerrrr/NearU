@extends('layouts.app')

@section('title', 'Manage Users - Admin')

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <div class="page-header">
        <div class="header-top">
          <h2>👥 Manage Users</h2>
          <a href="{{ route('admin.dashboard') }}" class="btn-back">← Back</a>
        </div>
      </div>
      
      <!-- User Statistics -->
      <div class="stats-container">
        <div class="stat-card">
          <div class="stat-icon">👥</div>
          <div class="stat-content">
            <div class="stat-number">{{ $stats['total_users'] }}</div>
            <div class="stat-label">Total Users</div>
          </div>
        </div>
        
        <div class="stat-card">
          <div class="stat-icon">🎓</div>
          <div class="stat-content">
            <div class="stat-number">{{ $stats['students'] }}</div>
            <div class="stat-label">Students</div>
          </div>
        </div>
        
        <div class="stat-card">
          <div class="stat-icon">🏠</div>
          <div class="stat-content">
            <div class="stat-number">{{ $stats['owners'] }}</div>
            <div class="stat-label">Owners</div>
          </div>
        </div>
        
        <div class="stat-card">
          <div class="stat-icon">🛡️</div>
          <div class="stat-content">
            <div class="stat-number">{{ $stats['admins'] }}</div>
            <div class="stat-label">Admins</div>
          </div>
        </div>
      </div>

      <!-- Category Filter -->
      <div class="category-filter">
        <label>Filter by Category:</label>
        <select name="category" id="categoryFilter" onchange="filterByCategory()">
          <option value="">All Users</option>
          <option value="student">🎓 Students</option>
          <option value="owner">🏠 Owners</option>
          <option value="admin">🛡️ Admins</option>
        </select>
      </div>

      <!-- Users List -->
      <div class="users-list">
        @forelse($users as $user)
          <div class="user-card">
            <div class="user-info">
              <div class="user-avatar">
                {{ substr($user->name, 0, 1) }}
              </div>
              <div class="user-details">
                <div class="user-name">{{ $user->name }}</div>
                <div class="user-email">{{ $user->email }}</div>
                <div class="user-meta">
                  <span class="user-type {{ $user->user_type }}">{{ $user->user_type }}</span>
                  @if($user->user_type === 'owner')
                    <span class="verification-status {{ $user->verification_status ?? 'unverified' }}">
                      {{ $user->verification_status ?? 'unverified' }}
                    </span>
                  @endif
                  <span class="user-category">{{ $user->user_type }}</span>
                  <span class="user-date">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
              </div>
            </div>
            <div class="user-actions">
              <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-blue">View</a>
              <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-green">Edit</a>
              @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline; margin: 0; padding: 0;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-red" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                </form>
              @endif
            </div>
          </div>
        @empty
          <div class="empty-state">
            <div class="empty-icon">👥</div>
            <h3>No users found</h3>
            <p>Try adjusting your search criteria</p>
          </div>
        @endforelse
      </div>

      <!-- Pagination -->
      <div class="pagination">
        {{ $users->links() }}
      </div>
    </div>
  </div>

  @include('partials.footer')
</div>
@endsection

@push('styles')
<style>
/* Wrap container for admin users page - override layout styles */
.wrap {
  width: 100% !important;
  max-width: 100% !important;
  margin: 0 auto !important;
  padding: 5rem 1rem 1rem 1rem !important;
  box-sizing: border-box !important;
  box-shadow: none !important;
  background: transparent !important;
  min-height: 100vh !important;
}

/* Admin specific container */
.screen.active .cs {
  max-width: 900px !important;
  margin: 0 auto !important;
  width: 100% !important;
  padding: 0 1rem !important;
  background: white !important;
  border-radius: 20px !important;
  box-shadow: 0 4px 16px rgba(45, 125, 79, 0.1) !important;
}

/* Extend white background to cover header area */
.screen.active {
  background: white !important;
  padding-top: 0 !important;
}

/* Statistics Cards Container */
.stats-container {
  display: grid !important;
  grid-template-columns: 1fr 1fr !important;
  grid-template-rows: 1fr 1fr !important;
  gap: 1rem !important;
  margin-bottom: 1.5rem !important;
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
  font-size: 1.8rem !important;
  font-weight: 700 !important;
  color: #1f2937 !important;
  margin-bottom: 0.25rem !important;
  line-height: 1 !important;
}

/* Category Filter Styles */
.category-filter {
  margin: 1.5rem 0 !important;
  padding: 1.5rem !important;
  background: linear-gradient(135deg, #ffffff, #f8fafc) !important;
  border: 2px solid #2D7D4F !important;
  border-radius: 20px !important;
  box-shadow: 0 4px 16px rgba(45, 125, 79, 0.1) !important;
}

.category-filter label {
  font-weight: 700 !important;
  margin-bottom: 0.75rem !important;
  color: #2D7D4F !important;
  display: block !important;
  font-size: 1rem !important;
  text-transform: uppercase !important;
  letter-spacing: 0.5px !important;
}

.category-filter select {
  padding: 0.75rem 1rem !important;
  border: 2px solid #2D7D4F !important;
  border-radius: 12px !important;
  background: white !important;
  font-size: 0.95rem !important;
  color: #374151 !important;
  width: 100% !important;
  max-width: 400px !important;
  font-weight: 600 !important;
  transition: all 0.3s ease !important;
  cursor: pointer !important;
}

.category-filter select:hover {
  border-color: #1f5c38 !important;
  box-shadow: 0 2px 8px rgba(45, 125, 79, 0.2) !important;
}

.category-filter select:focus {
  outline: none !important;
  border-color: #16a34a !important;
  box-shadow: 0 0 0 3px rgba(45, 125, 79, 0.1) !important;
}

.stat-label {
  font-size: 0.9rem !important;
  color: #6b7280 !important;
  font-weight: 600 !important;
  text-transform: uppercase !important;
  letter-spacing: 0.5px !important;
}

.screen,
.screen.active,
.cs {
  width: 100% !important;
  max-width: 100% !important;
}

.users-list {
  display: grid !important;
  grid-template-columns: 1fr !important;
  gap: 1rem !important;
  width: 100% !important;
  align-items: stretch;
}

.user-card {
  width: 100% !important;
  min-width: 0 !important;
  box-sizing: border-box;
}

.user-info {
  min-width: 0;
}

.user-card {
  background: linear-gradient(135deg, #ffffff, #f8fafc);
  border: 2px solid #2D7D4F;
  border-radius: 24px;
  padding: 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 8px 32px rgba(45, 125, 79, 0.15);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  gap: 1.5rem;
  position: relative;
  overflow: hidden;
}

.user-card::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 100%;
  height: 100%;
  background: linear-gradient(45deg, rgba(45, 125, 79, 0.03), rgba(45, 125, 79, 0.08));
  border-radius: 50%;
  transition: all 0.4s ease;
}

.user-card::after {
  content: '';
  position: absolute;
  top: 10px;
  right: 10px;
  width: 8px;
  height: 8px;
  background: linear-gradient(135deg, #6ee7b7, #34d399);
  border-radius: 50%;
  box-shadow: 0 2px 8px rgba(45, 125, 79, 0.3);
  transition: all 0.3s ease;
}

.user-card:hover::before {
  top: -30%;
  right: -30%;
}

.user-card:hover::after {
  transform: scale(1.5);
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.5);
}

.user-card:hover {
  transform: translateY(-6px) scale(1.02);
  box-shadow: 0 16px 40px rgba(45, 125, 79, 0.25);
  border-color: #1f5c38;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  flex: 1;
}

.user-avatar {
  width: 56px;
  height: 56px;
  border-radius: 20px;
  background: linear-gradient(135deg, #2D7D4F, #16a34a);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 1.3rem;
  box-shadow: 0 6px 16px rgba(45, 125, 79, 0.3);
  flex-shrink: 0;
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
}

.user-avatar::after {
  content: '';
  position: absolute;
  top: -2px;
  left: -2px;
  right: -2px;
  bottom: -2px;
  background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.15) 50%, transparent 70%);
  border-radius: 20px;
  z-index: -1;
  transition: all 0.3s ease;
}

.user-card:hover .user-avatar {
  transform: rotate(5deg) scale(1.1);
  box-shadow: 0 8px 20px rgba(45, 125, 79, 0.4);
}

.user-details {
  flex: 1;
}

.user-name {
  font-weight: 700;
  font-size: 1.2rem;
  color: #1f2937;
  margin-bottom: 0.4rem;
  letter-spacing: 0.3px;
  position: relative;
  z-index: 1;
}

.user-email {
  color: #6b7280;
  font-size: 0.9rem;
  margin-bottom: 0.5rem;
}

.user-meta {
  display: flex;
  gap: 0.5rem;
  align-items: center;
  flex-wrap: wrap;
  margin-top: 0.25rem;
}

.user-type {
  padding: 0.4rem 0.8rem;
  border-radius: 16px;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.6px;
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
}

.user-type::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2));
  transition: left 0.3s ease;
}

.user-type:hover::before {
  left: 100%;
}

.user-type.student {
  background: #dbeafe;
  color: #1d4ed8;
}

.user-type.owner {
  background: #fef3c7;
  color: #92400E;
}

.user-type.admin {
  background: #dcfce7;
  color: #166534;
}

.verification-status {
  padding: 0.3rem 0.8rem;
  border-radius: 12px;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: capitalize;
  letter-spacing: 0.5px;
  position: relative;
  overflow: hidden;
}

.verification-status::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15));
  transition: left 0.3s ease;
}

.verification-status:hover::before {
  left: 100%;
}

.verification-status.verified {
  background: #dcfce7;
  color: #166534;
}

.verification-status.under_review {
  background: #fef3c7;
  color: #92400E;
}

.verification-status.rejected {
  background: #fecaca;
  color: #dc2626;
}

.verification-status.unverified {
  background: #f3f4f6;
  color: #6b7280;
}

.user-date {
  color: #9ca3af;
  font-size: 0.7rem;
}

.user-category {
  padding: 0.2rem 0.5rem;
  border-radius: 8px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: capitalize;
  letter-spacing: 0.5px;
  position: relative;
  overflow: hidden;
}

.user-category::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(45, 125, 79, 0.1));
  transition: left 0.3s ease;
}

.user-category:hover::before {
  left: 100%;
}

.user-actions {
  display: flex;
  flex-direction: row;
  gap: 0.5rem;
  align-items: center;
  flex-shrink: 0;
  width: auto;
}

.btn-sm {
  padding: 0.8rem 1.2rem;
  font-size: 0.85rem;
  border-radius: 16px;
  text-decoration: none;
  font-weight: 600;
  border: none;
  cursor: pointer;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  white-space: nowrap;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
  width: 80px;
  height: 40px;
  box-sizing: border-box;
  transform: translateZ(0);
}

.btn-sm::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2));
  transition: left 0.3s ease;
}

.btn-sm:hover::before {
  left: 100%;
}

.btn-blue {
  background: #3B82F6 !important;
  color: white !important;
  border: 2px solid #3B82F6 !important;
}

.btn-blue:hover {
  background: #2563EB !important;
  border-color: #2563EB !important;
}

.btn-green {
  background: #16a34a;
  color: white;
  border: 2px solid #16a34a;
}

.btn-green:hover {
  background: #15803d;
  border-color: #15803d;
}

.btn-red {
  background: #dc2626;
  color: white;
  border: 2px solid #dc2626;
}

.btn-red:hover {
  background: #b91c1c;
  border-color: #b91c1c;
}

.empty-state {
  text-align: center;
  padding: 3rem 1rem;
  color: #6b7280;
}

.empty-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.empty-state h3 {
  margin-bottom: 0.5rem;
  color: #374151;
}

.pagination {
  margin-top: 2rem;
  text-align: center;
}

.page-header {
  margin-bottom: 1rem;
}

.header-top {
  display: flex;
  align-items: center;
  gap: 1rem;
  justify-content: flex-start;
  position: relative;
}

.header-top .btn-back {
  position: absolute;
  right: 0;
  top: 0;
}

.header-top h2 {
  margin: 0;
  color: #374151;
  font-size: 1.5rem;
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

/* Tablet Responsive Styles */
@media (max-width: 1024px) {
  .wrap {
    max-width: 100% !important;
    padding: 5rem 1rem 1rem 1rem !important;
  }
}

/* Mobile Responsive Styles */
@media (max-width: 768px) {
  .wrap {
    padding: 4rem 0.75rem 0.75rem 0.75rem !important;
  }
  
  .screen.active .cs {
    max-width: 100% !important;
    padding: 0 0.5rem !important;
    margin: 0 !important;
  }
  
  .stats-container {
    grid-template-columns: 1fr 1fr !important;
    grid-template-rows: 1fr 1fr !important;
    gap: 0.75rem !important;
  }
  
  .stat-card {
    padding: 1rem !important;
    min-height: 80px !important;
  }
  
  .stat-icon {
    width: 40px !important;
    height: 40px !important;
    font-size: 1.5rem !important;
  }
  
  .category-filter {
    padding: 1rem !important;
    margin: 1rem 0 !important;
  }
  
  .category-filter label {
    font-size: 0.9rem !important;
    margin-bottom: 0.5rem !important;
  }
  
  .category-filter select {
    max-width: 100% !important;
    padding: 0.6rem 0.8rem !important;
    font-size: 0.9rem !important;
  }
  
  .stat-number {
    font-size: 1.5rem !important;
  }
  
  .stat-label {
    font-size: 0.8rem !important;
  }
  
  .users-list {
    grid-template-columns: 1fr !important;
    margin: 0 !important;
    width: 100% !important;
    gap: 0.75rem !important;
  }
  
  .user-card {
    padding: 1rem !important;
    flex-direction: column !important;
    align-items: flex-start !important;
    gap: 1rem !important;
  }
  
  .user-info {
    width: 100% !important;
  }
  
  .user-actions {
    width: auto !important;
    flex-direction: row !important;
    gap: 0.4rem !important;
  }
  
  .btn-sm {
    width: 70px !important;
    height: 36px !important;
    padding: 0.6rem 0.8rem !important;
    font-size: 0.8rem !important;
  }
}

@media (max-width: 480px) {
  .wrap {
    padding: 3rem 0.5rem 0.5rem 0.5rem !important;
    max-width: 100% !important;
  }
  
  .screen.active .cs {
    max-width: 100% !important;
    padding: 0 0.25rem !important;
    margin: 0 !important;
  }
  
  .users-list {
    margin: 0 !important;
    width: 100% !important;
    gap: 0.5rem !important;
  }
  
  .header-top {
    flex-direction: column !important;
    align-items: flex-start !important;
    gap: 0.75rem !important;
  }
  
  .user-card {
    padding: 0.75rem !important;
  }
  
  .user-actions {
    width: auto !important;
    flex-direction: row !important;
    gap: 0.3rem !important;
  }
  
  .btn-sm {
    width: 60px !important;
    height: 32px !important;
    padding: 0.5rem 0.6rem !important;
    font-size: 0.75rem !important;
  }
}
</style>
@endpush

@push('scripts')
<script>
function filterByCategory() {
  var categorySelect = document.getElementById('categoryFilter');
  var category = categorySelect.value;
  
  // Redirect to filtered results
  if (category) {
    window.location.href = '{{ route("admin.users.index") }}?category=' + category;
  } else {
    window.location.href = '{{ route("admin.users.index") }}';
  }
}

// Set initial category from URL parameter
document.addEventListener('DOMContentLoaded', function() {
  var urlParams = new URLSearchParams(window.location.search);
  var category = urlParams.get('category');
  
  if (category) {
    document.getElementById('categoryFilter').value = category;
  }
  
});
</script>
@endpush