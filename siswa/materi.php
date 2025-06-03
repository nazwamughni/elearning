<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
    header('Location: ../index.php');
    exit;
}

require '../config.php';

$stmt = $pdo->query("SELECT * FROM materi ORDER BY created_at DESC");
$materis = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<title>Materi Siswa</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light px-3">
  <a class="navbar-brand" href="dashboard.php">E-Learning</a>
  <ul class="navbar-nav ms-auto">
    <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
    <li class="nav-item"><a href="profile.php" class="nav-link">Profile</a></li>
    <li class="nav-item"><a href="tugas.php" class="nav-link">Tugas</a></li>
    <li class="nav-item"><a href="kuis.php" class="nav-link">Kuis</a></li>
    <li class="nav-item"><a href="../logout.php" class="nav-link">Logout</a></li>
  </ul>
</nav>

<div class="container mt-4">
  <h3>Materi Pelajaran</h3>
  <?php if (count($materis) === 0): ?>
    <p>Belum ada materi yang diupload.</p>
  <?php else: ?>
    <div class="list-group">
      <?php foreach ($materis as $materi): ?>
        <a href="<?= htmlspecialchars($materi['file_path']) ?>" class="list-group-item list-group-item-action" target="_blank">
          <?= htmlspecialchars($materi['title']) ?>
          <small class="text-muted d-block">Diupload: <?= $materi['created_at'] ?></small>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
