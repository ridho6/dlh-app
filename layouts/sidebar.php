<nav class="sidebar">
  <div class="logo-area">
    <img src="<?= BASE_URL ?>assets/images/logo-bjm.png" alt="Logo">
    <h5>APLIKASI<br>MANAJEMEN DOKUMEN<br>DLH BANJARMASIN</h5>
  </div>
  <div class="nav flex-column mt-3">
    <a href="<?= BASE_URL ?>dashboard.php" class="nav-link <?= ($page == 'dashboard') ? 'active' : '' ?>">
      <i class="bi bi-house-door"></i> Dashboard
    </a>

    <?php if ($_SESSION['role'] == 'admin'): ?>
      <a href="<?= BASE_URL ?>user/user_show.php" class="nav-link">
        <i class="bi bi-people"></i> Manajemen User
      </a>
    <?php endif; ?>

    <a href="<?= BASE_URL ?>surat-sk/index.php" class=" nav-link">
      <i class="bi bi-file-earmark-text"></i> Surat Keputusan
    </a>
    <a href="<?= BASE_URL ?>surat_masuk/index.php" class="nav-link">
      <i class="bi bi-inbox"></i> Surat Masuk
    </a>
    <a href="<?= BASE_URL ?>surat_keluar/index.php" class="nav-link">
      <i class="bi bi-send"></i> Surat Keluar
    </a>
    <a href="<?= BASE_URL ?>nota_dinas/index.php" class="nav-link">
      <i class="bi bi-sticky"></i> Nota Dinas
    </a>
    <a href="<?= BASE_URL ?>surat_tugas/index.php" class="nav-link">
      <i class="bi bi-briefcase-fill"></i> Surat Tugas
    </a>
    <a href="<?= BASE_URL ?>laporan/index.php" class="nav-link">
      <i class="bi bi-printer-fill"></i> Laporan Arsip
    </a>
  </div>
</nav>