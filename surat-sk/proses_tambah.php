<?php
session_start();
require_once '../config/database.php';

if (isset($_POST['simpan'])) {

  // ... (Bagian atas kode sama persis dengan sebelumnya) ...
  $tgl_sk     = $_POST['tgl_sk'];
  $ket_sk     = $_POST['keterangan_sk'];
  $perihal    = $_POST['perihal'];
  $bidang     = $_POST['bidang'];
  $user_id    = $_SESSION['user_id'];

  // 1. Generate Tanggal & Romawi (Sama)
  $dateObj = new DateTime($tgl_sk);
  $tahun   = $dateObj->format('Y');
  $bulan_angka = (int)$dateObj->format('n');
  $romawi = [1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
  $bulan_romawi = $romawi[$bulan_angka];

  // 2. Kode Bidang (Sama)
  $parts = explode(' ', $bidang);
  $kode_bidang = $parts[0];

  // 3. Cek No Urut (Sama)
  $query = "SELECT MAX(no_urut) as max_no FROM surat_keputusan WHERE tahun = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$tahun]);
  $result = $stmt->fetch();
  $no_urut_baru = ($result['max_no']) ? $result['max_no'] + 1 : 1;

  // 4. Generate Nomor (Sama)
  $nomor_final = sprintf("%04d", $no_urut_baru) . "/" . $kode_bidang . "/" . $bulan_romawi . "/" . $tahun;

  // 5. Upload File (Sama)
  $file_path = null;
  if (!empty($_FILES['file_sk']['name'])) {
    $target_dir = "../uploads/";
    $file_name = time() . "_" . basename($_FILES['file_sk']['name']);
    $target_file = $target_dir . $file_name;
    if (move_uploaded_file($_FILES['file_sk']['tmp_name'], $target_file)) {
      $file_path = "uploads/" . $file_name;
    }
  }

  // 6. SIMPAN (DENGAN STATUS PENDING)
  $sql = "INSERT INTO surat_keputusan (no_urut, tgl_sk, keterangan_sk, perihal, bidang, bulan, tahun, file_path, nomor_sk, created_by, status_validasi) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    $no_urut_baru,
    $tgl_sk,
    $ket_sk,
    $perihal,
    $bidang,
    $bulan_romawi,
    $tahun,
    $file_path,
    $nomor_final,
    $user_id
  ]);

  $_SESSION['success'] = "SK berhasil dicatat! Status Pending.";
  header("Location: index.php");
  exit;
}
