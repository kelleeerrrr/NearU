@extends('layouts.app')

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

.back-btn{
  background:var(--green);
  color:#fff;
  border:none;
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

.password-container{
  position:relative;
}

.password-toggle{
  position:absolute;
  right:10px;
  top:50%;
  transform:translateY(-50%);
  background:none;
  border:none;
  color:var(--t2);
  cursor:pointer;
  font-size:1.2rem;
  padding:5px;
}

.password-toggle:hover{
  color:var(--t1);
}

.save-btn{
  background:var(--blue);
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
</style>
@endpush

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active">

    <div class="cs">
      {{-- TOP ACTIONS --}}
      <div class="top-actions">

        {{-- BACK BUTTON --}}
        <button class="icon-btn back-btn" onclick="window.location.href='/profile'">
          ← Back
        </button>

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
          @if(auth()->user()->profile_photo_path)
            <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Profile">
          @else
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
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

        <div class="edit-form">
          <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div>
              <label class="input-label" for="name">Full Name</label>
              <input type="text" id="name" name="name" class="input" value="{{ auth()->user()->name }}" placeholder="Juan Dela Cruz" required>
            </div>
            
            <div>
              <label class="input-label" for="email">Email Address</label>
              <input type="email" id="email" name="email" class="input" value="{{ auth()->user()->email }}" placeholder="juan.delacruz@email.com" required>
            </div>
            
            <div>
              <label class="input-label" for="phone">Phone Number</label>
              <input type="text" id="phone" name="phone" class="input" value="{{ auth()->user()->phone }}" placeholder="09123456789">
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
                <input type="password" id="current_password" name="current_password" class="input" placeholder="••••••••" required>
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

        <div style="margin-top: 1rem; padding: 1rem; background: var(--bg); border-radius: 12px; font-size: 0.85rem; color: var(--t2);">
          <strong>💡 Tip:</strong> Use a strong password with at least 8 characters, including uppercase, lowercase, numbers, and symbols.
        </div>
      </div>

      {{-- DANGER ZONE --}}
      <div class="section" style="border-color: var(--red);">
        <div class="section-title" style="color: var(--red);">⚠️ Danger Zone</div>
        
        <div style="padding: 1rem; background: var(--red-lt); border-radius: 12px; margin-bottom: 1rem;">
          <p style="color: var(--red); font-weight: 600; margin-bottom: 0.5rem;">Delete Account</p>
          <p style="font-size: 0.85rem; color: var(--t2);">Once you delete your account, there is no going back. Please be certain.</p>
        </div>
        
        <button class="icon-btn" style="background: var(--red); color: white; border: none;" onclick="if(confirm('Are you sure you want to delete your account? This action cannot be undone.')) window.location.href='/profile/delete'">
          🗑️ Delete Account
        </button>
      </div>

    </div>{{-- /.cs --}}
  </div>{{-- /.screen --}}

  {{-- BOTTOM NAV --}}
  <div class="bot-nav">
    <div class="nav-i" id="nav-home" onclick="window.location.href='/'"><span>🏠</span><div>Home</div></div>
    <div class="nav-i" id="nav-map" onclick="window.location.href='{{ route('student.map') }}'"><span>📍</span><div>Map</div></div>
    <div class="nav-i" id="nav-messages" onclick="window.location.href='/messages'"><span>💬</span><div>Messages</div></div>
    <div class="nav-i active" id="nav-profile" onclick="window.location.href='/profile'"><span>👤</span><div>Profile</div></div>
  </div>

</div>{{-- /.wrap --}}

@endsection

@push('scripts')
<script>
// PHOTO UPLOAD
document.getElementById('photoInput')?.addEventListener('change', function(e){
  const file = e.target.files[0];
  if(!file) return;

  const formData = new FormData();
  formData.append('photo', file);
  formData.append('_token', '{{ csrf_token() }}');

  document.getElementById('uploadStatus').innerText = "Uploading...";

  const reader = new FileReader();
  reader.onload = function(){
    const avatar = document.querySelector('.avatar-display');
    if(avatar) {
      avatar.innerHTML = `<img src="${reader.result}" alt="Profile">`;
    }
  }
  reader.readAsDataURL(file);

  fetch("{{ route('profile.photo.update') }}", {
    method: "POST",
    body: formData
  })
  .then(response => {
    console.log('Response status:', response.status);
    if (response.ok) {
      // Try to parse JSON, but if it fails, assume success since photo was uploaded
      return response.json().catch(() => ({ success: true }));
    } else if (response.status === 422) {
      // Validation error
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
      document.getElementById('uploadStatus').innerText = "✅ Photo updated successfully!";
      setTimeout(() => {
        document.getElementById('uploadStatus').innerText = "";
      }, 3000);
    } else {
      document.getElementById('uploadStatus').innerText = "❌ Upload failed.";
    }
  })
  .catch(error => {
    console.error('Upload error:', error);
    document.getElementById('uploadStatus').innerText = "❌ " + error.message;
  })
  });


// PASSWORD VISIBILITY TOGGLE
function togglePassword(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const toggleButton = passwordField.nextElementSibling;
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleButton.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
    } else {
        passwordField.type = 'password';
        toggleButton.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';
    }
}

// DARK MODE
function toggleDarkMode() {
    document.body.classList.toggle('dark');

    if(document.body.classList.contains('dark')) {
        localStorage.setItem('theme', 'dark');
    } else {
        localStorage.setItem('theme', 'light');
    }
}

// load saved theme
window.onload = function () {
    if(localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('dark');
    }
};
</script>
@endpush
