<?php
session_start();
require_once '../../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['222247_nama_produk']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['222247_kategori']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['222247_harga']);
    $stok = mysqli_real_escape_string($koneksi, $_POST['222247_stok']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['222247_deskripsi']);
    $rating = isset($_POST['222247_rating']) ? floatval($_POST['222247_rating']) : 0.00;

    // Proses upload file gambar
    $foto_produk = $_FILES['foto']['name'];
    $foto_tmp = $_FILES['foto']['tmp_name'];
    $foto_target_dir = '../produk/';

    // Mengambil ekstensi file gambar
    $file_type = strtolower(pathinfo($foto_produk, PATHINFO_EXTENSION));

    // Validasi upload gambar
    $allowed_file_types = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($file_type, $allowed_file_types)) {
        // Buat nama acak untuk file gambar
        $nama_foto_baru = uniqid() . '.' . $file_type;
        $foto_target_file = $foto_target_dir . $nama_foto_baru;

        if (move_uploaded_file($foto_tmp, $foto_target_file)) {
            // Query untuk menyimpan data produk termasuk kolom rating
            $query = "INSERT INTO tbl_produk_222247 (222247_nama_produk, 222247_kategori, 222247_harga, 222247_stok, 222247_deskripsi, 222247_rating, 222247_foto)
                      VALUES ('$nama_produk', '$kategori', '$harga', '$stok', '$deskripsi', '$rating', '$nama_foto_baru')";

            if (mysqli_query($koneksi, $query)) {
                $_SESSION['message'] = "Produk berhasil ditambahkan!";
                header('Location: ../data_produk.php');
                exit();
            } else {
                $_SESSION['error'] = "Gagal menyimpan data produk!";
                header('Location: ../tambah_produk.php');
                exit();
            }
        } else {
            $_SESSION['error'] = "Gagal mengupload foto produk!";
            header('Location: ../tambah_produk.php');
            exit();
        }
    } else {
        $_SESSION['error'] = "Format file gambar tidak valid! Hanya diperbolehkan jpg, jpeg, png, atau gif.";
        header('Location: ../tambah_produk.php');
        exit();
    }
} else {
    $_SESSION['error'] = "Metode pengiriman data tidak valid!";
    header('Location: ../tambah_produk.php');
    exit();
}
?>
