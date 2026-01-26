<?php
session_start();
require_once '../config/database.php';

if (!isset($_GET['id'])) {
  header("Location: index.php");
  exit;
}
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM surat_keputusan WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();
if (!$data) {
  header("Location: index.php");
  exit;
}

function cekSelected($value, $dbValue)
{
  return ($value == $dbValue) ? 'selected' : '';
}
?>

<?php require_once '../layouts/header.php'; ?>
<?php require_once '../layouts/sidebar.php'; ?>

<div class="main-content">
  <?php require_once '../layouts/navbar.php'; ?>
  <div class="container-fluid">
    <div class="card shadow" style="max-width: 900px; margin: 0 auto;">
      <div class="card-header bg-warning">
        <h5 class="mb-0 text-dark">Edit SK Kepala Dinas</h5>
      </div>
      <div class="card-body">
        <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?= $data['id'] ?>">
          <input type="hidden" name="file_lama" value="<?= $data['file_path'] ?>">

          <div class="row">
            <div class="col-md-3 mb-3">
              <label class="fw-bold">No Urut</label>
              <input type="number" name="no_urut" class="form-control" value="<?= $data['no_urut'] ?>">
            </div>
            <div class="col-md-9 mb-3">
              <label class="fw-bold">Nomor SK Lengkap</label>
              <input type="text" name="nomor_sk" class="form-control fw-bold text-primary" value="<?= htmlspecialchars($data['nomor_sk']) ?>">
            </div>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Tanggal</label>
            <input type="date" name="tgl_sk" class="form-control" value="<?= $data['tgl_sk'] ?>" required>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Keterangan SK</label>
            <input type="text" name="keterangan_sk" class="form-control" value="<?= htmlspecialchars($data['keterangan_sk']) ?>" required>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Perihal</label>
            <textarea name="perihal" class="form-control" rows="2" required><?= htmlspecialchars($data['perihal']) ?></textarea>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Bidang</label>
            <select name="bidang" class="form-select" required>
              <option value="was-dlh – Pengawasan" <?= cekSelected('was-dlh – Pengawasan', $data['bidang']) ?>>was-dlh – Pengawasan</option>
              <option value="kps-dlh – Kebersihan dan Pengelolaan Sampah" <?= cekSelected('kps-dlh – Kebersihan dan Pengelolaan Sampah', $data['bidang']) ?>>kps-dlh – Kebersihan dan Pengelolaan Sampah</option>
              <option value="psp-dlh – Pertamanan, Sarana, dan Prasarana" <?= cekSelected('psp-dlh – Pertamanan, Sarana, dan Prasarana', $data['bidang']) ?>>psp-dlh – Pertamanan, Sarana, dan Prasarana</option>
              <option value="tpa-dlh – Tempat Pengelolaan Akhir Sampah" <?= cekSelected('tpa-dlh – Tempat Pengelolaan Akhir Sampah', $data['bidang']) ?>>tpa-dlh – Tempat Pengelolaan Akhir Sampah</option>
              <option value="set-dlh – Sekretariat" <?= cekSelected('set-dlh – Sekretariat', $data['bidang']) ?>>set-dlh – Sekretariat</option>
              <option value="tl-dlh – Tata Lingkungan" <?= cekSelected('tl-dlh – Tata Lingkungan', $data['bidang']) ?>>tl-dlh – Tata Lingkungan</option>
              <option value="lab-dlh – Laboratorium" <?= cekSelected('lab-dlh – Laboratorium', $data['bidang']) ?>>lab-dlh – Laboratorium</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="fw-bold">File Surat</label>
            <?php if (!empty($data['file_path'])): ?>
              <div class="mb-2"><a href="<?= BASE_URL . $data['file_path'] ?>" target="_blank" class="btn btn-sm btn-info text-white">Lihat File Lama</a></div>
            <?php endif; ?>
            <input type="file" name="file_sk" class="form-control" accept=".pdf">
          </div>

          <div class="d-flex justify-content-between">
            <a href="index.php" class="btn btn-secondary">Batal</a>
            <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php require_once '../layouts/footer.php'; ?>