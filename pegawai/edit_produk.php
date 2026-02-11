<?php
ob_start();
session_start();
require_once '../koneksi.php';
$title = "Edit Produk | Fitnes Suplement";

// Cek apakah sesi login ada
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

// Ambil ID produk yang akan diedit
if (isset($_GET['id'])) {
    $id_produk = $_GET['id'];

    // Ambil data produk berdasarkan ID
    $query = "SELECT * FROM tbl_produk_222247 WHERE 222247_id_produk = '$id_produk'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        $produk = mysqli_fetch_assoc($result);
    } else {
        $_SESSION['error'] = "Produk tidak ditemukan!";
        header('Location: data_produk.php');
        exit();
    }
} else {
    $_SESSION['error'] = "ID Produk tidak valid!";
    header('Location: data_produk.php');
    exit();
}

require_once 'include/header.php';
require_once 'include/navbar.php';
require_once 'include/sidebar.php';
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Produk</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Edit Produk</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i>Pesan</h5>
                    <?php echo $_SESSION['error']; ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Form Edit Produk</h3>
                        </div>
                        <div class="card-body">
                            <form action="proses_produk/p_edit_produk.php" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="222247_id_produk" value="<?php echo $produk['222247_id_produk']; ?>">

                                <div class="form-group">
                                    <label for="nama_produk">Nama Produk</label>
                                    <input type="text" id="nama_produk" name="222247_nama_produk" class="form-control" value="<?php echo $produk['222247_nama_produk']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="kategori">Kategori</label>
                                    <select id="kategori" name="222247_kategori" class="form-control" required>
                                        <option value="Suplemen" <?php echo ($produk['222247_kategori'] == 'Suplemen') ? 'selected' : ''; ?>>Suplemen</option>
                                        <option value="Alat Fitness" <?php echo ($produk['222247_kategori'] == 'Alat Fitness') ? 'selected' : ''; ?>>Alat Fitness</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="harga">Harga</label>
                                    <input type="number" id="harga" name="222247_harga" class="form-control" value="<?php echo $produk['222247_harga']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="stok">Stok</label>
                                    <input type="number" id="stok" name="222247_stok" class="form-control" value="<?php echo $produk['222247_stok']; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi</label>
                                    <textarea id="deskripsi" name="222247_deskripsi" class="form-control" required><?php echo $produk['222247_deskripsi']; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Foto Produk</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="exampleInputFile" name="foto">
                                            <label class="custom-file-label" for="exampleInputFile">Pilih File</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Upload</span>
                                        </div>
                                    </div>
                                    <small>Foto saat ini: <?php echo $produk['222247_foto']; ?></small>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="data_produk.php" class="btn btn-warning">Kembali</a>
                            </form>
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
<script>
    $(function() {
        bsCustomFileInput.init();
    });
</script>
