<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<title>NearU — Add Listing</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<style>
*{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent;}
:root{
  --bg:#F0F7F2;--surface:#fff;--card:#fff;
  --t1:#141F14;--t2:#5E6E5E;--border:#D6E8DC;
  --green:#2D7D4F;--green-dk:#1f5c38;--green-lt:#E8F7EE;
  --gold:#F2B705;--gold-dk:#c99200;--gold-lt:#FFFBEB;
  --blue:#3B82F6;--blue-lt:#EFF6FF;
  --red:#C8102E;--red-lt:#FFF0F2;
}
body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--t1);}
.wrap{max-width:480px;margin:0 auto;background:var(--surface);min-height:100vh;padding-bottom:40px;}

/* TOP BAR */
.top-bar{background:linear-gradient(135deg,#1a3a1a,#2D7D4F);padding:.9rem 1.4rem;display:flex;justify-content:space-between;align-items:center;color:#fff;position:sticky;top:0;z-index:60;box-shadow:0 2px 12px rgba(45,125,79,.25);}
.back-btn{background:rgba(255,255,255,.15);border:none;color:#fff;font-size:.82rem;font-weight:700;cursor:pointer;padding:.4rem .7rem;border-radius:10px;}
.top-title{font-family:'Syne',sans-serif;font-size:1rem;font-weight:700;}

/* FORM */
.form-wrap{padding:1.2rem;}
.form-sec{font-family:'Syne',sans-serif;font-size:.85rem;font-weight:800;color:var(--t2);text-transform:uppercase;letter-spacing:.8px;padding:.7rem 0 .5rem;border-bottom:1px solid var(--border);margin-bottom:.9rem;}
.fg{margin-bottom:1rem;}
.fg label{display:block;margin-bottom:.42rem;font-weight:700;font-size:.8rem;color:var(--t2);text-transform:uppercase;letter-spacing:.5px;}
.fg input,.fg select,.fg textarea{width:100%;padding:.82rem 1rem;border:2px solid var(--border);border-radius:12px;font-family:'DM Sans',sans-serif;font-size:.9rem;outline:none;color:var(--t1);background:var(--card);transition:border .2s,box-shadow .2s;}
.fg input:focus,.fg select:focus,.fg textarea:focus{border-color:var(--green);box-shadow:0 0 0 3px rgba(45,125,79,.1);}
.fg-row{display:grid;grid-template-columns:1fr 1fr;gap:.7rem;margin-bottom:1rem;}
.fg-row .fg{margin-bottom:0;}

/* PHOTO DROP ZONE */
.drop-zone{border:2.5px dashed var(--border);border-radius:16px;padding:1.6rem 1rem;text-align:center;cursor:pointer;background:var(--bg);transition:all .22s;margin-bottom:.85rem;}
.drop-zone:hover,.drop-zone.active{border-color:var(--green);background:var(--green-lt);}
.drop-zone-icon{font-size:2.5rem;margin-bottom:.4rem;}
.drop-zone-title{font-weight:700;font-size:.88rem;color:var(--t1);}
.drop-zone-sub{font-size:.74rem;color:var(--t2);margin-top:.3rem;}

/* PHOTO GRID */
#photoGrid{display:grid;grid-template-columns:repeat(3,1fr);gap:.55rem;margin-bottom:.9rem;}
.photo-thumb{position:relative;border-radius:12px;overflow:hidden;aspect-ratio:1;background:var(--bg);border:2px solid var(--border);}
.photo-thumb img{width:100%;height:100%;object-fit:cover;display:block;}
.photo-thumb.cover{border-color:var(--gold);box-shadow:0 0 0 2px var(--gold);}
.thumb-del{position:absolute;top:4px;right:4px;width:22px;height:22px;border-radius:50%;background:rgba(200,16,46,.9);color:#fff;border:none;font-size:.75rem;cursor:pointer;display:flex;align-items:center;justify-content:center;font-weight:800;}
.thumb-cover-label{position:absolute;bottom:0;left:0;right:0;background:rgba(242,183,5,.9);color:#1F2933;font-size:.6rem;font-weight:800;text-align:center;padding:.18rem;letter-spacing:.3px;}
.photo-add-tile{border-radius:12px;aspect-ratio:1;border:2.5px dashed var(--border);display:flex;flex-direction:column;align-items:center;justify-content:center;cursor:pointer;background:var(--bg);font-size:1.5rem;color:var(--t2);}
.photo-add-tile:hover{border-color:var(--green);color:var(--green);background:var(--green-lt);}

/* COUNT PILL */
.count-pill{display:inline-flex;align-items:center;gap:.35rem;background:var(--green-lt);color:var(--green);border:1.5px solid rgba(45,125,79,.2);padding:.3rem .9rem;border-radius:20px;font-size:.76rem;font-weight:800;}

/* TOGGLE ROW */
.toggle-row{display:flex;justify-content:space-between;align-items:center;padding:.65rem 0;border-bottom:1px solid var(--border);}
.toggle-label{font-size:.88rem;font-weight:600;color:var(--t1);}
.toggle-sub{font-size:.72rem;color:var(--t2);margin-top:.1rem;}
.tog{position:relative;display:inline-block;width:46px;height:24px;}
.tog input{opacity:0;width:0;height:0;}
.sl{position:absolute;cursor:pointer;inset:0;background:#ccc;border-radius:24px;transition:.3s;}
.sl:before{position:absolute;content:'';height:16px;width:16px;left:4px;bottom:4px;background:#fff;transition:.3s;border-radius:50%;}
input:checked+.sl{background:var(--green);}
input:checked+.sl:before{transform:translateX(22px);}

/* PIN MAP */
#pinMap{width:100%;height:230px;border-radius:16px;overflow:hidden;border:2px solid var(--border);background:var(--bg);}
.map-btns{display:flex;gap:.5rem;margin-bottom:.7rem;}
.map-btn{flex:1;padding:.62rem;border-radius:50px;font-size:.78rem;font-weight:700;border:none;cursor:pointer;transition:all .18s;font-family:'DM Sans',sans-serif;}
.map-btn.loc{border:1.5px solid var(--green);color:var(--green);background:var(--green-lt);}
.map-btn.loc:hover{background:var(--green);color:#fff;}
.map-btn.reset{border:1.5px solid var(--border);color:var(--t2);background:var(--card);}
.coord-display{text-align:center;font-size:.75rem;color:var(--t2);margin-top:.5rem;padding:.35rem;background:var(--bg);border-radius:8px;font-family:monospace;}

/* SUBMIT */
.submit-btn{width:100%;padding:.95rem;background:var(--green);color:#fff;border:none;border-radius:50px;font-size:.97rem;font-weight:700;cursor:pointer;margin-top:.4rem;font-family:'DM Sans',sans-serif;transition:all .2s;box-shadow:0 4px 16px rgba(45,125,79,.35);}
.submit-btn:hover{background:var(--green-dk);transform:translateY(-1px);}

/* TOAST */
#toast{position:fixed;top:72px;left:50%;transform:translateX(-50%);background:#0a1f0e;color:#fff;padding:10px 22px;border-radius:50px;font-size:.83rem;font-weight:700;z-index:20000;opacity:0;transition:opacity .28s;pointer-events:none;white-space:nowrap;}
#toast.show{opacity:1;}
#toast.ok{background:var(--green);}
#toast.warn{background:#92400E;}
</style>
</head>
<body>
<div id="toast"></div>
<div class="wrap">
  <!-- TOP BAR -->
  <div class="top-bar">
    <button class="back-btn" onclick="history.back()">← Back</button>
    <div class="top-title">➕ Add Listing</div>
    <div style="width:68px"></div>
  </div>

  <div class="form-wrap">

    <!-- PHOTOS -->
    <div class="form-sec">📸 Property Photos</div>
    <div style="font-size:.78rem;color:var(--t2);margin-bottom:.75rem;line-height:1.5;">
      Add up to <strong>10 photos</strong>. First photo is the cover. Tap 🗑️ to remove. Drag to reorder.
    </div>
    <div class="drop-zone" id="dropZone"
         onclick="document.getElementById('fileInput').click()"
         ondragover="onDragOver(event)"
         ondrop="onDrop(event)">
      <div class="drop-zone-icon">📷</div>
      <div class="drop-zone-title">Tap to add photos</div>
      <div class="drop-zone-sub">or drag & drop · JPG, PNG, HEIC · up to 10 photos</div>
    </div>
    <input type="file" id="fileInput" accept="image/*" multiple style="display:none;" onchange="onFileSelect(event)">
    <div id="photoGrid"></div>
    <div id="countPill" style="display:none;text-align:center;margin-bottom:.9rem;">
      <span class="count-pill">📸 <span id="countTxt">0</span> photo(s) added · first = cover</span>
    </div>

    <!-- BASIC INFO -->
    <div class="form-sec">🏠 Basic Info</div>
    <div class="fg">
      <label>Street / Address</label>
      <input type="text" id="street" placeholder="e.g. Jupiter Street, Blk 5">
    </div>
    <div class="fg-row">
      <div class="fg">
        <label>Type</label>
        <select id="type">
          <option value="Room">🛏️ Room</option>
          <option value="Bedspace">🛌 Bedspace</option>
          <option value="Unit">🏠 Unit</option>
        </select>
      </div>
      <div class="fg">
        <label>Monthly Rent (₱)</label>
        <input type="number" id="price" placeholder="e.g. 3200">
      </div>
    </div>
    <div class="fg-row">
      <div class="fg">
        <label>Gender Policy</label>
        <select id="gender">
          <option value="Any">👥 Any</option>
          <option value="Female">👩 Female</option>
          <option value="Male">👨 Male</option>
        </select>
      </div>
      <div class="fg">
        <label>Walk to Campus</label>
        <input type="number" id="walk" placeholder="mins" min="1" max="60">
      </div>
    </div>

    <!-- INCLUSIONS -->
    <div class="form-sec">🛋️ Inclusions</div>
    <div class="fg">
      <label>Bathroom</label>
      <select id="bath">
        <option value="Private">🚿 Private</option>
        <option value="Shared">🚿 Shared</option>
      </select>
    </div>
    <div class="fg">
      <label>Furnishings</label>
      <input type="text" id="furn" placeholder="e.g. Bed, Cabinet, Study Table">
    </div>
    <div class="fg">
      <label>Appliances</label>
      <input type="text" id="app" placeholder="e.g. Fan, Rice Cooker, AC">
    </div>
    <div class="fg">
      <label>Bills Included</label>
      <input type="text" id="bills" placeholder="e.g. Water & Wifi, All bills">
    </div>

    <!-- HOUSE RULES -->
    <div class="form-sec">⚙️ House Rules</div>
    <div class="fg">
      <label>Curfew</label>
      <select id="curfew">
        <option value="No curfew">🌙 No Curfew</option>
        <option value="10 PM">🕙 10 PM</option>
        <option value="11 PM">🕚 11 PM</option>
        <option value="12 AM">🕛 12 AM</option>
      </select>
    </div>
    <div class="toggle-row">
      <div>
        <div class="toggle-label">📶 WiFi Included</div>
        <div class="toggle-sub">Is WiFi part of the rent?</div>
      </div>
      <label class="tog"><input type="checkbox" id="wifi"><span class="sl"></span></label>
    </div>
    <div class="toggle-row">
      <div>
        <div class="toggle-label">🐾 Pets Allowed</div>
        <div class="toggle-sub">Can tenants bring pets?</div>
      </div>
      <label class="tog"><input type="checkbox" id="pets"><span class="sl"></span></label>
    </div>
    <div class="fg" style="margin-top:.9rem;">
      <label>Nearby Landmarks</label>
      <input type="text" id="near" placeholder="e.g. 7-11, Laundry shop, Carinderia">
    </div>

    <!-- PIN MAP -->
    <div class="form-sec">📍 Pin Your Location</div>
    <div style="font-size:.8rem;color:var(--t2);margin-bottom:.75rem;line-height:1.5;">
      Tap the map or drag the red pin to mark your property's exact location.
    </div>
    <div class="map-btns">
      <button class="map-btn loc" onclick="useMyLocation()">📍 Use My Location</button>
      <button class="map-btn reset" onclick="resetPin()">🔄 Reset to GCH</button>
    </div>
    <div id="pinMap"></div>
    <div id="coordDisplay" class="coord-display">📍 Lat: 13.78680, Lng: 121.07520</div>

    <!-- SUBMIT -->
    <button class="submit-btn" onclick="publishListing()">✅ Publish Listing</button>
    <div style="height:1rem;"></div>
  </div>
</div>

<script>
/* ── Photo Uploader ── */
let photos = [];
const MAX  = 10;
const GCH  = [13.7868, 121.0752];
let pinLat = GCH[0], pinLng = GCH[1];
let map, marker;

function onFileSelect(e) { addFiles([...e.target.files]); e.target.value = ''; }
function onDragOver(e)   { e.preventDefault(); document.getElementById('dropZone').classList.add('active'); }
function onDrop(e) {
  e.preventDefault();
  document.getElementById('dropZone').classList.remove('active');
  addFiles([...e.dataTransfer.files].filter(f => f.type.startsWith('image/')));
}

function addFiles(files) {
  const rem = MAX - photos.length;
  if (rem <= 0) { showToast('⚠️ Maximum 10 photos reached', 'warn'); return; }
  const toAdd = files.slice(0, rem);
  let done = 0;
  toAdd.forEach(f => {
    const r = new FileReader();
    r.onload = ev => { photos.push({ id: Date.now() + Math.random(), dataUrl: ev.target.result }); done++; if (done === toAdd.length) renderGrid(); };
    r.readAsDataURL(f);
  });
  if (files.length > rem) showToast(`⚠️ Only ${rem} more photo(s) can be added`, 'warn');
}

function removePhoto(idx) { photos.splice(idx, 1); renderGrid(); }
function setCover(idx)    { if (!idx) return; const [p] = photos.splice(idx, 1); photos.unshift(p); renderGrid(); showToast('⭐ Cover photo updated!', 'ok'); }

function renderGrid() {
  const grid = document.getElementById('photoGrid');
  const pill = document.getElementById('countPill');
  const txt  = document.getElementById('countTxt');
  const dz   = document.getElementById('dropZone');

  const thumbs = photos.map((p, i) => `
    <div class="photo-thumb ${i === 0 ? 'cover' : ''}" ondblclick="setCover(${i})" title="${i === 0 ? 'Cover' : 'Double-tap to set as cover'}">
      <img src="${p.dataUrl}" alt="">
      <button class="thumb-del" onclick="event.stopPropagation();removePhoto(${i})">✕</button>
      ${i === 0 ? '<div class="thumb-cover-label">⭐ COVER</div>' : ''}
    </div>`).join('');

  const addTile = photos.length < MAX
    ? `<div class="photo-add-tile" onclick="document.getElementById('fileInput').click()">➕<div style="font-size:.65rem;margin-top:.25rem;font-weight:700;">Add</div></div>` : '';

  grid.innerHTML = thumbs + addTile;
  pill.style.display = photos.length ? 'block' : 'none';
  txt.textContent = photos.length;
  dz.style.display = photos.length >= MAX ? 'none' : '';
}

/* ── Pin Picker Map ── */
document.addEventListener('DOMContentLoaded', () => {
  map = L.map('pinMap', { zoomControl: true, scrollWheelZoom: false }).setView(GCH, 17);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap', maxZoom: 19 }).addTo(map);

  const redIcon = L.divIcon({ html: '<div style="font-size:2rem;line-height:1;filter:drop-shadow(0 2px 4px rgba(0,0,0,.4));">📍</div>', className: '', iconAnchor: [16, 32], iconSize: [32, 32] });
  marker = L.marker(GCH, { draggable: true, icon: redIcon }).addTo(map);
  marker.bindPopup('<b>Your listing</b><br>Drag to reposition', { closeButton: false }).openPopup();

  marker.on('dragend', () => {
    const p = marker.getLatLng();
    pinLat = p.lat; pinLng = p.lng;
    updateCoordDisplay();
  });

  map.on('click', e => {
    marker.setLatLng(e.latlng);
    pinLat = e.latlng.lat; pinLng = e.latlng.lng;
    updateCoordDisplay();
    showToast('📍 Location pinned!', 'ok');
  });
});

function updateCoordDisplay() {
  document.getElementById('coordDisplay').textContent = `📍 Lat: ${pinLat.toFixed(5)},  Lng: ${pinLng.toFixed(5)}`;
}
function useMyLocation() {
  if (!navigator.geolocation) { showToast('GPS not available', 'warn'); return; }
  showToast('Getting your location…');
  navigator.geolocation.getCurrentPosition(pos => {
    const ll = [pos.coords.latitude, pos.coords.longitude];
    pinLat = ll[0]; pinLng = ll[1];
    marker.setLatLng(ll); map.setView(ll, 18);
    updateCoordDisplay();
    showToast('📍 Location set to GPS!', 'ok');
  }, () => showToast('⚠️ Could not get location. Tap map to pin.', 'warn'));
}
function resetPin() {
  pinLat = GCH[0]; pinLng = GCH[1];
  marker.setLatLng(GCH); map.setView(GCH, 17);
  updateCoordDisplay();
}

/* ── Publish ── */
function publishListing() {
  const street = document.getElementById('street').value.trim();
  const price  = document.getElementById('price').value;
  if (!street) { showToast('⚠️ Street/Address is required', 'warn'); return; }
  if (!price)  { showToast('⚠️ Monthly rent is required', 'warn'); return; }

  // Build listing object (would be POSTed to API in production)
  const listing = {
    street, price: parseInt(price),
    type:   document.getElementById('type').value,
    gender: document.getElementById('gender').value,
    walk:   parseInt(document.getElementById('walk').value) || 10,
    bath:   document.getElementById('bath').value,
    furn:   document.getElementById('furn').value,
    app:    document.getElementById('app').value,
    bills:  document.getElementById('bills').value,
    curfew: document.getElementById('curfew').value,
    wifi:   document.getElementById('wifi').checked,
    pets:   document.getElementById('pets').checked,
    near:   document.getElementById('near').value,
    lat:    pinLat, lng: pinLng,
    photos: photos.map(p => p.dataUrl),
    status: 'Available',
  };

  console.log('New listing:', listing);
  showToast('🎉 Listing published!', 'ok');
  setTimeout(() => window.location.href = 'index', 1200);
}

function showToast(msg, cls = '') {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.className = 'show ' + cls;
  clearTimeout(t._t);
  t._t = setTimeout(() => { t.className = ''; }, 3000);
}
</script>
</body>
</html>