@extends('layouts.owner')

@section('title', 'Edit Listing — NearU')

@push('styles')

<style>
body {
  font-family: Arial, sans-serif;
  background: #f5f5f5;
  color: #333;
}

.edit-container {
  max-width: 600px;
  margin: 20px auto;
  padding: 20px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.edit-header {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-bottom: 25px;
  padding-bottom: 15px;
  border-bottom: 1px solid #eee;
}

.back-link {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  color: #666;
  text-decoration: none;
  font-weight: 600;
  padding: 8px 12px;
  border: 1px solid #dddddd;
  border-radius: 6px;
  transition: all 0.2s;
}
.back-link:hover {
  background: #f8f8f8;
  border-color: #999;
}

.edit-title {
  font-size: 24px;
  font-weight: 800;
  color: #333;
}

.form-group {
  margin-bottom: 20px;
}

.form-label {
  display: block;
  font-size: 14px;
  font-weight: 600;
  color: #555;
  margin-bottom: 6px;
}

.form-input {
  width: 100%;
  padding: 12px 15px;
  border: 2px solid #ddd;
  border-radius: 6px;
  font-size: 15px;
  font-family: inherit;
  transition: border-color 0.2s, box-shadow 0.2s;
}

.form-input:focus {
  outline: none;
  border-color: #4CAF50;
  box-shadow: 0 0 0 3px rgba(76,175,80,0.1);
}

.form-select {
  width: 100%;
  padding: 12px 15px;
  border: 2px solid #ddd;
  border-radius: 6px;
  font-size: 15px;
  font-family: inherit;
  background: white;
  cursor: pointer;
}

.form-actions {
  display: flex;
  gap: 12px;
  margin-top: 30px;
  padding-top: 20px;
  border-top: 1px solid #eee;
}

.btn {
  padding: 12px 24px;
  border-radius: 6px;
  font-size: 15px;
  font-weight: 600;
  border: none;
  cursor: pointer;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.btn-cancel {
  background: transparent;
  color: #666;
  border: 2px solid #ddd;
}

.btn-cancel:hover {
  background: #f8f8f8;
  border-color: #999;
}

.btn-submit {
  background: #4CAF50;
  color: white;
  flex: 1;
}

.btn-submit:hover {
  background: #45a049;
}

.alert-error {
  background: #fff5f5;
  border: 1px solid #fed7d7;
  color: #e53e3e;
  padding: 15px;
  border-radius: 6px;
  margin-bottom: 20px;
}

.alert-error ul {
  margin: 10px 0 0 20px;
}

.success-notification {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: #4CAF50;
  color: white;
  padding: 20px 30px;
  border-radius: 8px;
  box-shadow: 0 4px 20px rgba(76,175,80,0.3);
  z-index: 1000;
  display: none;
  align-items: center;
  min-width: 300px;
  text-align: center;
  font-weight: 600;
  font-size: 16px;
}
</style>
@endpush

@section('content')

<!-- Success Notification -->
@if(session('success'))
<div id="successNotification" class="success-notification">
  {{ session('success') }}
</div>
@endif

<div class="edit-container">
  <div class="edit-header">
    <a href="{{ route('owner.listings.index') }}" class="back-btn" style="background: #2D7D4F; color: white; padding: 8px 10px; border-radius: 8px; text-decoration: none; display: inline-block; font-weight: 600; transition: background 0.3s ease;">← Back</a>
    <h1 class="edit-title">Edit Listing</h1>
  </div>

  @if($errors->any())
    <div class="alert-error">
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

    <div class="form-group">
      <label class="form-label" for="street">Street Address</label>
      <select name="street" id="street" class="form-select" required>
        <option value="">Select street...</option>
        <option value="Mars" {{ old('street', $listing->street) == 'Mars' ? 'selected' : '' }}>Mars</option>
        <option value="Jupiter" {{ old('street', $listing->street) == 'Jupiter' ? 'selected' : '' }}>Jupiter</option>
        <option value="Earth" {{ old('street', $listing->street) == 'Earth' ? 'selected' : '' }}>Earth</option>
        <option value="Venus" {{ old('street', $listing->street) == 'Venus' ? 'selected' : '' }}>Venus</option>
        <option value="Saturn" {{ old('street', $listing->street) == 'Saturn' ? 'selected' : '' }}>Saturn</option>
        <option value="Other" {{ old('street', $listing->street) == 'Other' ? 'selected' : '' }}>Other</option>
      </select>
    </div>

    <div class="form-group">
      <label class="form-label" for="price">Monthly Price (₱)</label>
      <input type="number" name="price" id="price" class="form-input" 
             value="{{ old('price', $listing->price) }}" 
             placeholder="0.00" min="0" required>
    </div>

    <div class="form-group">
      <label class="form-label" for="type">Type</label>
      <select name="type" id="type" class="form-select" required>
        <option value="Room" {{ old('type', $listing->type) == 'Room' ? 'selected' : '' }}>Room</option>
        <option value="Bedspace" {{ old('type', $listing->type) == 'Bedspace' ? 'selected' : '' }}>Bedspace</option>
        <option value="Studio" {{ old('type', $listing->type) == 'Studio' ? 'selected' : '' }}>Studio</option>
      </select>
    </div>

    <div class="form-group">
      <label class="form-label" for="status">Status</label>
      <select name="status" id="status" class="form-select" required>
        <option value="available" {{ strtolower(old('status', $listing->status)) == 'available' ? 'selected' : '' }}>Available</option>
        <option value="unavailable" {{ strtolower(old('status', $listing->status)) == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
      </select>
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

@if(session('success'))
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
@endif