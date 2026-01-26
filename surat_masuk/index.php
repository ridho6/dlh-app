<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: " . BASE_URL . "index.php");
  exit;
}

$title = "Surat Masuk";
$page  = "surat_masuk";

// Urutkan ASC (No 1 diatas)
$query = "SELECT * FROM surat_masuk ORDER BY id ASC";
$data = $pdo->query($query)->fetchAll();
?>

<?php require_once '../layouts/header.php'; ?>
<?php require_once '../layouts/sidebar.php'; ?>

<div class="main-content">
  <?php require_once '../layouts/navbar.php'; ?>

  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold text-dark m-0">Arsip Surat Masuk</h2>

      <?php if (in_array($_SESSION['role'], ['admin', 'pegawai', 'sekretariat'])): ?>
        <a href="tambah.php" class="btn btn-primary">
          <i class="bi bi-plus-lg me-2"></i>Catat Surat Masuk
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
                <th rowspan="2">No</th>
                <th rowspan="2">Tanggal</th>
                <th rowspan="2">Dari</th>
                <th rowspan="2">Perihal</th>
                <th colspan="3" class="bg-warning text-dark">Disposisi</th>
                <th rowspan="2">File Surat</th>
                <th rowspan="2">Keterangan</th>
                <th rowspan="2" width="100">Aksi</th>
              </tr>
              <tr>
                <th class="bg-warning text-dark" style="font-size: 0.9rem;">Kadis</th>
                <th class="bg-warning text-dark" style="font-size: 0.9rem;">Sekretaris</th>
                <th class="bg-warning text-dark" style="font-size: 0.9rem;">Bidang</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($data as $row): ?>
                <tr>
                  <td class="text-center fw-bold"><?= $row['no_urut'] ?></td>

                  <td class="text-center" style="white-space: nowrap;">
                    <?= date('d/m/Y', strtotime($row['tgl_terima'])) ?>
                  </td>

                  <td><?= htmlspecialchars($row['pengirim']) ?></td>
                  <td><?= htmlspecialchars($row['perihal']) ?></td>

                  <td class="text-center small"><?= htmlspecialchars($row['disposisi_kadis'] ?? '-') ?></td>
                  <td class="text-center small"><?= htmlspecialchars($row['disposisi_sekretaris'] ?? '-') ?></td>
                  <td class="text-center small"><?= htmlspecialchars($row['disposisi_bidang'] ?? '-') ?></td>

                  <td class="text-center">
                    <?php if (!empty($row['file_path'])): ?>
                      <a href="<?= BASE_URL . $row['file_path'] ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                      </a>
                    <?php else: ?>
                      -
                    <?php endif; ?>
                  </td>

                  <td><?= htmlspecialchars($row['keterangan']) ?></td>

                  <td class="text-center">
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm" title="Edit / Isi Disposisi">
                      <i class="bi bi-pencil-square"></i>
                    </a>

                    <?php if ($_SESSION['role'] == 'admin'): ?>
                      <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">
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