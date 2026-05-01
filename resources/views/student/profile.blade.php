@extends('layouts.app')

@section('title', 'Profile - NearU')

@section('content')
<div class="wrap">
  @include('partials.navbar')

  <div class="screen active" style="max-width: 600px; margin: auto; padding: 2rem;">

    <!-- PROFILE HEADER -->
    <div class="u-hero" style="display:flex; align-items:center; flex-direction:column; text-align:center; padding-bottom:1.5rem; border-bottom:1px solid #ddd;">

      <div class="u-av" style="width:70px;height:70px;border-radius:50%;background:#eee;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:bold;overflow:hidden;">

        @if(auth()->user()->profile_photo_path)
          <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}"
               style="width:100%;height:100%;object-fit:cover;">
        @else
          {{ substr(auth()->user()->name, 0, 1) }}
        @endif

      </div>

      <div style="margin-top:1rem;">
        <h3>{{ auth()->user()->name }}</h3>
        <p>Phone: {{ auth()->user()->phone ?? 'Not set' }}</p>
        <p>Member Since: {{ auth()->user()->created_at->format('M Y') }}</p>
      </div>

    </div>

    <!-- EDIT BUTTON -->
    <div style="margin-top:1.5rem;text-align:center;">
      <button onclick="toggleEditProfile()"
              style="padding:.7rem 1.2rem;background:#007bff;color:#fff;border:none;border-radius:6px;">
        Edit Profile
      </button>
    </div>

    <!-- EDIT PANEL -->
    <div id="editProfileContainer" style="display:none;margin-top:2rem;border:1px solid #ddd;padding:1rem;border-radius:8px;">

      <h3>Edit Profile</h3>

      <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom:1rem;">
          <label>Name</label>
          <input type="text" name="name" value="{{ auth()->user()->name }}"
                 style="width:100%;padding:.5rem;">
        </div>

        <div style="margin-bottom:1rem;">
          <label>Email</label>
          <input type="email" name="email" value="{{ auth()->user()->email }}"
                 style="width:100%;padding:.5rem;">
        </div>

        <div style="margin-bottom:1rem;">
          <label>Phone</label>
          <input type="text" name="phone" value="{{ auth()->user()->phone }}"
                 style="width:100%;padding:.5rem;">
        </div>

        <button type="submit"
                style="background:green;color:#fff;padding:.5rem 1rem;border:none;border-radius:5px;">
          Save Changes
        </button>
      </form>

      <!-- PHOTO UPLOAD -->
      <div style="margin-top:2rem;">
        <h4>Profile Photo (Auto Upload)</h4>

        <input type="file" id="photoInput" accept="image/*">

        <p id="uploadStatus" style="font-size:12px;color:gray;"></p>

        <div style="margin-top:1rem;">
          <img id="photoPreview"
               src="{{ auth()->user()->profile_photo_path ? asset('storage/' . auth()->user()->profile_photo_path) : '' }}"
               style="width:120px;height:120px;border-radius:50%;object-fit:cover;">
        </div>
      </div>

    </div>

    <!-- MENU -->
    <div style="margin-top:2rem;">

      <div onclick="window.location.href='/saved'"
           style="display:flex;justify-content:space-between;align-items:center;padding:1rem 0;cursor:pointer;border-bottom:1px solid #f0f0f0;">
        <div>❤️ Saved Listings</div>
        <div>→</div>
      </div>

      <div onclick="window.location.href='/visits'"
           style="display:flex;justify-content:space-between;align-items:center;padding:1rem 0;cursor:pointer;border-bottom:1px solid #f0f0f0;">
        <div>📅 Scheduled Visits</div>
        <div>→</div>
      </div>

      <!-- CHECKLIST FIXED -->
      <div onclick="window.location.href='{{ route('checklist') }}'"
           style="display:flex;justify-content:space-between;align-items:center;padding:1rem 0;cursor:pointer;border-bottom:1px solid #f0f0f0;">
        <div>📋 Move-in Checklist</div>
        <div>→</div>
      </div>

      <!-- DARK MODE -->
      <div onclick="toggleDarkMode()"
           style="display:flex;justify-content:space-between;align-items:center;padding:1rem 0;cursor:pointer;border-bottom:1px solid #f0f0f0;">
        <div>🌙 Dark Mode</div>
        <div>→</div>
      </div>

      <!-- LOGOUT -->
      <div onclick="logoutUser()"
           style="display:flex;justify-content:space-between;align-items:center;padding:1rem 0;cursor:pointer;color:red;">
        <div>🚪 Log Out</div>
        <div>→</div>
      </div>

    </div>

  </div>

  @include('partials.footer')
</div>

<!-- LOGOUT FORM -->
<form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">
  @csrf
</form>

@endsection

@push('scripts')
<script>

// EDIT PROFILE
function toggleEditProfile(){
  const el = document.getElementById('editProfileContainer');
  if(el) el.style.display = (el.style.display === 'block') ? 'none' : 'block';
}

// PHOTO UPLOAD
document.getElementById('photoInput')?.addEventListener('change', function(e){

  const file = e.target.files[0];
  if(!file) return;

  const formData = new FormData();
  formData.append('profile_photo', file);
  formData.append('_token', '{{ csrf_token() }}');

  document.getElementById('uploadStatus').innerText = "Uploading...";

  const reader = new FileReader();
  reader.onload = function(){
    document.getElementById('photoPreview').src = reader.result;
  }
  reader.readAsDataURL(file);

  fetch("{{ route('profile.photo.update') }}", {
    method: "POST",
    body: formData
  })
  .then(() => {
    document.getElementById('uploadStatus').innerText = "Uploaded successfully!";
  })
  .catch(() => {
    document.getElementById('uploadStatus').innerText = "Upload failed.";
  });

});

// LOGOUT
function logoutUser(){
  document.getElementById('logout-form').submit();
}

// DARK MODE
function toggleDarkMode(){
  document.body.classList.toggle('dark');
  localStorage.setItem('darkMode', document.body.classList.contains('dark'));
}

// LOAD DARK MODE
if(localStorage.getItem('darkMode') === 'true'){
  document.body.classList.add('dark');
}

</script>
@endpush