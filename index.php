<?php
session_start();
require_once 'config/database.php';

// 1. Jika sudah login, langsung lempar ke Dashboard
if (isset($_SESSION['user_id'])) {
  header("Location: dashboard.php");
  exit;
}

// 2. Proses Login saat tombol ditekan
if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->execute([$username]);
  $user = $stmt->fetch();

  // Cek Password
  if ($user && password_verify($password, $user['password'])) {
    // Set Session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role']    = $user['role'];
    $_SESSION['nama']    = $user['nama_lengkap'];

    // Redirect ke Dashboard
    header("Location: dashboard.php");
    exit;
  } else {
    $error = "Username atau password salah!";
  }
}

// Judul Halaman
$title = "Login - DLH Banjarmasin";
?>

<?php require_once 'layouts/header.php'; ?>

<style>
  body {
    background-color: #1a5d35;
    /* Warna Hijau DLH */
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
  }

  .card-login {
    width: 100%;
    max-width: 400px;
    border-radius: 10px;
    overflow: hidden;
  }
</style>

<div class="card card-login shadow-lg">
  <div class="card-header bg-white text-center py-4">
    <img src="assets/images/logo-bjm.png" alt="Logo" width="60" class="mb-2">
    <h4 class="fw-bold text-dark m-0">DLH ARSIP</h4>
    <small class="text-muted">Silakan Login Terlebih Dahulu</small>
  </div>
  <div class="card-body p-4 bg-light">

    <?php if (isset($error)): ?>
      <div class="alert alert-danger text-center py-2"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label fw-bold">Username</label>
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="fas fa-user"></i></span>
          <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
        </div>
      </div>
      <div class="mb-4">
        <label class="form-label fw-bold">Password</label>
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="fas fa-lock"></i></span>
          <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
        </div>
      </div>
      <button type="submit" name="login" class="btn btn-success w-100 py-2 fw-bold">MASUK APLIKASI</button>
    </form>
  </div>
  <div class="card-footer text-center bg-white py-3">
    <small class="text-muted">&copy; <?= date('Y') ?> Dinas Lingkungan Hidup</small>
  </div>
</div>

<?php require_once 'layouts/footer.php'; ?>