<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#2D7D4F">

  <title>Register - NearU</title>

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">

  <style>
    /* ═══════════════════════════════════════
       RESET & CSS VARIABLES
    ═══════════════════════════════════════ */
    *, *::before, *::after {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      -webkit-tap-highlight-color: transparent;
    }

    :root {
      /* Backgrounds */
      --bg:         #F0F7F2;
      --surface:    #fff;
      --card:       #fff;

      /* Text */
      --t1:         #141F14;
      --t2:         #5E6E5E;

      /* Border */
      --border:     #D6E8DC;

      /* Green palette */
      --green:      #2D7D4F;
      --green-dk:   #1f5c38;
      --green-lt:   #E8F7EE;

      /* Gold palette */
      --gold:       #F2B705;
      --gold-dk:    #c99200;
      --gold-lt:    #FFFBEB;

      /* Shadows */
      --sh:         0 2px 14px rgba(45, 125, 79, .08);
      --sh2:        0 6px 28px rgba(45, 125, 79, .16);

      /* Shared tokens */
      --rad:        18px;
      --transition: .2s ease;
    }

    /* ── BASE ── */
    body {
      font-family: 'DM Sans', sans-serif;
      background: linear-gradient(150deg, #0a1f0e, #1a4d2e, #0d2e48);
      color: var(--t1);
      margin: 0;
      padding: 0;
      min-height: 100vh;
    }

    html, body {
      height: 100%;
      overflow-x: hidden;
    }

    /* ── AUTH PAGES ── */
    .auth-wrap {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 2rem;
    }

    .auth-box {
      background: var(--surface);
      padding: 2.2rem;
      border-radius: 26px;
      max-width: 400px;
      width: 100%;
      margin: 0 auto;
      box-shadow: 0 20px 60px rgba(0, 0, 0, .25);
    }

    .auth-logo { text-align: center; margin-bottom: 1.6rem; }

    .auth-logo h1 {
      font-family: 'Syne', sans-serif;
      font-size: 2.5rem;
      font-weight: 800;
      color: var(--green);
    }
    .auth-logo h1 em { color: var(--gold); font-style: normal; }
    .auth-logo p { color: #6B7280; font-size: .88rem; margin-top: .3rem; }

    /* Input groups */
    .ig { margin-bottom: 1rem; }

    .ig label {
      display: block;
      margin-bottom: .42rem;
      font-weight: 700;
      font-size: .82rem;
      color: #374151;
      text-transform: uppercase;
      letter-spacing: .5px;
    }

    .ig input {
      width: 100%;
      padding: .88rem 1rem;
      border: 2px solid #E5E7EB;
      border-radius: 12px;
      font-family: 'DM Sans', sans-serif;
      font-size: .94rem;
      outline: none;
      color: var(--t1);
      background: var(--surface);
      transition: border var(--transition), box-shadow var(--transition);
    }
    .ig input:focus {
      border-color: var(--green);
      box-shadow: 0 0 0 3px rgba(45, 125, 79, .12);
    }

    /* Auth button */
    .auth-btn {
      width: 100%;
      padding: .95rem;
      background: var(--green);
      color: #fff;
      border: none;
      border-radius: 50px;
      font-size: .97rem;
      font-weight: 700;
      cursor: pointer;
      margin-top: .6rem;
      font-family: 'DM Sans', sans-serif;
      transition: all var(--transition);
      box-shadow: 0 4px 16px rgba(45, 125, 79, .35);
    }
    .auth-btn:hover {
      background: var(--green-dk);
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(45, 125, 79, .45);
    }
    .auth-btn:active { transform: scale(.97); }

    /* Auth helper text */
    .auth-link {
      text-align: center;
      margin-top: 1.2rem;
      color: #6B7280;
      font-size: .87rem;
    }
    .auth-link a {
      color: var(--green);
      font-weight: 700;
      text-decoration: none;
    }
    .auth-link a:hover { text-decoration: underline; }

    /* User type toggle (signup) */
    .utype {
      flex: 1;
      padding: 1.1rem .7rem;
      border: 2px solid #E5E7EB;
      border-radius: 14px;
      text-align: center;
      cursor: pointer;
      transition: all .22s;
      background: var(--surface);
    }
    .utype.sel {
      border-color: var(--green);
      background: var(--green);
      color: #fff;
    }
    .utype.sel div { color: #fff !important; }
    .utype:hover:not(.sel) {
      border-color: var(--green);
      transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 480px) {
      .auth-wrap {
        padding: 1rem;
      }
      .auth-box {
        padding: 1.8rem;
        border-radius: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="auth-wrap"> 
    <div class="auth-box"> 
      <div class="auth-logo">
        <img src="{{ asset('nearu-logo.png') }}" alt="NearU Logo" style="max-width: 200px; margin-top: -1.8rem; margin-bottom: -1rem;">
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
              <div style="font-size:1.7rem;margin-bottom:.3rem; width: 80px;">🎓</div>
              <div style="font-weight:800;font-size:.87rem;">Student</div>
              <div style="font-size:.74rem;color:#6B7280;">Looking for dorm</div>
            </div>
            <div class="utype {{ old('user_type') === 'owner' ? 'sel' : '' }}" onclick="selectUserType('owner')" id="oOpt">
              <div style="font-size:1.7rem;margin-bottom:.3rem; width: 80px;">🏠</div>
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
</body>
</html>