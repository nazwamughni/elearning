<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<title>Dashboard Siswa</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light px-3">
  <a class="navbar-brand" href="#">E-Learning</a>
  <div class="collapse navbar-collapse">
    <ul class="navbar-nav ms-auto">
      <li class="nav-item"><a href="profile.php" class="nav-link">Profile</a></li>
      <li class="nav-item"><a href="materi.php" class="nav-link">Materi</a></li>
      <li class="nav-item"><a href="tugas.php" class="nav-link">Tugas</a></li>
      <li class="nav-item"><a href="kuis.php" class="nav-link">Kuis</a></li>
      <li class="nav-item"><a href="../logout.php" class="nav-link">Logout</a></li>
    </ul>
  </div>
</nav>
<div class="container mt-4">
  <h3>Selamat datang, <?= htmlspecialchars($_SESSION['name']) ?></h3>
  <p>Ini adalah dashboard siswa.</p>
  <!-- Bisa kamu tambahkan statistik, tugas terbaru, pengumuman dll -->
</div>
</body>
</html>
