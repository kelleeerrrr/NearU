<!-- TOP BAR -->
<div class="top-bar">

  <!-- BACK BUTTON -->
  <button class="bb" id="backBtn" style="display:none;" onclick="history.back()">
    ← Back
  </button>

  <!-- LOGO -->
  <div class="tb-logo">Near<em>U</em></div>

  <!-- TITLE (optional page title) -->
  <div class="tb-title" id="tbTitle" style="display:none;"></div>

  <!-- RIGHT SIDE BUTTONS -->
  <div class="tb-right">

    <!-- NOTIFICATIONS -->
    <a href="{{ route('admin.notifications.index') }}" class="ib">
        🔔
    </a>

  </div>

</div>