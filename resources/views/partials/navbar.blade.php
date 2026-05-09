<!-- TOP BAR -->
<div class="top-bar">

  <!-- BACK BUTTON -->
  <button class="bb" id="backBtn" style="display:none;" onclick="history.back()">
    ← Back
  </button>

  <!-- LOGO -->
  <div class="tb-logo">
    <img src="{{ asset('storage/nearu-logo.png') }}" alt="NearU Logo" style="height: 40px; margin-left: -1rem; margin-right: -1rem; vertical-align: middle;">
    Near<em>U</em>
  </div>

  <!-- TITLE (optional page title) -->
  <div class="tb-title" id="tbTitle" style="display:none;"></div>

  <!-- RIGHT SIDE BUTTONS -->
  <div class="tb-right">

    <!-- NOTIFICATIONS -->
    @auth
      @if(auth()->user()->user_type === 'admin')
        <a href="{{ route('admin.notifications.index') }}" class="ib notification-icon" style="position:relative;">
            🔔
            @php
              $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
            @endphp
            @if($unreadCount > 0)
              <span class="notification-badge" style="position:absolute;top:-5px;right:-5px;background:#e74c3c;color:white;border-radius:50%;width:18px;height:18px;font-size:11px;font-weight:bold;display:flex;align-items:center;justify-content:center;">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
            @endif
        </a>
      @elseif(auth()->user()->user_type === 'owner')
        <a href="{{ route('notifications.owner') }}" class="ib notification-icon" style="position:relative;">
            🔔
            @php
              $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
            @endphp
            @if($unreadCount > 0)
              <span class="notification-badge" style="position:absolute;top:-5px;right:-5px;background:#e74c3c;color:white;border-radius:50%;width:18px;height:18px;font-size:11px;font-weight:bold;display:flex;align-items:center;justify-content:center;">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
            @endif
        </a>
      @else
        <a href="{{ route('notifications.index') }}" class="ib notification-icon" style="position:relative;">
            🔔
            @php
              $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
            @endphp
            @if($unreadCount > 0)
              <span class="notification-badge" style="position:absolute;top:-5px;right:-5px;background:#e74c3c;color:white;border-radius:50%;width:18px;height:18px;font-size:11px;font-weight:bold;display:flex;align-items:center;justify-content:center;">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
            @endif
        </a>
      @endif
    @endauth

  </div>

</div>