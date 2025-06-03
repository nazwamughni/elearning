<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'siswa') {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $tugas_id = $_POST['tugas_id'];

    if (isset($_FILES['file_tugas']) && $_FILES['file_tugas']['error'] == 0) {
        $file = $_FILES['file_tugas'];

        $uploads_dir = '../uploads/tugas';
        $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $allowed_ext = ['pdf','doc','docx'];

        if (!in_array(strtolower($file_ext), $allowed_ext)) {
            die("Format file tidak diizinkan.");
        }

        $new_filename = $user_id . '_' . time() . '.' . $file_ext;
        $target_file = $uploads_dir . '/' . $new_filename;

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            // Simpan ke database
            $stmt = $conn->prepare("INSERT INTO tugas_upload (tugas_id, siswa_id, file_path, uploaded_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("iis", $tugas_id, $user_id, $new_filename);
            $stmt->execute();

            echo "File berhasil diupload. <a href='upload_tugas.php'>Upload lagi</a>";
        } else {
            echo "Gagal upload file. Cek permission folder uploads/tugas";
        }
    } else {
        echo "File tidak ditemukan atau error saat upload.";
    }
} else {
    echo "Metode request salah.";
}
?>
