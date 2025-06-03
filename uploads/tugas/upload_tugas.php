<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'siswa') {
    header('Location: ../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

<h2>Upload Tugas</h2>

<form action="upload_tugas_process.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="tugas_id" class="form-label">Pilih Tugas:</label>
        <select name="tugas_id" id="tugas_id" class="form-select" required>
            <option value="">-- Pilih Tugas --</option>
            <?php
            include '../config.php';
            $query = $conn->query("SELECT id, judul FROM tugas");
            while($row = $query->fetch_assoc()) {
                echo '<option value="'.$row['id'].'">'.htmlspecialchars($row['judul']).'</option>';
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="file_tugas" class="form-label">Pilih File Tugas (PDF/DOC/DOCX):</label>
        <input type="file" class="form-control" id="file_tugas" name="file_tugas" accept=".pdf,.doc,.docx" required>
    </div>

    <button type="submit" class="btn btn-primary">Upload</button>
</form>

</body>
</html>
