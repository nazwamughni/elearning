<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    header('Location: ../index.php');
    exit;
}
require '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];

    $stmt = $conn->prepare("INSERT INTO kuis (judul, deskripsi) VALUES (?, ?)");
    $stmt->bind_param("ss", $judul, $deskripsi);
    if ($stmt->execute()) {
        header('Location: kelola_kuis.php');
        exit;
    } else {
        $error = "Gagal menambahkan kuis.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buat Kuis Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h3>Buat Kuis Baru</h3>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="post">
        <div class="mb-3">
            <label for="judul" class="form-label">Judul Kuis</label>
            <input type="text" name="judul" id="judul" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Kuis</button>
    </form>
</body>
</html>
