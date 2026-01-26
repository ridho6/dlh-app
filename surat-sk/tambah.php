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
        <h5 class="mb-0">Input SK Kepala Dinas</h5>
      </div>
      <div class="card-body">
        <form action="proses_tambah.php" method="POST" enctype="multipart/form-data">

          <div class="mb-3">
            <label class="fw-bold">Tanggal</label>
            <input type="date" name="tgl_sk" class="form-control" value="<?= date('Y-m-d') ?>" required>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Keterangan SK</label>
            <input type="text" name="keterangan_sk" class="form-control" placeholder="" required>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Perihal</label>
            <textarea name="perihal" class="form-control" rows="2" placeholder="Detail mengenai SK tersebut..." required></textarea>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Bidang</label>
            <select name="bidang" class="form-select" required>
              <option value="">- Pilih Bidang -</option>
              <option value="was-dlh – Pengawasan">was-dlh – Pengawasan</option>
              <option value="kps-dlh – Kebersihan dan Pengelolaan Sampah">kps-dlh – Kebersihan dan Pengelolaan Sampah</option>
              <option value="psp-dlh – Pertamanan, Sarana, dan Prasarana">psp-dlh – Pertamanan, Sarana, dan Prasarana</option>
              <option value="tpa-dlh – Tempat Pengelolaan Akhir Sampah">tpa-dlh – Tempat Pengelolaan Akhir Sampah</option>
              <option value="set-dlh – Sekretariat">set-dlh – Sekretariat</option>
              <option value="tl-dlh – Tata Lingkungan">tl-dlh – Tata Lingkungan</option>
              <option value="lab-dlh – Laboratorium">lab-dlh – Laboratorium</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="fw-bold">File Surat (PDF)</label>
            <input type="file" name="file_sk" class="form-control" accept=".pdf">
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