@extends('layouts.owner')

@section('title', 'Add Listing — NearU')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>
:root {
  --green: #2D7D4F;
  --green-dark: #1f5c3b;
  --green-light: #E9F6EF;
  --green-mid: #b9d6c4;
  --gold: #F2B705;
  --gold-text: #7a5a02;
  --card: #fff;
  --border: #E3ECE6;
  --text: #1a2e22;
  --muted: #6C7A73;
  --danger: #C8102E;
  --input-bg: #F7FAF8;
  --shadow: 0 2px 16px rgba(45,125,79,.07);
}

/* ── SUB-HEADER ── */
.listing-header {
  background: linear-gradient(135deg, var(--green), var(--green-dark));
  padding: 12px 16px 14px;
  position: sticky;
  top: 57px;
  z-index: 40;
}

.listing-header-row {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 12px;
}

.back-btn {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  background: rgba(255,255,255,.15);
  color: #fff;
  border: 1.5px solid rgba(255,255,255,.3);
  padding: .4rem .85rem;
  border-radius: 50px;
  font-size: .8rem;
  font-weight: 700;
  text-decoration: none;
  font-family: 'DM Sans', sans-serif;
  transition: background .15s;
  flex-shrink: 0;
}
.back-btn:hover { background: rgba(255,255,255,.28); }

.listing-header-title {
  font-family: 'Syne', sans-serif;
  font-size: 1rem;
  font-weight: 800;
  color: #fff;
}

/* ── STEPS ── */
.steps-track {
  display: flex;
  align-items: center;
  background: rgba(255,255,255,.12);
  border: 1px solid rgba(255,255,255,.2);
  border-radius: 50px;
  padding: 5px 10px;
}
.step-item { flex: 1; display: flex; align-items: center; gap: 5px; min-width: 0; }
.step-dot {
  width: 24px; height: 24px; border-radius: 50%;
  background: rgba(255,255,255,.2);
  display: flex; align-items: center; justify-content: center;
  font-size: 10px; font-weight: 800; color: rgba(255,255,255,.6);
  flex-shrink: 0; transition: all .2s;
}
.step-item.done .step-dot  { background: rgba(255,255,255,.85); color: var(--green); }
.step-item.active .step-dot { background: #fff; color: var(--green); box-shadow: 0 0 0 3px rgba(255,255,255,.3); }
.step-text { font-size: 10px; font-weight: 700; color: rgba(255,255,255,.55); letter-spacing: .03em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.step-item.active .step-text { color: #fff; }
.step-item.done .step-text  { color: rgba(255,255,255,.8); }
.step-connector { flex-shrink: 0; width: 16px; height: 1.5px; background: rgba(255,255,255,.2); margin: 0 2px; }
.step-connector.done { background: rgba(255,255,255,.5); }

/* ── CONTENT ── */
.content { padding: 14px 16px 30px; }

/* SECTION LABEL */
.section-label {
  font-size: 10px; font-weight: 800; color: var(--muted);
  letter-spacing: .1em; text-transform: uppercase;
  margin: 18px 0 8px 2px;
  display: flex; align-items: center; gap: 8px;
}
.section-label::after { content: ''; flex: 1; height: 1px; background: var(--border); }

/* CARD */
.card {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 14px;
  margin-bottom: 10px;
  box-shadow: var(--shadow);
}

/* ── PHOTO UPLOAD ── */
.upload-zone {
  border: 2px dashed var(--green-mid);
  padding: 18px; border-radius: 12px;
  text-align: center; cursor: pointer;
  background: linear-gradient(180deg, #f7fbf8, #eef7f1);
  transition: all .2s;
}
.upload-zone:hover { border-color: var(--green); background: var(--green-light); }
.upload-zone .upload-icon { font-size: 26px; margin-bottom: 6px; }
.upload-zone p { font-weight: 700; color: var(--green); font-size: 13px; }
.upload-zone small { color: var(--muted); font-size: 11px; }

/* Photo grid — shows all uploaded photos */
.photo-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 8px;
  margin-top: 12px;
}

.photo-item {
  height: 94px; border-radius: 12px; overflow: hidden;
  position: relative; border: 1px solid var(--border);
  background: #f0f0f0;
}
.photo-item img { width: 100%; height: 100%; object-fit: cover; display: block; }

.del-btn {
  position: absolute; top: 5px; right: 5px;
  background: var(--danger); border: none; color: #fff;
  border-radius: 50%; width: 22px; height: 22px;
  font-size: 13px; line-height: 1; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 1px 4px rgba(0,0,0,.3);
  z-index: 2;
}

.cover-badge {
  position: absolute; bottom: 0; width: 100%;
  background: var(--gold); font-size: 9px; font-weight: 900;
  text-align: center; padding: 3px 0;
  color: var(--gold-text); letter-spacing: .05em;
}

.more-overlay {
  position: absolute; inset: 0;
  background: rgba(0, 0, 0, .44);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 13px;
  font-weight: 700;
  letter-spacing: .02em;
}

/* FIELDS */
.field { display: flex; flex-direction: column; gap: 5px; margin-bottom: 10px; }
.field:last-child { margin-bottom: 0; }
.field > label { font-size: 11px; font-weight: 700; color: var(--muted); letter-spacing: .02em; }

input[type="text"],
input[type="number"],
select,
textarea {
  width: 100%; padding: 11px 13px;
  border: 1.5px solid var(--border); border-radius: 12px;
  background: var(--input-bg); outline: none;
  font-size: 13px; font-family: 'DM Sans', sans-serif; color: var(--text);
  transition: border-color .15s, box-shadow .15s, background .15s;
  appearance: none; -webkit-appearance: none;
}
input:focus, select:focus, textarea:focus {
  border-color: var(--green); background: #fff;
  box-shadow: 0 0 0 3px rgba(45,125,79,.1);
}
select {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236C7A73' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
  background-repeat: no-repeat; background-position: right 13px center; padding-right: 32px; cursor: pointer;
}

/* PRICE */
.price-wrap {
  display: flex; border: 1.5px solid var(--border); border-radius: 12px;
  overflow: hidden; background: var(--input-bg);
  transition: border-color .15s, box-shadow .15s;
}
.price-wrap:focus-within { border-color: var(--green); background: #fff; box-shadow: 0 0 0 3px rgba(45,125,79,.1); }
.price-symbol { padding: 11px 13px; background: var(--green-light); font-weight: 800; color: var(--green); font-size: 14px; border-right: 1.5px solid var(--green-mid); }
.price-wrap input { border: none; border-radius: 0; background: transparent; flex: 1; }
.price-wrap input:focus { box-shadow: none; background: transparent; }

.two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.divider { height: 1px; background: var(--border); margin: 13px 0; }
.sub-label { font-size: 11px; font-weight: 700; color: var(--muted); margin-bottom: 7px; letter-spacing: .02em; }

/* PILLS */
.pill-group { display: flex; flex-wrap: wrap; gap: 6px; }
.pill-label {
  display: flex; align-items: center; gap: 5px;
  background: var(--input-bg); border: 1.5px solid var(--border);
  padding: 7px 13px; border-radius: 999px;
  font-size: 12px; font-weight: 600; cursor: pointer;
  transition: all .15s; color: var(--text); user-select: none;
}
.pill-label:hover { border-color: var(--green-mid); background: var(--green-light); }
.pill-label input { display: none; }
.pill-label:has(input:checked) { background: var(--green); border-color: var(--green); color: #fff; }

/* MAP */
#map {
  height: 220px !important;
  width: 100% !important;
  border-radius: 14px;
  border: 1px solid var(--border);
  margin-top: 10px;
  z-index: 1;
  position: relative;
  background: #f0f0f0;
}

/* Ensure Leaflet tiles display properly */
.leaflet-container {
  height: 100% !important;
  width: 100% !important;
  border-radius: 14px;
}

.leaflet-tile-pane {
  z-index: 1 !important;
}

.leaflet-marker-pane {
  z-index: 2 !important;
}
.coords-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 10px; }
.map-hint {
  display: flex; align-items: center; gap: 8px;
  background: #fffbeb; border: 1px solid #fde68a;
  border-radius: 10px; padding: 9px 12px; margin-top: 10px;
}
.map-hint-icon { font-size: 16px; flex-shrink: 0; }
.map-hint p { font-size: 11px; color: #92400e; font-weight: 500; line-height: 1.4; }
input[readonly] { background: var(--input-bg); color: var(--muted); cursor: default; }
input[readonly]:focus { border-color: var(--border); box-shadow: none; }

/* ── PUBLISH BUTTON — static, below map ── */
.submit-wrap {
  margin-top: 16px;
}
.submit-btn {
  width: 100%; padding: 15px;
  background: linear-gradient(135deg, var(--green), var(--green-dark));
  border: none; color: #fff;
  border-radius: 16px; font-size: 15px; font-weight: 800; cursor: pointer;
  font-family: 'DM Sans', sans-serif; letter-spacing: -.01em;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  transition: opacity .15s, transform .1s;
  box-shadow: 0 4px 20px rgba(45,125,79,.35);
}
.submit-btn:hover { opacity: .93; }
.submit-btn:active { transform: scale(.98); }
.submit-btn .arrow { font-size: 18px; }

/* VALIDATION */
.alert-error {
  background: #fff5f5; border: 1px solid #fecaca;
  border-radius: 10px; padding: .75rem 1rem;
  font-size: .82rem; color: var(--danger); margin-bottom: 12px;
}
.alert-error ul { padding-left: 1rem; margin-top: .25rem; }
</style>
@endpush

@section('content')

{{-- ── SUB-HEADER ── --}}
<div class="listing-header">
  <div class="listing-header-row">
    <a href="{{ route('owner.listings.index') }}" class="back-btn" style="background: #2D7D4F; color: white; padding: 8px 10px; border-radius: 8px; text-decoration: none; display: inline-block; font-weight: 600; transition: background 0.3s ease;">← Back</a>
    <div class="listing-header-title">🏠 Add Listing</div>
  </div>

  <div class="steps-track">
    <div class="step-item done">
      <div class="step-dot">✓</div>
      <div class="step-text">Photos</div>
    </div>
    <div class="step-connector done"></div>
    <div class="step-item active">
      <div class="step-dot">2</div>
      <div class="step-text">Details</div>
    </div>
    <div class="step-connector"></div>
    <div class="step-item">
      <div class="step-dot">3</div>
      <div class="step-text">Features</div>
    </div>
    <div class="step-connector"></div>
    <div class="step-item">
      <div class="step-dot">4</div>
      <div class="step-text">Location</div>
    </div>
  </div>
</div>

{{-- ── FORM ── --}}
<form method="POST" action="{{ route('owner.listings.store') }}" enctype="multipart/form-data">
@csrf

<div class="content">

  @if($errors->any())
  <div class="alert-error">
    <strong>Please fix the following:</strong>
    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
  @endif

  {{-- PHOTOS --}}
  <div class="section-label">Photos</div>
  <div class="card">
    <div class="upload-zone" onclick="document.getElementById('photos').click()">
      <div class="upload-icon">📷</div>
      <p>Tap to upload photos</p>
      <small>Up to 6 photos · First photo becomes the cover</small>
    </div>
    <input type="file" id="photos" name="photos[]" multiple hidden accept="image/*">
    <div class="photo-grid" id="grid"></div>
  </div>

  {{-- BASIC INFO --}}
  <div class="section-label">Basic Info</div>
  <div class="card">

    <div class="field">
      <label>Street</label>
      <select name="street">
        <option value="">Select street…</option>
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
        <input type="number" name="price" placeholder="0.00" min="0" required>
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
          <option>Any</option>
          <option>Female</option>
          <option>Male</option>
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

  {{-- FEATURES --}}
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

  {{-- RULES --}}
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

  {{-- LOCATION --}}
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

  {{-- PUBLISH BUTTON — static, right below map --}}
  <div class="submit-wrap">
    <button type="submit" class="submit-btn">
      Publish Listing <span class="arrow">→</span>
    </button>
  </div>

</div>{{-- /content --}}

</form>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
/* ── PHOTO UPLOAD WITH WORKING PREVIEW ── */
let files = [];
let input, grid;
const maxFiles = 6;

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
  input = document.getElementById('photos');
  grid = document.getElementById('grid');
  
  if (input) {
    input.addEventListener('change', function(e) {
  const newFiles = Array.from(e.target.files);
  const combined = [...files, ...newFiles];
  if (combined.length > maxFiles) {
    alert('You can upload up to 6 photos only.');
  }
  files = combined.slice(0, maxFiles);
  input.value = ''; // reset so same file can be re-added
  renderPhotos();
    });
  }
  
  // Initialize map after DOM is ready
  initializeMap();
});

function renderPhotos() {
  console.log('renderPhotos called, files count:', files.length);
  console.log('grid element:', grid);
  console.log('input element:', input);
  
  if (!grid) {
    console.error('Grid element not found!');
    return;
  }
  
  const dt = new DataTransfer();
  files.forEach(f => dt.items.add(f));
  input.files = dt.files;

  grid.innerHTML = '';
  const maxPreview = 3;
  const overflow = files.length > maxPreview ? files.length - maxPreview : 0;

  files.forEach(function(file, index) {
    console.log('Processing file:', file.name, 'index:', index);
    const url = URL.createObjectURL(file);
    console.log('Created URL:', url);

    const item = document.createElement('div');
    item.className = 'photo-item';
    item.dataset.index = index;

    const img = document.createElement('img');
    img.src = url;
    img.alt = 'Photo ' + (index + 1);
    item.appendChild(img);

    if (index === 0) {
      const badge = document.createElement('div');
      badge.className = 'cover-badge';
      badge.textContent = 'COVER';
      item.appendChild(badge);
    }

    // Show "more" overlay on the last visible photo if there are more photos
    if (index === maxPreview - 1 && overflow > 0) {
      const overlay = document.createElement('div');
      overlay.className = 'more-overlay';
      overlay.textContent = '+' + overflow + ' more';
      item.appendChild(overlay);
    }

    const delBtn = document.createElement('button');
    delBtn.type = 'button';
    delBtn.className = 'del-btn';
    delBtn.textContent = '×';
    delBtn.addEventListener('click', function() {
      removePhoto(index);
    });
    item.appendChild(delBtn);

    // Only append if this photo should be visible in the preview
    if (index < maxPreview) {
      grid.appendChild(item);
    }
  });
}

function removePhoto(index) {
  files.splice(index, 1);
  renderPhotos();
}

/* ── MAP INITIALIZATION ── */
function initializeMap() {
  console.log('Initializing map...');
  const mapElement = document.getElementById('map');
  console.log('Map element found:', mapElement);
  
  if (!mapElement) {
    console.error('Map element not found!');
    return;
  }
  
  const defaultLat = 13.78456;
  const defaultLng = 121.07428;

  // Ensure map container has proper dimensions
  mapElement.style.height = '220px';
  mapElement.style.width = '100%';
  mapElement.style.backgroundColor = '#f0f0f0'; // Add background color for debugging
  
  console.log('Map element styles:', {
    height: mapElement.style.height,
    width: mapElement.style.width,
    display: getComputedStyle(mapElement).display,
    visibility: getComputedStyle(mapElement).visibility,
    zIndex: getComputedStyle(mapElement).zIndex
  });
  
  try {
    console.log('Creating Leaflet map...');
    var map = L.map('map').setView([defaultLat, defaultLng], 16);
    console.log('Map created successfully');

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors',
      maxZoom: 19
    }).addTo(map);

    // Initialize marker
    var marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

    function setCoords(lat, lng) {
      const latInput = document.getElementById('lat');
      const lngInput = document.getElementById('lng');
      if (latInput) latInput.value = lat.toFixed(6);
      if (lngInput) lngInput.value = lng.toFixed(6);
    }

    setCoords(defaultLat, defaultLng);

    marker.on('dragend', function(e) {
      var p = marker.getLatLng();
      setCoords(p.lat, p.lng);
    });

    map.on('click', function(e) {
      marker.setLatLng(e.latlng);
      setCoords(e.latlng.lat, e.latlng.lng);
    });

    // Fix map display issues
    setTimeout(function() {
      map.invalidateSize();
    }, 500);
    
  } catch (error) {
    console.error('Map initialization error:', error);
  }
}
</script>
@endpush