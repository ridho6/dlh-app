<?php
session_start();
require_once '../config/database.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  if ($id == $_SESSION['user_id']) {
    $_SESSION['error'] = "Anda tidak bisa menghapus akun sendiri!";
  } else {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['success'] = "User berhasil dihapus!";
  }
}

header("Location: user_show.php");
exit;
