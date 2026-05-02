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
    <button class="ib"
        onclick="window.location.href='{{ Route::has('notifications.index') ? route('notifications.index') : url('/notifications') }}'">

      🔔

      <span id="notifBadge" style="
        position:absolute;
        top:5px;
        right:8px;
        background:red;
        color:white;
        font-size:10px;
        padding:2px 5px;
        border-radius:50%;
        display:none;
      ">0</span>

    </button>

  </div>

</div>