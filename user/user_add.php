<?php
session_start();
require_once '../config/database.php';

// PROSES SIMPAN
if (isset($_POST['simpan'])) {
  $username = htmlspecialchars($_POST['username']);
  $nama     = htmlspecialchars($_POST['nama_lengkap']);
  $role     = $_POST['role'];
  $pass     = password_hash($_POST['password'], PASSWORD_DEFAULT);

  // Cek Username Duplikat
  $cek = $pdo->prepare("SELECT id FROM users WHERE username = ?");
  $cek->execute([$username]);

  if ($cek->rowCount() > 0) {
    $error = "Username sudah digunakan!";
  } else {
    $stmt = $pdo->prepare("INSERT INTO users (username, password, nama_lengkap, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $pass, $nama, $role]);

    $_SESSION['success'] = "User berhasil ditambahkan!";
    header("Location: user_show.php"); // Kembali ke tabel
    exit;
  }
}
?>

<?php require_once '../layouts/header.php'; ?>
<?php require_once '../layouts/sidebar.php'; ?>

<div class="main-content">
  <?php require_once '../layouts/navbar.php'; ?>

  <div class="container-fluid">
    <div class="card shadow" style="max-width: 600px; margin: 0 auto;">
      <div class="card-header bg-success text-white">
        <h5 class="mb-0">Tambah User Baru</h5>
      </div>
      <div class="card-body">
        <?php if (isset($error)): ?>
          <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
          <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-select" required>
              <option value="pegawai">Pegawai</option>
              <option value="kepala">Kepala Dinas</option>
              <!-- <option value="sekretariat">Sekretariat</option>
              <option value="kabid">Kepala Bidang</option> -->
              <option value="admin">Admin</option>
            </select>
          </div>
          <div class="d-flex justify-content-between">
            <a href="user_show.php" class="btn btn-secondary">Kembali</a>
            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once '../layouts/footer.php'; ?>