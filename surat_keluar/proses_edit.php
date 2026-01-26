<?php
session_start();
require_once '../config/database.php';

if (isset($_POST['update'])) {
  $id         = $_POST['id'];
  $no_urut    = $_POST['no_urut']; // Ambil dari input manual
  $nomor_lama = $_POST['nomor_surat'];
  $tgl_kirim  = $_POST['tgl_kirim'];
  $tujuan     = $_POST['tujuan'];
  $perihal    = $_POST['perihal'];
  $kode_klas  = $_POST['kode_klasifikasi'];
  $bidang     = $_POST['bidang'];
  $file_path  = $_POST['file_lama'];

  // 1. GENERATE BULAN & TAHUN BARU
  $dateObj = new DateTime($tgl_kirim);
  $tahun_baru   = $dateObj->format('Y');
  $bulan_angka = (int)$dateObj->format('n');
  $romawi = [1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
  $bulan_romawi_baru = $romawi[$bulan_angka];

  // 2. KODE BIDANG BARU
  $kode_bidang_map = [
    'Sekretariat'     => 'SET-DLH',
    'Tata Lingkungan' => 'TL-DLH',
    'Pengawasan'      => 'WAS-DLH',
    'Kebersihan'      => 'KEB-DLH',
    'Pertamanan'      => 'PSP-DLH',
    'TPA'             => 'TPA-DLH',
    'Laboratorium'    => 'LAB-DLH'
  ];
  $kode_bidang_sk = isset($kode_bidang_map[$bidang]) ? $kode_bidang_map[$bidang] : 'DLH';

  // 3. RAKIT ULANG NOMOR SURAT KELUAR
  // Format: [Kode]/[NoUrut 4 digit]/[KodeBidang]/[Bulan]/[Tahun]
  $nomor_final = $kode_klas . "/" . sprintf("%04d", $no_urut) . "/" . $kode_bidang_sk . "/" . $bulan_romawi_baru . "/" . $tahun_baru;

  // 4. UPLOAD FILE
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

  // 5. UPDATE
  $sql = "UPDATE surat_keluar SET 
            no_urut = ?, 
            nomor_surat = ?, 
            tgl_kirim = ?, 
            tujuan = ?, 
            perihal = ?, 
            kode_klasifikasi = ?, 
            bidang = ?, 
            bulan = ?, 
            tahun = ?, 
            file_path = ? 
            WHERE id = ?";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    $no_urut,
    $nomor_final, // Simpan nomor hasil rakitan
    $tgl_kirim,
    $tujuan,
    $perihal,
    $kode_klas,
    $bidang,
    $bulan_romawi_baru,
    $tahun_baru,
    $file_path,
    $id
  ]);

  $_SESSION['success'] = "Surat Keluar diperbarui! Nomor Baru: " . $nomor_final;
  header("Location: index.php");
  exit;
} else {
  header("Location: index.php");
  exit;
}
