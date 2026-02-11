<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
        <span class="brand-text font-weight-light">Admin Fitnes Mart makassar</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <?php
        // Include database connection
        include '../koneksi.php';

        // Ambil data pegawai berdasarkan username yang disimpan di session
        $username = $_SESSION['username'] ?? '';
        $role = $_SESSION['role'] ?? '';

        if ($username) {
            $stmt = $koneksi->prepare("SELECT 222247_nama_lengkap, 222247_role FROM tbl_pegawai_222247 WHERE 222247_username = ?");
            if ($stmt) {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                // Cek apakah data ditemukan
                if ($result && $result->num_rows > 0) {
                    $pegawai = $result->fetch_assoc();
                    $namaLengkap = htmlspecialchars($pegawai['222247_nama_lengkap']);
                    $role = htmlspecialchars($pegawai['222247_role']);
                } else {
                    $namaLengkap = "User";
                    $role = "Guest";
                }

                $stmt->close();
            } else {
                echo "Error preparing statement: " . $koneksi->error;
                $namaLengkap = "User";
                $role = "Guest";
            }
        } else {
            $namaLengkap = "User";
            $role = "Guest"; // Default role jika tidak ada session
        }

        // Function to set 'active' class on the current menu item
        function setActive($page)
        {
            return basename($_SERVER['PHP_SELF']) == $page ? 'active' : '';
        }
        ?>

        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="assets/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo $namaLengkap; ?></a>
                <small class="d-block text-muted"><?php echo htmlspecialchars($role); ?></small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Menu Dashboard -->
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?php echo setActive('index.php'); ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Menu Data Pegawai -->
                <li class="nav-item">
                    <a href="data_pegawai.php" class="nav-link <?php echo setActive('data_pegawai.php'); ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Data Admin</p>
                    </a>
                </li>

                <!-- Menu Data Pengguna -->
                <li class="nav-item">
                    <a href="data_pengguna.php" class="nav-link <?php echo setActive('data_pengguna.php'); ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Data Pelanggan</p>
                    </a>
                </li>

                <!-- Menu Data Produk -->
                <li class="nav-item">
                    <a href="data_produk.php" class="nav-link <?php echo setActive('data_produk.php'); ?>">
                        <i class="nav-icon fas fa-dumbbell"></i>
                        <p>Data Produk</p>
                    </a>
                </li>

                <!-- Menu Data Pesanan -->
                <li class="nav-item">
                    <a href="data_pesanan.php" class="nav-link <?php echo setActive('data_pesanan.php'); ?>">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Data Pesanan</p>
                    </a>
                </li>

                <!-- Menu Data Pembayaran -->
                <li class="nav-item">
                    <a href="data_pembayaran.php" class="nav-link <?php echo setActive('data_pembayaran.php'); ?>">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>Data Pembayaran</p>
                    </a>
                </li>

                <li class="nav-header">LAINNYA</li>

                <!-- Menu Generate Laporan -->
                <li class="nav-item">
                    <a href="generate_laporan.php" class="nav-link <?php echo setActive('generate_laporan.php'); ?>">
                        <i class="nav-icon fas fa-file-export"></i>
                        <p>Generate Laporan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="data_ulasan.php" class="nav-link <?php echo setActive('data_ulasan.php'); ?>">
                        <i class="nav-icon fas fa-star"></i>
                        <p>Ulasan Produk</p>
                    </a>
                </li>

                <!-- Menu Logout -->
                <li class="nav-item">
                    <a href="logout.php" class="nav-link" data-toggle="modal" data-target="#logoutModal">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Keluar</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Yakin dek?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Pilih miki "keluar" kalau tidak "cancel" saja sayang 😘</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>