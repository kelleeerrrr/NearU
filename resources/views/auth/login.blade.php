@extends('layouts.app')

@section('title', 'Login - NearU')

@section('content')
<div class="auth-wrap">
  <div class="auth-box">

    {{-- Logo --}}
    <div class="auth-logo">
      <h1>Near<em>U</em></h1>
      <p>Welcome back! 👋</p>
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

    {{-- Demo tip --}}
    <div class="auth-demo-tip">
      💡 <strong>Demo tip:</strong> Use any email with "owner"
      (e.g. <em>owner@example.com</em>) to access the
      <strong>Owner Dashboard</strong>. Any other email opens the student view.
    </div>

  </div>
</div>
@endsection