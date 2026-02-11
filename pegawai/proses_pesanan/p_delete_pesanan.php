<?php
session_start();
require_once '../../koneksi.php';

// Memeriksa apakah ID pesanan ada di URL
if (!isset($_GET['222247_id_pesanan']) || empty($_GET['222247_id_pesanan'])) {
    $_SESSION['error'] = "ID pesanan tidak valid!";
    header('Location: ../data_pesanan.php');
    exit();
}

// Mendapatkan ID pesanan dari URL
$id_pesanan = mysqli_real_escape_string($koneksi, $_GET['222247_id_pesanan']);

try {
    // Mengecek apakah pesanan sedang digunakan di data pembayaran
    $check_stmt = $koneksi->prepare("SELECT COUNT(*) as count FROM tbl_pembayaran_222247 WHERE 222247_id_pesanan = ?");
    $check_stmt->bind_param("i", $id_pesanan);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $row = $check_result->fetch_assoc();
    
    if ($row['count'] > 0) {
        // Jika ada referensi data di tabel pembayaran, tampilkan pesan error
        $_SESSION['error'] = "Pesanan ini tidak dapat dihapus karena sedang digunakan di data pembayaran!";
    } else {
        // Jika tidak ada referensi, lanjutkan dengan proses penghapusan
        $stmt = $koneksi->prepare("DELETE FROM tbl_pesanan_222247 WHERE 222247_id_pesanan = ?");
        $stmt->bind_param("i", $id_pesanan);
        
        if ($stmt->execute()) {
            $_SESSION['delete'] = "Data pesanan berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus data pesanan!";
        }
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
}

// Redirect kembali ke halaman data pesanan
header('Location: ../data_pesanan.php');
exit();
?>
