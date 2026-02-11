<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

// Koneksi ke database
include '../koneksi.php';

// Cek apakah ID pesanan ada di POST
if (!isset($_POST['id_pesanan'])) {
    echo "ID pesanan tidak valid.";
    exit();
}

$id_pesanan = intval($_POST['id_pesanan']);

// Cek apakah sudah ada bukti pembayaran untuk pesanan ini
$query_check = "SELECT * FROM tbl_pembayaran_222247 WHERE 222247_id_pesanan = ?";
$stmt_check = $koneksi->prepare($query_check);
$stmt_check->bind_param("i", $id_pesanan);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    echo "Bukti pembayaran sudah ada untuk pesanan ini. Anda tidak dapat meng-upload bukti pembayaran lagi.";
    exit();
}

// Cek apakah ada file yang diupload
if (isset($_FILES['bukti_pembayaran'])) {
    $errors = array();
    $file_name = $_FILES['bukti_pembayaran']['name'];
    $file_size = $_FILES['bukti_pembayaran']['size'];
    $file_tmp = $_FILES['bukti_pembayaran']['tmp_name'];
    $file_type = $_FILES['bukti_pembayaran']['type'];
    
    // Validasi file
    $allowed_extensions = array("jpg", "jpeg", "png", "gif");
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    if (!in_array($file_ext, $allowed_extensions)) {
        $errors[] = "Ekstensi file tidak diizinkan. Hanya JPG, JPEG, PNG, GIF yang diperbolehkan.";
    }
    
    if ($file_size > 2097152) {
        $errors[] = "Ukuran file harus kurang dari 2MB.";
    }
    
    if (empty($errors)) {
        // Nama file baru
        $file_name = time() . '_' . $file_name;
        $upload_path = '../pegawai/bukti_pembayaran/' . $file_name;

        // Pindahkan file ke direktori upload
        if (move_uploaded_file($file_tmp, $upload_path)) {
            // Masukkan data pembayaran ke database
            $query = "INSERT INTO tbl_pembayaran_222247 (222247_id_pesanan, 222247_status_pembayaran, 222247_tanggal_pembayaran, 222247_bukti_pembayaran) 
                      VALUES (?, 'Diproses', NOW(), ?)";
            $stmt = $koneksi->prepare($query);
            $stmt->bind_param("is", $id_pesanan, $file_name);
            $stmt->execute();

            // Cek apakah data pembayaran berhasil disimpan
            if ($stmt->affected_rows > 0) {
                // Ambil data jumlah produk yang dipesan
                $query_jumlah_produk = "SELECT 222247_id_produk, 222247_jumlah 
                                        FROM tbl_pesanan_222247 
                                        WHERE 222247_id_pesanan = ?";
                $stmt_jumlah_produk = $koneksi->prepare($query_jumlah_produk);
                $stmt_jumlah_produk->bind_param("i", $id_pesanan);
                $stmt_jumlah_produk->execute();
                $result_jumlah_produk = $stmt_jumlah_produk->get_result();

                if ($result_jumlah_produk->num_rows > 0) {
                    $data_produk = $result_jumlah_produk->fetch_assoc();
                    $id_produk = $data_produk['222247_id_produk'];
                    $jumlah_pesanan = $data_produk['222247_jumlah'];

                    // Kurangi stok produk
                    $query_update_stok = "UPDATE tbl_produk_222247 
                                          SET 222247_stok = 222247_stok - ? 
                                          WHERE 222247_id_produk = ?";
                    $stmt_update_stok = $koneksi->prepare($query_update_stok);
                    $stmt_update_stok->bind_param("ii", $jumlah_pesanan, $id_produk);
                    $stmt_update_stok->execute();

                    if ($stmt_update_stok->affected_rows > 0) {
                        // Redirect ke riwayat pembayaran jika stok berhasil diperbarui
                        header('Location: ../riwayat_pembayaran.php');
                        exit();
                    } else {
                        echo "Gagal mengupdate stok produk.";
                    }
                } else {
                    echo "Produk tidak ditemukan dalam pesanan.";
                }
            } else {
                echo "Gagal menyimpan data pembayaran.";
            }
        } else {
            echo "Gagal mengupload file.";
        }
    } else {
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
} else {
    echo "Tidak ada file yang diupload.";
}

// Tutup koneksi
$stmt_check->close();
$koneksi->close();
?>
