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

// Koneksi ke database
include 'koneksi.php';

// Ambil ID Produk dari URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_produk = intval($_GET['id']);
} else {
    die("ID Produk tidak ditemukan.");
}

// Ambil data produk
$query_produk = "SELECT 222247_id_produk, 222247_nama_produk, 222247_foto FROM tbl_produk_222247 WHERE 222247_id_produk = ?";
$stmt = $koneksi->prepare($query_produk);
$stmt->bind_param('i', $id_produk);
$stmt->execute();
$result_produk = $stmt->get_result();
$produk = $result_produk->fetch_assoc();

// Debug: Pastikan produk ditemukan
if (!$produk) {
    die("Produk tidak ditemukan.");
}

// Proses penyimpanan ulasan jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = intval($_POST['rating']);
    $komentar = htmlspecialchars(trim($_POST['komentar']));

    if ($rating < 1 || $rating > 5) {
        $error = "Rating harus antara 1 dan 5.";
    } else {
        $query_insert_ulasan = "INSERT INTO tbl_ulasan_222247 (222247_produk_id, 222247_pengguna_id, 222247_ulasan_text, 222247_rating) 
                                VALUES (?, ?, ?, ?)";
        $stmt = $koneksi->prepare($query_insert_ulasan);
        $stmt->bind_param('iisi', $id_produk, $_SESSION['user_id'], $komentar, $rating);

        if ($stmt->execute()) {
            $success = "Ulasan berhasil disimpan.";
        } else {
            $error = "Gagal menyimpan ulasan: " . $stmt->error;
        }
    }
}

// Ambil ulasan terkait produk ini
$query_ulasan = "SELECT u.222247_rating, u.222247_ulasan_text, u.222247_tanggal_ulasan, p.222247_nama_lengkap 
                 FROM tbl_ulasan_222247 AS u
                 JOIN tbl_pengguna_222247 AS p ON u.222247_pengguna_id = p.222247_id_pengguna
                 WHERE u.222247_produk_id = ?";
$stmt = $koneksi->prepare($query_ulasan);
$stmt->bind_param('i', $id_produk);
$stmt->execute();
$result_ulasan = $stmt->get_result();

// Set title halaman
$title = "Ulasan Produk";
include 'include/header.php';
include 'include/navbar.php';
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Ulasan Produk</h1>
                </div>
                <div class="col-sm-6">
                    <a href="produk.php" class="btn btn-primary btn-sm mt-2 float-right">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <!-- Card Produk dengan Gambar dan Info Produk -->
                <div class="col-lg-8 offset-lg-2">
                    <div class="card shadow-lg">
                        <div class="card-header bg-info text-white text-center">
                            <h5>Ulasan untuk Produk: <?php echo htmlspecialchars($produk['222247_nama_produk']); ?></h5>
                        </div>
                        <div class="card-body text-center">
                            <!-- Tampilkan Gambar Produk -->
                            <img src="pegawai/produk/<?php echo htmlspecialchars($produk['222247_foto']); ?>" class="img-fluid" alt="Gambar Produk" style="max-height: 300px; object-fit: cover;">

                            <!-- Form Tambah Ulasan -->
                            <hr>
                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php elseif (isset($success)): ?>
                                <div class="alert alert-success"><?php echo $success; ?></div>
                            <?php endif; ?>

                            <form method="POST" action="">
                                <div class="form-group">
                                    <label for="rating">Rating (1-5)</label>
                                    <select class="form-control" id="rating" name="rating" required>
                                        <option value="">Pilih Rating</option>
                                        <option value="1">1 - Sangat Buruk</option>
                                        <option value="2">2 - Buruk</option>
                                        <option value="3">3 - Cukup</option>
                                        <option value="4">4 - Bagus</option>
                                        <option value="5">5 - Sangat Bagus</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="komentar">Komentar</label>
                                    <textarea class="form-control" id="komentar" name="komentar" rows="4" placeholder="Tulis ulasan Anda" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-success w-100">Simpan Ulasan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Tampilkan Ulasan yang Ada -->
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <h5 class="mb-4 text-center">Ulasan Lainnya:</h5>
                    <?php if ($result_ulasan->num_rows > 0): ?>
                        <?php while ($ulasan = $result_ulasan->fetch_assoc()): ?>
                            <div class="card mb-3 shadow-sm">
                                <div class="card-body">
                                    <h6>Rating: <?php echo str_repeat('⭐', $ulasan['222247_rating']); ?> - <?php echo htmlspecialchars($ulasan['222247_nama_lengkap']); ?></h6>
                                    <p class="mb-1"><?php echo htmlspecialchars($ulasan['222247_ulasan_text']); ?></p>
                                    <small class="text-muted">Diberikan pada: <?php echo date("d-m-Y H:i:s", strtotime($ulasan['222247_tanggal_ulasan'])); ?></small>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="alert alert-warning text-center">Belum ada ulasan untuk produk ini.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>
