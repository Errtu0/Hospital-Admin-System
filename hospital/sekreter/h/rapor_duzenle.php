<?php
require '../../vt.php';

if (!isset($_GET['hasta_id']) || empty($_GET['hasta_id'])) {
    exit("Hasta ID parametresi eksik.");
}

$hasta_id = $_GET['hasta_id'];


$sql_hasta = "SELECT * FROM hasta WHERE hasta_id = ?";
$stmt_hasta = $conn->prepare($sql_hasta);
$stmt_hasta->bind_param("i", $hasta_id);
$stmt_hasta->execute();
$result_hasta = $stmt_hasta->get_result();
$hasta = $result_hasta->fetch_assoc();

if (!$hasta) {
    exit("Bu hasta bulunamadı.");
}


$sql_raporlar = "SELECT * FROM raporlar WHERE hasta_id = ?";
$stmt_raporlar = $conn->prepare($sql_raporlar);
$stmt_raporlar->bind_param("i", $hasta_id);
$stmt_raporlar->execute();
$result_raporlar = $stmt_raporlar->get_result();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapor Yönetimi - <?= htmlspecialchars($hasta['ad_soyad']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="container mx-auto p-6">

    <h1 class="text-3xl font-bold mb-6">Rapor Yönetimi: <?= htmlspecialchars($hasta['ad_soyad']) ?></h1>

    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr>
                <th class="border px-4 py-2">Rapor Tarihi</th>
                <th class="border px-4 py-2">Doktor</th>
                <th class="border px-4 py-2">Dosya</th>
                <th class="border px-4 py-2">İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($rapor = $result_raporlar->fetch_assoc()): ?>
                <?php
                    
                    $doktor_id = $rapor['doktor_id'];
                    $sql_doktor = "SELECT ad_soyad FROM kullanicilar WHERE doktor_id = ? AND rol = 'doktor'";
                    $stmt_doktor = $conn->prepare($sql_doktor);
                    $stmt_doktor->bind_param("i", $doktor_id);
                    $stmt_doktor->execute();
                    $result_doktor = $stmt_doktor->get_result();
                    $doktor = $result_doktor->fetch_assoc();
                    $doktor_adi = $doktor ? htmlspecialchars($doktor['ad_soyad']) : 'Bilinmiyor'; 
                ?>
                <tr>
                    <td class="border px-4 py-2"><?= htmlspecialchars($rapor['rapor_olusturma_tarihi']) ?></td>
                    <td class="border px-4 py-2"><?= $doktor_adi ?></td> 
                    <td class="border px-4 py-2">
                        <a href="../../raporlar/<?= htmlspecialchars($rapor['rapor_dosyasi']) ?>" target="_blank" class="text-blue-500">Raporu Görüntüle</a>
                    </td>
                    <td class="border px-4 py-2">
                        <a href="rapor_guncelle.php?rapor_id=<?= $rapor['rapor_id'] ?>" class="bg-blue-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Düzenle</a>
                        <a href="rapor_sil.php?rapor_id=<?= $rapor['rapor_id'] ?>" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Bu raporu silmek istediğinizden emin misiniz?')">Sil</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
