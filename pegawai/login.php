<?php
ob_start();
include '../koneksi.php';
session_start();

// Cek apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi panjang username dan password
    if (strlen($username) < 8 || strlen($username) > 10) {
        $error = "Username harus minimal 8 karakter maksimal 10 karakter!";
    } elseif (strlen($password) < 8 || strlen($password) > 10) {
        $error = "Password harus minimal 8 karakter maksimal 10 karakter!";
    } else {
        // Proses login jika validasi berhasil
        $stmt = $koneksi->prepare("SELECT * FROM tbl_pegawai_222247 WHERE 222247_username = ? AND 222247_kata_sandi = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $_SESSION['login'] = true;
            $_SESSION['username'] = $user['222247_username'];
            $_SESSION['nama_lengkap'] = $user['222247_nama_lengkap'];
            $_SESSION['user_id'] = $user['222247_id_pegawai'];
            $_SESSION['role'] = $user['222247_role'];

            $session_token = bin2hex(random_bytes(16));
            $_SESSION['session_token'] = $session_token;

            $update_stmt = $koneksi->prepare("UPDATE tbl_pegawai_222247 SET 222247_session_token = ? WHERE 222247_id_pegawai = ?");
            $update_stmt->bind_param("si", $session_token, $user['222247_id_pegawai']);
            $update_stmt->execute();

            header('Location: index.php');
            exit();
        } else {
            $error = "Username atau password salah!";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LOGIN | ADMIN</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
    <style>
        body.login-page {
            background-image: url('../admin.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            backdrop-filter: blur(0px);
            -webkit-backdrop-filter: blur(0px);
        }
    </style>

</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="login.php" class="h1"><b>Admin</b><br>Fitnes Mart Makassar</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Masuk sebagai admin fitnes mart</p>

                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php } ?>
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-success alert-dismissible">
                        <?php echo $_SESSION['message']; ?>
                        <?php unset($_SESSION['message']); ?>
                    </div>
                <?php endif; ?>

                <form action="login.php" method="post">
    <div class="input-group mb-3">
        <input type="text" name="username" class="form-control" placeholder="Username" 
               required pattern=".{8,10}" title="Username harus minimal 8 karakter maksimal 10 karakter">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-user"></span>
            </div>
        </div>
    </div>
    <div class="input-group mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" 
               required pattern=".{8,10}" title="Password harus minimal 8 karakter maksimal 10 karakter">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-8">
            <div class="icheck-primary">
                <input type="checkbox" id="remember">
                <label for="remember">Ingatkan saya</label>
            </div>
        </div>
        <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Masuk</button>
        </div>
    </div>
</form>

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="assets/dist/js/adminlte.min.js"></script>
</body>

</html>