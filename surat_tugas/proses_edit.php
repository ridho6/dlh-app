<?php
session_start();
require_once '../config/database.php';

if (isset($_POST['update'])) {
  $id          = $_POST['id'];
  $nomor_surat = $_POST['nomor_surat_tugas']; // Diambil dari inputan manual user
  $tgl_tugas   = $_POST['tgl_tugas'];
  $yang_tugas  = $_POST['yang_ditugaskan'];
  $perihal     = $_POST['perihal_penugasan'];
  $bidang      = $_POST['bidang'];
  $file_path   = $_POST['file_lama'];

  // --- SINKRONISASI BULAN & TAHUN ---
  // Walaupun user edit nomor surat manual, kita tetap update kolom bulan & tahun
  // di database agar fitur filter/laporan tetap akurat.
  $dateObj = new DateTime($tgl_tugas);
  $tahun_baru   = $dateObj->format('Y');

  $bulan_angka = (int)$dateObj->format('n');
  $romawi = [1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
  $bulan_romawi_baru = $romawi[$bulan_angka];
  // ----------------------------------

  // LOGIKA UPLOAD FILE
  if (!empty($_FILES['file_tugas']['name'])) {
    $target_dir = "../uploads/";
    $file_name = time() . "_" . basename($_FILES['file_tugas']['name']);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES['file_tugas']['tmp_name'], $target_file)) {
      // Hapus file lama jika ada
      if (!empty($file_path)) {
        $file_lama_fisik = "../" . $file_path;
        if (file_exists($file_lama_fisik)) unlink($file_lama_fisik);
      }
      $file_path = "uploads/" . $file_name;
    }
  }

  // UPDATE DATABASE
  $sql = "UPDATE surat_tugas SET 
            nomor_surat_tugas = ?, 
            tgl_tugas = ?, 
            yang_ditugaskan = ?, 
            perihal_penugasan = ?, 
            bidang = ?, 
            bulan = ?, 
            tahun = ?, 
            file_path = ? 
            WHERE id = ?";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    $nomor_surat,
    $tgl_tugas,
    $yang_tugas,
    $perihal,
    $bidang,
    $bulan_romawi_baru, // Update bulan (romawi) baru
    $tahun_baru,        // Update tahun baru
    $file_path,
    $id
  ]);

  $_SESSION['success'] = "Data Surat Tugas berhasil diperbarui!";
  header("Location: index.php");
  exit;
} else {
  header("Location: index.php");
  exit;
}
