<?php
session_start();
require_once '../config/database.php';

// Cek hak akses (Hanya Kepala)
if ($_SESSION['role'] != 'kepala') {
  header("Location: index.php");
  exit;
}

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  // Update status jadi disetujui
  $stmt = $pdo->prepare("UPDATE surat_keputusan SET status_validasi = 'disetujui' WHERE id = ?");
  $stmt->execute([$id]);

  $_SESSION['success'] = "SK telah disetujui!";
}

header("Location: index.php");
exit;
