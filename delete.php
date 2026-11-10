<?php
$id = $_GET['id'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "faskes_mjk";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
//DELETE FROM table_name WHERE condition;
$sql = "DELETE FROM faskes WHERE id = $id";
if ($conn->query($sql) === TRUE) {
 echo "Record with id = $id deleted successfully";
} else {
 echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
header("Location: index.php");
?>