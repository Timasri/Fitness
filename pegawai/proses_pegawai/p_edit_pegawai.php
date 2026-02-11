<?php
session_start();
require_once '../../koneksi.php';

// Cek apakah sesi login ada
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: ../login.php');
    exit();
}

// Memeriksa apakah id_pegawai ada di URL
if (!isset($_GET['222247_id_pegawai'])) {
    $_SESSION['error'] = "ID Pegawai tidak valid.";
    header('Location: ../data_pegawai.php');
    exit();
}

$id_pegawai = $_GET['222247_id_pegawai'];

// Mengambil data pegawai berdasarkan id_pegawai
try {
    $stmt = $koneksi->prepare("SELECT 222247_username, 222247_kata_sandi, 222247_nama_lengkap, 222247_role FROM tbl_pegawai_222247 WHERE 222247_id_pegawai = ?");
    $stmt->bind_param("i", $id_pegawai);
    $stmt->execute();
    $result = $stmt->get_result();

    // Memastikan pegawai ditemukan
    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Pegawai tidak ditemukan.";
        header('Location: ../data_pegawai.php');
        exit();
    }

    $data_pegawai = $result->fetch_assoc();
} catch (Exception $e) {
    $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
    header('Location: ../data_pegawai.php');
    exit();
}

// Memeriksa apakah data telah dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $username = $_POST['222247_username'];
    $kata_sandi = $_POST['222247_kata_sandi'];
    $nama_lengkap = $_POST['222247_nama_lengkap'];
    $role = $_POST['222247_role'];

    // Memeriksa apakah username telah diubah
    $old_username = $data_pegawai['222247_username'];

    try {
        // Memeriksa apakah username sudah ada
        $stmt = $koneksi->prepare("SELECT 222247_id_pegawai FROM tbl_pegawai_222247 WHERE 222247_username = ? AND 222247_id_pegawai != ?");
        $stmt->bind_param("si", $username, $id_pegawai);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['error'] = "Username sudah digunakan oleh pegawai lain.";
            header('Location: ../edit_pegawai.php?222247_id_pegawai=' . $id_pegawai);
            exit();
        }

        // Update data pegawai (username, nama_lengkap, dan role)
        $stmt = $koneksi->prepare("UPDATE tbl_pegawai_222247 SET 222247_username = ?, 222247_nama_lengkap = ?, 222247_role = ? WHERE 222247_id_pegawai = ?");
        $stmt->bind_param("sssi", $username, $nama_lengkap, $role, $id_pegawai);
        $stmt->execute();

        // Jika kata_sandi baru diberikan, maka update kata_sandi
        if (!empty($kata_sandi)) {
            $update_password_stmt = $koneksi->prepare("UPDATE tbl_pegawai_222247 SET 222247_kata_sandi = ? WHERE 222247_id_pegawai = ?");
            $update_password_stmt->bind_param("si", $kata_sandi, $id_pegawai);
            $update_password_stmt->execute();
            $update_password_stmt->close();
        }

        // Jika username, role, atau kata_sandi diubah, logout secara otomatis
        if ($username != $old_username || $role != $data_pegawai['222247_role']) {
            session_destroy(); // Mengeluarkan pengguna dari sesi
            session_start(); // Memulai sesi baru
            $_SESSION['message'] = "Username, role, atau kata sandi telah diubah. Silakan login kembali.";
            header('Location: ../login.php'); // Mengarahkan ke halaman login
            exit();
        }

        $_SESSION['message'] = "Pegawai berhasil diperbarui.";
        header('Location: ../data_pegawai.php');
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
        header('Location: ../edit_pegawai.php?222247_id_pegawai=' . $id_pegawai);
        exit();
    }
}
?>
