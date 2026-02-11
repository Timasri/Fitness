<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

// Koneksi ke database
include 'koneksi.php';

// Ambil data pengguna dari database
$query_user = "SELECT * FROM tbl_pengguna_222247 WHERE 222247_id_pengguna = ?";
$stmt_user = $koneksi->prepare($query_user);
$stmt_user->bind_param("i", $_SESSION['user_id']);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows === 0) {
    echo "Pengguna tidak ditemukan.";
    exit();
}

// Ambil data pengguna
$user = $result_user->fetch_assoc();

// Set title halaman
$title = "Profil Pengguna";
include 'include/header.php';
include 'include/navbar.php';
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Profil Pelanggan</h1>
                </div>
                
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h4>Profil Saya :</h4>
                    <div class="card mb-4">
                        <div class="card-body">
                            <p><strong>Nama Lengkap :</strong> <?php echo htmlspecialchars($user['222247_nama_lengkap']); ?></p>
                            <p><strong>Username :</strong> <?php echo htmlspecialchars($user['222247_username']); ?></p>
                            <p><strong>Email :</strong> <?php echo htmlspecialchars($user['222247_email']); ?></p>
                            <p><strong>Nomor Telepon :</strong> <?php echo htmlspecialchars($user['222247_nomor_telepon']); ?></p>
                            <p><strong>Alamat :</strong> <?php echo nl2br(htmlspecialchars($user['222247_alamat'])); ?></p>
                        </div>
                    </div>
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#editProfileModal">Edit Profil</a>
                    <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#ubahKataSandiModal">Ubah Kata Sandi</a>
                    <div class="mt-3">
                        <a class="btn btn-secondary float-right ml-2" href="index.php"><i class="fas"></i> Kembali ke beranda</a>
                        <a class="btn btn-danger float-right" href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                            <i class="fas fa-sign-out-alt"></i> Keluar dari akun
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Profil -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="proses_pengguna/proses_edit_profil.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_pengguna" value="<?php echo $user['222247_id_pengguna']; ?>">
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap:</label>
                        <input type="text" class="form-control" name="nama_lengkap" value="<?php echo htmlspecialchars($user['222247_nama_lengkap']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['222247_username']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['222247_email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="nomor_telepon">Nomor Telepon:</label>
                        <input type="text" class="form-control" name="nomor_telepon" value="<?php echo htmlspecialchars($user['222247_nomor_telepon']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat:</label>
                        <textarea class="form-control" name="alamat"><?php echo htmlspecialchars($user['222247_alamat']); ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ubah Kata Sandi -->
<div class="modal fade" id="ubahKataSandiModal" tabindex="-1" aria-labelledby="ubahKataSandiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="proses_pengguna/proses_edit_profil.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="ubahKataSandiModalLabel">Ubah Kata Sandi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="kata_sandi_lama">Kata Sandi Lama:</label>
                        <input type="password" class="form-control" name="kata_sandi_lama" required>
                    </div>
                    <div class="form-group">
                        <label for="kata_sandi_baru">Kata Sandi Baru:</label>
                        <input type="password" class="form-control" name="kata_sandi_baru" required>
                    </div>
                    <div class="form-group">
                        <label for="konfirmasi_kata_sandi">Konfirmasi Kata Sandi Baru:</label>
                        <input type="password" class="form-control" name="konfirmasi_kata_sandi" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ubah Kata Sandi</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const editModal = document.getElementById('editProfileModal');
    const timerSpan = document.getElementById('timer');
    const formEditProfil = document.getElementById('formEditProfil');
    let timer;

    // Event saat modal dibuka
    editModal.addEventListener('show.bs.modal', () => {
        let timeRemaining = 60; // waktu dalam detik
        timerSpan.textContent = `Waktu tersisa: ${timeRemaining} detik`;

        // Jalankan timer
        timer = setInterval(() => {
            timeRemaining--;
            timerSpan.textContent = `Waktu tersisa: ${timeRemaining} detik`;

            if (timeRemaining <= 0) {
                clearInterval(timer);
                timerSpan.textContent = "Waktu habis!";
                formEditProfil.querySelector('[type="submit"]').disabled = true; // Nonaktifkan tombol submit
            }
        }, 1000);
    });

    // Event saat modal ditutup
    editModal.addEventListener('hidden.bs.modal', () => {
        clearInterval(timer); // Hentikan timer
        timerSpan.textContent = ""; // Bersihkan timer
        formEditProfil.querySelector('[type="submit"]').disabled = false; // Aktifkan kembali tombol submit
    });
});
</script>


<?php 
$stmt_user->close();
$koneksi->close();
include 'include/footer.php'; 
?>
