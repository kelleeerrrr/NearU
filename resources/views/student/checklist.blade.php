@extends('layouts.app')

@section('title', 'Move-in Checklist')

@push('styles')
<style>
.icon-btn{
  padding:.5rem .8rem;
  border-radius:10px;
  border:1.5px solid var(--border);
  background:var(--card);
  cursor:pointer;
  font-weight:700;
  font-size:.8rem;
}

.back-btn{
  background:var(--green);
  color:#fff;
  border:none;
}
</style>
@endpush

@section('content')

<div class="wrap">

  @include('partials.navbar')

  <div class="screen active">
    <div class="cs">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
        <button class="icon-btn back-btn" onclick="window.location.href='/profile'">
          ← Back
        </button>
        <h2 style="font-size: 1.1rem; font-weight: 800; margin: 0; text-align: center; flex: 1;">📋 Move-in Checklist</h2>
        <div style="width: 60px;"></div>
      </div>

    <!-- PROGRESS -->
    <div style="margin-bottom:1rem;">
      <div id="progressText" style="font-size:14px;margin-bottom:5px;">0/0 completed</div>

      <div style="height:8px;background:#eee;border-radius:20px;">
        <div id="progressBar" style="height:8px;width:0%;background:#4CAF50;border-radius:20px;transition:.3s;"></div>
      </div>
    </div>

    <!-- CHECKLIST SECTIONS -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">

      <!-- SECTION 1: THINGS TO BRING -->
      <div class="checklist-section">
        <h3 style="font-size: 1rem; font-weight: 700; color: var(--green); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
          📦 Things to Bring
        </h3>
        <div style="background: var(--card); border: 1.5px solid var(--border); border-radius: 12px; padding: 1rem;">
          <div style="display: flex; flex-direction: column; gap: 0.8rem;">
            <label class="item"><input type="checkbox" data-id="bedding"> Bedding & pillows</label>
            <label class="item"><input type="checkbox" data-id="clothes"> Clothes & hangers</label>
            <label class="item"><input type="checkbox" data-id="toiletries"> Toiletries & towels</label>
            <label class="item"><input type="checkbox" data-id="kitchen"> Kitchen supplies</label>
            <label class="item"><input type="checkbox" data-id="study"> Study materials</label>
          </div>
        </div>
      </div>

      <!-- SECTION 2: BEFORE MOVING IN -->
      <div class="checklist-section">
        <h3 style="font-size: 1rem; font-weight: 700; color: var(--green); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
          🏠 Before Moving In
        </h3>
        <div style="background: var(--card); border: 1.5px solid var(--border); border-radius: 12px; padding: 1rem;">
          <div style="display: flex; flex-direction: column; gap: 0.8rem;">
            <label class="item"><input type="checkbox" data-id="contract"> Read and sign rental contract</label>
            <label class="item"><input type="checkbox" data-id="photo"> Photograph room condition</label>
            <label class="item"><input type="checkbox" data-id="test"> Test appliances & outlets</label>
            <label class="item"><input type="checkbox" data-id="rules"> Get house rules copy</label>
            <label class="item"><input type="checkbox" data-id="owner"> Save owner's number</label>
          </div>
        </div>
      </div>

      <!-- SECTION 3: AFTER MOVING IN -->
      <div class="checklist-section">
        <h3 style="font-size: 1rem; font-weight: 700; color: var(--green); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
          🎯 After Moving In
        </h3>
        <div style="background: var(--card); border: 1.5px solid var(--border); border-radius: 12px; padding: 1rem;">
          <div style="display: flex; flex-direction: column; gap: 0.8rem;">
            <label class="item"><input type="checkbox" data-id="utilities"> Set up utilities (water, electricity)</label>
            <label class="item"><input type="checkbox" data-id="internet"> Arrange internet connection</label>
            <label class="item"><input type="checkbox" data-id="neighbors"> Introduce yourself to neighbors</label>
            <label class="item"><input type="checkbox" data-id="emergency"> Know emergency exits & procedures</label>
            <label class="item"><input type="checkbox" data-id="mail"> Set up mail forwarding</label>
          </div>
        </div>
      </div>

      <!-- RESET BUTTON SECTION -->
      <div style="text-align: center; margin-top: 1rem;">
        <button onclick="resetChecklist()" style="background: var(--red); color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
          🔄 Reset All Items
        </button>
        <p style="font-size: 0.85rem; color: var(--t2); margin-top: 0.5rem;">This will clear all your checklist progress</p>
      </div>

    </div>

  </div>

    </div>{{-- /.cs --}}
  </div>{{-- /.screen --}}

  {{-- BOTTOM NAV --}}
  <div class="bot-nav">
    <div class="nav-i" id="nav-home" onclick="window.location.href='/'"><span>🏠</span><div>Home</div></div>
    <div class="nav-i" id="nav-map" onclick="window.location.href='{{ route('student.map') }}'"><span>📍</span><div>Map</div></div>
    <div class="nav-i" id="nav-messages" onclick="window.location.href='/messages'"><span>💬</span><div>Messages</div></div>
    <div class="nav-i active" id="nav-profile" onclick="window.location.href='/profile'"><span>👤</span><div>Profile</div></div>
  </div>

</div>{{-- /.wrap --}}

<!-- STYLE -->
<style>
.item{
  display:flex;
  gap:10px;
  padding:8px 0;
  cursor:pointer;
  user-select:none;
}

.item input{
  transform: scale(1.2);
  cursor:pointer;
}

.item.done{
  text-decoration: line-through;
  color: gray;
}
</style>

<!-- SCRIPT -->
<script>

const checkboxes = document.querySelectorAll('input[type="checkbox"]');

function updateProgress(){

  let total = checkboxes.length;
  let done = 0;

  checkboxes.forEach(cb => {

    const key = cb.dataset.id;
    const label = cb.parentElement;

    if(localStorage.getItem(key) === "1"){
      cb.checked = true;
      label.classList.add("done");
      done++;
    } else {
      label.classList.remove("done");
    }

  });

  document.getElementById("progressText").innerText = `${done}/${total} completed`;
  document.getElementById("progressBar").style.width = (done/total)*100 + "%";
}

checkboxes.forEach(cb => {

  const key = cb.dataset.id;

  cb.addEventListener("change", function(){

    localStorage.setItem(key, cb.checked ? "1" : "0");
    updateProgress();

  });

});

function resetChecklist(){
  checkboxes.forEach(cb => {
    localStorage.removeItem(cb.dataset.id);
  });

  updateProgress();
}

updateProgress();

</script>

@endsection