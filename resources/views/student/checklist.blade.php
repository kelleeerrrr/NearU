@extends('layouts.app')

@section('title', 'Move-in Checklist')

@section('content')

<div class="wrap">

  @include('partials.navbar')

  <div class="screen active" style="max-width:700px;margin:auto;padding:1rem;">

    <!-- HEADER -->
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
      <button onclick="history.back()" style="border:none;background:none;font-size:18px;">
        ← Back
      </button>

      <h3 style="margin:0;">📋 Move-in Checklist</h3>

      <button onclick="resetChecklist()" style="border:none;background:#ff4d4d;color:#fff;padding:6px 10px;border-radius:6px;">
        Reset
      </button>
    </div>

    <!-- PROGRESS -->
    <div style="margin-bottom:1rem;">
      <div id="progressText" style="font-size:14px;margin-bottom:5px;">0/0 completed</div>

      <div style="height:8px;background:#eee;border-radius:20px;">
        <div id="progressBar" style="height:8px;width:0%;background:#4CAF50;border-radius:20px;transition:.3s;"></div>
      </div>
    </div>

    <!-- CHECKLIST -->
    <div style="border:1px solid #eee;padding:1rem;border-radius:12px;">

      <!-- SECTION 1 -->
      <h4>📦 Things to Bring</h4>

      <label class="item"><input type="checkbox" data-id="bedsheets"> Bedsheets, pillows, blanket</label>
      <label class="item"><input type="checkbox" data-id="toiletries"> Toiletries (shampoo, soap, toothbrush)</label>
      <label class="item"><input type="checkbox" data-id="hangers"> Clothes hangers & laundry basket</label>
      <label class="item"><input type="checkbox" data-id="lamp"> Study lamp & extension cord</label>
      <label class="item"><input type="checkbox" data-id="lock"> Lock and key for cabinet</label>

      <hr>

      <!-- SECTION 2 -->
      <h4>📄 Documents Needed</h4>

      <label class="item"><input type="checkbox" data-id="id"> Valid ID (2 photocopies)</label>
      <label class="item"><input type="checkbox" data-id="student_id"> Student ID / Enrollment form</label>
      <label class="item"><input type="checkbox" data-id="contact"> Parent/Guardian contact info</label>
      <label class="item"><input type="checkbox" data-id="receipt"> Deposit payment receipt</label>

      <hr>

      <!-- SECTION 3 -->
      <h4>✅ Things to Verify</h4>

      <label class="item"><input type="checkbox" data-id="contract"> Read and sign rental contract</label>
      <label class="item"><input type="checkbox" data-id="photo"> Photograph room condition</label>
      <label class="item"><input type="checkbox" data-id="test"> Test appliances & outlets</label>
      <label class="item"><input type="checkbox" data-id="rules"> Get house rules copy</label>
      <label class="item"><input type="checkbox" data-id="owner"> Save owner's number</label>

    </div>

  </div>

  @include('partials.footer')

</div>

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