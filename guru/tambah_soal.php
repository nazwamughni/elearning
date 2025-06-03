<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    header('Location: ../index.php');
    exit;
}
require '../koneksi.php';

$kuis_id = $_GET['kuis_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $soal = $_POST['soal'];
    $jawaban_a = $_POST['jawaban_a'];
    $jawaban_b = $_POST['jawaban_b'];
    $jawaban_c = $_POST['jawaban_c'];
    $jawaban_d = $_POST['jawaban_d'];
    $jawaban_benar = $_POST['jawaban_benar']; // 'a', 'b', 'c', atau 'd'

    $stmt = $conn->prepare("INSERT INTO soal_kuis (kuis_id, soal, jawaban_a, jawaban_b, jawaban_c, jawaban_d, jawaban_benar) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $kuis_id, $soal, $jawaban_a, $jawaban_b, $jawaban_c, $jawaban_d, $jawaban_benar);

    if ($stmt->execute()) {
        $success = "Soal berhasil ditambahkan.";
    } else {
        $error = "Gagal menambahkan soal.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Soal Kuis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h3>Tambah Soal Kuis</h3>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
    <form method="post">
        <div class="mb-3">
            <label for="soal" class="form-label">Soal</label>
            <textarea name="soal" id="soal" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Pilihan Jawaban</label>
            <input type="text" name="jawaban_a" placeholder="Jawaban A" class="form-control mb-2" required>
            <input type="text" name="jawaban_b" placeholder="Jawaban B" class="form-control mb-2" required>
            <input type="text" name="jawaban_c" placeholder="Jawaban C" class="form-control mb-2" required>
            <input type="text" name="jawaban_d" placeholder="Jawaban D" class="form-control mb-2" required>
        </div>
        <div class="mb-3">
            <label for="jawaban_benar" class="form-label">Jawaban Benar</label>
            <select name="jawaban_benar" id="jawaban_benar" class="form-select" required>
                <option value="">Pilih jawaban benar</option>
                <option value="a">Jawaban A</option>
                <option value="b">Jawaban B</option>
                <option value="c">Jawaban C</option>
                <option value="d">Jawaban D</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Tambah Soal</button>
    </form>
</body>
</html>
