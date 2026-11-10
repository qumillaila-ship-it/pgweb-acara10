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
<html lang="id">
<head>
<meta charset="utf-8">
<title>FASKES KOTA MOJOKERTO</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.polylinemeasure@3.0.0/Leaflet.PolylineMeasure.css">
<style>
body { margin: 0; font-family: Arial, sans-serif; }
#map { height: 60vh; width: 100%; }
.isi { padding: 16px; }
table { border-collapse: collapse; width: 100%; }
th, td { border: 1px solid #999; padding: 8px; text-align: left; }
th { background: #eee; }
</style>
</head>
<body>

<div id="map"></div>

<div class="isi">
    <h2>Data Fasilitas Kesehatan</h2>
    <p><a href="input/index.html">Input Data</a></p>

    <?php if (count($data) > 0) { ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Jenis</th>
            <th>Nama Faskes</th>
            <th>Longitude</th>
            <th>Latitude</th>
            <th>Jumlah Dokter Umum</th>
            <th>Jumlah Dokter Spesialis</th>
            <th>Jumlah Dokter Gigi</th>
            <th colspan="2">Aksi</th>
        </tr>
        <?php foreach ($data as $f) { ?>
        <tr>
            <td><?php echo $f["id"]; ?></td>
            <td><?php echo htmlspecialchars($f["jenis"]); ?></td>
            <td><a href="#map" onclick="fokus(<?php echo (int) $f['id']; ?>)"><?php echo htmlspecialchars($f["nama"]); ?></a></td>
            <td><?php echo $f["lng"]; ?></td>
            <td><?php echo $f["lat"]; ?></td>
            <td><?php echo $f["umum"]; ?></td>
            <td><?php echo $f["spesialis"]; ?></td>
            <td><?php echo $f["gigi"]; ?></td>
            <td><a href="edit/index.php?id=<?php echo $f['id']; ?>">edit</a></td>
            <td><a href="delete.php?id=<?php echo $f['id']; ?>" onclick="return confirm('Hapus data ini?')">hapus</a></td>
        </tr>
        <?php } ?>
    </table>
    <?php } else { echo "Tidak ada data."; } ?>
</div>

<script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.polylinemeasure@3.0.0/Leaflet.PolylineMeasure.js"></script>
<script>
/* Initial Map */
var map = L.map('map').setView([-7.4726, 112.4338], 12);

/* Tile Basemap */
var basemap1 = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: 'Tiles &copy; Esri'
});
var basemap2 = L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
    attribution: 'Map data &copy; Google'
});
basemap1.addTo(map);

/* Layer Marker per jenis faskes */
var faskes = <?php echo json_encode($data); ?>;
var perJenis = {};
var markerById = {};

faskes.forEach(function (f) {
    if (!perJenis[f.jenis]) {
        perJenis[f.jenis] = L.featureGroup().addTo(map);
    }
    markerById[f.id] = L.marker([f.lat, f.lng])
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

/* Klik nama di tabel: peta zoom ke marker dan popup terbuka */
function fokus(id) {
    var m = markerById[id];
    if (!m) return;
    map.flyTo(m.getLatLng(), 17);
    m.openPopup();
}

/* Control Layer */
var baseMaps = {
    "Esri Imagery": basemap1,
    "Google Street": basemap2
};
L.control.layers(baseMaps, perJenis, {collapsed: false}).addTo(map);

/* Scale Bar */
L.control.scale({maxWidth: 150, position: 'bottomright'}).addTo(map);

/* Tool Ukur Jarak */
L.control.polylineMeasure({
    position: 'topleft',
    unit: 'metres',
    showBearings: false,
    clearMeasurementsOnStop: false
}).addTo(map);
</script>
</body>
</html>