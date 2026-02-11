<?php
ob_start();
session_start();

// Cek apakah sesi login ada
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

include 'include/header.php';
$title = "Dashboard Pegawai | 222247 Fitness";
include 'include/navbar.php';
include 'include/sidebar.php';
include '../koneksi.php'; // Include koneksi database

// Mengambil jumlah data dari setiap tabel yang relevan
$totalProduk = $koneksi->query("SELECT COUNT(*) AS total FROM tbl_produk_222247")->fetch_assoc()['total'];
$totalPengguna = $koneksi->query("SELECT COUNT(*) AS total FROM tbl_pengguna_222247")->fetch_assoc()['total'];
$totalPegawai = $koneksi->query("SELECT COUNT(*) AS total FROM tbl_pegawai_222247")->fetch_assoc()['total'];
$totalPesanan = $koneksi->query("SELECT COUNT(*) AS total FROM tbl_pesanan_222247")->fetch_assoc()['total'];
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Admin Fines Mart Makassar</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Admin 😘</a></li>
                        <li class="breadcrumb-item active">Dashboard admin</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info">
                        <h4>Selamat datang, Admin <?php echo $namaLengkap; ?>❤!</h4>
                        <p>Selamat bekerja ❤ semangat dan jangan dengarkan kata orang lain ❤</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Card untuk Data Produk -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo $totalProduk; ?></h3>
                            <p>Data Produk</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dumbbell"></i>
                        </div>
                        <a href="data_produk.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- Card untuk Data Pengguna -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?php echo $totalPengguna; ?></h3>
                            <p>Data Pelanggan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="data_pengguna.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- Card untuk Data Pegawai -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo $totalPegawai; ?></h3>
                            <p>Data Admin</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <a href="data_pegawai.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- Card untuk Data Pesanan -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?php echo $totalPesanan; ?></h3>
                            <p>Data Pesanan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <a href="data_pesanan.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include 'include/footer.php'; ?>
