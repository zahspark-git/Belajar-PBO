<?php
$koneksi = mysqli_connect(
    "localhost",
    "root",
    "",
    "db_login"
);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>