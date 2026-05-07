@extends('layouts.owner')

@section('title', 'Edit Profile - NearU')

@push('styles')
<style>
/* DARK MODE SUPPORT */
body.dark {
  --card: #1e1e1e;
  --bg: #121212;
  --border: #2a2a2a;
  --t2: #aaa;
}

/* HEADER ACTIONS */
.top-actions{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:1rem;
}

.icon-btn{
  padding:.5rem .8rem;
  border-radius:10px;
  border:1.5px solid var(--border);
  background:var(--card);
  cursor:pointer;
  font-weight:700;
  font-size:.8rem;
}

.logout-btn{
  background:#c0392b;
  color:#fff;
  border:none;
}

.back-btn{
  background:var(--card);
  color:var(--green);
  border:1.5px solid var(--border);
}

/* SECTION */
.section{
  margin-top:1rem;
  background:var(--card);
  border:1.5px solid var(--border);
  border-radius:18px;
  padding:1rem;
  box-shadow:var(--sh);
}

.section-title{
  font-weight:800;
  margin-bottom:1rem;
  font-size:.9rem;
}

/* EDIT FORM */
.edit-form{
  display:flex;
  flex-direction:column;
  gap:.6rem;
}

.input{
  width:100%;
  padding:.7rem;
  border-radius:0;
  border:1.5px solid var(--border);
  font-size:.85rem;
  background:transparent;
  color:inherit;
}

.input-label{
  display:block;
  margin-bottom:0.5rem;
  font-weight:600;
  font-size:.85rem;
  color:var(--t1);
}

.save-btn{
  width:100%;
  background:var(--green);
  color:#fff;
  padding:.7rem;
  border:none;
  border-radius:12px;
  font-weight:800;
  cursor:pointer;
}

/* PHOTO UPLOAD */
.photo-upload{
  margin-top:1rem;
}

.photo-preview{
  width:120px;height:120px;border-radius:50%;
  background:var(--bg);
  display:flex;align-items:center;justify-content:center;
  margin:1rem auto 0;
  overflow:hidden;
  border:3px solid var(--border);
}

.photo-preview img{
  width:100%;height:100%;object-fit:cover;
}

/* AVATAR DISPLAY */
.avatar-display{
  width:100px;height:100px;border-radius:50%;
  background:linear-gradient(135deg,#2D7D4F,#1f5c38);
  display:flex;align-items:center;justify-content:center;
  color:#fff;font-size:2rem;font-weight:800;
  margin:0 auto 1rem;
  overflow:hidden;
}

.avatar-display img{
  width:100%;height:100%;object-fit:cover;
}

/* PASSWORD CONTAINER */
.password-container{
  position:relative;
  display:flex;
  align-items:center;
}

.password-container input{
  padding-right:3rem;
}

.password-toggle{
  position:absolute;
  right:0.5rem;
  background:none;
  border:none;
  cursor:pointer;
  color:var(--t2);
  padding:0.25rem;
}

/* PAGE */
.page{
  padding:1rem;
  max-width:480px;
  margin:0 auto;
}
</style>
@endpush

@php
  // ✅ Use fresh DB data instead of stale session cache
  $user = \App\Models\User::find(auth()->id());
  $status = $user->verification_status ?? 'not_verified';
@endphp

@section('content')
<div class="page">

  {{-- TOP ACTIONS --}}
  <div class="top-actions">

    {{-- DARK MODE --}}
    <button class="icon-btn back-btn" onclick="toggleDark()">
      🌙 Dark Mode
    </button>

    {{-- LOGOUT --}}
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="icon-btn logout-btn">
        🚪 Logout
      </button>
    </form>

  </div>

  {{-- PAGE TITLE --}}
  <div style="text-align: center; margin-bottom: 2rem;">
    <h2 style="font-family: 'Syne', sans-serif; font-size: 1.5rem; color: var(--green);">
      Edit Profile
    </h2>
    <p style="color: var(--t2); margin-top: 0.5rem;">Update your account information</p>
  </div>

  {{-- PROFILE PHOTO SECTION --}}
  <div class="section">
    <div class="section-title">📷 Profile Photo</div>

    <div class="avatar-display">
      @if($user->profile_photo_path)
        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile">
      @else
        {{ strtoupper(substr($user->name, 0, 1)) }}
      @endif
    </div>

    <div style="text-align: center;">
      <input type="file" id="photoInput" accept="image/*" style="display:none;">
      <button type="button" class="icon-btn" onclick="document.getElementById('photoInput').click()">
        Choose New Photo
      </button>
      <p id="uploadStatus" style="font-size:.75rem;color:var(--t2);margin-top:.5rem;"></p>
    </div>
  </div>

  {{-- ACCOUNT INFORMATION SECTION --}}
  <div class="section">
    <div class="section-title">📋 Account Information</div>

    @if(session('success'))
      <div style="background: #E8F7EE; color: #1B5E20; padding: 0.75rem; border-radius: 12px; margin-bottom: 1rem; font-weight: 600;">
        {{ session('success') }}
      </div>
    @endif

    @if($errors->any())
      <div style="background: #FEF2F2; color: #991B1B; padding: 0.75rem; border-radius: 12px; margin-bottom: 1rem; font-weight: 600;">
        @foreach($errors->all() as $error)
          {{ $error }} @if(!$loop->last), @endif
        @endforeach
      </div>
    @endif

    <div class="edit-form">
      <form action="{{ route('owner.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div>
          <label class="input-label" for="name">Full Name</label>
          <input type="text" id="name" name="name" class="input" value="{{ old('name', $user->name) }}" placeholder="Juan Dela Cruz" required>
        </div>

        <div>
          <label class="input-label" for="email">Email Address</label>
          <input type="email" id="email" name="email" class="input" value="{{ old('email', $user->email) }}" placeholder="juan.delacruz@email.com" required>
        </div>

        <div>
          <label class="input-label" for="phone">Phone Number</label>
          <input type="text" id="phone" name="phone" class="input" value="{{ old('phone', $user->phone) }}" placeholder="09123456789">
        </div>

        <button type="submit" class="save-btn" style="margin-top: 1rem;">💾 Save Account Changes</button>
      </form>
    </div>
  </div>

  {{-- SECURITY SECTION --}}
  <div class="section">
    <div class="section-title">🔒 Security Settings</div>

    <div class="edit-form">
      <form action="{{ route('password.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div>
          <label class="input-label" for="current_password">Current Password</label>
          <div class="password-container">
            <input type="password" id="current_password" name="current_password" class="input" placeholder="••••" required>
            <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
            </button>
          </div>
        </div>

        <div>
          <label class="input-label" for="password">New Password</label>
          <div class="password-container">
            <input type="password" id="password" name="password" class="input" placeholder="NewPassword123!" required>
            <button type="button" class="password-toggle" onclick="togglePassword('password')">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
            </button>
          </div>
        </div>

        <div>
          <label class="input-label" for="password_confirmation">Confirm New Password</label>
          <div class="password-container">
            <input type="password" id="password_confirmation" name="password_confirmation" class="input" placeholder="NewPassword123!" required>
            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
            </button>
          </div>
        </div>

        <button type="submit" class="save-btn" style="background: var(--green); margin-top: 1rem;">🔐 Update Password</button>
      </form>
    </div>

    <div style="margin-top: 1rem; padding: 1rem; border-radius: 12px; font-size: 0.85rem; color: var(--t2);">
      <strong>💡 Tip:</strong> Use a strong password with at least 8 characters, including uppercase, lowercase, numbers, and symbols.
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
// PHOTO UPLOAD
// Ensure the event listener is properly attached
document.addEventListener('DOMContentLoaded', function() {
  const photoInput = document.getElementById('photoInput');
  if(photoInput) {
    photoInput.addEventListener('change', function(e){
      console.log('Photo input change event triggered');
      const file = e.target.files[0];
      if(!file) {
        console.log('No file selected');
        return;
      }

      console.log('File selected:', file.name, 'Size:', file.size, 'Type:', file.type);

      const formData = new FormData();
      formData.append('photo', file);
      formData.append('_token', '{{ csrf_token() }}');

      const uploadStatus = document.getElementById('uploadStatus');
      if(uploadStatus) {
        uploadStatus.innerText = "Uploading...";
        uploadStatus.style.color = 'var(--green)';
      }

      const reader = new FileReader();
      reader.onload = function(){
        console.log('FileReader loaded, updating preview');
        const avatar = document.querySelector('.avatar-display');
        if(avatar) {
          avatar.innerHTML = `<img src="${reader.result}" alt="Profile" style="width:100%;height:100%;object-fit:cover;">`;
          console.log('Avatar preview updated');
        }
      }
      reader.readAsDataURL(file);

      console.log('Starting upload to:', "{{ route('owner.profile.photo.update') }}");
      fetch("{{ route('owner.profile.photo.update') }}", {
        method: "POST",
        body: formData
      })
      .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (response.ok) {
          return response.json().catch(() => ({ success: true }));
        } else if (response.status === 422) {
          return response.json().then(data => {
            throw new Error(data.message || 'Validation failed');
          });
        } else {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
      })
      .then(data => {
        console.log('Response data:', data);
        if (data.success) {
          if(uploadStatus) {
            uploadStatus.innerText = "✅ Photo updated successfully!";
            uploadStatus.style.color = '#1B5E20';
          }
          setTimeout(() => {
            if(uploadStatus) {
              uploadStatus.innerText = "";
            }
          }, 3000);
        } else {
          if(uploadStatus) {
            uploadStatus.innerText = "❌ Upload failed.";
            uploadStatus.style.color = '#991B1B';
          }
        }
      })
      .catch(error => {
        console.error('Upload error:', error);
        if(uploadStatus) {
          uploadStatus.innerText = "❌ " + error.message;
          uploadStatus.style.color = '#991B1B';
        }
      });
    });
  } else {
    console.log('Photo input element not found');
  }
});

// PASSWORD VISIBILITY TOGGLE
function togglePassword(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const toggleButton = passwordField.nextElementSibling;

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleButton.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"></path><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';
    } else {
        passwordField.type = 'password';
        toggleButton.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
    }
}
</script>
@endpush