<?php
ob_start();
session_start();
require_once '../koneksi.php';
$title = "Data Ulasan | Fitnes Suplement";

// Cek apakah sesi login ada
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

require_once 'include/header.php';
require_once 'include/navbar.php';
require_once 'include/sidebar.php';

try {
    // Mengambil data dari tabel ulasan dengan join untuk mendapatkan nama pengguna dan produk
    $stmt = $koneksi->prepare("SELECT u.222247_ulasan_id, u.222247_ulasan_text, u.222247_rating, p.222247_nama_lengkap, pr.222247_nama_produk
                               FROM tbl_ulasan_222247 AS u
                               JOIN tbl_pengguna_222247 AS p ON u.222247_pengguna_id = p.222247_id_pengguna
                               JOIN tbl_produk_222247 AS pr ON u.222247_produk_id = pr.222247_id_produk");
    $stmt->execute();
    $result = $stmt->get_result();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Proses hapus ulasan
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $id_ulasan = intval($_GET['delete']);

    // Query untuk menghapus ulasan
    $delete_stmt = $koneksi->prepare("DELETE FROM tbl_ulasan_222247 WHERE 222247_ulasan_id = ?");
    $delete_stmt->bind_param('i', $id_ulasan);

    if ($delete_stmt->execute()) {
        $_SESSION['message'] = "Ulasan berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Gagal menghapus ulasan.";
    }

    // Redirect ke halaman yang sama setelah proses delete
    header('Location: data_ulasan.php');
    exit();
}

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Ulasan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Ulasan</li>
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
                    <h5><i class="icon fas fa-check"></i>Pesan</h5>
                    <?php echo $_SESSION['message']; ?>
                    <?php unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i>Pesan</h5>
                    <?php echo $_SESSION['error']; ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            <!-- End Notification Message -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Ulasan</h3>
                        </div>
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pengguna</th>
                                        <th>Nama Produk</th>
                                        <th>Rating</th>
                                        <th>Ulasan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($data_ulasan = $result->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo htmlspecialchars($data_ulasan['222247_nama_lengkap']); ?></td>
                                            <td><?php echo htmlspecialchars($data_ulasan['222247_nama_produk']); ?></td>
                                            <td><?php echo str_repeat('⭐', $data_ulasan['222247_rating']); ?></td>
                                            <td><?php echo htmlspecialchars($data_ulasan['222247_ulasan_text']); ?></td>
                                            <td>
                                                <a href="data_ulasan.php?delete=<?php echo $data_ulasan['222247_ulasan_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus ulasan ini?')">Hapus</a>
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
</div>
<!-- /.content-wrapper -->
<?php require_once 'include/footer.php'; ?>
