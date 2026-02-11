<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

// Koneksi ke database
include 'koneksi.php';

// Menentukan jumlah data per halaman
$limit = 3;

// Mengambil nomor halaman dari URL, jika tidak ada maka halaman 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query untuk menghitung total pembayaran
$query_total = "SELECT COUNT(*) AS total FROM tbl_pembayaran_222247 AS p
                JOIN tbl_pesanan_222247 AS pes ON p.222247_id_pesanan = pes.222247_id_pesanan
                WHERE pes.222247_id_pengguna = ?";
$stmt_total = $koneksi->prepare($query_total);
$stmt_total->bind_param("i", $_SESSION['user_id']);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_data = $result_total->fetch_assoc()['total'];

// Query untuk mengambil data pembayaran
$query_pembayaran = "SELECT p.222247_id_pembayaran, p.222247_tanggal_pembayaran, 
                        p.222247_status_pembayaran, p.222247_bukti_pembayaran,
                        p.222247_metode, pr.222247_nama_produk, pr.222247_foto, pes.222247_jumlah, pes.222247_total_harga,
                        i.222247_alamat AS info_alamat, i.222247_no_wa
                    FROM tbl_pembayaran_222247 AS p
                    JOIN tbl_pesanan_222247 AS pes ON p.222247_id_pesanan = pes.222247_id_pesanan
                    JOIN tbl_produk_222247 AS pr ON pes.222247_id_produk = pr.222247_id_produk
                    LEFT JOIN tbl_info_222247 AS i ON i.222247_id_pembayaran = p.222247_id_pembayaran
                    WHERE pes.222247_id_pengguna = ?
                    LIMIT ?, ?";
$stmt = $koneksi->prepare($query_pembayaran);
$stmt->bind_param("iii", $_SESSION['user_id'], $offset, $limit);
$stmt->execute();
$result_pembayaran = $stmt->get_result();

$total_pembayaran = $result_pembayaran->num_rows;

$title = "Riwayat Pembayaran";
include 'include/header.php';
include 'include/navbar.php';
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Riwayat Pembayaran</h1>
                </div>
                <div class="col-sm-6">
                    <a href="pembayaran.php" class="btn btn-primary btn-sm mt-2 float-right">
                        <i class="fas"></i> <b>Kembali</b>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <?php if ($total_pembayaran > 0): ?>
                    <?php while ($row = $result_pembayaran->fetch_assoc()): ?>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="card mb-4 shadow-sm hover-effect h-100">
                                <div style="height: 250px; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
                                    <img src="pegawai/produk/<?php echo htmlspecialchars($row['222247_foto']); ?>" class="card-img-top" alt="Foto Produk" style="width: 100%; height: 100%; object-fit: contain;">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title text-primary"><b><?php echo htmlspecialchars($row['222247_nama_produk']); ?></b></h5>
                                    <hr>
                                    <p class="card-text">
                                        <small class="text-muted"><i class="fas fa-calendar-alt"></i> <?php echo date("d-m-Y H:i", strtotime($row['222247_tanggal_pembayaran'])); ?></small><br>
                                        
                                        <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
                                            <?php
                                                $status = isset($row['222247_status_pembayaran']) ? trim($row['222247_status_pembayaran']) : '';
                                                if ($status === '') { $status = 'Dibatalkan'; }
                                                $statusLower = strtolower($status);
                                                $badgeClass = ($statusLower === 'lunas') ? 'badge-success bg-success' : (($statusLower === 'gagal' || $statusLower === 'dibatalkan') ? 'badge-danger bg-danger' : 'badge-warning bg-warning');
                                            ?>
                                            <span class="badge <?php echo $badgeClass; ?> p-2">
                                                <?php echo htmlspecialchars($status); ?>
                                            </span>
                                             <span class="font-weight-bold text-success">Rp <?php echo number_format($row['222247_total_harga'], 0, ',', '.'); ?></span>
                                        </div>

                                        <ul class="list-group list-group-flush mb-3">
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                Jumlah
                                                <span class="badge bg-secondary rounded-pill"><?php echo htmlspecialchars($row['222247_jumlah']); ?></span>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <i class="fab fa-whatsapp text-success"></i> <?php echo htmlspecialchars($row['222247_no_wa']); ?><br>
                                                <i class="fas fa-map-marker-alt text-danger"></i> <?php echo htmlspecialchars($row['info_alamat']); ?>
                                            </li>
                                        </ul>

                                        <div class="mt-3">
                                            <strong>Metode Pembayaran:</strong> 
                                            <span class="badge bg-info text-dark"><?php echo strtoupper($row['222247_metode']); ?></span>
                                            
                                            <?php if ($row['222247_metode'] !== 'cod'): ?>
                                                <div class="mt-2">
                                                    <strong>Bukti Transfer:</strong><br>
                                                    <a href="pegawai/bukti_pembayaran/<?php echo htmlspecialchars($row['222247_bukti_pembayaran']); ?>" target="_blank">
                                                        <img src="pegawai/bukti_pembayaran/<?php echo htmlspecialchars($row['222247_bukti_pembayaran']); ?>" 
                                                            class="img-thumbnail mt-1" alt="Bukti Pembayaran" style="max-height: 100px;">
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <div class="alert alert-secondary mt-2 py-2 px-3 text-sm">
                                                    <i class="fas fa-hand-holding-usd"></i> Bayar di tempat (COD)
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            Tidak ada riwayat pembayaran yang tersedia.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$stmt->close();
$stmt_total->close();
$koneksi->close();
include 'include/footer.php';
?>

<!-- Efek Hover -->
<style>
    .hover-effect {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-effect:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }

    .card-title {
        transition: color 0.3s ease;
    }

    .hover-effect:hover .card-title {
        color: #007bff;
    }
</style>
