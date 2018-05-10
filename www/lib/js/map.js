
var map, newUser, users, mapquest, firstLoad;

firstLoad = true;

//users = new L.FeatureGroup();
users = new L.MarkerClusterGroup({spiderfyOnMaxZoom: true, showCoverageOnHover: false, zoomToBoundsOnClick: true});
newUser = new L.LayerGroup();

mapquest = new L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
});

map = new L.Map('map', {
    center: new L.LatLng(49.3130650, 16.4777650),
    zoom: 15,
    layers: [mapquest, users, newUser]
});


$(document).ready(function() {
    $.ajaxSetup({cache:false});
    $('#map').css('height', ($(window).height() - 200));
    // getUsers(); //TODO odkomentovat
});


$(window).resize(function () {
    $('#map').css('height', ($(window).height() - 200));
    $('#map').css('height', ($(window).width() - 200));

}).resize();


function initRegistration() {
    map.addEventListener('click', onMapClick);
    $('#map').css('cursor', 'crosshair');
    return false;
}

function cancelRegistration() {
    newUser.clearLayers();
    $('#map').css('cursor', '');
    map.removeEventListener('click', onMapClick);
}






















/*

var popup = L.popup();


var lat = 49.313967;
var lng = 16.476821;

var marker = L.marker([lat,lng]).addTo(map)
    .bindPopup("Zde bydlím"
    ).openPopup();

function geoLocate() {
    map.locate({setView: true, maxZoom: 17});
}
var geolocControl = new L.control({
    position: 'topright'
});
geolocControl.onAdd = function (map) {
    var div = L.DomUtil.create('div', 'leaflet-control-zoom leaflet-control');
    div.innerHTML = '<a class="leaflet-control-geoloc" href="#" onclick="geoLocate(); return false;" title="My location"></a>';
    return div;
};

*/



