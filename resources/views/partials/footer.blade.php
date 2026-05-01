<!-- BOTTOM NAV — 4 items (Home, Map, Messages, Profile) -->
<div class="bot-nav">

  <!-- HOME -->
  <div class="nav-i" id="nav-home"
       onclick="window.location.href='{{ route('home') }}'">
    <span>🏠</span>
    <div>Home</div>
  </div>

  <!-- MAP -->
  <div class="nav-i" id="nav-map"
       onclick="window.location.href='{{ route('dorms.map') }}'">
    <span>📍</span>
    <div>Map</div>
  </div>

  <!-- MESSAGES -->
  <div class="nav-i" id="nav-messages"
       onclick="window.location.href='{{ route('messages.index') }}'">
    <span>💬</span>
    <div>Messages</div>
  </div>

  <!-- PROFILE (FIXED ROUTING) -->
  @php
      $profileRoute = auth()->check() && auth()->user()->user_type === 'owner'
          ? route('owner.account')
          : route('profile');
  @endphp

  <div class="nav-i" id="nav-profile"
       onclick="window.location.href='{{ $profileRoute }}'">
    <span>👤</span>
    <div>Profile</div>
  </div>

</div>

<!-- ACTIVE TAB HIGHLIGHT FIX -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    const path = window.location.pathname;

    document.querySelectorAll('.nav-i').forEach(el => {
        el.classList.remove('on');
    });

    // HOME
    if (path === "/student/home" || path === "/") {
        document.getElementById('nav-home')?.classList.add('on');
    }

    // MAP
    else if (path.startsWith("/map")) {
        document.getElementById('nav-map')?.classList.add('on');
    }

    // MESSAGES
    else if (path.startsWith("/messages")) {
        document.getElementById('nav-messages')?.classList.add('on');
    }

    // PROFILE (FIXED)
    else if (
        path.startsWith("/profile") ||
        path.startsWith("/owner/account")
    ) {
        document.getElementById('nav-profile')?.classList.add('on');
    }

});
</script>