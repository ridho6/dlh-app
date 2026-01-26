<?php
session_start();
require_once '../config/database.php';

if ($_SESSION['role'] != 'admin') {
  header("Location: index.php");
  exit;
}

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $stmt = $pdo->prepare("SELECT file_path FROM surat_tugas WHERE id = ?");
  $stmt->execute([$id]);
  $data = $stmt->fetch();

  if ($data && !empty($data['file_path'])) {
    $file_fisik = "../" . $data['file_path'];
    if (file_exists($file_fisik)) unlink($file_fisik);
  }

  $stmt = $pdo->prepare("DELETE FROM surat_tugas WHERE id = ?");
  $stmt->execute([$id]);

  $_SESSION['success'] = "Surat Tugas berhasil dihapus!";
}

header("Location: index.php");
exit;
