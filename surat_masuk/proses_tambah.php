<?php
session_start();
require_once '../config/database.php';

if (isset($_POST['simpan'])) {

  $tgl_terima = $_POST['tgl_terima'];
  $pengirim   = $_POST['pengirim'];
  $perihal    = $_POST['perihal'];
  $ket        = $_POST['keterangan'];

  $dis_kadis  = $_POST['disposisi_kadis'];
  $dis_sekre  = $_POST['disposisi_sekretaris'];
  $dis_bidang = $_POST['disposisi_bidang'];

  $user_id    = $_SESSION['user_id'];

  // Ambil Tahun
  $tahun = date('Y', strtotime($tgl_terima));

  // LOGIKA CEK NO URUT TERAKHIR (Per Tahun)
  $query = "SELECT MAX(no_urut) as max_no FROM surat_masuk WHERE tahun = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$tahun]);
  $result = $stmt->fetch();
  $no_urut_baru = ($result['max_no']) ? $result['max_no'] + 1 : 1;

  // UPLOAD FILE
  $file_path = null;
  if (!empty($_FILES['file_surat']['name'])) {
    $target_dir = "../uploads/";
    if (!file_exists($target_dir)) {
      mkdir($target_dir, 0777, true);
    }
    $file_name = time() . "_" . basename($_FILES['file_surat']['name']);
    $target_file = $target_dir . $file_name;
    if (move_uploaded_file($_FILES['file_surat']['tmp_name'], $target_file)) {
      $file_path = "uploads/" . $file_name;
    }
  }

  // SIMPAN
  $sql = "INSERT INTO surat_masuk (no_urut, tgl_terima, tahun, pengirim, perihal, disposisi_kadis, disposisi_sekretaris, disposisi_bidang, file_path, keterangan, created_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    $no_urut_baru,
    $tgl_terima,
    $tahun,
    $pengirim,
    $perihal,
    $dis_kadis,
    $dis_sekre,
    $dis_bidang,
    $file_path,
    $ket,
    $user_id
  ]);

  $_SESSION['success'] = "Surat Masuk berhasil dicatat! No Urut: " . $no_urut_baru;
  header("Location: index.php");
  exit;
}
