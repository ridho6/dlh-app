<?php
session_start();
require_once '../config/database.php';

if (isset($_POST['update'])) {
  $id         = $_POST['id'];
  $no_urut    = $_POST['no_urut'];
  $nomor_lama = $_POST['nomor_sk'];
  $tgl_sk     = $_POST['tgl_sk'];
  $ket_sk     = $_POST['keterangan_sk'];
  $perihal    = $_POST['perihal'];
  $bidang     = $_POST['bidang'];
  $file_path  = $_POST['file_lama'];

  // 1. GENERATE BULAN & TAHUN BARU
  $dateObj = new DateTime($tgl_sk);
  $tahun_baru   = $dateObj->format('Y');
  $bulan_angka = (int)$dateObj->format('n');
  $romawi = [1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
  $bulan_romawi_baru = $romawi[$bulan_angka];

  // 2. KODE BIDANG BARU
  $parts = explode(' ', $bidang);
  $kode_bidang = $parts[0];

  // 3. RAKIT ULANG NOMOR
  // Format: [NoUrut]/[KodeBidang]/[Bulan]/[Tahun]
  $nomor_final = sprintf("%04d", $no_urut) . "/" . $kode_bidang . "/" . $bulan_romawi_baru . "/" . $tahun_baru;

  // 4. UPLOAD FILE
  if (!empty($_FILES['file_sk']['name'])) {
    $target_dir = "../uploads/";
    $file_name = time() . "_" . basename($_FILES['file_sk']['name']);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES['file_sk']['tmp_name'], $target_file)) {
      if (!empty($file_path)) {
        $file_lama_fisik = "../" . $file_path;
        if (file_exists($file_lama_fisik)) unlink($file_lama_fisik);
      }
      $file_path = "uploads/" . $file_name;
    }
  }

  // 5. UPDATE
  $sql = "UPDATE surat_keputusan SET 
            no_urut = ?, 
            tgl_sk = ?, 
            keterangan_sk = ?, 
            perihal = ?, 
            bidang = ?, 
            bulan = ?, 
            tahun = ?, 
            file_path = ?,
            nomor_sk = ? 
            WHERE id = ?";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    $no_urut,
    $tgl_sk,
    $ket_sk,
    $perihal,
    $bidang,
    $bulan_romawi_baru,
    $tahun_baru,
    $file_path,
    $nomor_final,
    $id
  ]);

  $_SESSION['success'] = "SK diperbarui!";
  header("Location: index.php");
  exit;
} else {
  header("Location: index.php");
  exit;
}
