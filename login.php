<?php
ob_start();
session_start();
include 'koneksi.php'; // Memanggil file koneksi database

// Cek apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login']; // Menggunakan satu field untuk username atau email
    $password = $_POST['password'];

    // Mencegah SQL injection dengan prepared statements
    $stmt = $koneksi->prepare("SELECT * FROM tbl_pengguna_222247 WHERE 222247_username = ? OR 222247_email = ?");
    $stmt->bind_param("ss", $login, $login); // Bind login untuk username atau email
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah ada user dengan username atau email yang sesuai
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Cek password
        if ($user['222247_kata_sandi'] === $password) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $user['222247_username'];
            $_SESSION['nama_lengkap'] = $user['222247_nama_lengkap'];
            $_SESSION['user_id'] = $user['222247_id_pengguna'];  // Simpan ID pengguna ke dalam sesi
            $_SESSION['session_token'] = $user['222247_session_token']; // Menyimpan session token ke dalam sesi

            // Generate session token baru
            $session_token = bin2hex(random_bytes(16)); // Membuat token unik
            $_SESSION['session_token'] = $session_token; // Menyimpan token di sesi

            // Simpan token baru ke database
            $update_stmt = $koneksi->prepare("UPDATE tbl_pengguna_222247 SET 222247_session_token = ? WHERE 222247_id_pengguna = ?");
            $update_stmt->bind_param("si", $session_token, $user['222247_id_pengguna']);
            $update_stmt->execute();

            // Redirect ke halaman index.php setelah login berhasil
            header('Location: index.php');
            exit();
        } else {
            // Tampilkan pesan error jika password salah
            $error = "Password salah!";
        }
    } else {
        // Tampilkan pesan error jika username/email tidak ditemukan
        $error = "Username atau email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LOGIN | PELANGGAN</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="pegawai/assets/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="pegawai/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="pegawai/assets/dist/css/adminlte.min.css">
    <style>
        body.login-page {
            /* Menggunakan gambar login.jpg yang Anda tentukan */
            background-image: url('login.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            /* Menambahkan display flex untuk memusatkan login-box */
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh; /* Memastikan body mengisi seluruh viewport height */
            overflow: hidden; /* Mencegah scrollbar jika konten sedikit melebihi */
        }

        .login-box {
            width: 400px; /* Sedikit lebih lebar untuk tampilan yang lebih baik */
            opacity: 0; /* Mulai transparan untuk animasi */
            animation: fadeInLoginBox 0.8s 0.3s ease-out forwards; /* Tambahkan delay sedikit */
            /* 0.8s durasi, 0.3s delay, ease-out timing, forwards mempertahankan state akhir */
        }

        @keyframes fadeInLoginBox {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .login-box .card {
            background-color: rgba(255, 255, 255, 0.92) !important; /* Latar belakang card sedikit transparan, !important jika ada override */
            border-radius: 0.5rem; /* Sudut lebih membulat */
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); /* Shadow yang lebih soft */
        }

        .login-box .card-header {
            background-color: transparent !important; /* Header transparan */
            border-bottom: 1px solid rgba(0,0,0,0.08) !important; /* Garis bawah header yang halus */
        }

        .login-box .card-header a.h1 {
            color: #333; /* Warna teks judul lebih gelap agar kontras */
            font-weight: 700;
        }
        .login-box .card-header a.h1 b{
            color: #28a745; /* Warna hijau untuk Fitnes */
        }


        .login-box .btn {
            padding: 0.6rem 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .login-box .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        /* Styling untuk alert jika ada */
        .login-box .alert {
            animation: fadeInAlert 0.5s ease-out;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        @keyframes fadeInAlert {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .input-group .form-control {
            border-right: 0; /* Menghilangkan border kanan input agar menyatu dengan ikon */
        }
        .input-group .input-group-text {
            background-color: #fff; /* Pastikan background ikon putih */
            border-left: 0; /* Menghilangkan border kiri ikon */
        }

    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-success"> <!-- card-success untuk border atas hijau (sesuai tema fitness) -->
            <div class="card-header text-center">
                <a href="login.php" class="h1"><b>Fitnes</b> Suplement</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Masuk untuk akun pengguna</p>

                <?php

                ?>

                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> Gagal!</h5>
                        <?php echo htmlspecialchars($error); // Selalu escape output ?>
                    </div>
                <?php } ?>
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                        <?php echo htmlspecialchars($_SESSION['message']); // Selalu escape output ?>
                        <?php unset($_SESSION['message']); ?>
                    </div>
                <?php endif; ?>

                <form action="login.php" method="post">
                    <div class="input-group mb-3">
                        <input type="text" name="login" class="form-control" placeholder="Username atau Email" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-7">
                            <a href="registrasi.php" class="btn btn-success btn-block">Daftar Sekarang</a>
                        </div>
                        <!-- /.col -->
                        <div class="col-5">
                            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="pegawai/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="pegawai/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="pegawai/assets/dist/js/adminlte.min.js"></script>
</body>

</html>