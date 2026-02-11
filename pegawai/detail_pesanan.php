<?php
session_start();
require_once '../koneksi.php';

// Cek apakah ada ID pesanan di parameter URL
if (!isset($_GET['222247_id_pesanan']) || empty($_GET['222247_id_pesanan'])) {
    $_SESSION['error'] = "ID pesanan tidak valid!";
    header('Location: data_pesanan.php');
    exit();
}

$id_pesanan = mysqli_real_escape_string($koneksi, $_GET['222247_id_pesanan']);

try {
    // Mengambil data pesanan berdasarkan ID pesanan
    $stmt = $koneksi->prepare("SELECT p.222247_id_pesanan, u.222247_nama_lengkap, pr.222247_nama_produk, 
                                p.222247_tanggal_pesanan, p.222247_status_pesanan, p.222247_total_harga, p.222247_jumlah
                                FROM tbl_pesanan_222247 p
                                JOIN tbl_pengguna_222247 u ON p.222247_id_pengguna = u.222247_id_pengguna
                                JOIN tbl_produk_222247 pr ON p.222247_id_produk = pr.222247_id_produk
                                WHERE p.222247_id_pesanan = ?");
    $stmt->bind_param("i", $id_pesanan);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah pesanan ditemukan
    if ($result->num_rows == 0) {
        $_SESSION['error'] = "Pesanan tidak ditemukan!";
        header('Location: data_pesanan.php');
        exit();
    }

    $data_pesanan = $result->fetch_assoc();

    // Ambil deskripsi produk
    $short_description = '';
    if (isset($data_pesanan['222247_nama_produk'])) {
        $stmt_desc = $koneksi->prepare("SELECT 222247_deskripsi FROM tbl_produk_222247 WHERE 222247_nama_produk = ?");
        $stmt_desc->bind_param("s", $data_pesanan['222247_nama_produk']);
        $stmt_desc->execute();
        $result_desc = $stmt_desc->get_result();
        if ($desc_row = $result_desc->fetch_assoc()) {
            $short_description = $desc_row['222247_deskripsi'];
        }
        $stmt_desc->close();
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header('Location: data_pesanan.php');
    exit();
}

require_once 'include/header.php';
require_once 'include/navbar.php';
require_once 'include/sidebar.php';
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail Pesanan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Detail Pesanan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Display Notification Message -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Pesan</h5>
                    <?php echo $_SESSION['error']; ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php elseif (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Berhasil</h5>
                    <?php echo $_SESSION['success']; ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            <!-- End Notification Message -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Pesanan</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12 mb-4">
                                            <small class="text-muted d-block">ID Pesanan</small>
                                            <div class="h5 mb-0"><strong><?php echo htmlspecialchars($data_pesanan['222247_id_pesanan']); ?></strong></div>
                                        </div>

                                        <div class="col-12 col-md-6 mb-4">
                                            <small class="text-muted d-block">Nama Pengguna</small>
                                            <div><?php echo htmlspecialchars($data_pesanan['222247_nama_lengkap']); ?></div>
                                        </div>

                                        <div class="col-12 col-md-6 mb-4">
                                            <small class="text-muted d-block">Nama Produk</small>
                                            <div><?php echo htmlspecialchars($data_pesanan['222247_nama_produk']); ?></div>
                                        </div>

                                        <div class="col-12 col-md-6 mb-4">
                                            <small class="text-muted d-block">Tanggal Pesanan</small>
                                            <div><?php echo htmlspecialchars($data_pesanan['222247_tanggal_pesanan']); ?></div>
                                        </div>

                                        <div class="col-12 col-md-6 mb-4">
                                            <small class="text-muted d-block">Status Pesanan</small>
                                            <div>
                                                <span id="statusPesanan" class="badge badge-<?php echo strtolower($data_pesanan['222247_status_pesanan']) === 'selesai' ? 'success' : (strtolower($data_pesanan['222247_status_pesanan']) === 'dikirim' ? 'info' : (strtolower($data_pesanan['222247_status_pesanan']) === 'dibatalkan' ? 'danger' : (strtolower($data_pesanan['222247_status_pesanan']) === 'pembayaran diproses' ? 'primary' : 'warning'))); ?>" style="cursor: pointer;">
                                                    <?php echo htmlspecialchars($data_pesanan['222247_status_pesanan']); ?>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6 mb-4">
                                            <small class="text-muted d-block">Total Harga</small>
                                            <div><?php echo htmlspecialchars(number_format($data_pesanan['222247_total_harga'], 2, ',', '.')); ?> IDR</div>
                                        </div>

                                        <div class="col-12 col-md-6 mb-4">
                                            <small class="text-muted d-block">Jumlah</small>
                                            <div><?php echo htmlspecialchars($data_pesanan['222247_jumlah']); ?></div>
                                        </div>

                                        <div class="col-12 mb-4">
                                            <small class="text-muted d-block">Deskripsi Produk</small>
                                            <div><?php echo htmlspecialchars($short_description); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="data_pesanan.php" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var statusPesanan = document.getElementById('statusPesanan');
        var statuses = ['Pesanan Diproses', 'Pembayaran Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'];

        statusPesanan.addEventListener('click', function() {
            var currentStatus = this.innerText.trim();
            var nextStatusIndex = (statuses.indexOf(currentStatus) + 1) % statuses.length;
            var nextStatus = statuses[nextStatusIndex];

            if (confirm("Ubah status pesanan menjadi: " + nextStatus + "?")) {
                window.location.href = 'ubah_status.php?id_pesanan=<?php echo $id_pesanan; ?>&status_pesanan=' + encodeURIComponent(nextStatus);
            }
        });

    });
</script>