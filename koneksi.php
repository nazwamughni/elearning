<?php
$host = "localhost";      // biasanya localhost
$user = "root";           // default user XAMPP
$pass = "";               // kosongkan jika tidak pakai password
$db   = "elearning_db";   // sesuaikan dengan nama database kamu

$conn = mysqli_connect($host, $user, $pass, $db);

// Periksa koneksi
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>
