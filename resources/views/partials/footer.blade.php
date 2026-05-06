<!-- BOTTOM NAV — 4 items (Home, Map, Messages, Profile) -->
<div class="bot-nav">

  <!-- HOME -->
  <div class="nav-i" id="nav-home"
       onclick="window.location.href='{{ 
           auth()->user()->user_type === 'admin' 
               ? route('admin.dashboard') 
               : (auth()->user()->user_type === 'owner' 
                   ? route('owner.dashboard') 
                   : route('student.home'))
       }}'">
    <span>🏠</span>
    <div>Home</div>
  </div>

  <!-- MAP -->
  @if(auth()->user()->user_type !== 'admin')
  <div class="nav-i" id="nav-map"
       onclick="window.location.href='{{ 
           auth()->user()->user_type === 'owner' 
               ? '#' 
               : route('student.map')
       }}'">
    <span>📍</span>
    <div>Map</div>
  </div>
  @endif

  <!-- MESSAGES -->
  <div class="nav-i" id="nav-messages"
       onclick="window.location.href='{{ 
           auth()->user()->user_type === 'admin' 
               ? route('admin.owner-verifications.index')
               : (auth()->user()->user_type === 'owner' 
                   ? route('owner.inquiries.index')
                   : route('messages.index'))
       }}'">
    <span>{{ auth()->user()->user_type === 'admin' ? '📋' : '💬' }}</span>
    <div>{{ auth()->user()->user_type === 'admin' ? 'Verifications' : 'Messages' }}</div>
  </div>

  <!-- PROFILE -->
  <div class="nav-i" id="nav-profile"
       onclick="window.location.href='{{ 
           auth()->user()->user_type === 'admin' 
               ? route('admin.profile')
               : (auth()->user()->user_type === 'owner' 
                   ? route('owner.account')
                   : route('profile'))
       }}'">
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
    if (path === "/student/home" || path === "/admin/dashboard" || path === "/owner/dashboard" || path === "/") {
        document.getElementById('nav-home')?.classList.add('on');
    }

    // MAP
    else if (path.startsWith("/student/map")) {
        document.getElementById('nav-map')?.classList.add('on');
    }

    // MESSAGES/VERIFICATIONS
    else if (
        path.startsWith("/messages") || 
        path.startsWith("/owner/inquiries") ||
        path.startsWith("/admin/owner-verifications")
    ) {
        document.getElementById('nav-messages')?.classList.add('on');
    }

    // PROFILE
    else if (
        path.startsWith("/profile") ||
        path.startsWith("/owner/account") ||
        path === "/admin/dashboard" ||
        path === "/admin/profile"
    ) {
        document.getElementById('nav-profile')?.classList.add('on');
    }

});
</script>