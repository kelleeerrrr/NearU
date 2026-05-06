@extends('layouts.app')

@section('title', 'Register - NearU')

@section('content')
<!-- SIGNUP PAGE -->
<div id="signupPage" class="screen active">
  <div class="auth-wrap">
    <div class="auth-box">
      <div class="auth-logo">
        <h1>Near<em>U</em></h1>
        <p>Create your account</p>
      </div>

      @if($errors->any())
      <div style="background:#FEE2E2;border:1px solid #FECACA;border-radius:8px;padding:12px;margin-bottom:1rem;color:#991B1B;">
        <ul style="margin:0;padding:0;list-style:none;">
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <form method="POST" action="{{ route('register.post') }}">
        @csrf
        <div class="ig">
          <label>I am a...</label>
          <div style="display:flex;gap:.8rem;">
            <div class="utype {{ old('user_type') === 'student' ? 'sel' : '' }}" onclick="selectUserType('student')" id="sOpt">
              <div style="font-size:1.7rem;margin-bottom:.3rem;">🎓</div>
              <div style="font-weight:800;font-size:.87rem;">Student</div>
              <div style="font-size:.74rem;color:#6B7280;">Looking for dorm</div>
            </div>
            <div class="utype {{ old('user_type') === 'owner' ? 'sel' : '' }}" onclick="selectUserType('owner')" id="oOpt">
              <div style="font-size:1.7rem;margin-bottom:.3rem;">🏠</div>
              <div style="font-weight:800;font-size:.87rem;">Owner</div>
              <div style="font-size:.74rem;color:#6B7280;">Listing property</div>
            </div>
          </div>
          <input type="hidden" name="user_type" id="userTypeInput" value="{{ old('user_type', 'student') }}" required>
        </div>

        <div class="ig">
          <label>Full Name</label>
          <input type="text" name="name" value="{{ old('name') }}" placeholder="Juan Dela Cruz" required>
        </div>
        <div class="ig">
          <label>Email</label>
          <input type="email" name="email" value="{{ old('email') }}" placeholder="your@email.com" required>
        </div>
        <div class="ig">
          <label>Phone</label>
          <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="09XX XXX XXXX" pattern="[0-9]{11}" maxlength="11" oninput="if(this.value.length >= 11) this.value = this.value.slice(0, 11)" required>
        </div>
        <div class="ig">
          <label>Password</label>
          <input type="password" name="password" placeholder="••••••" required>
        </div>
        <div class="ig">
          <label>Confirm Password</label>
          <input type="password" name="password_confirmation" placeholder="••••••••" required>
        </div>

        <button class="auth-btn" type="submit">Create Account →</button>
      </form>

      <div class="auth-link">Have an account? <a href="{{ route('login') }}">Login</a></div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function selectUserType(type) {
  console.log('Selecting user type:', type);
  
  // Remove selected class from both options
  document.getElementById('sOpt').classList.remove('sel');
  document.getElementById('oOpt').classList.remove('sel');

  // Add selected class to chosen option
  if (type === 'student') {
    document.getElementById('sOpt').classList.add('sel');
  } else {
    document.getElementById('oOpt').classList.add('sel');
  }

  // Set hidden input value
  var userTypeInput = document.getElementById('userTypeInput');
  userTypeInput.value = type;
  console.log('User type input set to:', userTypeInput.value);
}

function validateForm() {
  var form = document.querySelector('form[action*="register"]');
  var email = document.querySelector('input[name="email"]').value;
  var phone = document.querySelector('input[name="phone"]').value;
  var password = document.querySelector('input[name="password"]').value;
  var passwordConfirmation = document.querySelector('input[name="password_confirmation"]').value;
  var name = document.querySelector('input[name="name"]').value;
  
  // Email validation (must contain @)
  if (!email.includes('@')) {
    alert('Please enter a valid email address with @ symbol');
    return false;
  }
  
  // Phone validation (11 digits only)
  if (!/^[0-9]{11}$/.test(phone)) {
    alert('Phone number must be exactly 11 digits');
    return false;
  }
  
  // Password validation (optional - no constraints)
  if (password.length < 1) {
    alert('Password is required');
    return false;
  }
  
  // Password confirmation
  if (password !== passwordConfirmation) {
    alert('Passwords do not match');
    return false;
  }
  
  // Name validation (required)
  if (name.trim() === '') {
    alert('Name field is required');
    return false;
  }
  
  // All fields required
  if (!email || !phone || !password || !passwordConfirmation || !name) {
    alert('All fields are required');
    return false;
  }
  
  return true;
}

// Initialize selection on page load
document.addEventListener('DOMContentLoaded', function() {
  var initialType = document.getElementById('userTypeInput').value || 'student';
  console.log('Initial user type:', initialType);
  
  // Add form validation to submit button
  var form = document.querySelector('form[action*="register"]');
  if (form) {
    form.addEventListener('submit', function(e) {
      if (!validateForm()) {
        e.preventDefault();
      }
    });
  }
  
  selectUserType(initialType);
});

// Fallback initialization
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', function() {
    selectUserType('student');
  });
} else {
  selectUserType('student');
}

// Form validation before submission
document.querySelector('form').addEventListener('submit', function(e) {
  var userType = document.getElementById('userTypeInput').value;
  console.log('Form submitting with user type:', userType);
  
  if (!userType || (userType !== 'student' && userType !== 'owner')) {
    e.preventDefault();
    alert('Please select whether you are a Student or Owner');
    return false;
  }
});
</script>
@endpush