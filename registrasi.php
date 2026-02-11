<?php
ob_start();
session_start();
include 'koneksi.php';

// Cek apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $kata_sandi = $_POST['kata_sandi'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $no_telepon = $_POST['no_telepon'];
    $alamat = $_POST['alamat']; // Menambahkan variabel alamat

    // Validasi jika username, email, no telepon sudah ada
    $stmt = $koneksi->prepare("SELECT * FROM tbl_pengguna_222247 WHERE 222247_username = ? OR 222247_email = ? OR 222247_nomor_telepon = ?");
    $stmt->bind_param("sss", $username, $email, $no_telepon);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Jika ada yang sama, tampilkan pesan error
        $error = "Username, Email, atau Nomor Telepon sudah terdaftar!";
    } else {
        // Jika tidak ada yang sama, lakukan pendaftaran
        $insert_stmt = $koneksi->prepare("INSERT INTO tbl_pengguna_222247 (222247_username, 222247_kata_sandi, 222247_nama_lengkap, 222247_email, 222247_nomor_telepon, 222247_alamat) VALUES (?, ?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("ssssss", $username, $kata_sandi, $nama_lengkap, $email, $no_telepon, $alamat); // Menambahkan alamat

        if ($insert_stmt->execute()) {
            // Pendaftaran berhasil
            echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='login.php';</script>";
            exit();
        } else {
            // Pendaftaran gagal
            $error = "Pendaftaran gagal! Silakan coba lagi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>REGISTRASI | FITNESS MART</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="pegawai/assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="pegawai/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="pegawai/assets/dist/css/adminlte.min.css">
    <style>
        body.register-page {
            /* Ganti 'URL_GAMBAR_BACKGROUND_ANDA.jpg' dengan path gambar Anda */
            background-image: url('https://images.unsplash.com/photo-1517836357463-d25dfeac3438?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTF8fGd5bXxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=1000&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex; /* Untuk memusatkan register-box */
            align-items: center; /* Untuk memusatkan register-box */
            justify-content: center; /* Untuk memusatkan register-box */
            min-height: 100vh; /* Memastikan body mengisi minimal seluruh viewport height */
            padding: 20px 0; /* Memberi padding atas bawah jika konten lebih panjang dari viewport */
            overflow-x: hidden; /* Mencegah scroll horizontal */
        }

        .register-box {
            width: 420px; /* Sedikit lebih lebar karena formnya lebih panjang */
            opacity: 0;
            animation: fadeInRegisterBox 0.8s 0.3s ease-out forwards;
        }

        @keyframes fadeInRegisterBox {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .register-box .card {
            background-color: rgba(255, 255, 255, 0.93) !important; /* Latar belakang card sedikit transparan */
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            margin-bottom: 0; /* AdminLTE terkadang memberi margin bawah pada card di register-page */
        }

        .register-box .card-header {
            background-color: transparent !important;
            border-bottom: 1px solid rgba(0,0,0,0.08) !important;
            padding-top: 1.25rem;
            padding-bottom: 1.25rem;
        }

        .register-box .card-header a.h1 {
            color: #333;
            font-weight: 700;
        }
        .register-box .card-header a.h1 b:first-child{ /* Untuk "Fitnes" */
            color: #007bff; /* Biru (primary) seperti card-outline */
        }
        .register-box .card-header a.h1 b:nth-child(2){ /* Untuk "Mart" */
            color: #28a745; /* Hijau untuk konsistensi tema fitness/mart */
        }


        .register-box .login-box-msg { /* Class ini juga dipakai di register */
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }

        .register-box .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-weight: 600;
            padding: 0.6rem 0.75rem;
        }
        .register-box .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
        
        .register-box .text-center a {
            color: #007bff;
            font-weight: 500;
        }
        .register-box .text-center a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        /* Styling untuk alert jika ada */
        .register-box .alert {
            animation: fadeInAlert 0.5s ease-out;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        @keyframes fadeInAlert {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .input-group .form-control,
        .form-group .form-control { /* Menargetkan textarea juga */
            border-right: 0;
        }
        .input-group .input-group-text {
            background-color: #fff;
            border-left: 0;
        }
        .form-group textarea.form-control {
            border-right: 1px solid #ced4da; /* Textarea tidak punya ikon, jadi kembalikan border kanannya */
            padding: .375rem .75rem; /* Pastikan padding konsisten */
        }

    </style>
</head>

<body class="hold-transition register-page">
    <div class="register-box">
        <div class="card card-outline card-primary"> <!-- card-primary untuk border atas biru -->
            <div class="card-header text-center">
                <a href="#" class="h1"><b>Fitnes</b> <b>Mart</b> Makassar</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Daftar akun baru Fitness Mart sekarang :</p>

                <?php
                // Untuk keperluan demonstrasi, Anda bisa uncomment ini untuk melihat tampilan alert
                // $error = "Email sudah terdaftar.";
                ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> Perhatian!</h5>
                        Masukkan : <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form action="" method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="username" placeholder="Username" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="nama_lengkap" placeholder="Nama Lengkap" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-id-card"></span> <!-- Ikon berbeda untuk nama lengkap -->
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="no_telepon" placeholder="Nomor Telepon" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-phone"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <textarea class="form-control" name="alamat" placeholder="Alamat Lengkap" rows="3" required></textarea>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="kata_sandi" placeholder="Kata Sandi" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2"> 
                        <div class="col-8 d-flex align-items-center">
                            <a href="login.php" class="text-center">Sudah punya akun? Masuk</a>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Daftar</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.register-box -->

    <script src="pegawai/assets/plugins/jquery/jquery.min.js"></script>
    <script src="pegawai/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="pegawai/assets/dist/js/adminlte.min.js"></script>
</body>

</html>