<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    header('Location: ../index.php');
    exit;
}

require '../config.php';

$message = '';
$error = '';

// Handle tambah tugas baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $deadline = $_POST['deadline'] ?? '';

    if (!$title || !$deadline) {
        $error = "Judul dan deadline harus diisi.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO tugas (title, description, deadline) VALUES (?, ?, ?)");
        if ($stmt->execute([$title, $description, $deadline])) {
            $message = "Tugas berhasil dibuat.";
        } else {
            $error = "Gagal membuat tugas.";
        }
    }
}

// Ambil semua tugas
$tugasList = $pdo->query("SELECT * FROM tugas ORDER BY created_at DESC")->fetchAll();

// Ambil data upload tugas siswa jika guru ingin beri nilai
$nilaiUpdateMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nilai_update'])) {
    $upload_id = $_POST['upload_id'];
    $nilai = $_POST['nilai'];

    if (!is_numeric($nilai) || $nilai < 0 || $nilai > 100) {
        $error = "Nilai harus angka antara 0 sampai 100.";
    } else {
        $stmt = $pdo->prepare("UPDATE tugas_upload SET nilai = ? WHERE id = ?");
        if ($stmt->execute([$nilai, $upload_id])) {
            $nilaiUpdateMsg = "Nilai berhasil diupdate.";
        } else {
            $error = "Gagal update nilai.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<title>Tugas Guru</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
  .nilai-input { max-width: 80px; }
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light px-3">
  <a class="navbar-brand" href="dashboard.php">E-Learning</a>
  <ul class="navbar-nav ms-auto">
    <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
    <li class="nav-item"><a href="materi.php" class="nav-link">Materi</a></li>
    <li class="nav-item"><a href="kuis.php" class="nav-link">Kuis</a></li>
    <li class="nav-item"><a href="../logout.php" class="nav-link">Logout</a></li>
  </ul>
</nav>

<div class="container mt-4">
  <h3>Manajemen Tugas</h3>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php elseif ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
  <?php elseif ($nilaiUpdateMsg): ?>
    <div class="alert alert-success"><?= htmlspecialchars($nilaiUpdateMsg) ?></div>
  <?php endif; ?>

  <h5>Buat Tugas Baru</h5>
  <form method="POST" class="mb-4">
    <input type="hidden" name="action" value="add" />
    <div class="mb-3">
      <label>Judul Tugas</label>
      <input type="text" name="title" class="form-control" required />
    </div>
    <div class="mb-3">
      <label>Deskripsi</label>
      <textarea name="description" class="form-control"></textarea>
    </div>
    <div class="mb-3">
      <label>Deadline</label>
      <input type="date" name="deadline" class="form-control" required />
    </div>
    <button type="submit" class="btn btn-primary">Buat Tugas</button>
  </form>

  <h5>Tugas dan Upload Siswa</h5>
  <?php if (count($tugasList) === 0): ?>
    <p>Belum ada tugas yang dibuat.</p>
  <?php else: ?>
    <?php foreach ($tugasList as $tugas): ?>
      <div class="card mb-3">
        <div class="card-header">
          <strong><?= htmlspecialchars($tugas['title']) ?></strong> (Deadline: <?= htmlspecialchars($tugas['deadline']) ?>)
        </div>
        <div class="card-body">
          <p><?= nl2br(htmlspecialchars($tugas['description'])) ?></p>

          <?php
          // Ambil upload tugas siswa yang terkait tugas ini
          $stmt = $pdo->prepare("SELECT tu.*, u.name as siswa_name FROM tugas_upload tu JOIN users u ON tu.siswa_id = u.id WHERE tu.tugas_id = ?");
          $stmt->execute([$tugas['id']]);
          $uploads = $stmt->fetchAll();
          ?>

          <?php if (count($uploads) === 0): ?>
            <p>Tidak ada siswa yang mengupload tugas ini.</p>
          <?php else: ?>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Nama Siswa</th>
                  <th>File Tugas</th>
                  <th>Upload Date</th>
                  <th>Nilai</th>
                  <th>Update Nilai</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($uploads as $upload): ?>
                  <tr>
                    <td><?= htmlspecialchars($upload['siswa_name']) ?></td>
                    <td><a href="../<?= htmlspecialchars($upload['file_path']) ?>" target="_blank">Lihat File</a></td>
                    <td><?= htmlspecialchars($upload['upload_date']) ?></td>
                    <td><?= $upload['nilai'] === null ? '<span class="text-warning">Belum dinilai</span>' : htmlspecialchars($upload['nilai']) ?></td>
                    <td>
                      <form method="POST" class="d-flex gap-2 align-items-center">
                        <input type="hidden" name="upload_id" value="<?= $upload['id'] ?>" />
                        <input type="number" name="nilai" min="0" max="100" step="1" class="form-control nilai-input" required value="<?= htmlspecialchars($upload['nilai'] ?? '') ?>" />
                        <button type="submit" name="nilai_update" value="1" class="btn btn-success btn-sm">Update</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
</body>
</html>
