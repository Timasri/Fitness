<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title ?? "Fitnes Mart Makassar"; ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="pegawai/assets/plugins/fontawesome-free/css/all.min.css">
    

    <!-- Theme style -->
    <link rel="stylesheet" href="pegawai/assets/dist/css/adminlte.min.css">
    <style>
        .content-wrapper {
            position: relative;
            background-image: url('6.jpg');
            background-size:cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Overlay untuk efek blur */
        .content-wrapper::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: inherit;
            filter: blur(8px);
            z-index: 0;
        }

        /* Konten di atas latar belakang buram */
        .content {
            position: relative;
            z-index: 1;
        }

        /* Kelas untuk tombol nonaktif */
        .btn-disabled {
            pointer-events: none;
            /* Menonaktifkan klik */
            background-color: #d6d6d6;
            /* Mengubah warna latar belakang agar terlihat nonaktif */
            border-color: #d6d6d6;
            /* Mengubah warna border agar sesuai */
            color: #999999;
            /* Warna teks lebih muda untuk tombol nonaktif */
        }
    </style>


</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">