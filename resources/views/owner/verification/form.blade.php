@extends('layouts.owner')

@section('title', 'Owner Verification — NearU')

@push('styles')
<style>
.wrap{
  max-width:480px;
  margin:0 auto;
  font-family:'DM Sans',sans-serif;
  background:#fff;
  min-height:100vh;
}

.top{
  display:flex;
  justify-content:space-between;
  padding:1rem;
  background:#2D7D4F;
  color:#fff;
}

.back{
  background:rgba(255,255,255,.2);
  border:none;
  padding:.4rem .7rem;
  border-radius:10px;
  color:#fff;
  font-weight:700;
  cursor:pointer;
}

.title{font-weight:800;}

.hero{
  padding:1rem;
  text-align:center;
}

.hero h2{font-weight:900;}

.hero p{
  font-size:.8rem;
  color:#5E6E5E;
}

.docs{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:.7rem;
  padding:1rem;
}

.card{
  border:2px dashed #D6E8DC;
  border-radius:14px;
  padding:.9rem;
  text-align:center;
  cursor:pointer;
}

.card:hover{
  background:#E8F7EE;
  border-color:#2D7D4F;
}

.icon{font-size:1.5rem;}
.name{font-size:.75rem;font-weight:800;margin-top:.3rem;}
.desc{font-size:.65rem;color:#5E6E5E;margin-top:.2rem;}
.status{font-size:.65rem;margin-top:.3rem;color:#5E6E5E;}

.progress{padding:1rem;}
.bar{height:8px;background:#eee;border-radius:20px;overflow:hidden;}
.fill{height:100%;width:0%;background:#2D7D4F;transition:.3s;}

.count{text-align:center;font-size:.8rem;margin-top:.5rem;font-weight:700;}

.btn{
  width:calc(100% - 2rem);
  margin:1rem;
  padding:.9rem;
  border:none;
  border-radius:50px;
  background:#2D7D4F;
  color:#fff;
  font-weight:800;
  opacity:.5;
}

.btn.active{opacity:1;}

.warn{
  margin:0 1rem;
  padding:.8rem;
  background:#FFF7ED;
  border:1px solid #FCD34D;
  border-radius:12px;
  font-size:.75rem;
  font-weight:700;
  color:#92400E;
}

#toast{
  position:fixed;
  top:70px;
  left:50%;
  transform:translateX(-50%);
  background:#000;
  color:#fff;
  padding:.6rem 1rem;
  border-radius:50px;
  font-size:.8rem;
  opacity:0;
  transition:.3s;
}

#toast.show{opacity:1;}
</style>
@endpush

@section('content')

<div id="toast"></div>

  <!-- TOP -->
  <div class="top">
    <button class="back" onclick="history.back()">← Back</button>
    <div class="title">📎 Verification</div>
    <div></div>
  </div>

  <!-- HERO -->
  <div class="hero">
    <h2>Complete Verification</h2>
    <p>Upload all required documents</p>
  </div>

  <!-- PROGRESS -->
  <div class="progress">
    <div class="bar">
      <div class="fill" id="fill"></div>
    </div>
    <div class="count" id="count">0 / 6 completed</div>
  </div>

  <!-- WARNING -->
  <div class="warn">
    ⚠️ You cannot add listings until verified.
  </div>

  <!-- DOCS -->
  <div class="docs">

    <div class="card" onclick="upload('id')">
      <div class="icon">🪪</div>
      <div class="name">Government ID</div>
      <div class="desc">Driver's License, SSS, PhilSys</div>
      <div class="status" id="id">Not uploaded</div>
    </div>

    <div class="card" onclick="upload('selfie')">
      <div class="icon">🤳</div>
      <div class="name">Selfie with ID</div>
      <div class="desc">Clear photo holding your ID</div>
      <div class="status" id="selfie">Not uploaded</div>
    </div>

    <div class="card" onclick="upload('birth')">
      <div class="icon">📄</div>
      <div class="name">Birth Certificate</div>
      <div class="desc">PSA-issued</div>
      <div class="status" id="birth">Not uploaded</div>
    </div>

    <div class="card" onclick="upload('property')">
      <div class="icon">🏠</div>
      <div class="name">Property Proof</div>
      <div class="desc">Title / Tax / Deed</div>
      <div class="status" id="property">Not uploaded</div>
    </div>

    <div class="card" onclick="upload('utility')">
      <div class="icon">💡</div>
      <div class="name">Utility Bill</div>
      <div class="desc">Electric / Water / Internet</div>
      <div class="status" id="utility">Not uploaded</div>
    </div>

    <div class="card" onclick="upload('barangay')">
      <div class="icon">📋</div>
      <div class="name">Barangay Clearance</div>
      <div class="desc">Issued by barangay</div>
      <div class="status" id="barangay">Not uploaded</div>
    </div>

  </div>

  <button id="btn" class="btn" disabled onclick="submitDocs()">
    📤 Submit for Verification
  </button>

</div>

<input type="file" id="file" hidden onchange="fileChange(event)">

@endsection

@push('scripts')
<script>

let current = null;
let uploaded = {};

// CSRF
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function upload(key){
  current = key;
  document.getElementById('file').click();
}

function fileChange(e){
  const file = e.target.files[0];
  if(!file) return;

  let formData = new FormData();
  formData.append("file", file);
  formData.append("type", current);

  fetch("{{ route('owner.verification.upload') }}", {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": csrfToken,
      "Accept": "application/json"
    },
    body: formData
  })
  .then(async res => {
    const data = await res.json();
    if(!res.ok) throw data;
    return data;
  })
  .then(() => {

    document.getElementById(current).innerText = "Uploaded ✓";
    document.getElementById(current).style.color = "#2D7D4F";

    uploaded[current] = true;
    update();

    toast("Uploaded successfully!");

  })
  .catch(err => {
    console.log("UPLOAD ERROR:", err);
    toast(JSON.stringify(err));
  });

  e.target.value = '';
}

function update(){
  const total = 6;
  const done = Object.keys(uploaded).length;

  document.getElementById('count').innerText = `${done} / ${total} completed`;
  document.getElementById('fill').style.width = (done/total)*100 + "%";

  if(done === total){
    document.getElementById('btn').disabled = false;
    document.getElementById('btn').classList.add('active');
    toast("All documents complete!");
  }
}

function submitDocs(){
  fetch("{{ route('owner.verification.submit') }}", {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": csrfToken,
      "Accept": "application/json"
    }
  })
  .then(() => {
    toast("Submitted!");
    setTimeout(()=>window.location="/owner/dashboard",1200);
  });
}

function toast(msg){
  const t=document.getElementById('toast');
  t.innerText=msg;
  t.classList.add('show');
  setTimeout(()=>t.classList.remove('show'),2000);
}

</script>
@endpush