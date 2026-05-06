<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NearU — Add Listing</title>

<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
:root {
  --green: #2D7D4F;
  --green-dark: #1f5c3b;
  --green-light: #E9F6EF;
  --green-mid: #b9d6c4;
  --gold: #F2B705;
  --gold-text: #7a5a02;
  --bg: #F0F5F2;
  --card: #fff;
  --border: #E3ECE6;
  --text: #1a2e22;
  --muted: #6C7A73;
  --danger: #C8102E;
  --input-bg: #F7FAF8;
  --shadow: 0 2px 16px rgba(45,125,79,.07);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
  font-family: 'DM Sans', sans-serif;
  background: var(--bg);
  color: var(--text);
  min-height: 100vh;
}

/* WRAPPER */
.wrap {
  max-width: 560px;
  margin: auto;
  min-height: 100vh;
  background: var(--bg);
  padding-bottom: 40px;
}

/* HEADER */
.top {
  background: linear-gradient(135deg, var(--green), var(--green-dark));
  color: #fff;
  padding: 16px 18px 20px;
  position: sticky;
  top: 0;
  z-index: 100;
}

.top-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 14px;
}

.back {
  background: rgba(255,255,255,.15);
  border: none;
  color: #fff;
  width: 36px;
  height: 36px;
  border-radius: 10px;
  cursor: pointer;
  font-size: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background .15s;
}
.back:hover { background: rgba(255,255,255,.25); }

.top h1 {
  font-size: 16px;
  font-weight: 700;
  letter-spacing: -.01em;
}

.draft-btn {
  background: rgba(255,255,255,.15);
  border: none;
  color: rgba(255,255,255,.85);
  font-size: 12px;
  font-weight: 600;
  padding: 6px 12px;
  border-radius: 8px;
  cursor: pointer;
  font-family: 'DM Sans', sans-serif;
  transition: background .15s;
}
.draft-btn:hover { background: rgba(255,255,255,.25); }

/* STEP TABS */
.steps {
  display: flex;
  gap: 6px;
}

.step {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 5px;
  cursor: pointer;
}

.step-dot {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: rgba(255,255,255,.2);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 11px;
  font-weight: 700;
  color: rgba(255,255,255,.7);
  transition: all .2s;
}

.step.done .step-dot {
  background: rgba(255,255,255,.9);
  color: var(--green);
}

.step.active .step-dot {
  background: #fff;
  color: var(--green);
  box-shadow: 0 0 0 3px rgba(255,255,255,.3);
}

.step-label {
  font-size: 9px;
  font-weight: 700;
  color: rgba(255,255,255,.6);
  letter-spacing: .04em;
  text-transform: uppercase;
}

.step.active .step-label { color: #fff; }
.step.done .step-label { color: rgba(255,255,255,.8); }

.step-line {
  flex: 1;
  height: 1px;
  background: rgba(255,255,255,.2);
  margin-top: 14px;
  align-self: flex-start;
}

.step-line.done { background: rgba(255,255,255,.6); }

/* PROGRESS BAR */
.progress-bar {
  height: 3px;
  background: rgba(255,255,255,.15);
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
}
.progress-fill {
  height: 100%;
  background: rgba(255,255,255,.7);
  width: 50%;
  border-radius: 0 2px 2px 0;
  transition: width .3s ease;
}

/* CONTENT */
.content { padding: 16px; }

/* SECTION LABEL */
.section-label {
  font-size: 10px;
  font-weight: 800;
  color: var(--muted);
  letter-spacing: .1em;
  text-transform: uppercase;
  margin: 20px 0 8px 2px;
  display: flex;
  align-items: center;
  gap: 8px;
}
.section-label::after {
  content: '';
  flex: 1;
  height: 1px;
  background: var(--border);
}

/* CARD */
.card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 18px;
  padding: 16px;
  margin-bottom: 12px;
  box-shadow: var(--shadow);
}

/* PHOTO UPLOAD */
.upload-zone {
  border: 2px dashed var(--green-mid);
  padding: 18px;
  border-radius: 14px;
  text-align: center;
  cursor: pointer;
  background: linear-gradient(180deg, #f7fbf8, #eef7f1);
  transition: all .2s;
}
.upload-zone:hover {
  border-color: var(--green);
  background: var(--green-light);
}
.upload-zone .upload-icon { font-size: 26px; margin-bottom: 6px; }
.upload-zone p { font-weight: 700; color: var(--green); font-size: 13px; }
.upload-zone small { color: var(--muted); font-size: 11px; }

.photo-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 8px;
  margin-top: 12px;
}

.photo-item {
  height: 96px;
  border-radius: 12px;
  overflow: hidden;
  position: relative;
  border: 1px solid var(--border);
  box-shadow: 0 1px 4px rgba(0,0,0,.06);
}
.photo-item img { width: 100%; height: 100%; object-fit: cover; display: block; }

.del-btn {
  position: absolute;
  top: 5px;
  right: 5px;
  background: var(--danger);
  border: none;
  color: #fff;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  font-size: 10px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 1px 4px rgba(0,0,0,.2);
}

.cover-badge {
  position: absolute;
  bottom: 0;
  width: 100%;
  background: var(--gold);
  font-size: 9px;
  font-weight: 900;
  text-align: center;
  padding: 3px 0;
  color: var(--gold-text);
  letter-spacing: .05em;
}

.more-badge {
  position: absolute;
  bottom: 0;
  width: 100%;
  background: rgba(0,0,0,.55);
  font-size: 13px;
  font-weight: 800;
  text-align: center;
  padding: 16px 0 14px;
  color: #fff;
}

/* FORM FIELDS */
.field { display: flex; flex-direction: column; gap: 5px; margin-bottom: 10px; }
.field:last-child { margin-bottom: 0; }

.field > label {
  font-size: 11px;
  font-weight: 700;
  color: var(--muted);
  letter-spacing: .02em;
  display: block;
}

input[type="text"],
input[type="number"],
input[type="email"],
select,
textarea {
  width: 100%;
  padding: 11px 13px;
  border: 1.5px solid var(--border);
  border-radius: 12px;
  background: var(--input-bg);
  outline: none;
  font-size: 13px;
  font-family: 'DM Sans', sans-serif;
  color: var(--text);
  transition: border-color .15s, box-shadow .15s, background .15s;
  appearance: none;
  -webkit-appearance: none;
}

input:focus, select:focus, textarea:focus {
  border-color: var(--green);
  background: #fff;
  box-shadow: 0 0 0 3px rgba(45,125,79,.1);
}

select {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236C7A73' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 13px center;
  padding-right: 32px;
}

/* PRICE INPUT */
.price-wrap {
  display: flex;
  border: 1.5px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
  background: var(--input-bg);
  transition: border-color .15s, box-shadow .15s;
}
.price-wrap:focus-within {
  border-color: var(--green);
  background: #fff;
  box-shadow: 0 0 0 3px rgba(45,125,79,.1);
}
.price-symbol {
  padding: 11px 13px;
  background: var(--green-light);
  font-weight: 800;
  color: var(--green);
  font-size: 14px;
  border-right: 1.5px solid var(--green-mid);
}
.price-wrap input {
  border: none;
  border-radius: 0;
  background: transparent;
  box-shadow: none;
  flex: 1;
}
.price-wrap input:focus { box-shadow: none; background: transparent; }

/* TWO COLUMN */
.two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

/* CHECKBOX PILLS */
.pill-group { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 4px; }

.pill-label {
  display: flex;
  align-items: center;
  gap: 5px;
  background: var(--input-bg);
  border: 1.5px solid var(--border);
  padding: 7px 13px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all .15s;
  color: var(--text);
  user-select: none;
}
.pill-label:hover { border-color: var(--green-mid); background: var(--green-light); }
.pill-label input { display: none; }
.pill-label input:checked ~ span { color: #fff; }
.pill-label:has(input:checked) {
  background: var(--green);
  border-color: var(--green);
  color: #fff;
}

/* DIVIDER */
.divider {
  height: 1px;
  background: var(--border);
  margin: 14px 0;
}

/* SUB-LABEL */
.sub-label {
  font-size: 11px;
  font-weight: 700;
  color: var(--muted);
  margin-bottom: 6px;
  letter-spacing: .02em;
}

/* MAP */
#map {
  height: 220px;
  border-radius: 14px;
  border: 1px solid var(--border);
  margin-top: 10px;
  z-index: 1;
}

.coords-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  margin-top: 10px;
}

.map-hint {
  display: flex;
  align-items: center;
  gap: 8px;
  background: #fffbeb;
  border: 1px solid #fde68a;
  border-radius: 10px;
  padding: 9px 12px;
  margin-top: 10px;
}
.map-hint-icon { font-size: 16px; flex-shrink: 0; }
.map-hint p { font-size: 11px; color: #92400e; font-weight: 500; line-height: 1.4; }

/* SUBMIT */
.submit-wrap { padding: 0 0 8px; }

.submit-btn {
  width: 100%;
  padding: 15px;
  background: linear-gradient(135deg, var(--green), var(--green-dark));
  border: none;
  color: #fff;
  border-radius: 16px;
  font-size: 15px;
  font-weight: 800;
  cursor: pointer;
  font-family: 'DM Sans', sans-serif;
  letter-spacing: -.01em;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: opacity .15s, transform .1s;
  box-shadow: 0 4px 20px rgba(45,125,79,.35);
}
.submit-btn:hover { opacity: .93; }
.submit-btn:active { transform: scale(.98); }
.submit-btn .arrow { font-size: 18px; }

/* READONLY COORDS */
input[readonly] {
  background: var(--input-bg);
  color: var(--muted);
  cursor: default;
}
input[readonly]:focus {
  border-color: var(--border);
  box-shadow: none;
}
</style>
</head>

<body>
<div class="wrap">

  <!-- HEADER -->
  <div class="top" style="position:relative;">
    <div class="top-row">
      <button class="back" onclick="history.back()">←</button>
      <h1>Add Listing</h1>
      <button class="draft-btn">Save draft</button>
    </div>

    <!-- STEP TABS -->
    <div class="steps">
      <div class="step done">
        <div class="step-dot">✓</div>
        <div class="step-label">Photos</div>
      </div>
      <div class="step-line done"></div>
      <div class="step active">
        <div class="step-dot">2</div>
        <div class="step-label">Details</div>
      </div>
      <div class="step-line"></div>
      <div class="step">
        <div class="step-dot">3</div>
        <div class="step-label">Features</div>
      </div>
      <div class="step-line"></div>
      <div class="step">
        <div class="step-dot">4</div>
        <div class="step-label">Location</div>
      </div>
    </div>

    <div class="progress-bar">
      <div class="progress-fill"></div>
    </div>
  </div>

  <!-- CONTENT -->
  <div class="content">

    <form method="POST" action="{{ route('owner.listings.store') }}" enctype="multipart/form-data">
    @csrf

    <!-- PHOTOS -->
    <div class="section-label">Photos</div>
    <div class="card">
      <div class="upload-zone" onclick="document.getElementById('photos').click()">
        <div class="upload-icon">📷</div>
        <p>Tap to upload photos</p>
        <small>Up to 10 photos · First photo becomes the cover</small>
      </div>
      <input type="file" id="photos" name="photos[]" multiple hidden accept="image/*">
      <div class="photo-grid" id="grid"></div>
    </div>

    <!-- BASIC INFO -->
    <div class="section-label">Basic Info</div>
    <div class="card">

      <div class="field">
        <label>Street</label>
        <select name="street">
          <option>Mars</option>
          <option>Jupiter</option>
          <option>Earth</option>
          <option>Venus</option>
          <option>Saturn</option>
          <option>Other</option>
        </select>
      </div>

      <div class="field">
        <label>Monthly Rent</label>
        <div class="price-wrap">
          <div class="price-symbol">₱</div>
          <input type="number" name="price" placeholder="0.00" required>
        </div>
      </div>

      <div class="two-col">
        <div class="field">
          <label>Type</label>
          <select name="type">
            <option>Room</option>
            <option>Bedspace</option>
            <option>Unit</option>
          </select>
        </div>
        <div class="field">
          <label>Bathroom</label>
          <select name="bathroom">
            <option>Private</option>
            <option>Shared</option>
          </select>
        </div>
      </div>

      <div class="two-col">
        <div class="field">
          <label>Gender Policy</label>
          <select name="gender_policy">
            <option>Male</option>
            <option>Female</option>
            <option>Mixed</option>
          </select>
        </div>
        <div class="field">
          <label>Walk Time</label>
          <select name="walk_minutes">
            <option value="2">2 mins</option>
            <option value="4">4 mins</option>
            <option value="6">6 mins</option>
            <option value="8">8 mins</option>
            <option value="10">10 mins</option>
            <option value="12">12 mins</option>
            <option value="14">14 mins</option>
            <option value="16">16+ mins</option>
          </select>
        </div>
      </div>

    </div>

    <!-- FEATURES -->
    <div class="section-label">Features & Inclusions</div>
    <div class="card">

      <div class="sub-label">Furnishings</div>
      <div class="pill-group">
        <label class="pill-label"><input type="checkbox" name="furnishings[]" value="Bed"><span>🛏 Bed</span></label>
        <label class="pill-label"><input type="checkbox" name="furnishings[]" value="Table"><span>🪑 Table</span></label>
        <label class="pill-label"><input type="checkbox" name="furnishings[]" value="Chair"><span>💺 Chair</span></label>
        <label class="pill-label"><input type="checkbox" name="furnishings[]" value="Cabinet"><span>🗄 Cabinet</span></label>
        <label class="pill-label"><input type="checkbox" name="furnishings[]" value="Wardrobe"><span>👔 Wardrobe</span></label>
      </div>

      <div class="divider"></div>

      <div class="sub-label">Appliances</div>
      <div class="pill-group">
        <label class="pill-label"><input type="checkbox" name="appliances[]" value="Fan"><span>🌀 Fan</span></label>
        <label class="pill-label"><input type="checkbox" name="appliances[]" value="Aircon"><span>❄️ Aircon</span></label>
        <label class="pill-label"><input type="checkbox" name="appliances[]" value="Refrigerator"><span>🧊 Refrigerator</span></label>
        <label class="pill-label"><input type="checkbox" name="appliances[]" value="Stove"><span>🍳 Stove</span></label>
        <label class="pill-label"><input type="checkbox" name="appliances[]" value="TV"><span>📺 TV</span></label>
      </div>

      <div class="divider"></div>

      <div class="sub-label">Bills Included</div>
      <div class="pill-group">
        <label class="pill-label"><input type="checkbox" name="bills[]" value="Electricity"><span>⚡ Electricity</span></label>
        <label class="pill-label"><input type="checkbox" name="bills[]" value="Water"><span>💧 Water</span></label>
        <label class="pill-label"><input type="checkbox" name="bills[]" value="WiFi"><span>📶 WiFi</span></label>
      </div>

    </div>

    <!-- RULES -->
    <div class="section-label">Rules & Notes</div>
    <div class="card">
      <div class="field">
        <label>Curfew</label>
        <input type="text" name="curfew" placeholder="e.g. 10PM">
      </div>
      <div class="field">
        <label>Nearby Landmarks</label>
        <input type="text" name="nearby_landmarks" placeholder="e.g. 7/11, BSU Gate, Market">
      </div>
    </div>

    <!-- LOCATION -->
    <div class="section-label">Location</div>
    <div class="card">

      <div class="coords-row">
        <div class="field">
          <label>Latitude</label>
          <input type="text" id="lat" name="latitude" readonly>
        </div>
        <div class="field">
          <label>Longitude</label>
          <input type="text" id="lng" name="longitude" readonly>
        </div>
      </div>

      <div id="map"></div>

      <div class="map-hint">
        <div class="map-hint-icon">💡</div>
        <p>Drag the pin or tap anywhere on the map to set your exact location.</p>
      </div>

    </div>

    <!-- SUBMIT -->
    <div class="submit-wrap">
      <button type="submit" class="submit-btn">
        Publish Listing <span class="arrow">→</span>
      </button>
    </div>

    </form>

  </div><!-- /content -->
</div><!-- /wrap -->

<script>
/* PHOTO UPLOAD */
let files = [];
const input = document.getElementById('photos');
const grid = document.getElementById('grid');

input.addEventListener('change', e => {
  const newFiles = Array.from(e.target.files);
  files = [...files, ...newFiles].slice(0, 10);
  render();
  input.value = '';
});

function render() {
  grid.innerHTML = '';
  const dt = new DataTransfer();

  files.forEach((f, i) => {
    dt.items.add(f);
    const reader = new FileReader();
    reader.onload = e => {
      const div = document.createElement('div');
      div.className = 'photo-item';

      let badge = '';
      if (i === 0) badge = `<div class="cover-badge">COVER</div>`;
      if (files.length > 3 && i === 2) badge = `<div class="more-badge">+${files.length - 3}</div>`;

      div.innerHTML = `
        <img src="${e.target.result}" alt="Photo ${i + 1}">
        ${badge}
        <button type="button" class="del-btn" onclick="removePhoto(${i})">×</button>
      `;
      grid.appendChild(div);
    };
    reader.readAsDataURL(f);
  });

  input.files = dt.files;
}

function removePhoto(i) {
  files.splice(i, 1);
  const dt = new DataTransfer();
  files.forEach(f => dt.items.add(f));
  input.files = dt.files;
  render();
}

/* MAP */
const defaultLat = 13.78456;
const defaultLng = 121.07428;

let map = L.map('map').setView([defaultLat, defaultLng], 16);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '© OpenStreetMap contributors'
}).addTo(map);

let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

function setCoords(lat, lng) {
  document.getElementById('lat').value = lat.toFixed(6);
  document.getElementById('lng').value = lng.toFixed(6);
}

setCoords(defaultLat, defaultLng);

marker.on('dragend', e => {
  const p = marker.getLatLng();
  setCoords(p.lat, p.lng);
});

map.on('click', e => {
  marker.setLatLng(e.latlng);
  setCoords(e.latlng.lat, e.latlng.lng);
});
</script>

</body>
</html>