<?php
ob_start();
session_start();
require_once '../koneksi.php';
$title = "Data Pembayaran | Fitnes Suplement";

// Cek apakah sesi login ada
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

require_once 'include/header.php';
require_once 'include/navbar.php';
require_once 'include/sidebar.php';

try {
    // Mengambil data dari tabel pembayaran dan pesanan, termasuk tanggal pesanan
    $stmt = $koneksi->prepare("SELECT p.222247_id_pembayaran, p.222247_status_pembayaran, p.222247_tanggal_pembayaran, o.222247_total_harga, o.222247_id_pesanan, o.222247_tanggal_pesanan 
                               FROM tbl_pembayaran_222247 p 
                               JOIN tbl_pesanan_222247 o ON p.222247_id_pesanan = o.222247_id_pesanan");
    $stmt->execute();
    $result = $stmt->get_result();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Pembayaran</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Pembayaran</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Display Notification Message -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Pesan</h5>
                    <?php echo $_SESSION['message']; ?>
                    <?php unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['delete'])): ?>
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Pesan</h5>
                    <?php echo $_SESSION['delete']; ?>
                    <?php unset($_SESSION['delete']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Pesan</h5>
                    <?php echo $_SESSION['error']; ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            <!-- End Notification Message -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Pembayaran</h3>
                        </div>
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Pesanan</th> <!-- Tambahkan kolom Tanggal Pesanan -->
                                        <th>Status Pembayaran</th>
                                        <th>Tanggal Pembayaran</th>
                                        <th>Total Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($data_pembayaran = $result->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo htmlspecialchars($data_pembayaran['222247_tanggal_pesanan']); ?></td> <!-- Menampilkan Tanggal Pesanan -->
                                            <?php
                                                // Pastikan status tidak null/empty, trim dan set fallback
                                                $status = isset($data_pembayaran['222247_status_pembayaran']) ? trim($data_pembayaran['222247_status_pembayaran']) : '';
                                                if ($status === '') {
                                                    $status = 'Dibatalkan';
                                                }
                                                $lower = strtolower($status);
                                                if ($lower === 'lunas') {
                                                    $badge = 'success';
                                                } elseif ($lower === 'gagal' || $lower === 'dibatalkan') {
                                                    $badge = 'danger';
                                                } else {
                                                    $badge = 'warning';
                                                }
                                            ?>
                                            <td><span class="badge badge-<?php echo $badge; ?>"><?php echo htmlspecialchars($status); ?></span></td>
                                            <td><?php echo htmlspecialchars($data_pembayaran['222247_tanggal_pembayaran']); ?></td>
                                            <td>Rp <?php echo number_format($data_pembayaran['222247_total_harga'], 0, ',', '.'); ?></td>
                                            <td>
                                                <a href="detail_pembayaran.php?222247_id_pembayaran=<?php echo $data_pembayaran['222247_id_pembayaran']; ?>" class="btn btn-info btn-sm">Detail</a>
                                                <a href="proses_pembayaran/p_delete_pembayaran.php?222247_id_pembayaran=<?php echo $data_pembayaran['222247_id_pembayaran']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus data ini?')">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php require_once 'include/footer.php'; ?>
