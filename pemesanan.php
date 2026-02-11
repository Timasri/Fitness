<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

// Ambil data pengguna dari sesi
$user_id = $_SESSION['user_id'];
$nama_lengkap = $_SESSION['nama_lengkap'];

// Koneksi ke database
include 'koneksi.php';

// Cek apakah ID produk ada di URL
if (!isset($_GET['id'])) {
    echo "Produk tidak ditemukan.";
    exit();
}

$id_produk = intval($_GET['id']);

// Ambil data produk berdasarkan ID
$query_produk = "SELECT * FROM tbl_produk_222247 WHERE 222247_id_produk = ?";
$stmt = mysqli_prepare($koneksi, $query_produk);
mysqli_stmt_bind_param($stmt, 'i', $id_produk);
mysqli_stmt_execute($stmt);
$result_produk = mysqli_stmt_get_result($stmt);

if ($result_produk && mysqli_num_rows($result_produk) > 0) {
    $produk = mysqli_fetch_assoc($result_produk);
} else {
    echo "Produk tidak ditemukan.";
    exit();
}
?>

<?php include 'include/header.php'; ?>
<?php include 'include/navbar.php'; ?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail & Produk Fitness Mart</h1>
                </div>
                <div class="col-sm-6">
                    <a href="produk.php" class="btn btn-primary btn-sm mt-2 float-right">
                        <i class="fas fa-arrow-left"></i> <b>Kembali</b>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mb-4">
                    <div class="card hover-effect">
                        <div class="row no-gutters">
                            <div class="col-md-6">
                                <img src="pegawai/produk/<?php echo htmlspecialchars($produk['222247_foto']); ?>"
                                     class="card-img"
                                     alt="<?php echo htmlspecialchars($produk['222247_nama_produk']); ?>"
                                     style="object-fit: cover; height: 100%; border-radius: .25rem 0 0 .25rem;">
                            </div>
                            <div class="col-md-6">
                                <div class="card-body d-flex flex-column">
                                    <h4 class="card-title mb-3"><?php echo htmlspecialchars($produk['222247_nama_produk']); ?></h4>

                                    <ul class="list-group list-group-flush mb-3">
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <strong>Kategori</strong>
                                            <span class="badge badge-primary badge-pill"><?php echo htmlspecialchars($produk['222247_kategori']); ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <strong>Stok Tersedia</strong>
                                            <span><?php echo htmlspecialchars($produk['222247_stok']); ?> unit</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <strong>Harga</strong>
                                            <span class="h5 text-success font-weight-bold mb-0">Rp<?php echo number_format($produk['222247_harga'], 0, ',', '.'); ?></span>
                                        </li>
                                    </ul>

                                    <div class="mt-auto">
                                        <strong>Deskripsi Produk:</strong>
                                        <div id="description-container">
                                            <p class="card-text mt-1"><?php echo nl2br(htmlspecialchars($produk['222247_deskripsi'])); ?></p>
                                        </div>
                                        <a href="#" id="toggle-description" class="font-weight-bold small">Baca Selengkapnya...</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">

                            <form action="proses_pengguna/p_pemesanan.php" method="POST" class="hover-form">
                                <input type="hidden" name="id_produk" value="<?php echo $id_produk; ?>">
                                <div class="form-group">
                                    <label for="jumlah">Jumlah Pesanan</label>
                                    <input type="number" id="jumlah" name="jumlah" class="form-control"
                                           value="1" min="1" max="<?php echo htmlspecialchars($produk['222247_stok']); ?>" required>
                                </div>
                                <button type="submit" class="btn btn-success btn-lg btn-block mt-3 hover-btn"><i class="fas fa-shopping-cart"></i> Pesan Sekarang</button>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-primary text-white text-center">
                           <h5 class="mb-0">PENGGUNA LAIN JUGA MEMBELI PRODUK INI:</h5>
                        </div>
                        <div class="card-body">
                            <?php
                            // Ambil 3 produk lain secara acak
                            $query_lain = "SELECT * FROM tbl_produk_222247 WHERE 222247_id_produk != ? ORDER BY RAND() LIMIT 3";
                            $stmt_lain = mysqli_prepare($koneksi, $query_lain);
                            mysqli_stmt_bind_param($stmt_lain, 'i', $id_produk);
                            mysqli_stmt_execute($stmt_lain);
                            $result_lain = mysqli_stmt_get_result($stmt_lain);

                            if ($result_lain && mysqli_num_rows($result_lain) > 0) {
                                while ($produk_lain = mysqli_fetch_assoc($result_lain)) {
                            ?>
                                <a href="pemesanan.php?id=<?php echo $produk_lain['222247_id_produk']; ?>" class="text-decoration-none">
                                    <div class="d-flex align-items-center mb-3 p-2 border rounded hover-effect-light">
                                        <img src="pegawai/produk/<?php echo htmlspecialchars($produk_lain['222247_foto']); ?>"
                                             alt="<?php echo htmlspecialchars($produk_lain['222247_nama_produk']); ?>"
                                             style="width: 75px; height: 75px; object-fit: cover;"
                                             class="mr-3 rounded">
                                        <div class="text-dark">
                                            <p class="mb-1 font-weight-bold"><?php echo htmlspecialchars($produk_lain['222247_nama_produk']); ?></p>
                                            <p class="small mb-1 text-muted"><?php echo htmlspecialchars($produk_lain['222247_kategori']); ?></p>
                                            <p class="mb-0 text-success font-weight-bold">Rp<?php echo number_format($produk_lain['222247_harga'], 0, ',', '.'); ?></p>
                                        </div>
                                    </div>
                                </a>
                            <?php
                                }
                            } else {
                                echo '<p class="text-center text-muted">Tidak ada rekomendasi lain.</p>';
                            }
                            if(isset($stmt_lain)) mysqli_stmt_close($stmt_lain);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>

<style>
    /* Efek hover pada kartu produk */
    .hover-effect:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    .hover-effect-light:hover {
        background-color: #f8f9fa;
        border-color: #007bff !important;
        transition: all 0.2s ease-in-out;
    }

    /* Efek hover pada tombol form */
    .hover-btn:hover {
        transform: scale(1.03);
        transition: transform 0.2s ease;
    }

    .h-100 {
        height: 100% !important;
    }

    /* STYLE UNTUK MEMBATASI DESKRIPSI */
    #description-container {
        max-height: 100px; /* Batas tinggi awal */
        overflow: hidden;
        position: relative;
        transition: max-height 0.5s ease-out;
    }

    #description-container.expanded {
        max-height: 1000px; /* Tinggi maksimal saat dibuka */
        transition: max-height 0.5s ease-in;
    }

    /* Efek gradasi di bawah teks yang terpotong */
    #description-container:not(.expanded)::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 50px;
        background: linear-gradient(to bottom, rgba(255, 255, 255, 0), rgba(255, 255, 255, 1));
    }

</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggle-description');
    const container = document.getElementById('description-container');

    if (toggleBtn && container) {
        // Cek jika konten tidak melebihi batas, sembunyikan tombol
        if (container.scrollHeight <= 100) {
            toggleBtn.style.display = 'none';
        }

        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();

            const isExpanded = container.classList.contains('expanded');

            if (isExpanded) {
                container.classList.remove('expanded');
                this.textContent = 'Baca Selengkapnya...';
            } else {
                container.classList.add('expanded');
                this.textContent = 'Tampilkan lebih sedikit';
            }
        });
    }
});
</script>