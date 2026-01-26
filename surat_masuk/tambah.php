<?php
session_start();
require_once '../config/database.php';
?>

<?php require_once '../layouts/header.php'; ?>
<?php require_once '../layouts/sidebar.php'; ?>

<div class="main-content">
  <?php require_once '../layouts/navbar.php'; ?>

  <div class="container-fluid">
    <div class="card shadow" style="max-width: 900px; margin: 0 auto;">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Input Surat Masuk</h5>
      </div>
      <div class="card-body">
        <form action="proses_tambah.php" method="POST" enctype="multipart/form-data">

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="fw-bold">Tanggal</label>
              <input type="date" name="tgl_terima" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="fw-bold">Dari (Pengirim)</label>
              <input type="text" name="pengirim" class="form-control" required placeholder="">
            </div>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Perihal</label>
            <textarea name="perihal" class="form-control" rows="2" required></textarea>
          </div>

          <div class="card bg-light mb-3">
            <div class="card-body pt-2 pb-2">
              <h6 class="fw-bold text-muted mb-3">Info Disposisi</h6>
              <div class="row">
                <div class="col-md-4 mb-2">
                  <label class="small fw-bold">Disposisi Kadis</label>
                  <select name="disposisi_kadis" class="form-select form-select-sm">
                    <option value="">- Pilih -</option>
                    <option value="Sendiri">Sendiri</option>
                    <option value="Sekretaris">Sekretaris</option>
                    <option value="Tata Lingkungan">Tata Lingkungan</option>
                    <option value="Pengawasan">Pengawasan</option>
                    <option value="Kebersihan">Kebersihan</option>
                    <option value="Pertamanan">Pertamanan</option>
                    <option value="TPA">TPA</option>
                    <option value="Laboratorium">Laboratorium</option>
                  </select>
                </div>
                <div class="col-md-4 mb-2">
                  <label class="small fw-bold">Disposisi Sekretaris</label>
                  <select name="disposisi_sekretaris" class="form-select form-select-sm">
                    <option value="">- Pilih -</option>
                    <option value="Sendiri">Sendiri</option>
                    <option value="Sekretaris">Sekretaris</option>
                    <option value="Tata Lingkungan">Tata Lingkungan</option>
                    <option value="Pengawasan">Pengawasan</option>
                    <option value="Kebersihan">Kebersihan</option>
                    <option value="Pertamanan">Pertamanan</option>
                    <option value="TPA">TPA</option>
                    <option value="Laboratorium">Laboratorium</option>
                  </select>
                </div>
                <div class="col-md-4 mb-2">
                  <label class="small fw-bold">Disposisi Bidang</label>
                  <select name="disposisi_bidang" class="form-select form-select-sm">
                    <option value="">- Pilih -</option>
                    <option value="Sendiri">Sendiri</option>
                    <option value="Sekretaris">Sekretaris</option>
                    <option value="Tata Lingkungan">Tata Lingkungan</option>
                    <option value="Pengawasan">Pengawasan</option>
                    <option value="Kebersihan">Kebersihan</option>
                    <option value="Pertamanan">Pertamanan</option>
                    <option value="TPA">TPA</option>
                    <option value="Laboratorium">Laboratorium</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="fw-bold">File Surat</label>
            <input type="file" name="file_surat" class="form-control">
          </div>

          <div class="mb-3">
            <label class="fw-bold">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="2"></textarea>
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