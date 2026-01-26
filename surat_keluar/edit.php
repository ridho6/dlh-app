<?php
session_start();
require_once '../config/database.php';
require_once '../config/kode_klasifikasi.php';

if (!isset($_GET['id'])) {
  header("Location: index.php");
  exit;
}
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM surat_keluar WHERE id = ?");
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
        <h5 class="mb-0 text-dark">Edit Surat Keluar</h5>
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
              <label class="fw-bold">Nomor Surat Keluar</label>
              <input type="text" name="nomor_surat" class="form-control fw-bold text-primary" value="<?= htmlspecialchars($data['nomor_surat']) ?>">
            </div>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Tanggal</label>
            <input type="date" name="tgl_kirim" class="form-control" value="<?= $data['tgl_kirim'] ?>" required>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Tujuan</label>
            <input type="text" name="tujuan" class="form-control" value="<?= htmlspecialchars($data['tujuan']) ?>" required>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Perihal</label>
            <textarea name="perihal" class="form-control" rows="2" required><?= htmlspecialchars($data['perihal']) ?></textarea>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Kode Klasifikasi</label>
            <select name="kode_klasifikasi" class="form-select" required>
              <option value="">- Pilih Kode -</option>
              <?php foreach ($klasifikasi as $item): ?>
                <option value="<?= $item['kode']; ?>"
                  <?= cekSelected($item['kode'], $data['kode_klasifikasi']); ?>>
                  <?= $item['kode']; ?> - <?= $item['nama']; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Bidang</label>
            <select name="bidang" class="form-select" required>
              <option value="Sekretariat" <?= cekSelected('Sekretariat', $data['bidang']) ?>>Sekretariat</option>
              <option value="Tata Lingkungan" <?= cekSelected('Tata Lingkungan', $data['bidang']) ?>>Tata Lingkungan</option>
              <option value="Pengawasan" <?= cekSelected('Pengawasan', $data['bidang']) ?>>Pengawasan</option>
              <option value="Kebersihan" <?= cekSelected('Kebersihan', $data['bidang']) ?>>Kebersihan</option>
              <option value="Pertamanan" <?= cekSelected('Pertamanan', $data['bidang']) ?>>Pertamanan</option>
              <option value="TPA" <?= cekSelected('TPA', $data['bidang']) ?>>TPA</option>
              <option value="Laboratorium" <?= cekSelected('Laboratorium', $data['bidang']) ?>>Laboratorium</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="fw-bold">File Surat</label>
            <?php if (!empty($data['file_path'])): ?>
              <div class="mb-2"><a href="<?= BASE_URL . $data['file_path'] ?>" target="_blank" class="btn btn-sm btn-info text-white">Lihat File Lama</a></div>
            <?php endif; ?>
            <input type="file" name="file_surat" class="form-control" accept=".pdf">
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