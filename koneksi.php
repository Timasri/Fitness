<?php
$koneksi = mysqli_connect("localhost", "root", "", "222247_fitnes");

// Memeriksa koneksi
if (mysqli_connect_errno()) {
    echo "Koneksi database gagal : " . mysqli_connect_error();
}
?>
