@extends('layouts.app')

@section('title', 'Live Navigation Map')

@section('content')

<div class="wrap">

    @include('partials.navbar')

    <div class="screen active">

        {{-- INFO --}}
        <div class="map-info-card">
            <strong>🚀 Live Navigation Mode</strong><br>
            <small id="statusText">Detecting location...</small>
        </div>

        {{-- NEAR ME --}}
        <button class="near-btn" onclick="locateMe()">📍 Near Me</button>

        {{-- MAP --}}
        <div id="map"></div>

        {{-- NAVIGATION STEPS PANEL --}}
        <div id="navPanel" class="nav-panel hidden">
            <h4>🧭 Directions</h4>
            <div id="steps">Calculating route...</div>
        </div>

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

/* NEAR */
.near-btn{
    position:absolute;
    bottom:140px;
    right:15px;
    z-index:1000;
    background:#2563eb;
    color:#fff;
    border:none;
    padding:10px 14px;
    border-radius:50px;
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

/* ZOOM CONTROL MOVE */
.leaflet-control-zoom{
    position: fixed !important;
    bottom: 120px;
    right: 10px;
}

</style>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // 🟡 GOLDEN COUNTRY HOMES CENTER
    const CENTER = { lat: 13.7816, lng: 121.0659 };

    const map = L.map('map', {
        zoomControl: false
    }).setView([CENTER.lat, CENTER.lng], 17);

    L.control.zoom({ position: 'bottomright' }).addTo(map);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
    }).addTo(map);

    let userLatLng = null;
    let routeLine = null;
    let selectedMarker = null;

    // 🔴 YOU ICON
    const youIcon = L.divIcon({
        className: 'you-icon',
        html: '🔴'
    });

    // 🟢 🔵 ICONS
    const greenIcon = new L.Icon({
        iconUrl: "https://maps.google.com/mapfiles/ms/icons/green-dot.png",
        iconSize: [32,32]
    });

    const blueIcon = new L.Icon({
        iconUrl: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png",
        iconSize: [32,32]
    });

    // 📌 DORM DATA
    let dorms = [
        @foreach($dormListings as $dorm)
        {
            id: {{ $dorm->id }},
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

    // 📍 MARKERS
    dorms.forEach(d => {

        if(!d.lat || !d.lng) return;

        // type color
        let icon = greenIcon;
        if(d.type === 'Unit') icon = blueIcon;

        const marker = L.marker([d.lat, d.lng], {icon}).addTo(map);

        marker.on('click', () => {
            showPreview(d);
            drawRoute(d.lat, d.lng);
        });
    });

    // 🔴 USER LOCATION
    if(navigator.geolocation){

        navigator.geolocation.watchPosition(pos => {

            userLatLng = [pos.coords.latitude, pos.coords.longitude];

            L.marker(userLatLng, {icon: youIcon}).addTo(map);

            document.getElementById('statusText').innerText =
                "Live location active";

        });

    }

    // 🧭 ROUTE + LIVE NAV STYLE
    function drawRoute(lat,lng){

        if(!userLatLng) return;

        const url =
        `https://router.project-osrm.org/route/v1/walking/${userLatLng[1]},${userLatLng[0]};${lng},${lat}?overview=full&geometries=geojson`;

        fetch(url)
        .then(res => res.json())
        .then(data => {

            const coords = data.routes[0].geometry.coordinates;
            const latlngs = coords.map(c => [c[1], c[0]]);

            if(routeLine) map.removeLayer(routeLine);

            routeLine = L.polyline(latlngs,{
                color:"#2563eb",
                weight:5
            }).addTo(map);

            // 🚀 fake step UI (can upgrade later to real steps API)
            document.getElementById('navPanel').classList.remove('hidden');
            document.getElementById('steps').innerHTML = `
                <b>Step 1:</b> Head towards destination<br>
                <b>Step 2:</b> Walk straight ~${Math.round(data.routes[0].distance)}m<br>
                <b>Step 3:</b> Arrive at location
            `;
        });
    }

    // 🧾 POPUP
    window.showPreview = function(d){

        document.getElementById('pStreet').innerText = d.street;
        document.getElementById('pPrice').innerText = "₱" + d.price + "/mo";
        document.getElementById('pType').innerText = d.type;
        document.getElementById('pImage').src = d.image;

        document.getElementById('msgBtn').href =
            "/messages/create?dorm_id=" + d.id;

        document.getElementById('dirBtn').href =
            `https://www.google.com/maps/dir/?api=1&destination=${d.lat},${d.lng}`;

        document.getElementById('dormPreview').classList.remove('hidden');
    }

    window.closePreview = () =>
        document.getElementById('dormPreview').classList.add('hidden');

    // 📍 CENTER USER
    window.locateMe = function(){
        if(!userLatLng) return;
        map.flyTo(userLatLng, 18);
    }

});
</script>

@endsection