<?php
session_start();
require_once '../../koneksi.php';

// Cek apakah parameter ID pengguna ada
if (isset($_GET['222247_id_pengguna'])) {
    $id_pengguna = $_GET['222247_id_pengguna'];

    try {
        // Menghapus data pengguna berdasarkan ID
        $stmt = $koneksi->prepare("DELETE FROM tbl_pengguna_222247 WHERE 222247_id_pengguna = ?");
        $stmt->bind_param("i", $id_pengguna);
        
        if ($stmt->execute()) {
            $_SESSION['delete'] = "Data pengguna berhasil dihapus.";
        } else {
            $_SESSION['error'] = "Gagal menghapus data pengguna.";
        }

        $stmt->close();
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "ID pengguna tidak ditemukan.";
}

// Redirect kembali ke halaman data pengguna
header('Location: ../data_pengguna.php');
exit();
