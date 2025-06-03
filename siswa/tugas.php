<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
    header('Location: ../index.php');
    exit;
}

require '../config.php';

$user_id = $_SESSION['user_id'];

// Ambil tugas yang sudah dibuat guru
$tugasList = $pdo->query("SELECT * FROM tugas ORDER BY created_at DESC")->fetchAll();

// Handle upload tugas siswa
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tugas_id'])) {
    $tugas_id = $_POST['tugas_id'];
    $file = $_FILES['file'] ?? null;

    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        $message = "File harus diupload dengan benar.";
    } else {
        $uploads_dir = '../uploads/tugas';
        if (!is_dir($uploads_dir)) mkdir($uploads_dir, 0777, true);

        $filename = time() . '_' . basename($file['name']);
        $target_file = $uploads_dir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            // Simpan data upload tugas ke tabel tugas_upload
            $stmt = $pdo->prepare("INSERT INTO tugas_upload (tugas_id, siswa_id, file_path, upload_date) VALUES (?, ?, ?, NOW()) ON DUPLICATE KEY UPDATE file_path = VALUES(file_path), upload_date = NOW()");
            if ($stmt->execute([$tugas_id, $user_id, 'uploads/tugas/' . $filename])) {
                $message = "Tugas berhasil diupload.";
            } else {
                $message = "Gagal menyimpan data tugas.";
            }
        } else {
            $message = "Gagal mengupload file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<title>Tugas Siswa</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light px-3">
  <a class="navbar-brand" href="dashboard.php">E-Learning</a>
  <ul class="navbar-nav ms-auto">
    <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
    <li class="nav-item"><a href="materi.php" class="nav-link">Materi</a></li>
    <li class="nav-item"><a href="profile.php" class="nav-link">Profile</a></li>
    <li class="nav-item"><a href="kuis.php" class="nav-link">Kuis</a></li>
    <li class="nav-item"><a href="../logout.php" class="nav-link">Logout</a></li>
  </ul>
</nav>

<div class="container mt-4">
  <h3>Tugas</h3>

  <?php if ($message): ?>
    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <?php if (count($tugasList) === 0): ?>
    <p>Belum ada tugas dari guru.</p>
  <?php else: ?>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Judul Tugas</th>
          <th>Deskripsi</th>
          <th>Deadline</th>
          <th>Upload Tugas</th>
          <th>Status Nilai</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($tugasList as $tugas): 
          // Cek apakah siswa sudah upload tugas ini
          $stmt = $pdo->prepare("SELECT * FROM tugas_upload WHERE tugas_id = ? AND siswa_id = ?");
          $stmt->execute([$tugas['id'], $user_id]);
          $upload = $stmt->fetch();

          // Cek nilai dari guru
          $nilai = $upload ? $upload['nilai'] : null;
        ?>
        <tr>
          <td><?= htmlspecialchars($tugas['title']) ?></td>
          <td><?= htmlspecialchars($tugas['description']) ?></td>
          <td><?= htmlspecialchars($tugas['deadline']) ?></td>
          <td>
            <form action="" method="POST" enctype="multipart/form-data" class="d-flex gap-2 align-items-center">
              <input type="hidden" name="tugas_id" value="<?= $tugas['id'] ?>" />
              <input type="file" name="file" required />
              <button type="submit" class="btn btn-primary btn-sm">Upload</button>
            </form>
            <?php if ($upload): ?>
              <small class="text-success">Sudah upload: <a href="../<?= htmlspecialchars($upload['file_path']) ?>" target="_blank">Lihat file</a></small>
            <?php endif; ?>
          </td>
          <td>
            <?= is_null($nilai) ? '<span class="text-warning">Belum dinilai</span>' : '<span class="text-success">' . htmlspecialchars($nilai) . '</span>' ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
</body>
</html>
