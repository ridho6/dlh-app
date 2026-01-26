<?php
$host = "localhost";
$dbname = "dlh_arsip"; // Sesuaikan nama db
$username = "root";
$password = "";

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  date_default_timezone_set('Asia/Makassar');
} catch (PDOException $e) {
  die("Koneksi gagal: " . $e->getMessage());
}

// --- TAMBAHKAN INI ---
define('BASE_URL', 'http://localhost/dlh-app/');
