<?php
ob_start();
session_start();
require_once '../koneksi.php';
require_once 'include/header.php';
require_once 'include/navbar.php';
require_once 'include/sidebar.php';

// Cek apakah sesi login ada
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: ../login.php');
    exit();
}

// Ambil data dari formulir jika ada
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

?>

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Generate Laporan Pembayaran Fitnes Mart</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Fitnes Mart</a></li>
                        <li class="breadcrumb-item active">Generate Laporan Pembayaran</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <!-- Form Pilihan Tanggal -->
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title">Pilih Rentang Tanggal</h3>
                        </div>
                        <div class="card-body">
                            <form method="post" action="">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="start_date">Tanggal Awal</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="end_date">Tanggal Akhir</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success btn-block">Tampilkan Data</button>
                            </form>
                        </div>
                    </div>

                    <?php if ($start_date && $end_date): ?>
                        <?php
                        $query = "
                            SELECT 
                                p.222247_id_pembayaran, 
                                p.222247_status_pembayaran, 
                                p.222247_tanggal_pembayaran, 
                                o.222247_total_harga, 
                                o.222247_tanggal_pesanan,
                                o.222247_jumlah,
                                pr.222247_nama_produk, 
                                pr.222247_harga,
                                u.222247_nama_lengkap
                            FROM tbl_pembayaran_222247 p
                            JOIN tbl_pesanan_222247 o ON p.222247_id_pesanan = o.222247_id_pesanan
                            JOIN tbl_produk_222247 pr ON o.222247_id_produk = pr.222247_id_produk
                            JOIN tbl_pengguna_222247 u ON o.222247_id_pengguna = u.222247_id_pengguna
                            WHERE p.222247_tanggal_pembayaran BETWEEN ? AND ?
                        ";
                        $stmt = $koneksi->prepare($query);
                        $stmt->bind_param('ss', $start_date, $end_date);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        $total_pembayaran = 0;
                        while ($row = $result->fetch_assoc()) {
                            $total_pembayaran += $row['222247_total_harga'];
                        }
                        $result->data_seek(0);
                        ?>

                        <div class="card card-secondary mt-4">
                            <div class="card-header">
                                <h3 class="card-title">Data Pembayaran dari <?php echo htmlspecialchars($start_date); ?> hingga <?php echo htmlspecialchars($end_date); ?></h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Nama Lengkap</th>
                                                <th>Nama Produk</th>
                                                <th>Tanggal Pesanan</th>
                                                <th>Tanggal Pembayaran</th>
                                                <th>Harga</th>
                                                <th>Jumlah</th>
                                                <th>Total</th>
                                                <th>Status Pembayaran</th>                                                                                                                                                                                                                                                                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['222247_nama_lengkap']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['222247_nama_produk']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['222247_tanggal_pesanan']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['222247_tanggal_pembayaran']); ?></td>
                                                    <td><?php echo number_format($row['222247_harga'], 2, ',', '.'); ?></td>  <!-- Menampilkan harga produk -->
                                                    <td><?php echo htmlspecialchars($row['222247_jumlah']); ?></td>
                                                    <td><?php echo number_format($row['222247_total_harga'], 2, ',', '.'); ?></td>
                                                    <td><?php echo htmlspecialchars($row['222247_status_pembayaran']); ?></td>                                                                                                                                                                                                                                                          
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="alert alert-info">
                                    <h5> TOTAL KESELURUHAN = <?php echo number_format($total_pembayaran, 2, ',', '.'); ?></h5>
                                </div>

                                <form method="post" action="export_pdf.php" class="mt-3">
                                    <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                                    <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                                    <button type="submit" class="btn btn-success">Export to PDF</button>
                                </form>
                            </div>
                        </div>

                        <?php
                        $stmt->close();
                        $koneksi->close();
                        ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once 'include/footer.php'; ?>
