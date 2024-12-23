<?php
include 'koneksi.php'; // File koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $nim = $_POST['nim'];
    $ipk = $_POST['ipk'];

    // Hitung SKS maksimal berdasarkan IPK
    $sks = ($ipk >= 3) ? 24 : (($ipk >= 2) ? 20 : 18);

    // Validasi NIM unik
    $check = $conn->query("SELECT * FROM inputmhs WHERE nim = '$nim'");
    if ($check->num_rows == 0) {
        $conn->query("INSERT INTO inputmhs (namaMhs, nim, ipk, sks) VALUES ('$nama', '$nim', '$ipk', '$sks')");
    } else {
        echo "<script>alert('NIM sudah terdaftar!');</script>";
    }
}

// Ambil data mahasiswa dengan mata kuliah
$mahasiswa = $conn->query("
    SELECT inputmhs.id, inputmhs.namaMhs, inputmhs.nim, inputmhs.ipk, inputmhs.sks, 
           GROUP_CONCAT(jwl_mhs.matakuliah SEPARATOR ', ') AS matakuliah
    FROM inputmhs 
    LEFT JOIN jwl_mhs ON inputmhs.id = jwl_mhs.mhs_id
    GROUP BY inputmhs.id
");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Input Mahasiswa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        <h1 class="text-center">Sistem Input Kartu Rencana Studi (KRS)</h1>
        <form method="POST" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan Nama Mahasiswa" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="nim" class="form-control" placeholder="Masukkan NIM" required>
                </div>
                <div class="col-md-4">
                    <input type="number" step="0.01" name="ipk" class="form-control" placeholder="Masukkan IPK" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Input Mahasiswa</button>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Mahasiswa</th>
                    <th>NIM</th>
                    <th>IPK</th>
                    <th>SKS Maksimal</th>
                    <th>Mata Kuliah Diambil</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($row = $mahasiswa->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['namaMhs']; ?></td>
                        <td><?= $row['nim']; ?></td>
                        <td><?= $row['ipk']; ?></td>
                        <td><?= $row['sks']; ?></td>
                        <td><?= $row['matakuliah'] ?: '-'; ?></td>
                        <td>
                            <a href="edit_krs.php?id=<?= $row['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="hapus_mahasiswa.php?id=<?= $row['id']; ?>" class="btn btn-danger">Hapus</a>
                            <a href="lihat_krs.php?id=<?= $row['id']; ?>" class="btn btn-info">Lihat</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>
</body>

</html>