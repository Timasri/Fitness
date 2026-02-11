<?php
session_start();
include 'koneksi.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id_pesanan = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Cek status pesanan
    $query = "SELECT 222247_status_pesanan FROM tbl_pesanan_222247 WHERE 222247_id_pesanan = ? AND 222247_id_pengguna = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("ii", $id_pesanan, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $status = $row['222247_status_pesanan'];

        if ($status === 'Pesanan Diproses') {
            // Hapus pesanan jika masih diproses (belum checkout/bayar)
            $delete_query = "DELETE FROM tbl_pesanan_222247 WHERE 222247_id_pesanan = ?";
            $delete_stmt = $koneksi->prepare($delete_query);
            $delete_stmt->bind_param("i", $id_pesanan);
            if ($delete_stmt->execute()) {
                header("Location: pembayaran.php?status=success&message=" . urlencode("Pesanan berhasil dihapus."));
            } else {
                header("Location: pembayaran.php?status=error&message=" . urlencode("Gagal menghapus pesanan."));
            }
        } elseif ($status === 'Pembayaran Diproses') {
            // Batalkan pesanan jika pembayaran sedang diproses
            $update_query = "UPDATE tbl_pesanan_222247 SET 222247_status_pesanan = 'Dibatalkan' WHERE 222247_id_pesanan = ?";
            $update_stmt = $koneksi->prepare($update_query);
            $update_stmt->bind_param("i", $id_pesanan);
            if ($update_stmt->execute()) {
                header("Location: pembayaran.php?status=success&message=" . urlencode("Pesanan berhasil dibatalkan."));
            } else {
                header("Location: pembayaran.php?status=error&message=" . urlencode("Gagal membatalkan pesanan."));
            }
        } else {
            header("Location: pembayaran.php?status=error&message=" . urlencode("Pesanan tidak dapat dibatalkan dengan status saat ini."));
        }
    } else {
        header("Location: pembayaran.php?status=error&message=" . urlencode("Pesanan tidak ditemukan."));
    }
} else {
    header("Location: pembayaran.php");
}
?>
