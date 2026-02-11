<?php
ob_start();
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

// Ambil data pengguna dari sesi
$username = $_SESSION['username'];
$nama_lengkap = $_SESSION['nama_lengkap'];
$user_id = $_SESSION['user_id'];
$session_token = $_SESSION['session_token'];

// Ambil data yang relevan dari database
include 'koneksi.php';

// Ambil total produk
$query_produk = "SELECT COUNT(*) AS total_produk FROM tbl_produk_222247";
$result_produk = mysqli_query($koneksi, $query_produk);
$data_produk = mysqli_fetch_assoc($result_produk);
$total_produk = $data_produk['total_produk'];

// Ambil transaksi terakhir pengguna
$query_transaksi = "
    SELECT COUNT(*) AS total_transaksi 
    FROM tbl_pembayaran_222247 pb 
    JOIN tbl_pesanan_222247 ps ON pb.222247_id_pesanan = ps.222247_id_pesanan 
    WHERE ps.222247_id_pengguna = '$user_id'
";
$result_transaksi = mysqli_query($koneksi, $query_transaksi);
$data_transaksi = mysqli_fetch_assoc($result_transaksi);
$total_transaksi = $data_transaksi['total_transaksi'];

// Set title halaman
$title = "Menu Sistem Informasi Alat Fitnes dan Suplement";
include 'include/header.php';
include 'include/navbar.php';
?>
<div class="container">
    <!-- Selamat datang -->
    <div class="row mt-3">
        <div class="col-12 text-center">
            <h4><marquee>🏋‍♂Selamat datang <?php echo htmlspecialchars($nama_lengkap); ?> have a nice day :) ✨ <?php echo htmlspecialchars($username); ?> ✨ </marquee></h4>
        </div>
    </div>

    <!-- Menu utama -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"> ✨💪 Selamat belanja di fitnes mart makassar capai dan raih Goals mu ✨💪</h1>
                    </div>
                    
                </div>
            </div>
        </div>

        <!-- Kartu menu -->
        <div class="content">
            <div class="container">
                <div class="row text-center">
                    <!-- Kartu Produk -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <div class="small-box bg-info equal-height">
                            <div class="inner">
                                <h3><?php echo $total_produk; ?></h3>
                                <p>Produk Fitnes Mart</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <a href="produk.php" class="small-box-footer">Lihat Produk <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <!-- Kartu Riwayat Transaksi -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <div class="small-box bg-success equal-height">
                            <div class="inner">
                                <h3><?php echo $total_transaksi; ?></h3>
                                <p>Transaksi Terakhir</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-history"></i>
                            </div>
                            <a href="riwayat_pembayaran.php" class="small-box-footer">Lihat Riwayat <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <!-- Kartu Pembayaran -->
                    <div class="col-lg-4 col-md-6 col-12 mb-3">
                        <div class="small-box bg-warning equal-height">
                            <div class="inner">
                                <h3>Pesan</h3>
                                <p>Fitur Pemesanan</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-money-check-alt"></i>
                            </div>
                            <a href="pembayaran.php" class="small-box-footer">Lakukan Checkout <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

                
                <!-- Lokasi -->
                <div class="row mt-3">
                    <div class="col-12">
                        <h2>Lokasi Fitnes Mart Makassar</h2>
                        <p>Kunjungi dan liat langsung toko Fitnes Mart di lokasi berikut:</p>
                    </div>
                    <div class="col-12 text-center">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3973.7720559062186!2d119.48050597379233!3d-5.140361994836845!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbee33495a4f597%3A0xa374c458c000bb06!2sDipa%20Makassar%20University!5e0!3m2!1sen!2sid!4v1733624305187!5m2!1sen!2sid"
                            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy">
                        </iframe>
                        <div class="mt-3">
                            <a href="https://maps.app.goo.gl/PgqT8y8pmNPcr9BL6" target="_blank" class="btn btn-primary">
                                Lihat di Google Maps
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan footer -->
<?php include 'include/footer.php'; ?>
