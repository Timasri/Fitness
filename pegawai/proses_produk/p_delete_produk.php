<?php
session_start();
require_once '../../koneksi.php';

if (isset($_GET['222247_id_produk'])) {
    // Ambil ID produk dari URL
    $id_produk = mysqli_real_escape_string($koneksi, $_GET['222247_id_produk']);

    // Query untuk mendapatkan informasi produk termasuk foto
    $query_get_foto = "SELECT 222247_foto FROM tbl_produk_222247 WHERE 222247_id_produk = '$id_produk'";
    $result_get_foto = mysqli_query($koneksi, $query_get_foto);

    if (mysqli_num_rows($result_get_foto) > 0) {
        $data_produk = mysqli_fetch_assoc($result_get_foto);
        $foto_produk = $data_produk['222247_foto'];

        // Hapus produk dari database
        $query_delete = "DELETE FROM tbl_produk_222247 WHERE 222247_id_produk = '$id_produk'";
        if (mysqli_query($koneksi, $query_delete)) {
            // Jika foto produk ada dan bukan file default, hapus file dari folder
            $foto_target_dir = '../produk/';
            if ($foto_produk != 'produk.jpg' && file_exists($foto_target_dir . $foto_produk)) {
                unlink($foto_target_dir . $foto_produk);
            }

            // Set pesan sukses dan arahkan kembali ke halaman data produk
            $_SESSION['delete'] = "Produk berhasil dihapus!";
            header('Location: ../data_produk.php');
            exit();
        } else {
            $_SESSION['error'] = "Gagal menghapus produk!";
            header('Location: ../data_produk.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Produk tidak ditemukan!";
        header('Location: ../data_produk.php');
        exit();
    }
} else {
    $_SESSION['error'] = "ID produk tidak valid!";
    header('Location: ../data_produk.php');
    exit();
}
?>
