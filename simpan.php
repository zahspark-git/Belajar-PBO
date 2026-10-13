<?php
include 'koneksi.php';

// Atur header agar respons dikirim dalam format JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim          = $_POST["nim"] ?? "";
    $nama         = $_POST["nama"] ?? "";
    $fakultas     = $_POST["fakultas"] ?? "";
    $prodi        = $_POST["prodi"] ?? "";
    $asal_sekolah = $_POST["asal_sekolah"] ?? "";
    $semester     = $_POST["semester"] ?? "";
    $hobi         = $_POST["hobi"] ?? "";

    // Query menyimpan data ke database
    $query = "INSERT INTO input_mahasiswa (nim, nama_mahasiswa, fakultas, program_studi, asal_sekolah, semester, hobi)
    VALUES ('$nim', '$nama', '$fakultas', '$prodi', '$asal_sekolah', '$semester', '$hobi')";

    if (mysqli_query($koneksi, $query)) {
        echo json_encode(['status' => 'success', 'data' => $_POST]);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($koneksi)]);
    }
}
?>