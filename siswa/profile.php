<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
    header('Location: ../index.php');
    exit;
}
require '../koneksi.php';

$id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$id'");
$data = mysqli_fetch_assoc($query);

$nilai = mysqli_query($conn, "SELECT AVG(nilai) as rata2 FROM penilaian WHERE user_id='$id'");
$rata = mysqli_fetch_assoc($nilai);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profil Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h3>Profil Siswa</h3>
    <ul class="list-group">
        <li class="list-group-item">Nama: <?= htmlspecialchars($data['name']) ?></li>
        <li class="list-group-item">Email: <?= htmlspecialchars($data['email']) ?></li>
        <li class="list-group-item">Rata-rata Nilai: <?= number_format($rata['rata2'], 2) ?></li>
    </ul>
</body>
</html>
