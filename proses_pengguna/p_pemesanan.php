<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: ../login.php');
    exit();
}

// Ambil data pengguna dari sesi
$user_id = $_SESSION['user_id'];

// Koneksi ke database
include '../koneksi.php';

// Cek apakah data yang diperlukan ada di POST
if (!isset($_POST['id_produk'], $_POST['jumlah'])) {
    echo "Data pemesanan tidak lengkap.";
    exit();
}

$id_produk = intval($_POST['id_produk']);
$jumlah = intval($_POST['jumlah']);

// Ambil data produk
$query_produk = "SELECT * FROM tbl_produk_222247 WHERE 222247_id_produk = ?";
$stmt_produk = mysqli_prepare($koneksi, $query_produk);
mysqli_stmt_bind_param($stmt_produk, 'i', $id_produk);
mysqli_stmt_execute($stmt_produk);
$result_produk = mysqli_stmt_get_result($stmt_produk);

if ($result_produk && mysqli_num_rows($result_produk) > 0) {
    $produk = mysqli_fetch_assoc($result_produk);
} else {
    echo "Produk tidak ditemukan.";
    exit();
}

// Cek apakah stok mencukupi
if ($produk['222247_stok'] < $jumlah) {
    echo "<script>alert('Stok tidak mencukupi.'); window.location.href = '../pemesanan.php?id=$id_produk';</script>";
    exit();
}

// Hitung total harga
$total_harga = $jumlah * $produk['222247_harga'];
$tanggal_pesanan = date('Y-m-d H:i:s');
$status_pesanan = 'Pesanan Diproses'; // Status awal

// Simpan pesanan ke database
$query_pesanan = "INSERT INTO tbl_pesanan_222247 (222247_id_pengguna, 222247_id_produk, 222247_tanggal_pesanan, 222247_status_pesanan, 222247_total_harga, 222247_jumlah) 
                  VALUES (?, ?, ?, ?, ?, ?)";
$stmt_pesanan = mysqli_prepare($koneksi, $query_pesanan);
mysqli_stmt_bind_param($stmt_pesanan, 'iissdi', $user_id, $id_produk, $tanggal_pesanan, $status_pesanan, $total_harga, $jumlah);

if (mysqli_stmt_execute($stmt_pesanan)) {
    // Kurangi stok produk
    $query_update_stok = "UPDATE tbl_produk_222247 SET 222247_stok = 222247_stok - ? WHERE 222247_id_produk = ?";
    $stmt_update_stok = mysqli_prepare($koneksi, $query_update_stok);
    mysqli_stmt_bind_param($stmt_update_stok, 'ii', $jumlah, $id_produk);

    if (mysqli_stmt_execute($stmt_update_stok)) {
        // Stok berhasil diperbarui, arahkan ke halaman pembayaran
        echo "<script>alert('Pesanan berhasil!'); window.location.href = '../pembayaran.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui stok.'); window.location.href = '../pemesanan.php?id=$id_produk';</script>";
    }
} else {
    echo "<script>alert('Gagal memproses pesanan. Coba lagi.'); window.location.href = '../pemesanan.php?id=$id_produk';</script>";
}
?>
