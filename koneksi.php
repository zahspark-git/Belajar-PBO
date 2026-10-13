<?php
$koneksi = mysqli_connect(
    "localhost",
    "root",
    "",
    "db_mahasiswa" // Ganti db_login menjadi db_mahasiswa
);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>