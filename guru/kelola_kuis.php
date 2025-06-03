<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    header('Location: ../index.php');
    exit;
}
require '../koneksi.php';

$result = mysqli_query($conn, "SELECT * FROM kuis ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Kuis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h3>Daftar Kuis</h3>
    <a href="buat_kuis.php" class="btn btn-success mb-3">Buat Kuis Baru</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= htmlspecialchars($row['judul']) ?></td>
                <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                <td>
                    <a href="tambah_soal.php?kuis_id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Tambah Soal</a>
                    <!-- Bisa juga tambah edit atau hapus kuis -->
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
