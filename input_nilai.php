<?php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'pengajar') {
    header("Location: login.php");
    exit;
}
?>

<h2>Input Nilai</h2>

<form method="POST" action="simpan_nilai.php">

Tanggal:
<input type="date" name="tanggal" required><br><br>

Siswa:
<select name="siswa_id">
<?php
$data = $conn->query("SELECT * FROM siswa");
while ($s = $data->fetch_assoc()) {
    echo "<option value='{$s['id']}'>{$s['nama']}</option>";
}
?>
</select><br><br>

Buku:
<input type="text" name="buku"><br><br>

Bab:
<input type="text" name="bab"><br><br>

Halaman:
<input type="text" name="halaman"><br><br>

<hr>

Nilai:
<input type="text" name="detail[nilai]"><br><br>

Hafalan Sekarang:
<input type="text" name="detail[hafalan_sekarang]"><br><br>

Nilai Hafalan Sekarang:
<input type="text" name="detail[nilai_hafalan_sekarang]"><br><br>

Hafalan Lalu:
<input type="text" name="detail[hafalan_lalu]"><br><br>

Nilai Hafalan Lalu:
<input type="text" name="detail[nilai_hafalan_lalu]"><br><br>

<button type="submit">Simpan</button>

</form>