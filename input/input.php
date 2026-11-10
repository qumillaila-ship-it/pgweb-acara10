<?php
$jenis = $_POST['jenis'];
$nama_faskes = $_POST['nama_faskes'];
$longitude = $_POST['longitude'];
$latitude = $_POST['latitude'];
$jumlah_dokter_umum = $_POST['jumlah_dokter_umum'];
$jumlah_dokter_spesialis = $_POST['jumlah_dokter_spesialis'];
$jumlah_dokter_gigi = $_POST['jumlah_dokter_gigi'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "faskes_mjk";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO faskes (`jenis`, `nama faskes`, `longitude`, `latitude`, `jumlah dokter umum`, `jumlah dokter spesialis`, `jumlah dokter gigi`)
        VALUES ('$jenis', '$nama_faskes', $longitude, $latitude, $jumlah_dokter_umum, $jumlah_dokter_spesialis, $jumlah_dokter_gigi)";

if ($conn->query($sql) === TRUE) {
    $conn->close();
    header("Location: ../index.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    $conn->close();
}
?>