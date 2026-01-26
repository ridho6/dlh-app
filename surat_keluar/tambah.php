<?php
session_start();
require_once '../config/database.php';
require_once '../config/kode_klasifikasi.php';
?>

<?php require_once '../layouts/header.php'; ?>
<?php require_once '../layouts/sidebar.php'; ?>

<div class="main-content">
  <?php require_once '../layouts/navbar.php'; ?>

  <div class="container-fluid">
    <div class="card shadow" style="max-width: 900px; margin: 0 auto;">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Input Surat Keluar</h5>
      </div>
      <div class="card-body">
        <form action="proses_tambah.php" method="POST" enctype="multipart/form-data">

          <div class="mb-3">
            <label class="fw-bold">Tanggal</label>
            <input type="date" name="tgl_kirim" class="form-control" value="<?= date('Y-m-d') ?>" required>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Tujuan Alamat Penerima</label>
            <input type="text" name="tujuan" class="form-control" placeholder="" required>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Perihal</label>
            <textarea name="perihal" class="form-control" rows="2" required></textarea>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Kode Klasifikasi</label>
            <select name="kode_klasifikasi" class="form-select" required>
              <option value="">- Pilih Kode -</option>

              <?php foreach ($klasifikasi as $item): ?>
                <option value="<?= $item['kode']; ?>">
                  <?= str_repeat('&nbsp;', substr_count($item['kode'], '.') * 4); ?>
                  <?= $item['kode']; ?> - <?= $item['nama']; ?>
                </option>
              <?php endforeach; ?>

            </select>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Bidang</label>
            <select name="bidang" class="form-select" required>
              <option value="">- Pilih Bidang -</option>
              <option value="Sekretariat">Sekretariat</option>
              <option value="Tata Lingkungan">Tata Lingkungan</option>
              <option value="Pengawasan">Pengawasan</option>
              <option value="Kebersihan">Kebersihan dan Pengelolaan Sampah</option>
              <option value="Pertamanan">Pertamanan, Sarana, dan Prasarana</option>
              <option value="TPA">Tempat Pengelolaan Akhir Sampah</option>
              <option value="Laboratorium">Laboratorium Lingkungan</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="fw-bold">File Surat (PDF)</label>
            <input type="file" name="file_surat" class="form-control" accept=".pdf">
          </div>

          <div class="d-flex justify-content-between mt-4">
            <a href="index.php" class="btn btn-secondary">Batal</a>
            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once '../layouts/footer.php'; ?>