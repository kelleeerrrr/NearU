@extends('layouts.app')

@section('title', 'Edit User - Admin')

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <div class="page-header">
        <div class="header-top">
          <h2>✏️ Edit User</h2>
          <a href="{{ route('admin.users.show', $user->id) }}" class="btn-back">← Back</a>
        </div>
      </div>

      <div class="edit-card">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
          @csrf
          @method('PUT')
          
          <div class="form-section">
            <h3>📋 Basic Information</h3>
            
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" id="name" name="name" value="{{ $user->name }}" required>
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" id="email" name="email" value="{{ $user->email }}" required>
            </div>

            <div class="form-group">
              <label for="user_type">User Type</label>
              <select id="user_type" name="user_type" required>
                <option value="student" {{ $user->user_type === 'student' ? 'selected' : '' }}>Student</option>
                <option value="owner" {{ $user->user_type === 'owner' ? 'selected' : '' }}>Owner</option>
                <option value="admin" {{ $user->user_type === 'admin' ? 'selected' : '' }}>Admin</option>
              </select>
            </div>

            @if($user->user_type === 'owner')
              <div class="form-group">
                <label for="verification_status">Verification Status</label>
                <select id="verification_status" name="verification_status">
                  <option value="unverified" {{ ($user->verification_status ?? 'unverified') === 'unverified' ? 'selected' : '' }}>Unverified</option>
                  <option value="under_review" {{ ($user->verification_status ?? 'unverified') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                  <option value="verified" {{ ($user->verification_status ?? 'unverified') === 'verified' ? 'selected' : '' }}>Verified</option>
                  <option value="rejected" {{ ($user->verification_status ?? 'unverified') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
              </div>
            @endif
          </div>

          <div class="form-section">
            <h3>🔐 Security</h3>
            
            <div class="form-group">
              <label for="password">New Password (leave blank to keep current)</label>
              <input type="password" id="password" name="password" placeholder="Enter new password">
              <small>Leave this field empty if you don't want to change the password</small>
            </div>

            <div class="form-group">
              <label for="password_confirmation">Confirm New Password</label>
              <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
            </div>
          </div>

          <div class="form-actions">
            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-gray">Cancel</a>
            <button type="submit" class="btn btn-green">Update User</button>
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
  margin-left: auto;
}

.btn-back:hover {
  background: #1f5c38;
  border-color: #1f5c38;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.4);
  text-decoration: none;
}

.edit-card {
  background: white;
  border: 2px solid #2D7D4F;
  border-radius: 16px;
  padding: 1.5rem;
  box-shadow: 0 4px 16px rgba(45, 125, 79, 0.1);
  width: 100%;
  max-width: none;
}

.form-section {
  margin-bottom: 1.5rem;
}

.form-section h3 {
  margin: 0 0 1rem 0;
  color: #374151;
  padding-bottom: 0.5rem;
  border-bottom: 2px solid #e5e7eb;
  font-size: 1.1rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.form-group {
  margin-bottom: 1rem;
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
  padding: 0.75rem 1rem;
  border: 2px solid #e5e7eb;
  border-radius: 8px;
  font-size: 1rem;
  transition: all 0.2s;
  background: #f9fafb;
}

.form-group input:focus,
.form-group select:focus {
  outline: none;
  border-color: #2D7D4F;
  background: white;
  box-shadow: 0 0 0 3px rgba(45, 125, 79, 0.1);
}

.form-group small {
  display: block;
  margin-top: 0.25rem;
  color: #6b7280;
  font-size: 0.8rem;
}

.form-actions {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  padding-top: 1.5rem;
  border-top: 2px solid #e5e7eb;
  margin-top: 1.5rem;
}

.btn {
  padding: 0.8rem 1.5rem;
  border-radius: 12px;
  text-decoration: none;
  font-weight: 600;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 0.9rem;
  text-align: center;
  height: 48px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  box-sizing: border-box;
}

.btn-gray {
  background: #6b7280;
  color: white;
  border: 2px solid #6b7280;
}

.btn-gray:hover {
  background: #4b5563;
  border-color: #4b5563;
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(107, 114, 128, 0.3);
}

.btn-green {
  background: #16a34a;
  color: white;
  border: 2px solid #16a34a;
}

.btn-green:hover {
  background: #15803d;
  border-color: #15803d;
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(22, 163, 74, 0.3);
}
</style>
@endpush
