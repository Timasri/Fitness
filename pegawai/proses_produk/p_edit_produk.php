<?php
session_start();
require_once '../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_produk = mysqli_real_escape_string($koneksi, $_POST['222247_id_produk']);
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['222247_nama_produk']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['222247_kategori']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['222247_harga']);
    $stok = mysqli_real_escape_string($koneksi, $_POST['222247_stok']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['222247_deskripsi']);

    // Ambil data produk yang lama untuk mendapatkan nama file foto lama
    $query_get_foto = "SELECT 222247_foto FROM tbl_produk_222247 WHERE 222247_id_produk = '$id_produk'";
    $result_get_foto = mysqli_query($koneksi, $query_get_foto);
    $data_produk = mysqli_fetch_assoc($result_get_foto);
    $foto_lama = $data_produk['222247_foto'];

    // Cek apakah ada file foto baru yang diunggah
    if (!empty($_FILES['foto']['name'])) {
        // Proses upload file gambar baru
        $foto_produk = $_FILES['foto']['name'];
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_target_dir = '../produk/';

        // Dapatkan ekstensi file
        $file_type = strtolower(pathinfo($foto_produk, PATHINFO_EXTENSION));

        // Validasi upload gambar
        $allowed_file_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_type, $allowed_file_types)) {
            // Beri nama acak pada foto baru
            $nama_foto_baru = uniqid() . '.' . $file_type;
            $foto_target_file = $foto_target_dir . $nama_foto_baru;

            // Hapus foto lama jika ada dan bukan default
            if (file_exists($foto_target_dir . $foto_lama) && $foto_lama != 'produk.jpg') {
                unlink($foto_target_dir . $foto_lama);
            }

            // Pindahkan foto baru ke folder produk
            if (move_uploaded_file($foto_tmp, $foto_target_file)) {
                // Update produk dengan foto baru
                $query = "UPDATE tbl_produk_222247 SET 222247_nama_produk = '$nama_produk', 222247_kategori = '$kategori', 
                          222247_harga = '$harga', 222247_stok = '$stok', 222247_deskripsi = '$deskripsi', 222247_foto = '$nama_foto_baru'
                          WHERE 222247_id_produk = '$id_produk'";
            } else {
                $_SESSION['error'] = "Gagal mengupload foto produk!";
                header('Location: ../edit_produk.php?id=' . $id_produk);
                exit();
            }
        } else {
            $_SESSION['error'] = "Format file gambar tidak valid! Hanya diperbolehkan jpg, jpeg, png, atau gif.";
            header('Location: ../edit_produk.php?id=' . $id_produk);
            exit();
        }
    } else {
        // Update produk tanpa mengubah foto
        $query = "UPDATE tbl_produk_222247 SET 222247_nama_produk = '$nama_produk', 222247_kategori = '$kategori', 
                  222247_harga = '$harga', 222247_stok = '$stok', 222247_deskripsi = '$deskripsi'
                  WHERE 222247_id_produk = '$id_produk'";
    }

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['message'] = "Produk berhasil diperbarui!";
        header('Location: ../data_produk.php');
        exit();
    } else {
        $_SESSION['error'] = "Gagal memperbarui data produk!";
        header('Location: ../edit_produk.php?id=' . $id_produk);
        exit();
    }
} else {
    $_SESSION['error'] = "Metode pengiriman data tidak valid!";
    header('Location: ../data_produk.php');
    exit();
}
?>
