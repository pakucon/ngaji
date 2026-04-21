<?php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'murid') {
    header("Location: login.php");
    exit;
}

$siswa_id = $_SESSION['siswa_id'];

// Ambil data penilaian + detail
$query = $conn->query("
SELECT p.id, p.tanggal, p.buku, p.bab, p.halaman, pd.key_name, pd.value
FROM penilaian p
JOIN penilaian_detail pd ON p.id = pd.penilaian_id
WHERE p.siswa_id = '$siswa_id'
ORDER BY p.tanggal DESC
");

$data = [];

while ($row = $query->fetch_assoc()) {
    $id = $row['id'];

    if (!isset($data[$id])) {
        $data[$id] = [
            'tanggal' => $row['tanggal'],
            'buku' => $row['buku'],
            'bab' => $row['bab'],
            'halaman' => $row['halaman'],
            'detail' => []
        ];
    }

    $data[$id]['detail'][$row['key_name']] = $row['value'];
}

echo "<h2>Nilai Saya</h2>";

foreach ($data as $d) {
    echo "<hr>";
    echo "<b>Tanggal:</b> {$d['tanggal']}<br>";
    echo "<b>Buku:</b> {$d['buku']}<br>";
    echo "<b>Bab:</b> {$d['bab']}<br>";
    echo "<b>Halaman:</b> {$d['halaman']}<br><br>";

    foreach ($d['detail'] as $key => $val) {
        echo "<b>" . ucfirst(str_replace('_',' ',$key)) . ":</b> $val<br>";
    }
}