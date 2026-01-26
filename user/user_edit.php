<?php
session_start();
require_once '../config/database.php';

if (!isset($_GET['id'])) {
  header("Location: user_show.php");
  exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
  header("Location: user_show.php");
  exit;
}
?>

<?php require_once '../layouts/header.php'; ?>
<?php require_once '../layouts/sidebar.php'; ?>

<div class="main-content">
  <?php require_once '../layouts/navbar.php'; ?>

  <div class="container-fluid">
    <div class="card shadow" style="max-width: 600px; margin: 0 auto;">
      <div class="card-header bg-warning">
        <h5 class="mb-0 text-dark">Edit Data User</h5>
      </div>
      <div class="card-body">
        <form action="user_update.php" method="POST">
          <input type="hidden" name="id_user" value="<?= $user['id'] ?>">

          <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($user['nama_lengkap']) ?>" required>
          </div>
          <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
            <small class="text-muted">Pastikan username tidak sama dengan user lain.</small>
          </div>
          <div class="mb-3">
            <label>Password Baru <small class="text-muted">(Kosongkan jika tidak diganti)</small></label>
            <input type="password" name="password" class="form-control">
          </div>
          <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-select" required>
              <option value="pegawai" <?= ($user['role'] == 'pegawai') ? 'selected' : '' ?>>Pegawai</option>
              <option value="kepala" <?= ($user['role'] == 'kepala') ? 'selected' : '' ?>>Kepala Dinas</option>
              <!-- <option value="sekretariat" <?= ($user['role'] == 'sekretariat') ? 'selected' : '' ?>>Sekretariat</option>
              <option value="kabid" <?= ($user['role'] == 'kabid') ? 'selected' : '' ?>>Kepala Bidang</option> -->
              <option value="admin" <?= ($user['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
            </select>
          </div>
          <div class="d-flex justify-content-between">
            <a href="user_show.php" class="btn btn-secondary">Batal</a>
            <button type="submit" name="update" class="btn btn-primary">Update Data</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once '../layouts/footer.php'; ?>