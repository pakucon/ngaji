<?php
include 'config.php';

$tanggal = $_POST['tanggal'];
$siswa_id = $_POST['siswa_id'];
$pengajar_id = $_SESSION['user_id'];
$buku = $_POST['buku'];
$bab = $_POST['bab'];
$halaman = $_POST['halaman'];

// 1. Simpan ke penilaian
$conn->query("INSERT INTO penilaian (tanggal, siswa_id, pengajar_id, buku, bab, halaman)
VALUES ('$tanggal', '$siswa_id', '$pengajar_id', '$buku', '$bab', '$halaman')");

// Ambil ID terakhir
$penilaian_id = $conn->insert_id;

// 2. Simpan detail
foreach ($_POST['detail'] as $key => $value) {
    if ($value != '') {
        $conn->query("INSERT INTO penilaian_detail (penilaian_id, key_name, value)
        VALUES ('$penilaian_id', '$key', '$value')");
    }
}

echo "Data berhasil disimpan! <a href='input_nilai.php'>Input lagi</a>";