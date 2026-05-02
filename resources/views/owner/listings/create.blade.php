<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NearU — Add Listing</title>

<link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
/* (KEEPING YOUR ORIGINAL DESIGN — CLEANED) */
body{font-family:'DM Sans',sans-serif;background:#F0F7F2;margin:0;}
.wrap{max-width:480px;margin:auto;background:#fff;min-height:100vh;}
.top-bar{background:#2D7D4F;color:#fff;padding:14px;display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;}
.back-btn{background:rgba(255,255,255,.2);border:none;color:#fff;padding:6px 10px;border-radius:8px;font-weight:700;}
.form-wrap{padding:16px;}
.form-sec{font-weight:800;margin:18px 0 10px;color:#5E6E5E;font-size:12px;text-transform:uppercase;}
input,select{width:100%;padding:10px;border:2px solid #D6E8DC;border-radius:10px;margin-top:4px;}
label{font-size:12px;font-weight:700;color:#5E6E5E;text-transform:uppercase;}
.row{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
.btn{width:100%;padding:12px;border:none;background:#2D7D4F;color:#fff;font-weight:700;border-radius:30px;margin-top:16px;cursor:pointer;}
.btn:hover{background:#1f5c38;}

/* PHOTO GRID (IMPROVED UX) */
.photo-grid{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:8px;
  margin-top:10px;
}
.photo{
  position:relative;
  height:90px;
  border-radius:10px;
  overflow:hidden;
  border:2px solid #D6E8DC;
}
.photo img{width:100%;height:100%;object-fit:cover;}
.photo .del{
  position:absolute;top:4px;right:4px;
  background:#C8102E;color:#fff;border:none;
  border-radius:50%;width:20px;height:20px;
  font-size:10px;
}
.cover-badge{
  position:absolute;bottom:0;
  width:100%;
  background:#F2B705;
  font-size:10px;
  font-weight:800;
  text-align:center;
}
.upload-box{
  border:2px dashed #D6E8DC;
  padding:14px;
  text-align:center;
  border-radius:12px;
  background:#F0F7F2;
  cursor:pointer;
}
.upload-box:hover{border-color:#2D7D4F;}

small{color:#5E6E5E;}
</style>
</head>

<body>
<div class="wrap">

<div class="top-bar">
  <button class="back-btn" onclick="history.back()">← Back</button>
  <div><b>Add Listing</b></div>
  <div></div>
</div>

<div class="form-wrap">

@if ($errors->any())
<div style="background:#fff0f2;padding:10px;border-radius:10px;color:#C8102E;font-size:13px;">
  <ul>
    @foreach($errors->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif

{{-- FORM --}}
<form action="{{ route('owner.listings.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="form-sec">📸 Photos</div>

<div class="upload-box" onclick="document.getElementById('photos').click()">
  Tap to upload photos (max 10)<br>
  <small>First image = cover photo</small>
</div>

<input type="file" id="photos" name="photos[]" multiple accept="image/*" hidden onchange="preview(event)">

<div id="grid" class="photo-grid"></div>

<div class="form-sec">🏠 Basic Info</div>

<label>Street</label>
<input type="text" name="street" required>

<div class="row">
  <div>
    <label>Price</label>
    <input type="number" name="price" required>
  </div>
  <div>
    <label>Type</label>
    <select name="type">
      <option>Room</option>
      <option>Bedspace</option>
      <option>Unit</option>
    </select>
  </div>
</div>

<label>Gender Policy</label>
<input type="text" name="gender_policy">

<div class="row">
  <div>
    <label>Walk (mins)</label>
    <input type="number" name="walk_minutes">
  </div>
  <div>
    <label>Bathroom</label>
    <select name="bathroom">
      <option>Private</option>
      <option>Shared</option>
    </select>
  </div>
</div>

<label>Furnishings</label>
<input type="text" name="furnishings">

<label>Appliances</label>
<input type="text" name="appliances">

<label>Bills Included</label>
<input type="text" name="bills_included">

<label>Curfew</label>
<input type="text" name="curfew">

<label><input type="checkbox" name="wifi"> WiFi Included</label>
<br>
<label><input type="checkbox" name="pets"> Pets Allowed</label>

<label>Nearby Landmarks</label>
<input type="text" name="nearby_landmarks">

<div class="form-sec">📍 Location</div>

<div class="row">
  <div>
    <label>Latitude</label>
    <input type="text" id="lat" name="latitude" readonly>
  </div>
  <div>
    <label>Longitude</label>
    <input type="text" id="lng" name="longitude" readonly>
  </div>
</div>

<div id="map" style="height:220px;border-radius:12px;margin-top:10px;"></div>

<button class="btn">Publish Listing</button>

</form>

</div>
</div>

<script>
let filesList = [];

function preview(e){
  const files = Array.from(e.target.files);
  filesList = files.slice(0,10);

  const grid = document.getElementById('grid');
  grid.innerHTML = '';

  filesList.forEach((file,i)=>{
    const reader = new FileReader();
    reader.onload = (ev)=>{

      const div = document.createElement('div');
      div.className = 'photo';

      div.innerHTML = `
        <img src="${ev.target.result}">
        ${i === 0 ? '<div class="cover-badge">COVER</div>' : ''}
        <button type="button" class="del" onclick="removePhoto(${i})">×</button>
      `;

      grid.appendChild(div);
    }
    reader.readAsDataURL(file);
  });
}

function removePhoto(i){
  filesList.splice(i,1);
  const dt = new DataTransfer();
  filesList.forEach(f => dt.items.add(f));
  document.getElementById('photos').files = dt.files;
  preview({target:{files:dt.files}});
}

/* MAP */
let map = L.map('map').setView([13.7868,121.0752],16);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

let marker = L.marker([13.7868,121.0752],{draggable:true}).addTo(map);

function setLatLng(lat,lng){
  document.getElementById('lat').value = lat;
  document.getElementById('lng').value = lng;
}

setLatLng(13.7868,121.0752);

marker.on('dragend', e=>{
  let p = marker.getLatLng();
  setLatLng(p.lat,p.lng);
});

map.on('click', e=>{
  marker.setLatLng(e.latlng);
  setLatLng(e.latlng.lat,e.latlng.lng);
});
</script>

</body>
</html>