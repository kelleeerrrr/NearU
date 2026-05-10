<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#2D7D4F">

  <title>Login - NearU</title>

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
      align-items: center;
    }

    .auth-box {
      background: var(--surface);
      padding: 2.2rem 2.2rem 1.5rem 2.2rem;
      border-radius: 26px;
      max-width: 400px;
      width: 100%;
      margin: 0 auto;
      box-shadow: 0 20px 60px rgba(0, 0, 0, .25);
      max-height: 90vh;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .auth-logo { 
      text-align: center; 
      margin-bottom: 1.2rem; 
      margin-top: -0.5rem;
    }

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

    /* Auth alerts */
    .auth-alert {
      border-radius: 12px;
      padding: .78rem 1rem;
      margin-bottom: 1rem;
      font-size: .84rem;
      line-height: 1.5;
      display: flex;
      flex-direction: column;
      gap: .28rem;
    }
    .auth-alert--error {
      background: #FEF2F2;
      border: 1.5px solid #FECACA;
      color: #991B1B;
    }
    .auth-alert--success {
      background: #F0FDF4;
      border: 1.5px solid #BBF7D0;
      color: #3D7A4A;
    }

    /* Responsive */
    @media (max-width: 480px) {
      .auth-wrap {
        padding: 1rem;
        justify-content: center;
        align-items: center;
      }
      .auth-box {
        padding: 1.5rem;
        border-radius: 20px;
        max-height: 85vh;
      }
      .auth-logo img {
        max-width: 150px;
      }
    }
  </style>
</head>
<body>
  <div class="auth-wrap">
    <div class="auth-box">

      {{-- Logo --}}
      <div class="auth-logo">
        <img src="{{ asset('nearu-logo.png') }}" alt="NearU Logo" style="max-width: 180px; height: auto;">
        <h1>Near<em>U</em></h1>
        <p>NearU makes your dorm finding easier and greater! 🏠</p>
      </div>

      {{-- Validation Errors --}}
      @if ($errors->any())
        <div class="auth-alert auth-alert--error">
          @foreach ($errors->all() as $error)
            <div class="auth-alert__item">⚠️ {{ $error }}</div>
          @endforeach
        </div>
      @endif

      {{-- Session Status (e.g. after password reset) --}}
      @if (session('status'))
        <div class="auth-alert auth-alert--success">
          ✅ {{ session('status') }}
        </div>
      @endif

      {{-- Registration Success Message --}}
      @if (session('success'))
        <div class="auth-alert auth-alert--success">
          ✅ {{ session('success') }}
        </div>
      @endif

      {{-- Login Form --}}
      <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="ig">
          <label for="email">Email</label>
          <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="your@email.com"
            autocomplete="email"
            required
            autofocus
          >
        </div>

        <div class="ig">
          <label for="password">Password</label>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="••••••••"
            autocomplete="current-password"
            required
          >
        </div>

        <button class="auth-btn" type="submit">Login →</button>
      </form>

      {{-- Sign up link --}}
      <div class="auth-link">
        No account? <a href="{{ route('register') }}">Sign up</a>
      </div>

    </div>
  </div>
</body>
</html>