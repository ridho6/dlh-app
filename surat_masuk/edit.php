<?php
session_start();
require_once '../config/database.php';

if (!isset($_GET['id'])) {
  header("Location: index.php");
  exit;
}
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM surat_masuk WHERE id = ?");
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
        <h5 class="mb-0 text-dark">Edit / Isi Disposisi Surat Masuk</h5>
      </div>
      <div class="card-body">
        <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?= $data['id'] ?>">
          <input type="hidden" name="file_lama" value="<?= $data['file_path'] ?>">

          <div class="row">
            <div class="col-md-2 mb-3">
              <label class="fw-bold">No Urut</label>
              <input type="number" name="no_urut" class="form-control" value="<?= $data['no_urut'] ?>">
            </div>
            <div class="col-md-5 mb-3">
              <label class="fw-bold">Tanggal</label>
              <input type="date" name="tgl_terima" class="form-control" value="<?= $data['tgl_terima'] ?>" required>
            </div>
            <div class="col-md-5 mb-3">
              <label class="fw-bold">Dari (Pengirim)</label>
              <input type="text" name="pengirim" class="form-control" value="<?= htmlspecialchars($data['pengirim']) ?>" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="fw-bold">Perihal</label>
            <textarea name="perihal" class="form-control" rows="2" required><?= htmlspecialchars($data['perihal']) ?></textarea>
          </div>

          <div class="card bg-light border-warning mb-3">
            <div class="card-header bg-warning text-dark py-1"><strong>Lembar Disposisi</strong></div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4 mb-2">
                  <label class="fw-bold">Kadis</label>
                  <select name="disposisi_kadis" class="form-select">
                    <option value="">- Kosong -</option>
                    <option value="Sendiri" <?= cekSelected('Sendiri', $data['disposisi_kadis']) ?>>Sendiri</option>
                    <option value="Sekretaris" <?= cekSelected('Sekretaris', $data['disposisi_kadis']) ?>>Sekretaris</option>
                    <option value="Tata Lingkungan" <?= cekSelected('Tata Lingkungan', $data['disposisi_kadis']) ?>>Tata Lingkungan</option>
                    <option value="Pengawasan" <?= cekSelected('Pengawasan', $data['disposisi_kadis']) ?>>Pengawasan</option>
                    <option value="Kebersihan" <?= cekSelected('Kebersihan', $data['disposisi_kadis']) ?>>Kebersihan</option>
                    <option value="Pertamanan" <?= cekSelected('Pertamanan', $data['disposisi_kadis']) ?>>Pertamanan</option>
                    <option value="TPA" <?= cekSelected('TPA', $data['disposisi_kadis']) ?>>TPA</option>
                    <option value="Laboratorium" <?= cekSelected('Laboratorium', $data['disposisi_kadis']) ?>>Laboratorium</option>
                  </select>
                </div>
                <div class="col-md-4 mb-2">
                  <label class="fw-bold">Sekretaris</label>
                  <select name="disposisi_sekretaris" class="form-select">
                    <option value="">- Kosong -</option>
                    <option value="Sendiri" <?= cekSelected('Sendiri', $data['disposisi_sekretaris']) ?>>Sendiri</option>
                    <option value="Sekretaris" <?= cekSelected('Sekretaris', $data['disposisi_sekretaris']) ?>>Sekretaris</option>
                    <option value="Tata Lingkungan" <?= cekSelected('Tata Lingkungan', $data['disposisi_sekretaris']) ?>>Tata Lingkungan</option>
                    <option value="Pengawasan" <?= cekSelected('Pengawasan', $data['disposisi_sekretaris']) ?>>Pengawasan</option>
                    <option value="Kebersihan" <?= cekSelected('Kebersihan', $data['disposisi_sekretaris']) ?>>Kebersihan</option>
                    <option value="Pertamanan" <?= cekSelected('Pertamanan', $data['disposisi_sekretaris']) ?>>Pertamanan</option>
                    <option value="TPA" <?= cekSelected('TPA', $data['disposisi_sekretaris']) ?>>TPA</option>
                    <option value="Laboratorium" <?= cekSelected('Laboratorium', $data['disposisi_sekretaris']) ?>>Laboratorium</option>
                  </select>
                </div>
                <div class="col-md-4 mb-2">
                  <label class="fw-bold">Bidang</label>
                  <select name="disposisi_bidang" class="form-select">
                    <option value="">- Kosong -</option>
                    <option value="Sendiri" <?= cekSelected('Sendiri', $data['disposisi_bidang']) ?>>Sendiri</option>
                    <option value="Sekretaris" <?= cekSelected('Sekretaris', $data['disposisi_bidang']) ?>>Sekretaris</option>
                    <option value="Tata Lingkungan" <?= cekSelected('Tata Lingkungan', $data['disposisi_bidang']) ?>>Tata Lingkungan</option>
                    <option value="Pengawasan" <?= cekSelected('Pengawasan', $data['disposisi_bidang']) ?>>Pengawasan</option>
                    <option value="Kebersihan" <?= cekSelected('Kebersihan', $data['disposisi_bidang']) ?>>Kebersihan</option>
                    <option value="Pertamanan" <?= cekSelected('Pertamanan', $data['disposisi_bidang']) ?>>Pertamanan</option>
                    <option value="TPA" <?= cekSelected('TPA', $data['disposisi_bidang']) ?>>TPA</option>
                    <option value="Laboratorium" <?= cekSelected('Laboratorium', $data['disposisi_bidang']) ?>>Laboratorium</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="fw-bold">File Surat</label>
            <?php if (!empty($data['file_path'])): ?>
              <div class="mb-2"><a href="<?= BASE_URL . $data['file_path'] ?>" target="_blank" class="btn btn-sm btn-info text-white">Lihat File Lama</a></div>
            <?php endif; ?>
            <input type="file" name="file_surat" class="form-control">
          </div>

          <div class="mb-3">
            <label class="fw-bold">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="2"><?= htmlspecialchars($data['keterangan']) ?></textarea>
          </div>

          <div class="d-flex justify-content-between">
            <a href="index.php" class="btn btn-secondary">Batal</a>
            <button type="submit" name="update" class="btn btn-primary">Update Data</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php require_once '../layouts/footer.php'; ?>