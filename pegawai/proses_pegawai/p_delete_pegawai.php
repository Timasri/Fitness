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

try {
    // Mengambil data pegawai berdasarkan id_pegawai
    $stmt = $koneksi->prepare("SELECT 222247_session_token FROM tbl_pegawai_222247 WHERE 222247_id_pegawai = ?");
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
    $session_token = $data_pegawai['222247_session_token'];

    // Menghapus pegawai
    $stmt = $koneksi->prepare("DELETE FROM tbl_pegawai_222247 WHERE 222247_id_pegawai = ?");
    $stmt->bind_param("i", $id_pegawai);
    $stmt->execute();

    // Jika session_token yang dihapus sama dengan session_token saat ini, logout
    if ($session_token === $_SESSION['session_token']) {
        // Menghancurkan sesi untuk logout otomatis
        session_destroy(); // Mengeluarkan pengguna dari sesi
        session_start(); // Memulai sesi baru
        $_SESSION['message'] = "Akun anda telah dihapus, silahkan konfirmasi ke admin.";
        
        // Mengarahkan kembali ke halaman login
        header('Location: ../login.php');
        exit();
    }

    // Jika tidak logout, arahkan kembali ke data pegawai
    $_SESSION['message'] = "Pegawai berhasil dihapus.";
    header('Location: ../data_pegawai.php');
    exit();
} catch (Exception $e) {
    $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
    header('Location: ../data_pegawai.php');
    exit();
}
?>
