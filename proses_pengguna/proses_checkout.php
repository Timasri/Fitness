<?php
ob_start();
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

// Koneksi ke database
include 'koneksi.php';

// Ambil data dari form
$pesanan_ids = $_POST['pesanan_ids']; // String yang diterima
$payment_method = $_POST['payment_method'];
$payment_details = '';
$no_wa = $_POST['no_wa'];  // Nomor WhatsApp
$alamat = $_POST['alamat']; // Alamat pengiriman
$titik_lokasi = $_POST['titik_lokasi'];  // Nomor WhatsApp
$kode_pos = $_POST['kode_pos'];
$pesanan_ids_array = explode(',', $pesanan_ids);
$nama_penerima = $_POST['nama_penerima'];

error_log("Nama Penerima: " . $nama_penerima);
error_log("Kode Pos: " . $kode_pos);


// Proses untuk metode pembayaran transfer
if (
    $payment_method === 'sea_bank_instant' || $payment_method === 'sea_bank' || $payment_method === 'bca' ||
    $payment_method === 'bri' || $payment_method === 'mandiri' || $payment_method === 'bni' ||
    $payment_method === 'danamon' || $payment_method === 'bsi' || $payment_method === 'permata'
) {
    $transfer_receipt = $_FILES['transfer_receipt'];
    // Simpan bukti transfer jika ada file
    if ($transfer_receipt['error'] == 0) {
        $upload_dir = 'pegawai/bukti_pembayaran/';

        // Membuat nama file unik dengan md5
        $filename = md5(uniqid(rand(), true)) . '.' . pathinfo($transfer_receipt['name'], PATHINFO_EXTENSION);
        $transfer_receipt_path = $upload_dir . $filename;

        // Pindahkan file ke direktori tujuan
        move_uploaded_file($transfer_receipt['tmp_name'], $transfer_receipt_path);
    }
    // Simpan hanya nama file dalam database
    $payment_details = $filename;
}

// Dapatkan tanggal pembayaran saat ini
$tanggal_pembayaran = date('Y-m-d H:i:s');

// Set status pembayaran (misalnya, status "Diproses")
$status_pembayaran = 'Pembayaran Diproses';

// Query untuk memasukkan data pembayaran ke tabel
$query = "
    INSERT INTO tbl_pembayaran_222247 
    (222247_id_pesanan, 222247_status_pembayaran, 222247_tanggal_pembayaran, 
     222247_metode, 222247_bukti_pembayaran)
    VALUES (?, ?, ?, ?, ?)
";
$stmt = $koneksi->prepare($query);

// Query untuk memasukkan data ke tbl_info_222247 (informasi pengguna)
$query_info = "
    INSERT INTO tbl_info_222247 (222247_no_wa, 222247_alamat, 222247_titik_lokasi, 222247_kode_pos, 222247_id_pembayaran ,222247_nama_penerima)
    VALUES (?, ?, ?, ?, ?, ?)
";
$stmt_info = $koneksi->prepare($query_info);

// Proses setiap pesanan ID
foreach ($pesanan_ids_array as $pesanan_id) {
    // Proses untuk memasukkan data pembayaran
    $stmt->bind_param(
        "issss",
        $pesanan_id,
        $status_pembayaran,
        $tanggal_pembayaran,
        $payment_method,
        $payment_details,
    );

    if (!$stmt->execute()) {
        // Jika ada error, simpan pesan error ke log atau tampilkan
        error_log("Gagal memproses pembayaran untuk pesanan ID: $pesanan_id");
    }

    // Ambil ID pembayaran untuk setiap pesanan
    $last_payment_id = $koneksi->insert_id; // ID pembayaran terakhir yang dimasukkan

    // Menyimpan data informasi pengguna ke tbl_info_222247 untuk setiap pesanan
    $stmt_info->bind_param("ssssis", $no_wa, $alamat, $titik_lokasi, $kode_pos, $last_payment_id, $nama_penerima);

    if (!$stmt_info->execute()) {
        // Jika gagal, simpan pesan error ke log atau tampilkan
        error_log("Gagal memasukkan data ke tbl_info_222247 untuk pesanan ID: $pesanan_id.");
    }
}

// Tutup statement dan koneksi
$stmt->close();
$stmt_info->close();
$koneksi->close();

// Redirect ke halaman riwayat pembayaran dengan pesan sukses
echo ini_get('error_log');
// header('Location: riwayat_pembayaran.php?status=success&message=Pembayaran%20berhasil%20diproses!');
exit();
?>
