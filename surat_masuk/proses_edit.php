<?php
session_start();
require_once '../config/database.php';

if (isset($_POST['update'])) {
  $id         = $_POST['id'];
  $no_urut    = $_POST['no_urut'];
  $tgl_terima = $_POST['tgl_terima'];
  $pengirim   = $_POST['pengirim'];
  $perihal    = $_POST['perihal'];
  $ket        = $_POST['keterangan'];

  $dis_kadis  = $_POST['disposisi_kadis'];
  $dis_sekre  = $_POST['disposisi_sekretaris'];
  $dis_bidang = $_POST['disposisi_bidang'];

  $file_path  = $_POST['file_lama'];

  // Update Tahun (jika tanggal berubah)
  $tahun = date('Y', strtotime($tgl_terima));

  // Upload File
  if (!empty($_FILES['file_surat']['name'])) {
    $target_dir = "../uploads/";
    $file_name = time() . "_" . basename($_FILES['file_surat']['name']);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES['file_surat']['tmp_name'], $target_file)) {
      if (!empty($file_path)) {
        $file_lama_fisik = "../" . $file_path;
        if (file_exists($file_lama_fisik)) unlink($file_lama_fisik);
      }
      $file_path = "uploads/" . $file_name;
    }
  }

  $sql = "UPDATE surat_masuk SET 
            no_urut = ?, 
            tgl_terima = ?, 
            tahun = ?,
            pengirim = ?, 
            perihal = ?, 
            disposisi_kadis = ?, 
            disposisi_sekretaris = ?, 
            disposisi_bidang = ?, 
            file_path = ?, 
            keterangan = ? 
            WHERE id = ?";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    $no_urut,
    $tgl_terima,
    $tahun,
    $pengirim,
    $perihal,
    $dis_kadis,
    $dis_sekre,
    $dis_bidang,
    $file_path,
    $ket,
    $id
  ]);

  $_SESSION['success'] = "Data Surat Masuk berhasil diperbarui!";
  header("Location: index.php");
  exit;
} else {
  header("Location: index.php");
  exit;
}
