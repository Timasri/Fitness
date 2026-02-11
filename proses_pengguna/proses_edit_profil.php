<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

// Koneksi ke database
include '../koneksi.php';

// Ambil ID pengguna dari sesi
$id_pengguna = $_SESSION['user_id'];

// Cek apakah data yang diperlukan ada di POST
if (isset($_POST['username'], $_POST['email'], $_POST['nama_lengkap'], $_POST['nomor_telepon'], $_POST['alamat'])) {
    // Ambil data dari POST
    $username = $_POST['username'];
    $email = $_POST['email'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $alamat = $_POST['alamat'];

    // Cek jika username atau email diubah
    $query_user = "SELECT 222247_username, 222247_email FROM tbl_pengguna_222247 WHERE 222247_id_pengguna = ?";
    $stmt_user = $koneksi->prepare($query_user);
    $stmt_user->bind_param("i", $id_pengguna);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $data_user = $result_user->fetch_assoc();

    $username_dulu = $data_user['222247_username'];
    $email_dulu = $data_user['222247_email'];

    // Update profil pengguna
    $query_update = "UPDATE tbl_pengguna_222247 SET 
                     222247_username = ?, 
                     222247_email = ?, 
                     222247_nama_lengkap = ?, 
                     222247_nomor_telepon = ?, 
                     222247_alamat = ? 
                     WHERE 222247_id_pengguna = ?";
    $stmt_update = $koneksi->prepare($query_update);
    $stmt_update->bind_param("sssssi", $username, $email, $nama_lengkap, $nomor_telepon, $alamat, $id_pengguna);

    if ($stmt_update->execute()) {
        // Jika username atau email diubah, logout pengguna
        if ($username !== $username_dulu || $email !== $email_dulu) {
            // Hapus sesi dan redirect ke login
            session_unset();
            session_destroy();
            header('Location: ../login.php?pesan=Profil berhasil diubah. Silakan login kembali.');
            exit();
        } else {
            echo "<script>alert('Profil berhasil diperbarui!'); window.location.href = '../profil.php';</script>";
        }
    } else {
        echo "<script>alert('Gagal memperbarui profil. Silakan coba lagi.'); window.location.href = '../profil.php';</script>";
    }
} elseif (isset($_POST['kata_sandi_lama'], $_POST['kata_sandi_baru'], $_POST['konfirmasi_kata_sandi'])) {
    // Ambil data untuk ubah kata sandi
    $kata_sandi_lama = $_POST['kata_sandi_lama'];
    $kata_sandi_baru = $_POST['kata_sandi_baru'];
    $konfirmasi_kata_sandi = $_POST['konfirmasi_kata_sandi'];

    // Cek kata sandi lama
    $query_check_password = "SELECT 222247_kata_sandi FROM tbl_pengguna_222247 WHERE 222247_id_pengguna = ?";
    $stmt_check_password = $koneksi->prepare($query_check_password);
    $stmt_check_password->bind_param("i", $id_pengguna);
    $stmt_check_password->execute();
    $result_password = $stmt_check_password->get_result();
    $data_password = $result_password->fetch_assoc();

    if ($data_password['222247_kata_sandi'] === $kata_sandi_lama) {
        // Cek apakah kata sandi baru dan konfirmasi sama
        if ($kata_sandi_baru === $konfirmasi_kata_sandi) {
            // Update kata sandi
            $query_update_password = "UPDATE tbl_pengguna_222247 SET 222247_kata_sandi = ? WHERE 222247_id_pengguna = ?";
            $stmt_update_password = $koneksi->prepare($query_update_password);
            $stmt_update_password->bind_param("si", $kata_sandi_baru, $id_pengguna);

            if ($stmt_update_password->execute()) {
                echo "<script>alert('Kata sandi berhasil diubah!'); window.location.href = '../profil.php';</script>";
            } else {
                echo "<script>alert('Gagal mengubah kata sandi. Silakan coba lagi.'); window.location.href = '../profil.php';</script>";
            }
        } else {
            echo "<script>alert('Kata sandi baru dan konfirmasi tidak sama.'); window.location.href = '../profil.php';</script>";
        }
    } else {
        echo "<script>alert('Kata sandi lama salah.'); window.location.href = '../profil.php';</script>";
    }
} else {
    echo "<script>alert('Data tidak lengkap.'); window.location.href = '../profil.php';</script>";
}

$stmt_user->close();
$stmt_update->close();
$koneksi->close();
?>
