<div class="top-navbar">
  <button class="btn btn-outline-success btn-sm d-md-none"><i class="fas fa-bars"></i></button>
  <div class="ms-auto d-flex align-items-center gap-3">
    <span class="text-secondary">
      Halo, <strong><?= $_SESSION['nama'] ?></strong>
      <span class="badge bg-success ms-1"><?= strtoupper($_SESSION['role']) ?></span>
    </span>
    <a href="<?= BASE_URL ?>logout.php" class="btn btn-danger btn-sm">
      <i class="bi bi-box-arrow-right"></i> Keluar
    </a>
  </div>
</div>