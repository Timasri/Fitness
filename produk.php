<?php
ob_start();
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    // Jika belum login, arahkan ke halaman login
    header('Location: login.php');
    exit();
}

// Ambil data pengguna dari sesi
$username = $_SESSION['username'];
$nama_lengkap = $_SESSION['nama_lengkap'];

// Koneksi ke database
include 'koneksi.php'; // pastikan file koneksi.php sudah ada

// Tentukan jumlah produk per halaman
$per_page = 3;

// Ambil nomor halaman saat ini dari URL, jika tidak ada set halaman ke 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Hitung offset untuk query SQL
$offset = ($page - 1) * $per_page;

// Ambil data produk dari database dengan paginasi
$query_produk = "SELECT * FROM tbl_produk_222247 LIMIT $per_page OFFSET $offset";
$result_produk = mysqli_query($koneksi, $query_produk);

if (!$result_produk) {
    die("Query failed: " . mysqli_error($koneksi)); // Menampilkan pesan kesalahan jika query gagal
}

$total_produk = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tbl_produk_222247")); // Menghitung total produk
$total_page = ceil($total_produk / $per_page); // Hitung total halaman

// Set title halaman
$title = "Daftar Produk";
include 'include/header.php';
include 'include/navbar.php';

function truncate_description($description, $word_limit = 50)
{
    $words = explode(' ', $description);
    if (count($words) > $word_limit) {
        $description = implode(' ', array_slice($words, 0, $word_limit)) . '...';
    }
    return $description;
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Daftar Produk</h1>
                </div>
                <div class="col-sm-6">
                    <a href="index.php" class="btn btn-primary btn-sm mt-2 float-right">
                        <b>Kembali</b> <i class="fas"> </i> 
                    </a>
                </div>

            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <!-- Kartu Total Produk -->
                <div class="col-lg-4 col-12">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo $total_produk; ?></h3>
                            <p>Total Produk Tersedia</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Daftar Produk dalam bentuk kartu -->
                <?php
                if ($total_produk > 0) {
                    while ($row = mysqli_fetch_assoc($result_produk)) {
                        // Ambil nilai rating dan buat simbol bintang
                        $rating = floatval($row['222247_rating']);
                        $stars = str_repeat('⭐', floor($rating)); // Bintang berdasarkan nilai rating
                        // Cek stok produk
                        $stok = $row['222247_stok'];
                        $disabled = ($stok == 0) ? 'disabled' : ''; // Menambahkan atribut disabled jika stok = 0
                        $btn_class = ($stok == 0) ? 'btn-disabled' : 'btn-primary';

                        // Potong deskripsi jika lebih dari 50 kata
                        $short_description = truncate_description($row['222247_deskripsi']);
                ?>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="card mb-4">
                                <img src="pegawai/produk/<?php echo htmlspecialchars($row['222247_foto']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['222247_nama_produk']); ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><b>Nama Produk: <?php echo htmlspecialchars($row['222247_nama_produk']); ?></b></h5>
                                    <p class="card-text">
                                        Jenis: <?php echo htmlspecialchars($row['222247_kategori']); ?><br>
                                        Harga: Rp<?php echo number_format($row['222247_harga'], 2, ',', '.'); ?><br>
                                        Stok: <?php echo htmlspecialchars($stok); ?><br>
                                        Deskripsi: <?php echo htmlspecialchars($short_description); ?><br>
                                        Rating: <?php echo $stars; ?>
                                    </p>
                                    <!-- Tombol Pesan dengan kelas dan JavaScript -->
                                    <a href="pemesanan.php?id=<?php echo $row['222247_id_produk']; ?>" class="btn <?php echo $btn_class; ?>" <?php echo ($stok == 0) ? 'onclick="return false;"' : ''; ?>>Pesan</a>
                                    <a href="ulasan.php?id=<?php echo $row['222247_id_produk']; ?>" class="btn btn-secondary">Lihat Ulasan</a>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                } else {
                    ?>
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            Tidak ada produk yang tersedia.
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>

            <!-- Paginasi -->
            <div class="row">
                <div class="col-12">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1) { ?>
                                <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a></li>
                            <?php } ?>
                            <?php for ($i = 1; $i <= $total_page; $i++) { ?>
                                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php } ?>
                            <?php if ($page < $total_page) { ?>
                                <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a></li>
                            <?php } ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<style>

    
    .card:hover {
        transform: scale(1.05); /* Membesarkan sedikit kartu */
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); /* Menambahkan bayangan */
    }

    .card-img-top:hover {
        opacity: 0.8; /* Sedikit transparan pada gambar */
        transition: opacity 0.3s ease-in-out;
    }

    .btn:hover {
        background-color: #007bff; /* Warna hover tombol */
        color: #fff;
    }
</style>


<?php include 'include/footer.php'; ?>
