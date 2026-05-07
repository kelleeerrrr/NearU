@extends('layouts.app')

@section('title', 'Create User - Admin')

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <div class="page-header">
        <a href="{{ route('admin.users.index') }}" class="back-link">← Back to Users</a>
        <h2>➕ Create New User</h2>
      </div>

      <div class="create-card">
        <form action="{{ route('admin.users.store') }}" method="POST">
          @csrf
          
          <div class="form-section">
            <h3>📋 Basic Information</h3>
            
            <div class="form-group">
              <label for="name">Name *</label>
              <input type="text" id="name" name="name" value="{{ old('name') }}" required>
              @error('name')
                <span class="error-message">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="email">Email *</label>
              <input type="email" id="email" name="email" value="{{ old('email') }}" required>
              @error('email')
                <span class="error-message">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="phone">Phone</label>
              <input type="tel" id="phone" name="phone" value="{{ old('phone') }}">
              @error('phone')
                <span class="error-message">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="user_type">User Type *</label>
              <select id="user_type" name="user_type" required>
                <option value="">Select User Type</option>
                <option value="student" {{ old('user_type') === 'student' ? 'selected' : '' }}>Student</option>
                <option value="owner" {{ old('user_type') === 'owner' ? 'selected' : '' }}>Owner</option>
                <option value="admin" {{ old('user_type') === 'admin' ? 'selected' : '' }}>Admin</option>
              </select>
              @error('user_type')
                <span class="error-message">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="verification_status">Verification Status</label>
              <select id="verification_status" name="verification_status">
                <option value="not_verified" {{ old('verification_status') === 'not_verified' ? 'selected' : '' }}>Not Verified</option>
                <option value="under_review" {{ old('verification_status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                <option value="approved" {{ old('verification_status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ old('verification_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
              </select>
              @error('verification_status')
                <span class="error-message">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="status">Account Status</label>
              <select id="status" name="status">
                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
              </select>
              @error('status')
                <span class="error-message">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="form-section">
            <h3>🔐 Security</h3>
            
            <div class="form-group">
              <label for="password">Password *</label>
              <input type="password" id="password" name="password" required>
              @error('password')
                <span class="error-message">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="password_confirmation">Confirm Password *</label>
              <input type="password" id="password_confirmation" name="password_confirmation" required>
              @error('password_confirmation')
                <span class="error-message">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="form-actions">
            <a href="{{ route('admin.users.index') }}" class="btn btn-gray">Cancel</a>
            <button type="submit" class="btn btn-green">Create User</button>
          </div>
        </form>
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

.create-card {
  background: white;
  border: 2px solid #2D7D4F;
  border-radius: 16px;
  padding: 2rem;
  box-shadow: 0 4px 16px rgba(45, 125, 79, 0.1);
  max-width: 600px;
}

.form-section {
  margin-bottom: 2rem;
}

.form-section h3 {
  margin: 0 0 1rem 0;
  color: #374151;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid #e5e7eb;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #374151;
}

.form-group input,
.form-group select {
  width: 100%;
  padding: 0.75rem;
  border: 2px solid #e5e7eb;
  border-radius: 8px;
  font-size: 1rem;
  transition: border-color 0.2s;
  box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus {
  outline: none;
  border-color: #2D7D4F;
}

.error-message {
  display: block;
  margin-top: 0.25rem;
  color: #dc2626;
  font-size: 0.8rem;
}

.form-actions {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
  padding-top: 1rem;
  border-top: 1px solid #e5e7eb;
}

.btn {
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 0.9rem;
}

.btn-gray {
  background: #6b7280;
  color: white;
}

.btn-gray:hover {
  background: #4b5563;
}

.btn-green {
  background: #16a34a;
  color: white;
}

.btn-green:hover {
  background: #15803d;
}
</style>
@endpush
