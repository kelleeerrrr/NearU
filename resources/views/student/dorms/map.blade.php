@extends('layouts.app')

@section('title', 'GCH Live Navigation Map')

@section('content')

<div class="wrap">

    @include('partials.navbar')

    <div class="screen active">

        {{-- INFO PANEL --}}
        <div class="map-info-card">
            <strong>Golden Country Homes Map</strong><br>
            <small id="statusText">Detecting location...</small>
        </div>

        {{-- NEAR ME BUTTON --}}
        <button class="near-btn" onclick="locateMe()">
            📍 Near Me
        </button>

        {{-- MAP --}}
        <div id="map"></div>

        {{-- DORM PREVIEW CARD (LIKE GOOGLE MAPS) --}}
        <div id="dormPreview" class="preview hidden">
            <h4 id="pTitle"></h4>
            <p id="pPrice"></p>
            <p id="pLocation"></p>

            <div class="preview-actions">
                <a id="viewBtn" href="#" class="btn blue">View</a>
                <button onclick="closePreview()" class="btn gray">Close</button>
            </div>
        </div>

    </div>

    @include('partials.footer')

</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>

.screen{
    height:calc(100vh - 120px);
    position:relative;
}

#map{
    width:100%;
    height:100%;
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

/* NEAR ME BUTTON */
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
    cursor:pointer;
}

/* PREVIEW CARD */
.preview{
    position:absolute;
    bottom:90px;
    left:10px;
    right:10px;
    background:#fff;
    padding:12px;
    border-radius:14px;
    box-shadow:0 6px 18px rgba(0,0,0,.15);
    z-index:1000;
}

.preview.hidden{ display:none; }

.preview h4{ margin:0; font-size:1rem; }
.preview p{ margin:2px 0; font-size:.85rem; }

.preview-actions{
    display:flex;
    gap:.5rem;
    margin-top:.5rem;
}

.btn{
    padding:8px 12px;
    border-radius:10px;
    text-decoration:none;
    font-size:.8rem;
    font-weight:700;
}

.btn.blue{ background:#2563eb; color:#fff; }
.btn.gray{ background:#e5e7eb; color:#000; border:none; }

</style>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const CENTER = { lat: 13.7816, lng: 121.0659 };

    const map = L.map('map').setView([CENTER.lat, CENTER.lng], 17);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
    }).addTo(map);

    let userMarker = null;
    let userLatLng = null;
    let routeLine = null;
    let activeDestination = null;
    let selectedMarker = null;

    let dorms = [
        @foreach($dormListings as $dorm)
        {
            id: {{ $dorm->id }},
            title: @json($dorm->title),
            price: {{ $dorm->price }},
            location: @json($dorm->location),
            lat: {{ $dorm->latitude ?? 'null' }},
            lng: {{ $dorm->longitude ?? 'null' }}
        },
        @endforeach
    ];

    // MARKERS
    dorms.forEach(d => {
        if(!d.lat || !d.lng) return;

        const marker = L.marker([d.lat, d.lng]).addTo(map);

        marker.on('click', function () {

            showPreview(d);

            activeDestination = d;

            if(userLatLng){
                drawRoute(d.lat, d.lng);
            }

            if(selectedMarker){
                selectedMarker.setOpacity(1);
            }

            marker.setOpacity(0.6);
            selectedMarker = marker;
        });
    });

    // GPS TRACKING
    if(navigator.geolocation){

        navigator.geolocation.watchPosition(function(pos){

            userLatLng = [
                pos.coords.latitude,
                pos.coords.longitude
            ];

            if(!userMarker){
                userMarker = L.circleMarker(userLatLng,{
                    radius:8,
                    color:"#2563eb",
                    fillColor:"#2563eb",
                    fillOpacity:1
                }).addTo(map);
            } else {
                userMarker.setLatLng(userLatLng);
            }

            document.getElementById('statusText').innerText =
                "Live location active";

        });

    } else {
        document.getElementById('statusText').innerText =
            "Location not supported";
    }

    // ROUTE
    function drawRoute(lat,lng){

        const url =
        `https://router.project-osrm.org/route/v1/walking/${userLatLng[1]},${userLatLng[0]};${lng},${lat}?overview=full&geometries=geojson`;

        fetch(url)
        .then(res => res.json())
        .then(data => {

            const coords = data.routes[0].geometry.coordinates;

            const latlngs = coords.map(c => [c[1], c[0]]);

            if(routeLine){
                map.removeLayer(routeLine);
            }

            routeLine = L.polyline(latlngs,{
                color:"#2563eb",
                weight:5
            }).addTo(map);
        });
    }

    // PREVIEW CARD
    window.showPreview = function(d){

        document.getElementById('pTitle').innerText = d.title;
        document.getElementById('pPrice').innerText = "₱" + d.price;
        document.getElementById('pLocation').innerText = d.location;

        document.getElementById('viewBtn').href = "/dorms/" + d.id;

        document.getElementById('dormPreview').classList.remove('hidden');
    }

    window.closePreview = function(){
        document.getElementById('dormPreview').classList.add('hidden');
    }

    // NEAR ME
    window.locateMe = function(){

        if(!userLatLng) return alert("Location not found yet");

        map.flyTo(userLatLng, 18, {
            animate:true,
            duration:1.2
        });
    }

});
</script>

@endsection