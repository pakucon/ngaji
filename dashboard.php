<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<h2>Dashboard</h2>

<p>Halo, <?php echo $_SESSION['nama']; ?>!</p>
<p>Role: <?php echo $_SESSION['role']; ?></p>

<a href="logout.php">Logout</a>

<hr>

<?php if ($_SESSION['role'] == 'pengajar'): ?>
    <a href="input_nilai.php">Input Nilai</a><br>
    <a href="raport.php">Lihat Raport Siswa</a>
<?php endif; ?>

<?php if ($_SESSION['role'] == 'murid'): ?>
    <a href="lihat_nilai.php">Lihat Nilai Saya</a><br>
    <a href="raport.php">Lihat Raport Saya</a>
<?php endif; ?>