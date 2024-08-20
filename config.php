<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_gigseats";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully";
}

// Disarankan untuk menutup koneksi setelah selesai digunakan
// $conn->close();
?>
