<?php
session_start();
require_once '../config/database.php';

if (isset($_POST['simpan'])) {

  // 1. Ambil Inputan Dasar
  $tgl_tugas   = $_POST['tgl_tugas'];
  $yang_tugas  = $_POST['yang_ditugaskan'];
  $perihal     = $_POST['perihal_penugasan'];
  $bidang      = $_POST['bidang'];
  $user_id     = $_SESSION['user_id'];

  // 2. TENTUKAN TAHUN & BULAN ROMAWI
  $dateObj = new DateTime($tgl_tugas);
  $tahun   = $dateObj->format('Y');

  $bulan_angka = (int)$dateObj->format('n');
  $romawi = [1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
  $bulan_romawi = $romawi[$bulan_angka];

  // 3. TENTUKAN KODE BIDANG (ST-DLH)
  $parts = explode(' ', $bidang);
  $kode_bidang = strtoupper($parts[0]);

  // --- LOGIKA BARU: HITUNG NOMOR URUT BERDASARKAN DATA TAHUN INI ---

  // Cari nomor surat terakhir di tahun yang sama
  $query = "SELECT nomor_surat_tugas FROM surat_tugas 
              WHERE tahun = ? 
              ORDER BY id DESC LIMIT 1";

  $stmt = $pdo->prepare($query);
  $stmt->execute([$tahun]);
  $last_data = $stmt->fetch();

  $urutan_baru = 1; // Default jika belum ada data (Mulai dari 1)

  if ($last_data) {
    // Format di DB: 0001/ST-DLH/I/2026
    // Kita pecah string berdasarkan tanda garis miring '/'
    $pecah_nomor = explode('/', $last_data['nomor_surat_tugas']);

    // Ambil bagian pertama (angka 0001), ubah jadi integer, lalu tambah 1
    $urutan_terakhir = (int)$pecah_nomor[0];
    $urutan_baru = $urutan_terakhir + 1;
  }

  // Format menjadi 4 digit (0001, 0002, dst)
  $prefix_nomor = sprintf("%04d", $urutan_baru);

  // Gabungkan menjadi Nomor ST Lengkap
  $nomor_final = $prefix_nomor . "/" . $kode_bidang . "/" . $bulan_romawi . "/" . $tahun;
  // ------------------------------------------------------------------

  // 4. UPLOAD FILE
  $file_path = null;
  if (!empty($_FILES['file_tugas']['name'])) {
    $target_dir = "../uploads/";
    if (!file_exists($target_dir)) {
      mkdir($target_dir, 0777, true);
    }
    $file_name = time() . "_" . basename($_FILES['file_tugas']['name']);
    $target_file = $target_dir . $file_name;
    if (move_uploaded_file($_FILES['file_tugas']['tmp_name'], $target_file)) {
      $file_path = "uploads/" . $file_name;
    }
  }

  // 5. SIMPAN KE DATABASE (Langsung dengan nomor yang sudah jadi)
  $sql = "INSERT INTO surat_tugas (nomor_surat_tugas, tgl_tugas, yang_ditugaskan, perihal_penugasan, bidang, bulan, tahun, file_path, created_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([$nomor_final, $tgl_tugas, $yang_tugas, $perihal, $bidang, $bulan_romawi, $tahun, $file_path, $user_id]);

  $_SESSION['success'] = "Surat Tugas berhasil dicatat!";
  header("Location: index.php");
  exit;
}
