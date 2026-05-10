@extends('layouts.app')

@section('title', 'User Details - Admin')

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <div class="page-header">
        <div class="header-top">
          <h2>👤 User Details</h2>
          <a href="{{ route('admin.users.index') }}" class="btn-back">← Back</a>
        </div>
      </div>

      <div class="user-detail-card">
        <div class="user-header">
          <div class="user-avatar-large force-circle">
            @if($user->profile_photo_path ?? $user->avatar)
              <img src="{{ Storage::url($user->profile_photo_path ?? $user->avatar) }}" alt="{{ $user->name }}" style="width: 85px; height: 85px; object-fit: cover; border-radius: 50%; display: block;">
            @else
              {{ substr($user->name, 0, 1) }}
            @endif
          </div>
          <div class="user-basic-info">
            <h3>{{ $user->name }}</h3>
            <p class="user-email">{{ $user->email }}</p>
            <div class="user-badges">
              <span class="user-type {{ $user->user_type }}">{{ $user->user_type }}</span>
              @if($user->user_type === 'owner')
                <span class="verification-status {{ $user->verification_status ?? 'unverified' }}">
                  {{ $user->verification_status ?? 'unverified' }}
                </span>
              @endif
            </div>
          </div>
        </div>

        <div class="user-details-grid">
          <div class="detail-section">
            <h4>📋 Account Information</h4>
            <div class="detail-item">
              <span class="label">User ID:</span>
              <span class="value">#{{ $user->id }}</span>
            </div>
            <div class="detail-item">
              <span class="label">Email:</span>
              <span class="value">{{ $user->email }}</span>
            </div>
            <div class="detail-item">
              <span class="label">User Type:</span>
              <span class="value">{{ $user->user_type }}</span>
            </div>
            @if($user->user_type === 'owner')
              <div class="detail-item">
                <span class="label">Verification Status:</span>
                <span class="value">{{ $user->verification_status ?? 'unverified' }}</span>
              </div>
            @endif
            <div class="detail-item">
              <span class="label">Joined:</span>
              <span class="value">{{ $user->created_at->format('F d, Y') }}</span>
            </div>
            <div class="detail-item">
              <span class="label">Last Updated:</span>
              <span class="value">{{ $user->updated_at->format('F d, Y') }}</span>
            </div>
          </div>

          @if($user->user_type === 'owner' && $user->dormListings->count() > 0)
            <div class="detail-section">
              <h4>🏠 Listings ({{ $user->dormListings->count() }})</h4>
              <div class="listings-list">
                @foreach($user->dormListings as $listing)
                  <div class="listing-item">
                    <div class="listing-info">
                      <div class="listing-street">{{ $listing->street }}</div>
                      <div class="listing-meta">
                        <span class="listing-type">{{ $listing->type }}</span>
                        <span class="listing-price">₱{{ number_format($listing->price, 0) }}/mo</span>
                        <span class="listing-status {{ $listing->status }}">{{ $listing->status }}</span>
                      </div>
                    </div>
                    <a href="{{ route('dorms.show', $listing->id) }}" class="btn btn-sm btn-blue" target="_blank">View</a>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
    
    <div class="user-actions-section">
      <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-green">Edit User</a>
      @if($user->id !== auth()->id())
        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-red" onclick="return confirm('Are you sure you want to delete this user?')">Delete User</button>
        </form>
      @endif
    </div>
  </div>

  @include('partials.footer')
</div>
@endsection

@push('styles')
<style>
.page-header {
  margin-bottom: 2rem;
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
  align-self: center;
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

.user-detail-card {
  background: white;
  border: 2px solid #2D7D4F;
  border-radius: 16px;
  padding: 1.5rem;
  box-shadow: 0 4px 16px rgba(45, 125, 79, 0.1);
}

.user-header {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
  margin-bottom: 1.5rem;
}

.user-avatar-large {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: linear-gradient(135deg, #2D7D4F, #1f5c38);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 2rem;
}

.user-basic-info {
  flex: 1;
}

.user-basic-info h3 {
  margin: 0 0 0.5rem 0;
  color: #1f2937;
}

.user-email {
  color: #6b7280;
  margin: 0 0 0.5rem 0;
}

.user-badges {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}

.user-actions-section {
  margin-top: 0.25rem;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  justify-content: center;
  max-width: 400px;
  margin-left: auto;
  margin-right: auto;
}

.user-actions-section .btn {
  padding: 0.8rem 1.2rem;
  font-size: 0.9rem;
  border-radius: 12px;
  text-decoration: none;
  font-weight: 600;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
  text-align: center;
  height: 48px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  box-sizing: border-box;
}

.user-actions-section .btn-green {
  background: #16a34a;
  color: white;
  border: 2px solid #16a34a;
}

.user-actions-section .btn-green:hover {
  background: #15803d;
  border-color: #15803d;
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(22, 163, 74, 0.3);
}

.user-actions-section .btn-red {
  background: #dc2626;
  color: white;
  border: 2px solid #dc2626;
}

.user-actions-section .btn-red:hover {
  background: #b91c1c;
  border-color: #b91c1c;
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(220, 38, 38, 0.3);
}

.user-actions-section form {
  margin: 0;
  padding: 0;
  border: none;
  background: none;
  display: block;
  height: 48px;
}

.user-actions-section form button {
  width: 100%;
  height: 100%;
  margin: 0;
}

.user-details-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
}

.detail-section h4 {
  margin: 0 0 1rem 0;
  color: #374151;
}

.detail-item {
  display: flex;
  justify-content: space-between;
  padding: 0.5rem 0;
  border-bottom: 1px solid #f3f4f6;
}

.detail-item:last-child {
  border-bottom: none;
}

.label {
  font-weight: 600;
  color: #6b7280;
}

.value {
  color: #374151;
}

.listings-list {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.listing-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem;
  background: #f8fafc;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
}

.listing-info {
  flex: 1;
}

.listing-street {
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 0.25rem;
}

.listing-meta {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}

.listing-type {
  background: #dbeafe;
  color: #1d4ed8;
  padding: 0.2rem 0.4rem;
  border-radius: 4px;
  font-size: 0.7rem;
  font-weight: 600;
}

.listing-price {
  color: #059669;
  font-weight: 600;
  font-size: 0.8rem;
}

.listing-status {
  padding: 0.2rem 0.4rem;
  border-radius: 4px;
  font-size: 0.7rem;
  font-weight: 600;
  text-transform: capitalize;
}

.listing-status.available {
  background: #dcfce7;
  color: #166534;
}

.listing-status.rented {
  background: #fef3c7;
  color: #92400E;
}

.listing-status.unavailable {
  background: #fecaca;
  color: #dc2626;
}

.btn {
  padding: 0.5rem 1rem;
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

.btn-red {
  background: #dc2626;
  color: white;
}

.btn-red:hover {
  background: #b91c1c;
}

.btn-sm {
  padding: 0.4rem 0.8rem;
  font-size: 0.8rem;
}

.btn-blue {
  background: #2563eb;
  color: white;
}

.btn-blue:hover {
  background: #1d4ed8;
}

.user-type {
  padding: 0.3rem 0.6rem;
  border-radius: 6px;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
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
  padding: 0.3rem 0.6rem;
  border-radius: 6px;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: capitalize;
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
</style>
@endpush
