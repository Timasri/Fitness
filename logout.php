<?php
session_start();

// Hapus semua variabel sesi
$_SESSION = [];

// Hapus sesi
session_destroy();

// Arahkan pengguna kembali ke halaman login
header('Location: login.php');
exit();
?>
