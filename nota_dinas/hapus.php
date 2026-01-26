<?php
session_start();
require_once '../config/database.php';

// Hanya Admin
if ($_SESSION['role'] != 'admin') {
  header("Location: index.php");
  exit;
}

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  // Ambil info file
  $stmt = $pdo->prepare("SELECT file_path FROM nota_dinas WHERE id = ?");
  $stmt->execute([$id]);
  $data = $stmt->fetch();

  // Hapus file fisik
  if ($data && !empty($data['file_path'])) {
    $file_fisik = "../" . $data['file_path'];
    if (file_exists($file_fisik)) {
      unlink($file_fisik);
    }
  }

  // Hapus record
  $stmt = $pdo->prepare("DELETE FROM nota_dinas WHERE id = ?");
  $stmt->execute([$id]);

  $_SESSION['success'] = "Nota Dinas berhasil dihapus!";
}

header("Location: index.php");
exit;
