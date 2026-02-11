<?php
session_start();
require_once '../koneksi.php';

if (!isset($_GET['id_pesanan'], $_GET['status_pesanan'])) {
    $_SESSION['error'] = "Parameter tidak valid!";
    header('Location: data_pesanan.php');
    exit();
}

$id_pesanan = mysqli_real_escape_string($koneksi, $_GET['id_pesanan']);
$status_pesanan = mysqli_real_escape_string($koneksi, $_GET['status_pesanan']);

// Validasi status baru
$valid_statuses = ['Pesanan Diproses', 'Pembayaran Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'];
if (!in_array($status_pesanan, $valid_statuses)) {
    $_SESSION['error'] = "Status tidak valid!";
    header('Location: data_pesanan.php');
    exit();
}

// Update status di database
$query = "UPDATE tbl_pesanan_222247 SET 222247_status_pesanan = ? WHERE 222247_id_pesanan = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("si", $status_pesanan, $id_pesanan);

if ($stmt->execute()) {
    $_SESSION['success'] = "Status pesanan berhasil diperbarui!";
} else {
    $_SESSION['error'] = "Gagal memperbarui status pesanan!";
}

header('Location: data_pesanan.php');
exit();
?>
