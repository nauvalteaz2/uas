<?php
include 'koneksi.php'; // File koneksi ke database

$id = $_GET['id']; // ID mahasiswa

// Ambil data mahasiswa
$mahasiswa = $conn->query("SELECT * FROM inputmhs WHERE id = $id")->fetch_assoc();

// Ambil mata kuliah yang diambil mahasiswa
$krs = $conn->query("SELECT * FROM jwl_mhs WHERE mhs_id = $id");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Lihat KRS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        <!-- Header -->
        <h1 class="text-center">Kartu Rencana Studi (KRS)</h1>
        <div class="alert alert-info">
            <strong>Mahasiswa:</strong> <?= $mahasiswa['namaMhs']; ?> |
            <strong>NIM:</strong> <?= $mahasiswa['nim']; ?> |
            <strong>IPK:</strong> <?= $mahasiswa['ipk']; ?>
        </div>

        <!-- Tabel Mata Kuliah yang Diambil -->
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Kelompok</th>
                    <th>Ruangan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                $total_sks = 0;
                while ($row = $krs->fetch_assoc()):
                    $total_sks += $row['sks']; ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['matakuliah']; ?></td>
                        <td><?= $row['sks']; ?></td>
                        <td><?= $row['kelp']; ?></td>
                        <td><?= $row['ruangan']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Total SKS -->
        <h4 class="text-end">Total SKS: <?= $total_sks; ?></h4>

        <!-- Tombol Cetak PDF -->
        <form action="cetak_krs.php" method="GET">
            <input type="hidden" name="id" value="<?= $id; ?>">
            <button type="submit" class="btn btn-primary">Cetak PDF</button>
            <a href="input_mahasiswa.php" class="btn btn-warning">Kembali</a>
        </form>
    </div>
</body>

</html>
