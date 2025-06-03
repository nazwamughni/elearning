<?php
// config.php
$host = "localhost";
$dbname = "elearning_db";
$username = "root";      // ganti sesuai user mysql kamu
$password = "";          // ganti sesuai password mysql kamu

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set error mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
