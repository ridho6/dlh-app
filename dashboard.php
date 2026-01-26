<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}

$title = "Dashboard";
$page = "dashboard";

// --- LOGIKA MENGHITUNG JUMLAH DATA (Statistik Umum) ---
try {
  $jml_masuk  = $pdo->query("SELECT COUNT(*) FROM surat_masuk")->fetchColumn();
  $jml_keluar = $pdo->query("SELECT COUNT(*) FROM surat_keluar")->fetchColumn();
  $jml_sk     = $pdo->query("SELECT COUNT(*) FROM surat_keputusan")->fetchColumn();
  $jml_nota   = $pdo->query("SELECT COUNT(*) FROM nota_dinas")->fetchColumn();
  $jml_tugas  = $pdo->query("SELECT COUNT(*) FROM surat_tugas")->fetchColumn();
} catch (Exception $e) {
  $jml_masuk = 0;
  $jml_keluar = 0;
  $jml_sk = 0;
  $jml_nota = 0;
  $jml_tugas = 0;
}

// --- LOGIKA NOTIFIKASI (KHUSUS KEPALA DINAS) ---
$pending_masuk  = 0;
$pending_keluar = 0;
$pending_sk     = 0;

if ($_SESSION['role'] == 'kepala') {
  // Hitung Surat Masuk yang belum didisposisi (isi_disposisi masih kosong/NULL atau status belum)
  // Asumsi di database Anda kolom disposisi_kadis kosong jika belum didisposisi
  $pending_masuk = $pdo->query("SELECT COUNT(*) FROM surat_masuk WHERE disposisi_kadis IS NULL OR disposisi_kadis = ''")->fetchColumn();

  // Hitung Surat Keluar Pending
  $pending_keluar = $pdo->query("SELECT COUNT(*) FROM surat_keluar WHERE status_validasi = 'pending'")->fetchColumn();

  // Hitung SK Pending
  $pending_sk = $pdo->query("SELECT COUNT(*) FROM surat_keputusan WHERE status_validasi = 'pending'")->fetchColumn();
}
?>

<?php require_once 'layouts/header.php'; ?>
<?php require_once 'layouts/sidebar.php'; ?>

<div class="main-content">
  <?php require_once 'layouts/navbar.php'; ?>

  <div class="container-fluid">
    <h2 class="fw-bold text-dark mb-4">Dashboard</h2>

    <?php if ($_SESSION['role'] == 'kepala'): ?>

      <?php if ($pending_masuk == 0 && $pending_keluar == 0 && $pending_sk == 0): ?>
        <div class="alert alert-success shadow-sm mb-4">
          <i class="bi bi-check-circle-fill me-2"></i>
          <strong>Kerja Bagus!</strong> Tidak ada dokumen yang perlu ditindaklanjuti saat ini.
        </div>
      <?php else: ?>
        <div class="row mb-3">
          <div class="col-12">
            <div class="card bg-warning bg-opacity-10 border-warning">
              <div class="card-body">
                <h5 class="card-title text-warning fw-bold mb-3">
                  <i class="bi bi-bell-fill me-2"></i> Perlu Tindak Lanjut:
                </h5>
                <div class="row g-2">

                  <?php if ($pending_masuk > 0): ?>
                    <div class="col-md-4">
                      <div class="alert alert-light border-warning d-flex align-items-center mb-0 shadow-sm">
                        <div class="fs-1 text-warning me-3"><i class="bi bi-inbox-fill"></i></div>
                        <div>
                          <h5 class="fw-bold mb-0"><?= $pending_masuk ?> Surat Masuk</h5>
                          <small class="text-muted">Belum didisposisi</small><br>
                          <a href="surat_masuk/index.php" class="btn btn-sm btn-warning mt-1">Proses Sekarang &rarr;</a>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>

                  <?php if ($pending_keluar > 0): ?>
                    <div class="col-md-4">
                      <div class="alert alert-light border-warning d-flex align-items-center mb-0 shadow-sm">
                        <div class="fs-1 text-warning me-3"><i class="bi bi-send-fill"></i></div>
                        <div>
                          <h5 class="fw-bold mb-0"><?= $pending_keluar ?> Surat Keluar</h5>
                          <small class="text-muted">Menunggu persetujuan</small><br>
                          <a href="surat_keluar/index.php" class="btn btn-sm btn-warning mt-1">Validasi Sekarang &rarr;</a>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>

                  <?php if ($pending_sk > 0): ?>
                    <div class="col-md-4">
                      <div class="alert alert-light border-warning d-flex align-items-center mb-0 shadow-sm">
                        <div class="fs-1 text-warning me-3"><i class="bi bi-file-earmark-text-fill"></i></div>
                        <div>
                          <h5 class="fw-bold mb-0"><?= $pending_sk ?> SK Kepala</h5>
                          <small class="text-muted">Menunggu tanda tangan</small><br>
                          <a href="surat_sk/index.php" class="btn btn-sm btn-warning mt-1">Validasi Sekarang &rarr;</a>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>

                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
    <?php endif; ?>
    <div class="row g-4">
      <div class="col-md-3">
        <div class="stat-card bg-primary text-white p-3 rounded shadow-sm d-flex justify-content-between align-items-center">
          <div>
            <p class="mb-0 op-7">Surat Masuk</p>
            <h3 class="fw-bold mb-0"><?= str_pad($jml_masuk, 2, '0', STR_PAD_LEFT) ?></h3>
          </div>
          <i class="bi bi-inbox-fill fs-1 opacity-50"></i>
        </div>
      </div>

      <div class="col-md-3">
        <div class="stat-card bg-success text-white p-3 rounded shadow-sm d-flex justify-content-between align-items-center">
          <div>
            <p class="mb-0 op-7">Surat Keluar</p>
            <h3 class="fw-bold mb-0"><?= str_pad($jml_keluar, 2, '0', STR_PAD_LEFT) ?></h3>
          </div>
          <i class="bi bi-send-fill fs-1 opacity-50"></i>
        </div>
      </div>

      <div class="col-md-3">
        <div class="stat-card bg-warning text-dark p-3 rounded shadow-sm d-flex justify-content-between align-items-center">
          <div>
            <p class="mb-0 op-7">Surat Keputusan</p>
            <h3 class="fw-bold mb-0"><?= str_pad($jml_sk, 2, '0', STR_PAD_LEFT) ?></h3>
          </div>
          <i class="bi bi-award-fill fs-1 opacity-50"></i>
        </div>
      </div>

      <div class="col-md-3">
        <div class="stat-card bg-info text-white p-3 rounded shadow-sm d-flex justify-content-between align-items-center">
          <div>
            <p class="mb-0 op-7">Nota Dinas</p>
            <h3 class="fw-bold mb-0"><?= str_pad($jml_nota, 2, '0', STR_PAD_LEFT) ?></h3>
          </div>
          <i class="bi bi-journal-text fs-1 opacity-50"></i>
        </div>
      </div>

      <div class="col-md-3">
        <div class="stat-card bg-indigo p-3 rounded shadow-sm d-flex justify-content-between align-items-center" style="background-color: #6610f2; color: white;">
          <div>
            <p class="mb-0 op-7">Surat Tugas</p>
            <h3 class="fw-bold mb-0"><?= str_pad($jml_tugas, 2, '0', STR_PAD_LEFT) ?></h3>
          </div>
          <i class="bi bi-briefcase-fill fs-1 opacity-50"></i>
        </div>
      </div>
    </div>

  </div>
</div>

<?php require_once 'layouts/footer.php'; ?>