<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: " . BASE_URL . "index.php");
  exit;
}

$title = "Laporan Arsip";
$page  = "laporan";
?>

<?php require_once '../layouts/header.php'; ?>
<?php require_once '../layouts/sidebar.php'; ?>

<div class="main-content">
  <?php require_once '../layouts/navbar.php'; ?>

  <div class="container-fluid">
    <div class="card shadow" style="max-width: 600px; margin: 0 auto;">
      <div class="card-header bg-dark text-white">
        <h5 class="mb-0"><i class="bi bi-printer me-2"></i> Cetak Laporan Arsip</h5>
      </div>
      <div class="card-body">

        <form action="cetak.php" method="GET" target="_blank">

          <div class="mb-3">
            <label class="fw-bold">Pilih Kategori Dokumen</label>
            <select name="kategori" class="form-select" required>
              <option value="">- Pilih Jenis Surat -</option>
              <option value="surat_masuk">Surat Masuk</option>
              <option value="surat_keluar">Surat Keluar</option>
              <option value="surat_sk">Surat Keputusan (SK)</option>
              <option value="nota_dinas">Nota Dinas</option>
              <option value="surat_tugas">Surat Tugas</option>
            </select>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="fw-bold">Dari Tanggal</label>
              <input type="date" name="tgl_awal" class="form-control" required value="<?= date('Y-m-01') ?>">
            </div>
            <div class="col-md-6 mb-3">
              <label class="fw-bold">Sampai Tanggal</label>
              <input type="date" name="tgl_akhir" class="form-control" required value="<?= date('Y-m-d') ?>">
            </div>
          </div>

          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-print"></i> Cetak Laporan
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once '../layouts/footer.php'; ?>