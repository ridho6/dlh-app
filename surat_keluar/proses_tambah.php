<?php
session_start();
require_once '../config/database.php';

if (isset($_POST['simpan'])) {

  $tgl_kirim  = $_POST['tgl_kirim'];
  $tujuan     = $_POST['tujuan'];
  $perihal    = $_POST['perihal'];
  $kode_klas  = $_POST['kode_klasifikasi'];
  $bidang     = $_POST['bidang'];
  $user_id    = $_SESSION['user_id'];

  // 1. GENERATE TAHUN & BULAN ROMAWI
  $dateObj = new DateTime($tgl_kirim);
  $tahun   = $dateObj->format('Y');
  $bulan_angka = (int)$dateObj->format('n');
  $romawi = [1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
  $bulan_romawi = $romawi[$bulan_angka];

  // 2. MAPPING KODE BIDANG UNTUK SURAT KELUAR
  // Sesuaikan kode singkatan di sini jika perlu
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

  // 3. LOGIKA CEK NO URUT TERAKHIR (Per Tahun)
  $query = "SELECT MAX(no_urut) as max_no FROM surat_keluar WHERE tahun = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$tahun]);
  $result = $stmt->fetch();

  $no_urut_baru = ($result['max_no']) ? $result['max_no'] + 1 : 1;

  // 4. GENERATE NOMOR SURAT KELUAR
  // Format: [Kode]/[NoUrut 4 digit]/[KodeBidang]/[Bulan]/[Tahun]
  // Contoh: 660/0001/SK-TL-DLH/I/2026
  $nomor_final = $kode_klas . "/" . sprintf("%04d", $no_urut_baru) . "/" . $kode_bidang_sk . "/" . $bulan_romawi . "/" . $tahun;

  // 5. UPLOAD FILE
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

  // 6. SIMPAN
  // status_validasi default 'pending' jika ada Kepala Dinas, atau 'disetujui' jika langsung
  $sql = "INSERT INTO surat_keluar (no_urut, tgl_kirim, tujuan, perihal, kode_klasifikasi, bidang, bulan, tahun, file_path, nomor_surat, created_by, status_validasi) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    $no_urut_baru,
    $tgl_kirim,
    $tujuan,
    $perihal,
    $kode_klas,
    $bidang,
    $bulan_romawi,
    $tahun,
    $file_path,
    $nomor_final,
    $user_id
  ]);

  $_SESSION['success'] = "Surat Keluar berhasil dicatat!";
  header("Location: index.php");
  exit;
}
