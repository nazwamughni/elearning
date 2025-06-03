<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    header('Location: ../index.php');
    exit;
}

require '../config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $file = $_FILES['file'] ?? null;

    if (!$title || !$file) {
        $error = "Judul dan file harus diisi.";
    } else {
        // Upload file
        $uploads_dir = '../uploads/materi';
        if (!is_dir($uploads_dir)) {
            mkdir($uploads_dir, 0777, true);
        }

        $filename = time() . '_' . basename($file['name']);
        $target_file = $uploads_dir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            // Simpan ke database, simpan path relatif
            $stmt = $pdo->prepare("INSERT INTO materi (title, file_path) VALUES (?, ?)");
            if ($stmt->execute([$title, 'uploads/materi/' . $filename])) {
                $success = "Materi berhasil diupload.";
            } else {
                $error = "Gagal menyimpan data materi.";
            }
        } else {
            $error = "Gagal mengupload file.";
        }
    }
}

// Ambil semua materi yang sudah diupload
$stmt = $pdo->query("SELECT * FROM materi ORDER BY created_at DESC");
$materis = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<title>Upload Materi Guru</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light px-3">
  <a class="navbar-brand" href="dashboard.php">E-Learning</a>
  <ul class="navbar-nav ms-auto">
    <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
    <li class="nav-item"><a href="tugas.php" class="nav-link">Tugas</a></li>
    <li class="nav-item"><a href="kuis.php" class="nav-link">Kuis</a></li>
    <li class="nav-item"><a href="../logout.php" class="nav-link">Logout</a></li>
  </ul>
</nav>

<div class="container mt-4">
  <h3>Upload Materi Pelajaran</h3>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php elseif ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <form action="" method="POST" enctype="multipart/form-data" class="mb-4">
    <div class="mb-3">
      <label>Judul Materi</label>
      <input type="text" name="title" class="form-control" required />
    </div>
    <div class="mb-3">
      <label>File Materi (pdf, doc, ppt, dll.)</label>
      <input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx" class="form-control" required />
    </div>
    <button type="submit" class="btn btn-primary">Upload</button>
  </form>

  <h4>Materi yang sudah diupload</h4>
  <?php if (count($materis) === 0): ?>
    <p>Belum ada materi.</p>
  <?php else: ?>
    <ul class="list-group">
      <?php foreach ($materis as $m): ?>
        <li class="list-group-item">
          <?= htmlspecialchars($m['title']) ?> 
          <a href="../<?= htmlspecialchars($m['file_path']) ?>" target="_blank" class="btn btn-sm btn-info float-end">Lihat</a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>
</body>
</html>
