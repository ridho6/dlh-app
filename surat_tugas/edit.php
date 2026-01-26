<?php
session_start();
require_once '../config/database.php';

if (!isset($_GET['id'])) {
  header("Location: index.php");
  exit;
}
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM surat_tugas WHERE id = ?");
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
    <div class="card shadow" style="max-width: 800px; margin: 0 auto;">
      <div class="card-header bg-warning">
        <h5 class="mb-0 text-dark">Edit Surat Tugas</h5>
      </div>
      <div class="card-body">
        <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?= $data['id'] ?>">
          <input type="hidden" name="file_lama" value="<?= $data['file_path'] ?>">

          <div class="mb-3">
            <label class="fw-bold">Nomor ST</label>
            <input type="text" name="nomor_surat_tugas" class="form-control fw-bold text-primary" value="<?= htmlspecialchars($data['nomor_surat_tugas']) ?>" required>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Tanggal Surat</label>
            <input type="date" name="tgl_tugas" class="form-control" value="<?= $data['tgl_tugas'] ?>" required>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Perihal Penugasan</label>
            <textarea name="perihal_penugasan" class="form-control" rows="2" required><?= htmlspecialchars($data['perihal_penugasan']) ?></textarea>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Yang Ditugaskan</label>
            <textarea name="yang_ditugaskan" class="form-control" rows="2" required><?= htmlspecialchars($data['yang_ditugaskan']) ?></textarea>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Bidang</label>
            <select name="bidang" class="form-select" required>
              <option value="">- Pilih Bidang -</option>
              <option value="st-dlh – Sekretariat" <?= cekSelected("st-dlh – Sekretariat", $data['bidang']) ?>>st-dlh – Sekretariat</option>
              <option value="st-dlh – Tata Lingkungan" <?= cekSelected("st-dlh – Tata Lingkungan", $data['bidang']) ?>>st-dlh – Tata Lingkungan</option>
              <option value="st-dlh – Pengawasan" <?= cekSelected("st-dlh – Pengawasan", $data['bidang']) ?>>st-dlh – Pengawasan</option>
              <option value="st-dlh – Kebersihan dan Pengelolaan Sampah" <?= cekSelected("st-dlh – Kebersihan dan Pengelolaan Sampah", $data['bidang']) ?>>st-dlh – Kebersihan dan Pengelolaan Sampah</option>
              <option value="st-dlh – Pertamanan, Sarana, dan Prasarana" <?= cekSelected("st-dlh – Pertamanan, Sarana, dan Prasarana", $data['bidang']) ?>>st-dlh – Pertamanan, Sarana, dan Prasarana</option>
              <option value="st-dlh – Tempat Pengelolaan Akhir Sampah" <?= cekSelected("st-dlh – Tempat Pengelolaan Akhir Sampah", $data['bidang']) ?>>st-dlh – Tempat Pengelolaan Akhir Sampah</option>
              <option value="st-dlh – Laboratorium Lingkungan" <?= cekSelected("st-dlh – Laboratorium Lingkungan", $data['bidang']) ?>>st-dlh – Laboratorium Lingkungan</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="fw-bold">File Surat</label>
            <?php if (!empty($data['file_path'])): ?>
              <div class="mb-2">
                <a href="<?= BASE_URL . $data['file_path'] ?>" target="_blank" class="btn btn-sm btn-info text-white">
                  <i class="bi bi-eye"></i> Lihat File Lama
                </a>
              </div>
            <?php endif; ?>
            <input type="file" name="file_tugas" class="form-control" accept=".pdf">
            <small class="text-muted">Biarkan kosong jika tidak ingin mengganti file.</small>
          </div>

          <div class="d-flex justify-content-between mt-4">
            <a href="index.php" class="btn btn-secondary">Batal</a>
            <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once '../layouts/footer.php'; ?>