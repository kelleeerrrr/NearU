@extends('layouts.owner')

@section('title', 'Edit Listing — NearU')

@push('styles')

<style>
body {
  font-family: 'DM Sans', sans-serif;
  background: #f8f9f7;
  color: var(--text, #1a2e22);
}

/* Sub-header */
.listing-header {
  background: #fff;
  padding: 12px 16px 14px;
  position: sticky;
  top: 57px;
  z-index: 40;
  border-bottom: 1px solid var(--border, #E3ECE6);
}

.listing-header-row {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 12px;
}

.back-btn {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  background: var(--green, #2D7D4F);
  color: #fff;
  border: none;
  padding: .4rem .85rem;
  border-radius: 50px;
  font-size: .8rem;
  font-weight: 700;
  text-decoration: none;
  font-family: 'DM Sans', sans-serif;
  transition: all .15s;
  flex-shrink: 0;
}
.back-btn:hover { background: var(--green-dark, #1f5c3b); }

.listing-header-title {
  font-family: 'Syne', sans-serif;
  font-size: 1rem;
  font-weight: 800;
  color: var(--text, #1a2e22);
}

.content {
  padding: 14px 16px 30px;
}

/* Form sections */
.form-section {
  background: white;
  border-radius: 14px;
  padding: 1.25rem;
  margin-bottom: 1.25rem;
  border: 1px solid var(--border, #E3ECE6);
  box-shadow: 0 2px 8px rgba(45,125,79,0.05);
}

.form-group {
  margin-bottom: 1.25rem;
}

.form-group:last-child {
  margin-bottom: 0;
}

.form-label {
  display: block;
  font-family: 'DM Sans', sans-serif;
  font-size: 0.9rem;
  font-weight: 700;
  color: var(--text, #1a2e22);
  margin-bottom: 0.5rem;
}

.form-input, .form-select {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 2px solid var(--border, #E3ECE6);
  border-radius: 12px;
  font-size: 0.95rem;
  font-family: 'DM Sans', sans-serif;
  background: var(--input-bg, #F7FAF8);
  transition: all 0.3s ease;
  color: var(--text, #1a2e22);
}

.form-input:focus, .form-select:focus {
  outline: none;
  border-color: var(--green, #2D7D4F);
  box-shadow: 0 0 0 3px rgba(45,125,79,0.1);
  background: white;
}

.form-actions {
  display: flex;
  gap: 1rem;
  margin-top: 1.5rem;
  padding: 1.25rem;
  background: white;
  border-radius: 14px;
  border: 1px solid var(--border, #E3ECE6);
  box-shadow: 0 2px 8px rgba(45,125,79,0.05);
}

.btn {
  padding: 0.75rem 1.5rem;
  border-radius: 12px;
  font-size: 0.95rem;
  font-weight: 700;
  font-family: 'DM Sans', sans-serif;
  border: none;
  cursor: pointer;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  min-height: 44px;
}

.btn-cancel {
  background: transparent;
  color: var(--muted, #6C7A73);
  border: 2px solid var(--border, #E3ECE6);
}

.btn-cancel:hover {
  background: var(--input-bg, #F7FAF8);
  border-color: var(--muted, #6C7A73);
  transform: translateY(-2px);
}

.btn-submit {
  background: var(--green, #2D7D4F);
  color: white;
  flex: 1;
  box-shadow: 0 4px 12px rgba(45,125,79,0.2);
}

.btn-submit:hover {
  background: var(--green-dark, #1f5c3b);
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(45,125,79,0.3);
}

.alert-error {
  background: linear-gradient(135deg, #fef2f2, #fee2e2);
  border: 2px solid var(--danger, #C8102E);
  color: var(--danger, #C8102E);
  padding: 1rem;
  border-radius: 12px;
  margin-bottom: 1.5rem;
  font-family: 'DM Sans', sans-serif;
  font-weight: 600;
}

.alert-error ul {
  margin: 0.5rem 0 0 1rem;
}

.success-notification {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: var(--green, #2D7D4F);
  color: white;
  padding: 1.5rem 2rem;
  border-radius: 16px;
  box-shadow: 0 8px 32px rgba(45,125,79,0.3);
  z-index: 1000;
  display: none;
  align-items: center;
  min-width: 300px;
  text-align: center;
  font-family: 'DM Sans', sans-serif;
  font-weight: 700;
  font-size: 1rem;
  border: 2px solid var(--green-dark, #1f5c3b);
}
</style>
@endpush

@section('content')

{{-- ── SUB-HEADER ── --}}
<div class="listing-header">
  <div class="listing-header-row">
    <a href="{{ route('owner.listings.index') }}" class="back-btn">← Back</a>
    <div class="listing-header-title">🏠 Edit Listing</div>
  </div>
</div>

<!-- Success Notification -->
@if(session('success'))
<div id="successNotification" class="success-notification">
  {{ session('success') }}
</div>
@endif

<div class="content">

  @if($errors->any())
    <div class="form-section alert-error">
      <strong>Please fix the following errors:</strong>
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('owner.listings.update', $listing->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Basic Information Section -->
    <div class="form-section">
      <div style="font-family: 'Syne', sans-serif; font-size: 1rem; font-weight: 800; color: var(--text, #1a2e22); margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid var(--green-light, #E9F6EF);">
        📍 Basic Information
      </div>

      <div class="form-group">
        <label class="form-label" for="street">Street Address</label>
        <select name="street" id="streetSelect" class="form-input" required>
          <option value="">Select street...</option>
          <option value="Mars" {{ old('street', explode(' - ', $listing->street)[0]) == 'Mars' ? 'selected' : '' }}>Mars</option>
          <option value="Jupiter" {{ old('street', explode(' - ', $listing->street)[0]) == 'Jupiter' ? 'selected' : '' }}>Jupiter</option>
          <option value="Earth" {{ old('street', explode(' - ', $listing->street)[0]) == 'Earth' ? 'selected' : '' }}>Earth</option>
          <option value="Venus" {{ old('street', explode(' - ', $listing->street)[0]) == 'Venus' ? 'selected' : '' }}>Venus</option>
          <option value="Saturn" {{ old('street', explode(' - ', $listing->street)[0]) == 'Saturn' ? 'selected' : '' }}>Saturn</option>
          <option value="Other" {{ !in_array(old('street', explode(' - ', $listing->street)[0]), ['Mars', 'Jupiter', 'Earth', 'Venus', 'Saturn']) ? 'selected' : '' }}>Other</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label" for="complete_address">Complete Street Address</label>
        <input type="text" name="complete_address" id="completeAddress" class="form-input" 
               value="{{ old('complete_address', isset(explode(' - ', $listing->street)[1]) ? explode(' - ', $listing->street)[1] : '') }}" 
               placeholder="Enter complete street address (e.g., 123 Mars Street, Brgy. Poblacion)..." maxlength="255">
        <div style="color: var(--muted, #6C7A73); font-size: 0.75rem; margin-top: 0.25rem; font-family: 'DM Sans', sans-serif;">
          Add the complete address details including house number, building name, or landmarks.
        </div>
      </div>
    </div>

    <!-- Pricing & Type Section -->
    <div class="form-section">
      <div style="font-family: 'Syne', sans-serif; font-size: 1rem; font-weight: 800; color: var(--text, #1a2e22); margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid var(--green-light, #E9F6EF);">
        💰 Pricing & Type
      </div>

      <div class="form-group">
        <label class="form-label" for="price">Monthly Price (₱)</label>
        <input type="number" name="price" id="price" class="form-input" 
               value="{{ old('price', $listing->price) }}" 
               placeholder="0.00" min="0" required>
      </div>

      <div class="form-group">
        <label class="form-label" for="type">Type</label>
        <select name="type" id="type" class="form-input" required>
          <option value="Room" {{ old('type', $listing->type) == 'Room' ? 'selected' : '' }}>Room</option>
          <option value="Bedspace" {{ old('type', $listing->type) == 'Bedspace' ? 'selected' : '' }}>Bedspace</option>
          <option value="Studio" {{ old('type', $listing->type) == 'Studio' ? 'selected' : '' }}>Studio</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label" for="status">Status</label>
        <select name="status" id="status" class="form-input" required>
          <option value="available" {{ strtolower(old('status', $listing->status)) == 'available' ? 'selected' : '' }}>Available</option>
          <option value="unavailable" {{ strtolower(old('status', $listing->status)) == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
        </select>
      </div>
    </div>

    <div class="form-actions">
      <a href="{{ route('owner.listings.index') }}" class="btn btn-cancel">
        Cancel
      </a>
      <button type="submit" class="btn btn-submit">
        Update Listing
      </button>
    </div>
  </form>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const notification = document.getElementById('successNotification');
    if (notification) {
        // Show notification
        notification.style.display = 'block';
        
        // Auto-hide after 3 seconds
        setTimeout(function() {
            notification.style.display = 'none';
        }, 3000);
    }
});
</script>
@endpush
@if(session('success'))
@endif