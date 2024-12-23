<?php
include 'koneksi.php'; // Koneksi ke database

// Cek apakah ID mahasiswa dikirimkan
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus data dari tabel jwl_mhs (data mata kuliah yang diambil mahasiswa)
    $conn->query("DELETE FROM jwl_mhs WHERE mhs_id = $id");

    // Hapus data dari tabel inputmhs (data mahasiswa)
    if ($conn->query("DELETE FROM inputmhs WHERE id = $id")) {
        // Redirect kembali ke halaman input_mahasiswa.php setelah berhasil
        header("Location: input_mahasiswa.php");
        exit();
    } else {
        echo "Error: " . $conn->error; // Tampilkan error jika terjadi masalah
    }
} else {
    // Redirect kembali jika ID tidak valid
    header("Location: input_mahasiswa.php");
    exit();
}
