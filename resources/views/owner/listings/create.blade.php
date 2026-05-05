<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NearU — Add Listing</title>

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
body{
  font-family:'DM Sans',sans-serif;
  background:#F3F7F4;
  margin:0;
}

.wrap{
  max-width:520px;
  margin:auto;
  background:#fff;
  min-height:100vh;
}

.top{
  background:#2D7D4F;
  color:#fff;
  padding:16px;
  display:flex;
  justify-content:space-between;
  align-items:center;
}

.back{
  background:rgba(255,255,255,.2);
  border:none;
  color:#fff;
  padding:6px 10px;
  border-radius:8px;
  cursor:pointer;
}

.content{padding:18px;}

.section{
  margin-top:18px;
  font-weight:800;
  font-size:12px;
  color:#5E6E5E;
  text-transform:uppercase;
}

label{
  font-size:12px;
  font-weight:700;
  color:#5E6E5E;
  display:block;
  margin-top:10px;
}

input,select{
  width:100%;
  padding:10px;
  border:2px solid #D6E8DC;
  border-radius:10px;
  margin-top:4px;
  outline:none;
}

input:focus,select:focus{
  border-color:#2D7D4F;
}

.row{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:10px;
}

/* Peso input */
.peso{
  display:flex;
  align-items:center;
  border:2px solid #D6E8DC;
  border-radius:10px;
  overflow:hidden;
}
.peso span{
  padding:10px;
  background:#E8F7EE;
  font-weight:700;
}
.peso input{
  border:none;
  flex:1;
}

/* chips */
.check-group{
  display:flex;
  flex-wrap:wrap;
  gap:6px;
  margin-top:6px;
}
.check-group label{
  background:#F0F7F2;
  padding:6px 10px;
  border-radius:20px;
  font-size:11px;
  cursor:pointer;
}

/* upload */
.upload{
  border:2px dashed #D6E8DC;
  padding:14px;
  border-radius:12px;
  text-align:center;
  cursor:pointer;
  background:#F0F7F2;
}

.grid{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:6px;
  margin-top:10px;
}

.photo{
  height:90px;
  border-radius:10px;
  overflow:hidden;
  position:relative;
  border:2px solid #D6E8DC;
}

.photo img{
  width:100%;
  height:100%;
  object-fit:cover;
}

.del{
  position:absolute;
  top:4px;
  right:4px;
  background:#C8102E;
  border:none;
  color:#fff;
  border-radius:50%;
  width:20px;
  height:20px;
  font-size:10px;
  cursor:pointer;
}

.cover{
  position:absolute;
  bottom:0;
  background:#F2B705;
  width:100%;
  font-size:10px;
  font-weight:800;
  text-align:center;
}

button.submit{
  width:100%;
  margin-top:20px;
  padding:12px;
  background:#2D7D4F;
  border:none;
  color:#fff;
  border-radius:30px;
  font-weight:700;
  cursor:pointer;
}

#map{height:220px;border-radius:12px;margin-top:10px;}
small{color:#5E6E5E;}
</style>
</head>

<body>
<div class="wrap">

<div class="top">
  <button class="back" onclick="history.back()">←</button>
  <b>Add Listing</b>
  <div></div>
</div>

<div class="content">

<form method="POST" action="{{ route('owner.listings.store') }}" enctype="multipart/form-data">
@csrf

{{-- PHOTOS --}}
<div class="section">📸 Photos</div>

<div class="upload" onclick="document.getElementById('photos').click()">
Tap to upload photos (max 10)<br>
<small>First photo = cover automatically</small>
</div>

<input type="file" id="photos" name="photos[]" multiple hidden>

<div class="grid" id="grid"></div>

{{-- BASIC INFO --}}
<div class="section">🏠 Basic Info</div>

<label>Street</label>
<select name="street">
  <option>Mars</option>
  <option>Jupiter</option>
  <option>Earth</option>
  <option>Venus</option>
  <option>Saturn</option>
  <option>Other</option>
</select>

<label>Exact Address (if Other)</label>
<input type="text" name="street_other" placeholder="Enter exact address">

<label>Price</label>
<div class="peso">
  <span>₱</span>
  <input type="number" name="price" required>
</div>

<label>Type</label>
<select name="type">
  <option>Room</option>
  <option>Bedspace</option>
  <option>Unit</option>
</select>

<label>Gender Policy</label>
<select name="gender_policy">
  <option>Male</option>
  <option>Female</option>
  <option>Mixed</option>
</select>

<label>Walk Time to BSU Alangilan</label>
<select name="walk_minutes">
  <option>2 mins</option>
  <option>4 mins</option>
  <option>6 mins</option>
  <option>8 mins</option>
  <option>10 mins</option>
  <option>12 mins</option>
  <option>14 mins</option>
  <option>16+ mins</option>
</select>

<label>Bathroom</label>
<select name="bathroom">
  <option>Private</option>
  <option>Shared</option>
</select>

{{-- FURNISHINGS --}}
<div class="section">🪑 Furnishings</div>
<div class="check-group">
<label><input type="checkbox" name="furnishings[]" value="Bed"> Bed</label>
<label><input type="checkbox" name="furnishings[]" value="Table"> Table</label>
<label><input type="checkbox" name="furnishings[]" value="Chair"> Chair</label>
<label><input type="checkbox" name="furnishings[]" value="Cabinet"> Cabinet</label>
<label><input type="checkbox" name="furnishings[]" value="Others"> Others</label>
</div>

{{-- APPLIANCES --}}
<div class="section">📺 Appliances</div>
<div class="check-group">
<label><input type="checkbox" name="appliances[]" value="Fan"> Fan</label>
<label><input type="checkbox" name="appliances[]" value="Aircon"> Aircon</label>
<label><input type="checkbox" name="appliances[]" value="Refrigerator"> Refrigerator</label>
<label><input type="checkbox" name="appliances[]" value="Stove"> Stove</label>
<label><input type="checkbox" name="appliances[]" value="Others"> Others</label>
</div>

{{-- BILLS --}}
<div class="section">💡 Bills Included</div>
<div class="check-group">
<label><input type="checkbox" name="bills[]" value="Electricity"> Electricity</label>
<label><input type="checkbox" name="bills[]" value="Water"> Water</label>
<label><input type="checkbox" name="bills[]" value="WiFi"> WiFi</label>
<label><input type="checkbox" name="bills[]" value="Others"> Others</label>
</div>

<label>Curfew</label>
<input type="text" name="curfew" placeholder="e.g. 10PM">

<label><input type="checkbox" name="wifi"> WiFi Included</label>
<label><input type="checkbox" name="pets"> Pets Allowed</label>

<label>Nearby Places</label>
<input type="text" name="nearby_landmarks"
placeholder="e.g. 7/11, Alfamart, BSU Gate, Market">

{{-- MAP --}}
<div class="section">📍 Location</div>

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

<div id="map"></div>

<button class="submit">Publish Listing</button>

</form>

</div>
</div>

<script>
let files = [];
const input = document.getElementById('photos');
const grid = document.getElementById('grid');

input.addEventListener('change', e=>{
  files = Array.from(e.target.files).slice(0,10);
  render();
});

function render(){
  grid.innerHTML='';
  const dt = new DataTransfer();

  files.forEach((f,i)=>{
    dt.items.add(f);

    const r = new FileReader();
    r.onload = e=>{
      const div = document.createElement('div');
      div.className='photo';

      div.innerHTML=`
        <img src="${e.target.result}">
        ${i===0?'<div class="cover">COVER</div>':''}
        <button type="button" class="del" onclick="remove(${i})">×</button>
      `;

      grid.appendChild(div);
    };
    r.readAsDataURL(f);
  });

  input.files = dt.files;
}

function remove(i){
  files.splice(i,1);
  const dt = new DataTransfer();
  files.forEach(f=>dt.items.add(f));
  input.files = dt.files;
  render();
}

/* MAP DEFAULT (BSU ALANGILAN) */
let defaultLat = 13.784561783857665;
let defaultLng = 121.07428658828714;

let map = L.map('map').setView([defaultLat, defaultLng], 16);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

let marker = L.marker([defaultLat, defaultLng],{draggable:true}).addTo(map);

function set(lat,lng){
  document.getElementById('lat').value = lat;
  document.getElementById('lng').value = lng;
}

set(defaultLat,defaultLng);

marker.on('dragend', e=>{
  let p = marker.getLatLng();
  set(p.lat,p.lng);
});

map.on('click', e=>{
  marker.setLatLng(e.latlng);
  set(e.latlng.lat,e.latlng.lng);
});
</script>

</body>
</html>