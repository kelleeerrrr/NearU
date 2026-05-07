@extends('layouts.app')

@section('title', 'Live Navigation Map')

@section('content')

<div class="wrap">

    @include('partials.navbar')

    <div class="screen active">

        {{-- INFO --}}
        <div class="map-info-card">
            <strong>🏫 Batangas State University Alangilan Campus</strong><br>
            <small id="statusText">Showing dorms near campus</small>
        </div>

        
        
        {{-- PIN TYPES LEGEND --}}
        <div class="pin-legend">
            <div class="legend-items">
                <div class="legend-item">
                    <span class="legend-icon marker-room">🛏️</span>
                    <span>Room</span>
                </div>
                <div class="legend-item">
                    <span class="legend-icon marker-bedspace">🛌</span>
                    <span>Bedspace</span>
                </div>
                <div class="legend-item">
                    <span class="legend-icon marker-unit">🏠</span>
                    <span>Unit</span>
                </div>
            </div>
        </div>
        
        {{-- MAP --}}
        <div id="map"></div>

        
        {{-- POPUP CARD --}}
        <div id="dormPreview" class="preview hidden">

            <img id="pImage">

            <h4 id="pStreet"></h4>
            <p id="pPrice"></p>
            <p id="pType"></p>

            <div class="preview-actions">
                <a id="msgBtn" class="btn blue">💬 Message</a>
                <a id="dirBtn" target="_blank" class="btn green">🧭 Directions</a>
                <button onclick="closePreview()" class="btn gray">Close</button>
            </div>
        </div>

    </div>

    @include('partials.footer')

</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>

/* MAP */
#map{
    width:100%;
    height:100%;
}

/* SCREEN */
.screen{
    height:calc(100vh - 120px);
    position:relative;
}

/* INFO */
.map-info-card{
    position:absolute;
    top:12px;
    left:12px;
    right:12px;
    z-index:1000;
    background:#fff;
    padding:12px;
    border-radius:14px;
    box-shadow:0 6px 18px rgba(0,0,0,.1);
}


/* POPUP */
.preview{
    position:absolute;
    bottom:90px;
    left:10px;
    right:10px;
    background:#fff;
    padding:12px;
    border-radius:14px;
    z-index:1000;
}

.preview.hidden{display:none;}
.preview img{width:100%;height:120px;object-fit:cover;border-radius:10px;margin-bottom:8px;}

/* NAV PANEL */
.nav-panel{
    position:absolute;
    top:90px;
    right:10px;
    width:220px;
    background:#fff;
    border-radius:12px;
    padding:10px;
    z-index:1000;
    box-shadow:0 6px 18px rgba(0,0,0,.15);
    font-size:.85rem;
}
.nav-panel.hidden{display:none;}

/* BUTTONS */
.btn{
    padding:8px 12px;
    border-radius:10px;
    font-size:.8rem;
    font-weight:700;
    text-decoration:none;
    border:none;
}
.btn.blue{background:#2563eb;color:#fff;}
.btn.green{background:#16a34a;color:#fff;}
.btn.gray{background:#e5e7eb;}

/* CUSTOM ZOOM CONTROLS */
.custom-zoom-control {
    display: flex;
    flex-direction: column;
    gap: 1px;
}

.zoom-btn {
    width: 28px;
    height: 28px;
    border: none;
    border-radius: 6px;
    background: linear-gradient(135deg, #2D7D4F, #1f5c38);
    color: white;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Arial', sans-serif;
    line-height: 1;
    box-shadow: 0 2px 6px rgba(45, 125, 79, 0.3);
    margin-bottom: 1.4rem;
}

.zoom-btn:hover {
    background: linear-gradient(135deg, #1f5c38, #2D7D4F);
    transform: scale(1.1);
    box-shadow: 0 6px 16px rgba(45, 125, 79, 0.3);
}

.zoom-btn:active {
    transform: scale(0.95);
}

/* CUSTOM MARKER STYLES */
.custom-marker {
    background: white;
    border-radius: 12px;
    padding: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border: 3px solid #2D7D4F;
    font-weight: 700;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 30px;
    height: 30px;
}

.marker-room {
    background: linear-gradient(135deg, #e8f5ee, #d1fae5);
    color: #1f5c38;
    border-color: #2D7D4F;
}

.marker-bedspace {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1d4ed8;
    border-color: #2563eb;
}

.marker-unit {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400E;
    border-color: #F2B705;
}

/* MAP IMPROVEMENTS */
#map {
    width: 100%;
    height: 100%;
    border-radius: 16px;
    overflow: hidden;
}

.leaflet-popup-content-wrapper {
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
}

.leaflet-popup-content {
    margin: 0;
    border-radius: 12px;
}

.leaflet-popup-tip {
    background: white;
}

.custom-popup .leaflet-popup-content {
    font-family: 'DM Sans', sans-serif;
    border-radius: 12px;
}

/* USER ICON STYLING */
.you-icon {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    border: 2px solid #dc2626;
    box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
}

/* PIN LEGEND */
.pin-legend {
    position: absolute;
    bottom: 20px;
    left: 12px;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 8px;
    padding: 6px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    border: 1px solid #2D7D4F;
    z-index: 1000;
    min-width: 50px;
    margin-bottom: 1.4rem;
}

.legend-title {
    font-weight: 700;
    font-size: 0.85rem;
    margin-bottom: 8px;
    color: #2D7D4F;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.legend-items {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.7rem;
    font-weight: 600;
}

.legend-icon {
    width: 16px;
    height: 16px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 8px;
    border: 1px solid;
}


</style>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // 🏫 BATANGAS STATE UNIVERSITY ALANGILAN CAMPUS
    const CENTER = { lat: 13.7841, lng: 121.0742 };

    const map = L.map('map', {
        zoomControl: false
    }).setView([CENTER.lat, CENTER.lng], 13);

    // Custom zoom controls
    const customZoomControl = L.control({ position: 'bottomright' });
    customZoomControl.onAdd = function(map) {
        const div = L.DomUtil.create('div', 'custom-zoom-control');
        div.innerHTML = `
            <button id="zoomIn" class="zoom-btn zoom-in">+</button>
            <button id="zoomOut" class="zoom-btn zoom-out">−</button>
        `;
        
        div.querySelector('#zoomIn').onclick = () => map.zoomIn();
        div.querySelector('#zoomOut').onclick = () => map.zoomOut();
        
        return div;
    };
    customZoomControl.addTo(map);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
    }).addTo(map);

    // 🏫 CAMPUS BOUNDARY
    const campusBoundary = L.circle([CENTER.lat, CENTER.lng], {
        color: '#2D7D4F',
        fillColor: '#2D7D4F',
        fillOpacity: 0.15,
        radius: 2000,
        weight: 3
    }).addTo(map);
    
    // Zoom to campus
    setTimeout(() => {
        map.setView([CENTER.lat, CENTER.lng], 16);
    }, 1000);
    
    // Ensure campus boundary is visible
    setTimeout(() => {
        map.fitBounds(campusBoundary.getBounds());
    }, 1000);

    let userLatLng = null;
    let routeLine = null;
    let selectedMarker = null;

    // 🔴 YOU ICON
    const youIcon = L.divIcon({
        className: 'you-icon',
        html: '🔴'
    });

    // 🎨 CUSTOM MARKER ICONS
    const createCustomIcon = (type, emoji) => {
        const colorClass = type === 'Room' ? 'marker-room' : 
                         type === 'Bedspace' ? 'marker-bedspace' : 'marker-unit';
        
        return L.divIcon({
            className: `custom-marker ${colorClass}`,
            html: `<span style="font-size: 16px;">${emoji}</span>`,
            iconSize: [30, 30],
            iconAnchor: [15, 15],
            popupAnchor: [0, -15]
        });
    };

    const roomIcon = createCustomIcon('Room', '🛏️');
    const bedspaceIcon = createCustomIcon('Bedspace', '🛌');
    const unitIcon = createCustomIcon('Unit', '🏠');

    // 📌 DORM DATA
    let dorms = [
        @foreach($dormListings as $dorm)
        {
            id: {{ $dorm->id }},
            owner_id: {{ $dorm->owner_id }},
            street: @json($dorm->street),
            price: {{ $dorm->price }},
            type: @json($dorm->type),
            lat: {{ $dorm->latitude ?? 'null' }},
            lng: {{ $dorm->longitude ?? 'null' }},
            image: @json(optional($dorm->images->first())->path
                ? asset('storage/' . $dorm->images->first()->path)
                : 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=400'
            )
        },
        @endforeach
    ];
    
    // 🧾 POPUP FUNCTIONS
    window.showPreview = function(d){
        document.getElementById('pStreet').innerText = d.street;
        document.getElementById('pPrice').innerText = "₱" + d.price + "/mo";
        document.getElementById('pType').innerText = d.type;
        document.getElementById('pImage').src = d.image;
        
        document.getElementById('msgBtn').href =
            "/messages/" + d.id + "/" + d.owner_id;
        
        document.getElementById('dirBtn').href =
            `https://www.google.com/maps/dir/?api=1&destination=${d.lat},${d.lng}`;
        
        document.getElementById('dormPreview').classList.remove('hidden');
    }
    
    window.closePreview = () =>
        document.getElementById('dormPreview').classList.add('hidden');
    
        
    // 📍 MARKERS
    dorms.forEach(d => {
        // Skip listings without valid coordinates
        if (!d.lat || !d.lng || d.lat === null || d.lng === null) {
            console.log('Skipping dorm without coordinates:', d.street);
            return;
        }
        
        // Get appropriate icon based on type
        let icon = roomIcon;
        if(d.type === 'Bedspace') icon = bedspaceIcon;
        if(d.type === 'Unit') icon = unitIcon;
        
        const marker = L.marker([d.lat, d.lng], {icon}).addTo(map);
        
        // Add popup tooltip
        marker.bindPopup(`
            <div style="padding: 8px; font-weight: 600; text-align: center;">
                <strong>${d.street}</strong><br>
                🏠 ${d.type}<br>
                💰 ₱${d.price}/mo
            </div>
        `, {
            className: 'custom-popup'
        });
        
        marker.on('click', () => {
            showPreview(d);
            drawRoute(d.lat, d.lng);
        });
        
        // Hover effect
        marker.on('mouseover', () => {
            marker.openPopup();
        });
    });
    
    // 🔴 USER LOCATION
    let userMarker = null;
    if(navigator.geolocation){

        navigator.geolocation.watchPosition(pos => {

            userLatLng = [pos.coords.latitude, pos.coords.longitude];

            // Remove previous marker if exists
            if (userMarker) {
                map.removeLayer(userMarker);
            }

            // Add new marker
            userMarker = L.marker(userLatLng, {icon: youIcon}).addTo(map);

            document.getElementById('statusText').innerText =
                "Live location active";

        });

    }

    
    
});
</script>

@endsection