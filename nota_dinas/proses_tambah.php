<?php
session_start();
require_once '../config/database.php';

if (isset($_POST['simpan'])) {

  $tgl_nota   = $_POST['tgl_nota'];
  $tujuan     = $_POST['tujuan'];
  $perihal    = $_POST['perihal'];
  $kode_klas  = $_POST['kode_klasifikasi'];
  $bidang     = $_POST['bidang'];
  $user_id    = $_SESSION['user_id'];

  // 1. GENERATE TAHUN & BULAN ROMAWI
  $dateObj = new DateTime($tgl_nota);
  $tahun   = $dateObj->format('Y');

  $bulan_angka = (int)$dateObj->format('n');
  $romawi = [1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
  $bulan_romawi = $romawi[$bulan_angka];

  // 2. MAPPING KODE BIDANG (LOGIKA BARU)
  // Ubah nama bidang dari form menjadi kode surat
  $kode_bidang_map = [
    'Sekretariat'     => 'ND-SET-DLH', // Contoh singkatan Sekretariat
    'Tata Lingkungan' => 'ND-TL-DLH',  // Sesuai permintaan
    'Pengawasan'      => 'ND-WAS-DLH',
    'Kebersihan'      => 'ND-KEB-DLH', // Kebersihan & Pengelolaan Sampah
    'Pertamanan'      => 'ND-PSP-DLH', // Pertamanan, Sarana & Prasarana
    'TPA'             => 'ND-TPA-DLH',
    'Laboratorium'    => 'ND-LAB-DLH'
  ];

  // Ambil kode berdasarkan bidang yang dipilih, default 'ND-DLH' jika tidak ketemu
  $kode_bidang_nd = isset($kode_bidang_map[$bidang]) ? $kode_bidang_map[$bidang] : 'ND-DLH';


  // 3. LOGIKA CEK NO URUT TERAKHIR
  $query = "SELECT MAX(no_urut) as max_no FROM nota_dinas WHERE tahun = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$tahun]);
  $result = $stmt->fetch();

  $no_urut_baru = ($result['max_no']) ? $result['max_no'] + 1 : 1;

  // 4. GENERATE NOMOR ND LENGKAP
  // Format: [KodeKlas]/[NoUrut 4 Digit]/[KodeBidang]/[Bulan]/[Tahun]
  // Contoh: 000/0001/ND-TL-DLH/I/2026

  $nomor_nd = $kode_klas . "/" . sprintf("%04d", $no_urut_baru) . "/" . $kode_bidang_nd . "/" . $bulan_romawi . "/" . $tahun;


  // 5. UPLOAD FILE
  $file_path = null;
  if (!empty($_FILES['file_nota']['name'])) {
    $target_dir = "../uploads/";
    if (!file_exists($target_dir)) {
      mkdir($target_dir, 0777, true);
    }
    $file_name = time() . "_" . basename($_FILES['file_nota']['name']);
    $target_file = $target_dir . $file_name;
    if (move_uploaded_file($_FILES['file_nota']['tmp_name'], $target_file)) {
      $file_path = "uploads/" . $file_name;
    }
  }

  // 6. SIMPAN KE DATABASE
  $sql = "INSERT INTO nota_dinas (no_urut, tgl_nota, tujuan, perihal, kode_klasifikasi, bidang, bulan, tahun, file_path, nomor_nota, created_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    $no_urut_baru,
    $tgl_nota,
    $tujuan,
    $perihal,
    $kode_klas,
    $bidang,
    $bulan_romawi,
    $tahun,
    $file_path,
    $nomor_nd,
    $user_id
  ]);

  $_SESSION['success'] = "Nota Dinas berhasil dibuat! Nomor: " . $nomor_nd;
  header("Location: index.php");
  exit;
}
