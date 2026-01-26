<?php
session_start();
require_once '../config/database.php';
?>

<?php require_once '../layouts/header.php'; ?>
<?php require_once '../layouts/sidebar.php'; ?>

<div class="main-content">
  <?php require_once '../layouts/navbar.php'; ?>

  <div class="container-fluid">
    <div class="card shadow" style="max-width: 800px; margin: 0 auto;">
      <div class="card-header bg-success text-white">
        <h5 class="mb-0">Input Surat Tugas</h5>
      </div>
      <div class="card-body">
        <form action="proses_tambah.php" method="POST" enctype="multipart/form-data">

          <div class="mb-3">
            <label class="fw-bold">Tanggal Surat</label>
            <input type="date" name="tgl_tugas" class="form-control" value="<?= date('Y-m-d') ?>" required>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Bidang</label>
            <select name="bidang" class="form-select" required>
              <option value="">- Pilih Bidang -</option>
              <option value="st-dlh – Sekretariat">st-dlh – Sekretariat</option>
              <option value="st-dlh – Tata Lingkungan">st-dlh – Tata Lingkungan</option>
              <option value="st-dlh – Pengawasan">st-dlh – Pengawasan</option>
              <option value="st-dlh – Kebersihan dan Pengelolaan Sampah">st-dlh – Kebersihan dan Pengelolaan Sampah</option>
              <option value="st-dlh – Pertamanan, Sarana, dan Prasarana">st-dlh – Pertamanan, Sarana, dan Prasarana</option>
              <option value="st-dlh – Tempat Pengelolaan Akhir Sampah">st-dlh – Tempat Pengelolaan Akhir Sampah</option>
              <option value="st-dlh – Laboratorium Lingkungan">st-dlh – Laboratorium Lingkungan</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Yang Ditugaskan</label>
            <textarea name="yang_ditugaskan" class="form-control" rows="2" placeholder="Masukkan nama pegawai..." required></textarea>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Perihal Penugasan</label>
            <textarea name="perihal_penugasan" class="form-control" rows="2" required></textarea>
          </div>

          <div class="mb-3">
            <label class="fw-bold">File Surat (PDF)</label>
            <input type="file" name="file_tugas" class="form-control" accept=".pdf">
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