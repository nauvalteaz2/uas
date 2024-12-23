<?php
$servername = "localhost"; // Sesuaikan dengan host server Anda
$username = "root";        // Username MySQL Anda
$password = "";            // Password MySQL Anda
$dbname = "krs_system";    // Nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
