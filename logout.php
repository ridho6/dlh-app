<?php
// 1. Mulai session (agar sistem tahu session mana yang mau dihapus)
session_start();

// 2. Kosongkan semua variabel session ($_SESSION['user_id'], $_SESSION['role'], dll)
$_SESSION = [];

// 3. Hapus session dari memori PHP
session_unset();

// 4. Hancurkan session fisik di server
session_destroy();

// 5. Kembalikan pengguna ke Halaman Login
header("Location: index.php");
exit;
