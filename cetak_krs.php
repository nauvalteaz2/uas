<?php
require('fpdf/fpdf.php');
include 'koneksi.php'; // File koneksi ke database

$id = $_GET['id']; // ID mahasiswa

// Ambil data mahasiswa
$mahasiswa = $conn->query("SELECT * FROM inputmhs WHERE id = $id")->fetch_assoc();

// Ambil mata kuliah yang diambil
$krs = $conn->query("SELECT * FROM jwl_mhs WHERE mhs_id = $id");

// Inisialisasi FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// Judul
$pdf->Cell(0, 10, 'Kartu Rencana Studi (KRS)', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(5);

// Informasi Mahasiswa
$pdf->Cell(0, 7, 'Nama: ' . $mahasiswa['namaMhs'] . '   NIM: ' . $mahasiswa['nim'] . '   IPK: ' . $mahasiswa['ipk'], 0, 1);
$pdf->Ln(5);

// Header Tabel Mata Kuliah
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 10, 'No', 1, 0, 'C');
$pdf->Cell(60, 10, 'Mata Kuliah', 1, 0, 'C');
$pdf->Cell(20, 10, 'SKS', 1, 0, 'C');
$pdf->Cell(40, 10, 'Kelompok', 1, 0, 'C');
$pdf->Cell(40, 10, 'Ruangan', 1, 1, 'C');

// Isi Tabel Mata Kuliah
$pdf->SetFont('Arial', '', 10);
$no = 1;
$total_sks = 0;

while ($row = $krs->fetch_assoc()) {
    $total_sks += $row['sks'];
    $pdf->Cell(10, 10, $no++, 1, 0, 'C');
    $pdf->Cell(60, 10, $row['matakuliah'], 1, 0, 'L');
    $pdf->Cell(20, 10, $row['sks'], 1, 0, 'C');
    $pdf->Cell(40, 10, $row['kelp'], 1, 0, 'C');
    $pdf->Cell(40, 10, $row['ruangan'], 1, 1, 'C');
}

// Total SKS
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(90, 10, 'Total SKS', 1, 0, 'C');
$pdf->Cell(20, 10, $total_sks, 1, 0, 'C');
$pdf->Cell(80, 10, '', 1, 1);

// Output PDF
$pdf->Output();
