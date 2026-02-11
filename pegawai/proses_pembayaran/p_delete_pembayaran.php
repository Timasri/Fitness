<?php
session_start();
require_once '../../koneksi.php';

// Validasi parameter
if (!isset($_GET['222247_id_pembayaran']) || empty($_GET['222247_id_pembayaran'])) {
    $_SESSION['error'] = "ID pembayaran tidak valid!";
    header('Location: ../data_pembayaran.php');
    exit();
}

$id_pembayaran = mysqli_real_escape_string($koneksi, $_GET['222247_id_pembayaran']);

try {
    // Hapus data pembayaran berdasarkan ID
    $stmt = $koneksi->prepare("DELETE FROM tbl_pembayaran_222247 WHERE 222247_id_pembayaran = ?");
    $stmt->bind_param("i", $id_pembayaran);

    if ($stmt->execute()) {
        $_SESSION['delete'] = "Data pembayaran berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Gagal menghapus data pembayaran!";
    }
    $stmt->close();
} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
}

header('Location: ../data_pembayaran.php');
exit();
