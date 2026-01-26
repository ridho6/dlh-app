<?php
session_start();
require_once '../config/database.php';

if (isset($_POST['update'])) {
  $id       = $_POST['id_user'];
  $username = htmlspecialchars($_POST['username']);
  $nama     = htmlspecialchars($_POST['nama_lengkap']);
  $role     = $_POST['role'];

  // Cek duplikat username (kecuali punya sendiri)
  $cek = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
  $cek->execute([$username, $id]);

  if ($cek->rowCount() > 0) {
    // Jika gagal, bisa kita buat session error (opsional) atau langsung redirect
    $_SESSION['error'] = "Gagal! Username sudah dipakai.";
    header("Location: user_show.php");
    exit;
  }

  // Proses Update
  if (!empty($_POST['password'])) {
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET username=?, nama_lengkap=?, role=?, password=? WHERE id=?");
    $stmt->execute([$username, $nama, $role, $pass, $id]);
  } else {
    $stmt = $pdo->prepare("UPDATE users SET username=?, nama_lengkap=?, role=? WHERE id=?");
    $stmt->execute([$username, $nama, $role, $id]);
  }

  $_SESSION['success'] = "Data berhasil diperbarui!";
  header("Location: user_show.php");
  exit;
} else {
  header("Location: user_show.php");
  exit;
}
