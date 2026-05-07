@extends('layouts.app')

@section('title', 'Notifications - Admin')

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <h2>🔔 Notifications</h2>
      
      <!-- Notification Actions -->
      <div class="notification-actions">
        <a href="{{ route('admin.notifications.create') }}" class="btn btn-green">
          <span>➕</span> Create Notification
        </a>
        <form action="{{ route('admin.notifications.markAllRead') }}" method="POST" style="display: inline;">
          @csrf
          <button type="submit" class="btn btn-blue">
            <span>✓</span> Mark All Read
          </button>
        </form>
      </div>

      <!-- Notifications List -->
      <div class="notifications-list">
        @forelse($notifications)
          <div class="empty-state">
            <div class="empty-icon">🔔</div>
            <h3>No notifications</h3>
            <p>Create notifications to keep users informed about new listings, users, and system updates.</p>
          </div>
        @else
          @foreach($notifications as $notification)
            <div class="notification-item {{ $notification->is_read ? 'read' : 'unread' }}" data-notification-id="{{ $notification->id }}">
              <div class="notification-icon">
                @switch($notification->type)
                  @case('new_user')
                    <span>👤</span>
                  @case('new_listing')
                    <span>🏠</span>
                  @case('system_alert')
                    <span>⚠️</span>
                  @case('info')
                    <span>ℹ️</span>
                  @default
                    <span>📢</span>
                @endswitch
              </div>
              
              <div class="notification-content">
                <div class="notification-header">
                  <div class="notification-title">{{ $notification->title }}</div>
                  <div class="notification-meta">
                    <span class="notification-type">{{ $notification->type }}</span>
                    <span class="notification-date">{{ $notification->created_at->format('M d, Y') }}</span>
                  </div>
                </div>
                <div class="notification-message">{{ $notification->message }}</div>
              </div>
              
              @if(!$notification->is_read)
                <div class="unread-indicator"></div>
              @endif
            </div>
          @endforeach
      </div>

      <!-- Pagination -->
      <div class="pagination">
        {{ $notifications->links() }}
      </div>
    </div>
  </div>

  @include('partials.footer')
</div>
@endsection

@push('styles')
<style>
.notification-actions {
  display: flex;
  gap: 1rem;
  margin-bottom: 1.5rem;
  flex-wrap: wrap;
}

.btn {
  padding: 0.5rem 1rem;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.85rem;
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

.btn-blue {
  background: #2D7D4F;
  color: white;
  border: 2px solid #2D7D4F;
}

.btn-blue:hover {
  background: #1f5c38;
  border-color: #1f5c38;
}

.notifications-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.notification-item {
  background: white;
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  padding: 1rem;
  display: flex;
  gap: 1rem;
  cursor: pointer;
  transition: all 0.2s;
  position: relative;
}

.notification-item:hover {
  border-color: #2D7D4F;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(45, 125, 79, 0.15);
}

.notification-item.unread {
  border-color: #2D7D4F;
  background: linear-gradient(135deg, #f0fdf4, #ffffff);
}

.notification-item.read {
  opacity: 0.7;
}

.notification-icon {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, #2D7D4F, #1f5c38);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  flex-shrink: 0;
}

.notification-content {
  flex: 1;
}

.notification-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 0.5rem;
}

.notification-title {
  font-weight: 700;
  color: #374151;
  font-size: 1rem;
}

.notification-meta {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}

.notification-type {
  padding: 0.25rem 0.5rem;
  border-radius: 6px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.notification-type.new_user {
  background: #dbeafe;
  color: #1d4ed8;
}

.notification-type.new_listing {
  background: #dcfce7;
  color: #166534;
}

.notification-type.system_alert {
  background: #fee2e2;
  color: #dc2626;
}

.notification-type.info {
  background: #e0e7ff;
  color: #3730a3;
}

.notification-date {
  color: #6b7280;
  font-size: 0.8rem;
}

.notification-message {
  color: #374151;
  line-height: 1.4;
}

.unread-indicator {
  position: absolute;
  top: -5px;
  right: -5px;
  width: 12px;
  height: 12px;
  background: #ef4444;
  border-radius: 50%;
  border: 2px solid white;
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
  margin: 0 0 0.5rem 0;
  color: #374151;
}

.empty-state p {
  margin: 0;
  line-height: 1.5;
}

.pagination {
  margin-top: 2rem;
  text-align: center;
}
</style>
@endpush
