<?php
$conn = new mysqli("localhost", "root", "", "faskes_mjk");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM faskes");
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        "id"        => $row["id"],
        "jenis"     => $row["jenis"],
        "nama"      => $row["nama faskes"],
        "lat"       => (float) $row["latitude"],
        "lng"       => (float) $row["longitude"],
        "umum"      => $row["jumlah dokter umum"],
        "spesialis" => $row["jumlah dokter spesialis"],
        "gigi"      => $row["jumlah dokter gigi"]
    ];
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<!-- Metadata -->
<meta charset="utf-8">
<!-- Judul pada tab browser -->
<title>FASKES KOTA MOJOKERTO</title>
<!-- Leaflet CSS Library -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.polylinemeasure@3.0.0/Leaflet.PolylineMeasure.css">
<style>
/* Tampilan peta fullscreen */
html, body, #map {
height: 100%;
width: 100%;
margin: 0px;
}
</style>
</head>
<body>
<!-- Leaflet JavaScript Library -->
<script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.polylinemeasure@3.0.0/Leaflet.PolylineMeasure.js"></script>
<div id="map"></div>
<script>
/* Initial Map */
var map = L.map('map').setView([-7.4726, 112.4338],11); //lat, long, zoom
/* Tile Basemap */
var basemap1 =
L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
attribution: 'Tiles &copy; Esri'
});
var basemap2 =
L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
maxZoom: 20,
subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
attribution: 'Map data &copy; Google'
});
basemap1.addTo(map);
/* Layer Marker per jenis faskes */
var faskes = <?php echo json_encode($data); ?>;
var perJenis = {};

faskes.forEach(function (f) {
    if (!perJenis[f.jenis]) {
        perJenis[f.jenis] = L.featureGroup().addTo(map);
    }
    L.marker([f.lat, f.lng])
        .bindPopup(
            "<b>" + f.nama + "</b><br>" +
            "Jenis: " + f.jenis + "<br>" +
            "Dokter umum: " + f.umum + "<br>" +
            "Dokter spesialis: " + f.spesialis + "<br>" +
            "Dokter gigi: " + f.gigi
        )
        .addTo(perJenis[f.jenis]);
});

/* Zoom otomatis ke seluruh marker */
if (faskes.length > 0) {
    var semua = L.featureGroup(Object.values(perJenis));
    map.fitBounds(semua.getBounds().pad(0.2));
}

/* Control Layer */
var baseMaps = {
    "Esri Imagery": basemap1,
    "Google Street": basemap2
};
L.control.layers(baseMaps, perJenis, {collapsed: false}).addTo(map);

/* Tool Ukur Jarak */
L.control.polylineMeasure({
    position: 'topleft',
    unit: 'metres',
    showBearings: false,
    clearMeasurementsOnStop: false
}).addTo(map);
/* Scale Bar */
L.control.scale({
maxWidth: 150,position:'bottomright'
}).addTo(map);
</script>
</body>
</html>