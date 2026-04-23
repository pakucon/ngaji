<?php
include 'config.php';
$bulan = $_GET['bulan'] ?? date('n');
$tahun = $_GET['tahun'] ?? date('Y');
$siswa_id = $_GET['siswa_id'] ?? null;

$bulan = max(1, min(12, (int)$bulan));
$tahun = (int)$tahun;
$siswa_id = $siswa_id ? (int)$siswa_id : null;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// logic siswa_id
if ($_SESSION['role'] == 'murid') {
    $siswa_id = (int)$_SESSION['siswa_id'];
} else {
    $siswa_id = $_GET['siswa_id'] ?? null;
    $siswa_id = $siswa_id ? (int)$siswa_id : null;
}
?>

<?php if ($_SESSION['role'] == 'pengajar'): ?>

<form method="GET">
    Pilih Siswa:
    <select name="siswa_id">
        <option value="">-- Pilih --</option>

        <?php
        $siswa = $conn->query("SELECT * FROM siswa");
        while ($s = $siswa->fetch_assoc()) {
            $selected = ($siswa_id == $s['id']) ? 'selected' : '';
            echo "<option value='{$s['id']}' $selected>{$s['nama']}</option>";
        }
        ?>
    </select>

    Bulan:
    <select name="bulan">
        <?php
        for ($i=1; $i<=12; $i++) {
            $selected = ($bulan == $i) ? 'selected' : '';
            echo "<option value='$i' $selected>$i</option>";
        }
        ?>
    </select>

    Tahun:
    <input type="number" name="tahun" value="<?= $tahun ?>">

    <button type="submit">Lihat</button>
</form>

<hr>

<?php endif; ?>

<?php if (!$siswa_id): ?>
    echo "<p>Silakan pilih siswa dulu</p>";
    exit;
<?php else: ?>

<?php
$stmt = $conn->prepare("
SELECT p.id, p.tanggal, p.buku, pd.key_name, pd.value
FROM penilaian p
JOIN penilaian_detail pd ON p.id = pd.penilaian_id
WHERE p.siswa_id = ?
AND MONTH(p.tanggal) = ?
AND YEAR(p.tanggal) = ?
ORDER BY p.tanggal ASC
");

$stmt->bind_param("iii", $siswa_id, $bulan, $tahun);
$stmt->execute();

$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $id = $row['id'];

    if (!isset($data[$id])) {
        $data[$id] = [
            'tanggal' => $row['tanggal'],
            'buku' => $row['buku'],
            'detail' => []
        ];
    }

    $data[$id]['detail'][$row['key_name']] = $row['value'];
}

$total = 0;
$count = 0;
$map = [
    'A' => 4,
    'B' => 3,
    'C' => 2,
    'D' => 1
];

foreach ($data as $d) {
    if (isset($d['detail']['nilai'])) {
        $nilai = strtoupper($d['detail']['nilai'] ?? '');

        if (isset($map[$nilai])) {
            $total += $map[$nilai];
            $count++;
        }
    }
}

$rata = $count > 0 ? $total / $count : 0;

echo "<h2>Raport</h2>";
echo "<h3>Periode: $bulan / $tahun</h3>";
echo "<p><strong>Rata-rata nilai:</strong> " . round($rata,2) . "</p>";

$stat = ['A'=>0,'B'=>0,'C'=>0,'D'=>0];

foreach ($data as $d) {
    $nilai = strtoupper($d['detail']['nilai'] ?? '');
    if (isset($stat[$nilai])) {
        $stat[$nilai]++;
    }
}

echo "<p>";
foreach ($stat as $k => $v) {
    echo "$k: $v ";
}
echo "</p>";

foreach ($data as $d) {
    echo "<hr>";
    echo "Tanggal: {$d['tanggal']}<br>";
    echo "Buku: " . htmlspecialchars($d['buku']) . "<br>";

    foreach ($d['detail'] as $k => $v) {
        echo ucfirst(str_replace('_',' ',$k)) . ": $v<br>";
    }
}
?>

<?php endif; ?>