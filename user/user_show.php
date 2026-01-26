<?php
session_start();
// Mundur 1 folder (../) untuk akses config
require_once '../config/database.php';

// Cek Security
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
  header("Location: ../dashboard.php");
  exit;
}

$title = "Manajemen User";
$page  = "users";

// Ambil Data User
$data_users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
?>

<?php require_once '../layouts/header.php'; ?>
<?php require_once '../layouts/sidebar.php'; ?>

<div class="main-content">
  <?php require_once '../layouts/navbar.php'; ?>

  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold text-dark m-0">Data Pengguna</h2>
      <a href="user_add.php" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Tambah User
      </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success alert-dismissible fade show">
        <?= $_SESSION['success'];
        unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Username</th>
                <th>Role</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1;
              foreach ($data_users as $user): ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= htmlspecialchars($user['nama_lengkap']) ?></td>
                  <td><?= htmlspecialchars($user['username']) ?></td>
                  <td>
                    <?php
                    if ($user['role'] == 'admin') echo '<span class="badge bg-danger">ADMIN</span>';
                    elseif ($user['role'] == 'kepala') echo '<span class="badge bg-success">KEPALA</span>';
                    elseif ($user['role'] == 'pegawai') echo '<span class="badge bg-info text-dark">PEGAWAI</span>';
                    elseif ($user['role'] == 'sekretariat') echo '<span class="badge bg-warning text-dark">SEKRETARIAT</span>';
                    elseif ($user['role'] == 'kabid') echo '<span class="badge bg-primary">KEPALA BIDANG</span>';
                    ?>
                  </td>
                  <td>
                    <a href="user_edit.php?id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">
                      <i class="bi bi-pencil-square"></i>
                    </a>

                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                      <a href="user_delete.php?id=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus user ini?')">
                        <i class="bi bi-trash"></i>
                      </a>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once '../layouts/footer.php'; ?>