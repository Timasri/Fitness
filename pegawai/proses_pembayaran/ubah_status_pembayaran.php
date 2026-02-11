<?php
session_start();
require_once '../../koneksi.php';

// Validasi parameter
if (!isset($_GET['id_pembayaran']) || !isset($_GET['status_pembayaran'])) {
    $_SESSION['error'] = "Data tidak lengkap!";
    header('Location: ../data_pembayaran.php');
    exit();
}

$id_pembayaran = mysqli_real_escape_string($koneksi, $_GET['id_pembayaran']);
$status_pembayaran = mysqli_real_escape_string($koneksi, $_GET['status_pembayaran']);

// Validasi status pembayaran
$valid_statuses = ['Pembayaran Diproses', 'Lunas', 'Gagal', 'Dibatalkan'];
if (!in_array($status_pembayaran, $valid_statuses)) {
    $_SESSION['error'] = "Status pembayaran tidak valid!";
    header('Location: ../data_pembayaran.php');
    exit();
}

try {
    $stmt = $koneksi->prepare("UPDATE tbl_pembayaran_222247 SET 222247_status_pembayaran = ? WHERE 222247_id_pembayaran = ?");
    $stmt->bind_param("si", $status_pembayaran, $id_pembayaran);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Status pembayaran berhasil diperbarui menjadi: " . htmlspecialchars($status_pembayaran);
    } else {
        $_SESSION['error'] = "Gagal memperbarui status pembayaran.";
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
}

header('Location: ../data_pembayaran.php');
exit();
?>
