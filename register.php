<?php
require 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    if (!$name || !$email || !$password || !in_array($role, ['siswa','guru'])) {
        $error = 'Isi semua data dengan benar.';
    } else {
        // Cek email sudah dipakai
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Email sudah terdaftar.';
        } else {
            // Hash password
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)");
            if ($stmt->execute([$name,$email,$hash,$role])) {
                $success = 'Registrasi berhasil. Silakan <a href="index.php">login</a>.';
            } else {
                $error = 'Registrasi gagal, coba lagi.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Register E-Learning</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
  <div class="container mt-5" style="max-width: 450px;">
    <h3 class="mb-4 text-center">Register E-Learning</h3>
    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <form method="POST" action="">
      <div class="mb-3">
        <label>Nama Lengkap</label>
        <input type="text" name="name" class="form-control" required />
      </div>
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required />
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required minlength="6" />
      </div>
      <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-select" required>
          <option value="">-- Pilih Role --</option>
          <option value="siswa">Siswa</option>
          <option value="guru">Guru</option>
        </select>
      </div>
      <button type="submit" class="btn btn-success w-100">Register</button>
      <p class="mt-3 text-center">Sudah punya akun? <a href="index.php">Login di sini</a></p>
    </form>
  </div>
</body>
</html>
