<?php
session_start();
require_once '../config/database.php';

if (isset($_POST['update'])) {
  $id         = $_POST['id'];
  $nomor_nota_input = $_POST['nomor_nota']; // Nomor lama dari inputan
  $tgl_nota   = $_POST['tgl_nota'];
  $tujuan     = $_POST['tujuan'];
  $perihal    = $_POST['perihal'];
  $nominal    = $_POST['nominal'];
  $kode_klas  = $_POST['kode_klasifikasi']; // Kode Baru (misal: 000.1.1)
  $bidang     = $_POST['bidang'];           // Bidang Baru
  $file_path  = $_POST['file_lama'];

  // 1. GENERATE BULAN & TAHUN BARU (Dari Tanggal yang diedit)
  $dateObj = new DateTime($tgl_nota);
  $tahun_baru   = $dateObj->format('Y');
  $bulan_angka = (int)$dateObj->format('n');
  $romawi = [1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
  $bulan_romawi_baru = $romawi[$bulan_angka];

  // 2. GENERATE KODE BIDANG BARU
  $kode_bidang_map = [
    'Sekretariat'     => 'ND-SET-DLH',
    'Tata Lingkungan' => 'ND-TL-DLH',
    'Pengawasan'      => 'ND-WAS-DLH',
    'Kebersihan'      => 'ND-KEB-DLH',
    'Pertamanan'      => 'ND-PSP-DLH',
    'TPA'             => 'ND-TPA-DLH',
    'Laboratorium'    => 'ND-LAB-DLH'
  ];
  $kode_bidang_baru = isset($kode_bidang_map[$bidang]) ? $kode_bidang_map[$bidang] : 'ND-DLH';

  // 3. LOGIKA RAKIT ULANG NOMOR ND
  // Kita ambil "No Urut" (0001) dari nomor lama, sisanya kita ganti baru.

  // Format Asli: [0]Kode / [1]NoUrut / [2]KodeBidang / [3]Bulan / [4]Tahun
  // Contoh: 000.1.5/0001/ND-PSP-DLH/I/2026

  $parts = explode('/', $nomor_nota_input);

  // Ambil No Urut (Bagian ke-2 / Index 1)
  // Jika format rusak/diedit manual aneh-aneh, kita coba ambil angka sebisanya atau default 0000
  $no_urut_lama = isset($parts[1]) ? $parts[1] : '0000';

  // RAKIT ULANG:
  // KodeKlasifikasiBaru / NoUrutLama / KodeBidangBaru / BulanBaru / TahunBaru
  $nomor_nota_final = $kode_klas . "/" . $no_urut_lama . "/" . $kode_bidang_baru . "/" . $bulan_romawi_baru . "/" . $tahun_baru;

  // 4. UPLOAD FILE (Logika Tetap)
  if (!empty($_FILES['file_nota']['name'])) {
    $target_dir = "../uploads/";
    $file_name = time() . "_" . basename($_FILES['file_nota']['name']);
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES['file_nota']['tmp_name'], $target_file)) {
      if (!empty($file_path)) {
        $file_lama_fisik = "../" . $file_path;
        if (file_exists($file_lama_fisik)) unlink($file_lama_fisik);
      }
      $file_path = "uploads/" . $file_name;
    }
  }

  // 5. UPDATE DATABASE
  $sql = "UPDATE nota_dinas SET 
            nomor_nota = ?, 
            tgl_nota = ?, 
            tujuan = ?, 
            perihal = ?, 
            nominal = ?,
            kode_klasifikasi = ?, 
            bidang = ?, 
            bulan = ?, 
            tahun = ?, 
            file_path = ? 
            WHERE id = ?";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    $nomor_nota_final, // Pakai nomor hasil rakit ulang
    $tgl_nota,
    $tujuan,
    $perihal,
    $nominal,
    $kode_klas,
    $bidang,
    $bulan_romawi_baru,
    $tahun_baru,
    $file_path,
    $id
  ]);

  $_SESSION['success'] = "Nota Dinas berhasil diperbarui! Nomor Baru: " . $nomor_nota_final;
  header("Location: index.php");
  exit;
} else {
  header("Location: index.php");
  exit;
}
