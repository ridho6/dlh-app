<?php
session_start();
require_once '../config/database.php';

// Hanya Admin yang boleh hapus
if ($_SESSION['role'] != 'admin') {
  header("Location: index.php");
  exit;
}

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  // 1. Ambil info file dulu
  $stmt = $pdo->prepare("SELECT file_path FROM surat_keputusan WHERE id = ?");
  $stmt->execute([$id]);
  $data = $stmt->fetch();

  // 2. Hapus File Fisik (Jika ada)
  if ($data && !empty($data['file_path'])) {
    $file_fisik = "../" . $data['file_path'];
    if (file_exists($file_fisik)) {
      unlink($file_fisik);
    }
  }

  // 3. Hapus Record Database
  $stmt = $pdo->prepare("DELETE FROM surat_keputusan WHERE id = ?");
  $stmt->execute([$id]);

  $_SESSION['success'] = "Data dan file berhasil dihapus!";
}

header("Location: index.php");
exit;
