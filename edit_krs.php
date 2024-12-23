<?php
include 'koneksi.php'; // File koneksi ke database

$id = $_GET['id']; // ID mahasiswa
// Ambil data mahasiswa
$mahasiswa = $conn->query("SELECT * FROM inputmhs WHERE id = $id")->fetch_assoc();
// Ambil daftar mata kuliah
$matakuliah = $conn->query("SELECT * FROM jwl_matakuliah");
// Ambil mata kuliah yang diambil mahasiswa
$krs = $conn->query("SELECT * FROM jwl_mhs WHERE mhs_id = $id");

// Tambah mata kuliah
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_krs'])) {
    $matakuliah_id = $_POST['matakuliah'];

    // Ambil detail mata kuliah berdasarkan ID
    $result = $conn->query("SELECT * FROM jwl_matakuliah WHERE id = $matakuliah_id");
    if ($result->num_rows > 0) {
        $matakuliah_data = $result->fetch_assoc();
        $matakuliah = $matakuliah_data['matakuliah'];
        $sks = $matakuliah_data['sks'];
        $kelp = $matakuliah_data['kelp'];
        $ruangan = $matakuliah_data['ruangan'];

        // Validasi apakah mata kuliah sudah diambil
        $check = $conn->query("SELECT * FROM jwl_mhs WHERE mhs_id = $id AND matakuliah = '$matakuliah'");
        if ($check->num_rows == 0) {
            $query = "INSERT INTO jwl_mhs (mhs_id, matakuliah, sks, kelp, ruangan) 
                      VALUES ($id, '$matakuliah', $sks, '$kelp', '$ruangan')";
            $conn->query($query);
        }
    }

    // Refresh halaman
    header("Location: edit_krs.php?id=$id");
}

// Hapus mata kuliah
if (isset($_GET['delete_krs'])) {
    $krs_id = $_GET['delete_krs'];
    $conn->query("DELETE FROM jwl_mhs WHERE id = $krs_id");
    header("Location: edit_krs.php?id=$id");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit KRS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        <!-- Header -->
        <h1 class="text-center">Sistem Input Kartu Rencana Studi (KRS)</h1>
        <div class="alert alert-info">
            <strong>Mahasiswa:</strong> <?= $mahasiswa['namaMhs']; ?> |
            <strong>NIM:</strong> <?= $mahasiswa['nim']; ?> |
            <strong>IPK:</strong> <?= $mahasiswa['ipk']; ?>
            <a href="input_mahasiswa.php" class="btn btn-warning float-end">Kembali ke data mahasiswa</a>
        </div>

        <!-- Form Tambah Mata Kuliah -->
        <form method="POST" class="mb-3">
            <div class="input-group">
                <select name="matakuliah" class="form-select" required>
                    <option value="" disabled selected>Pilih Mata Kuliah</option>
                    <?php while ($row = $matakuliah->fetch_assoc()): ?>
                        <option value="<?= $row['id']; ?>">
                            <?= $row['matakuliah']; ?> (<?= $row['sks']; ?> SKS)
                        </option>
                    <?php endwhile; ?>
                </select>
                <button type="submit" name="add_krs" class="btn btn-primary">Simpan</button>
            </div>
        </form>

        <!-- Tabel Mata Kuliah yang Diambil -->
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Kelompok</th>
                    <th>Ruangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                $total_sks = 0;
                while ($row = $krs->fetch_assoc()):
                    $total_sks += $row['sks']; ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['matakuliah']; ?></td>
                        <td><?= $row['sks']; ?></td>
                        <td><?= $row['kelp']; ?></td>
                        <td><?= $row['ruangan']; ?></td>
                        <td>
                            <a href="edit_krs.php?id=<?= $id; ?>&delete_krs=<?= $row['id']; ?>" class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <h4>Total SKS: <?= $total_sks; ?></h4>
    </div>
</body>

</html>