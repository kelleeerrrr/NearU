@extends('layouts.app')

@section('title', 'Create Notification - Admin')

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <h2>➕ Create Notification</h2>
      
      <form action="{{ route('admin.notifications.store') }}" method="POST" class="notification-form">
        @csrf
        @if($errors->any())
          <div class="error-messages">
            @foreach($errors->all() as $error)
              <div class="error-item">{{ $error }}</div>
            @endforeach
          </div>
        @endif

        <div class="form-group">
          <label for="title">Title</label>
          <input type="text" id="title" name="title" value="{{ old('title') }}" required>
        </div>

        <div class="form-group">
          <label for="message">Message</label>
          <textarea id="message" name="message" rows="4" required>{{ old('message') }}</textarea>
        </div>

        <div class="form-group">
          <label for="type">Type</label>
          <select id="type" name="type" required>
            <option value="new_user" {{ old('type') === 'new_user' ? 'selected' : '' }}>👤 New User</option>
            <option value="new_listing" {{ old('type') === 'new_listing' ? 'selected' : '' }}>🏠 New Listing</option>
            <option value="system_alert" {{ old('type') === 'system_alert' ? 'selected' : '' }}>⚠️ System Alert</option>
            <option value="info" {{ old('type') === 'info' ? 'selected' : '' }}>ℹ️ Information</option>
          </select>
        </div>

        <div class="form-group">
          <label for="priority">Priority</label>
          <select id="priority" name="priority" required>
            <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>🔵 Low Priority</option>
            <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>🟡 Medium Priority</option>
            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>🔴 High Priority</option>
          </select>
        </div>

        <div class="form-actions">
          <a href="{{ route('admin.notifications.index') }}" class="btn btn-gray">Cancel</a>
          <button type="submit" class="btn btn-green">Create Notification</button>
        </div>
      </form>
    </div>
  </div>

  @include('partials.footer')
</div>
@endsection

@push('styles')
<style>
.notification-form {
  background: white;
  border: 2px solid #2D7D4F;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.15);
  max-width: 600px;
}

.form-group {
  margin-bottom: 1.25rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 600;
  color: #374151;
}

.form-group input,
.form-group textarea,
.form-group select {
  width: 100%;
  padding: 0.75rem;
  border: 2px solid #e5e7eb;
  border-radius: 8px;
  font-size: 1rem;
  transition: border-color 0.2s;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
  outline: none;
  border-color: #2D7D4F;
  box-shadow: 0 0 0 3px rgba(45, 125, 79, 0.1);
}

.form-actions {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
  margin-top: 1.5rem;
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

.btn-gray {
  background: #6b7280;
  color: white;
  border: 2px solid #6b7280;
}

.btn-gray:hover {
  background: #4b5563;
  border-color: #4b5563;
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

.error-messages {
  margin-bottom: 1rem;
}

.error-item {
  background: #fef2f2;
  border: 1px solid #dc2626;
  color: #dc2626;
  padding: 0.75rem;
  border-radius: 6px;
  margin-bottom: 0.5rem;
}
</style>
@endpush
