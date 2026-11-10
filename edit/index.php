<!DOCTYPE html>
<html>
<head>
    <title>Form Edit Faskes</title>
</head>
<body>
    <h2>Form Edit Data Faskes</h2>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "faskes_mjk";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $id = $_GET['id'];
    $sql = "SELECT * FROM faskes WHERE id = $id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        echo "<form action='edit.php' onsubmit='return validateForm(event)' method='post'>";
        while($row = $result->fetch_assoc()) {
            echo "<input type='hidden' name='id' value='".$row['id']."'><br>";
            
            echo "<label for='jenis'>Jenis Faskes:</label><br>";
            echo "<input type='text' id='jenis' name='jenis' value='".$row['jenis']."' required><br><br>";
            
            echo "<label for='nama_faskes'>Nama Faskes:</label><br>";
            echo "<input type='text' id='nama_faskes' name='nama_faskes' value='".$row['nama faskes']."' required><br><br>";
            
            echo "<label for='longitude'>Longitude:</label><br>";
            echo "<input type='text' id='long' name='longitude' value='".$row['longitude']."' required><br><br>";
            
            echo "<label for='latitude'>Latitude:</label><br>";
            echo "<input type='text' id='lat' name='latitude' value='".$row['latitude']."' required><br><br>";
            
            echo "<label for='jumlah_dokter_umum'>Jumlah Dokter Umum:</label><br>";
            echo "<input type='number' id='jml_umum' name='jumlah_dokter_umum' value='".$row['jumlah dokter umum']."' min='0' required><br><br>";
            
            echo "<label for='jumlah_dokter_spesialis'>Jumlah Dokter Spesialis:</label><br>";
            echo "<input type='number' id='jml_spesialis' name='jumlah_dokter_spesialis' value='".$row['jumlah dokter spesialis']."' min='0' required><br><br>";
            
            echo "<label for='jumlah_dokter_gigi'>Jumlah Dokter Gigi:</label><br>";
            echo "<input type='number' id='jml_gigi' name='jumlah_dokter_gigi' value='".$row['jumlah dokter gigi']."' min='0' required><br><br>";
        }
        echo "<input type='submit' value='Submit'>";
        echo "</form>";
    } else {
        echo "Data tidak ditemukan.";
    }

    $conn->close();
    ?>
    
    <p id="informasi" style="color:red;"></p>

    <script>
    function validateForm(event) {
        let long = document.getElementById("long").value;
        let lat = document.getElementById("lat").value;
        let text = "";
        
        if (isNaN(long) || isNaN(lat) || long === "" || lat === "") {
            text = "Data Longitude dan Latitude harus berupa angka valid!";
            event.preventDefault();
            document.getElementById("informasi").innerHTML = text;
            return false;
        }
        return true;
    }
    </script>
</body>
</html>