<!-- TOP BAR -->
<div class="top-bar">

  <button class="bb" id="backBtn" style="display:none;" onclick="history.back()">
    ← Back
  </button>

  <div class="tb-logo">Near<em>U</em></div>

  <div class="tb-title" id="tbTitle" style="display:none;"></div>

  <div class="tb-right">

    <!-- Notifications Button (NOW REAL LINK INSTEAD OF ALERT) -->
    <button class="ib" onclick="window.location.href='{{ route('notifications.index') ?? '/notifications' }}'">
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