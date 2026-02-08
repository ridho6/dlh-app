<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: " . BASE_URL . "index.php");
  exit;
}

$title = "Surat Keputusan (SK)";
$page  = "surat_sk";

// Urutkan ASC (No 1 diatas)
$query = "SELECT * FROM surat_keputusan ORDER BY id ASC";
$data = $pdo->query($query)->fetchAll();
?>

<?php require_once '../layouts/header.php'; ?>
<?php require_once '../layouts/sidebar.php'; ?>

<div class="main-content">
  <?php require_once '../layouts/navbar.php'; ?>

  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold text-dark m-0">Arsip SK Kepala Dinas</h2>

      <?php if (in_array($_SESSION['role'], ['admin', 'pegawai', 'sekretariat'])): ?>
        <a href="tambah.php" class="btn btn-primary">
          <i class="bi bi-plus-lg me-2"></i>Catat Surat Keputusan
        </a>
      <?php endif; ?>
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
          <table class="table table-hover align-middle table-bordered">
            <thead class="table-light text-center align-middle">
              <tr>
                <th>Nomor</th>
                <th>Tanggal</th>
                <th>Keterangan SK</th>
                <th>Perihal</th>
                <th>Bidang</th>
                <th>File Surat</th>
                <th>Nomor SK</th>
                <th>Status</th>
                <th width="100">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($data as $row): ?>
                <tr>
                  <td class="text-center fw-bold"><?= $row['no_urut'] ?></td>

                  <td class="text-center" style="white-space: nowrap;">
                    <?= date('d/m/Y', strtotime($row['tgl_sk'])) ?>
                  </td>

                  <td><?= htmlspecialchars($row['keterangan_sk']) ?></td>
                  <td><?= htmlspecialchars($row['perihal']) ?></td>

                  <td><span class="badge bg-info text-dark"><?= htmlspecialchars($row['bidang']) ?></span></td>

                  <td class="text-center">
                    <?php if (!empty($row['file_path'])): ?>
                      <a href="<?= BASE_URL . $row['file_path'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                      </a>
                    <?php else: ?>
                      -
                    <?php endif; ?>
                  </td>

                  <td class="fw-bold text-primary" style="white-space: nowrap;">
                    <?= htmlspecialchars($row['nomor_sk']) ?>
                  </td>

                  <td class="text-center">
                    <?php if ($row['status_validasi'] == 'disetujui'): ?>
                      <span class="badge bg-success"><i class="bi bi-check-circle"></i> Disetujui</span>
                    <?php else: ?>
                      <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Pending</span>
                    <?php endif; ?>
                  </td>

                  <td class="text-center">
                    <?php if ($_SESSION['role'] == 'kepala'): ?>
                      <?php if ($row['status_validasi'] == 'pending'): ?>
                        <a href="validasi.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm" onclick="return confirm('Setujui SK ini?')" title="Setujui SK">
                          <i class="bi bi-check-lg"></i>
                        </a>
                      <?php endif; ?>

                    <?php else: ?>
                      <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                      <?php if ($_SESSION['role'] == 'admin'): ?>
                        <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus SK ini?')">
                          <i class="bi bi-trash"></i>
                        </a>
                      <?php endif; ?>
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