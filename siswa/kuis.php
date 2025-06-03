<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
    header('Location: ../index.php');
    exit;
}
require '../koneksi.php';

$result = mysqli_query($conn, "SELECT * FROM kuis");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kuis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h3>Daftar Kuis</h3>
    <table class="table">
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
                <td><a href="kerjakan_kuis.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Kerjakan</a></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
