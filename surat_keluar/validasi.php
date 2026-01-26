<?php
session_start();
require_once '../config/database.php';

// Hanya Kepala Dinas yang boleh akses
if ($_SESSION['role'] != 'kepala') {
  header("Location: index.php");
  exit;
}

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  // Ubah status jadi disetujui
  $stmt = $pdo->prepare("UPDATE surat_keluar SET status_validasi = 'disetujui' WHERE id = ?");
  $stmt->execute([$id]);

  $_SESSION['success'] = "Surat Keluar telah disetujui!";
}

header("Location: index.php");
exit;
