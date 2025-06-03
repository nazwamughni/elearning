<?php
session_start();
require 'config.php';

if (isset($_SESSION['user_id'])) {
    // Redirect sesuai role
    if ($_SESSION['role'] === 'siswa') {
        header('Location: siswa/dashboard.php');
        exit;
    } elseif ($_SESSION['role'] === 'guru') {
        header('Location: guru/dashboard.php');
        exit;
    }
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Simpan session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

        if ($user['role'] === 'siswa') {
            header('Location: siswa/dashboard.php');
        } else {
            header('Location: guru/dashboard.php');
        }
        exit;
    } else {
        $error = "Email atau password salah";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Login E-Learning</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
  <div class="container mt-5" style="max-width: 400px;">
    <h3 class="mb-4 text-center">Login E-Learning</h3>
    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" action="">
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required autofocus />
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required />
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
    <p class="mt-3 text-center">Belum punya akun? <a href="register.php">Register di sini</a></p>
  </div>
</body>
</html>
