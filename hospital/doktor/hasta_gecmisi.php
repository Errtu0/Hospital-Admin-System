<?php
require '../vt.php';


if (!isset($_GET['hasta_id']) || empty($_GET['hasta_id'])) {
    die("Hasta ID parametresi eksik.");
}

$hasta_id = $_GET['hasta_id']; 

$sql_hasta = "SELECT * FROM hasta WHERE hasta_id = ?";
$stmt_hasta = $conn->prepare($sql_hasta);
$stmt_hasta->bind_param("i", $hasta_id);
$stmt_hasta->execute();
$result_hasta = $stmt_hasta->get_result();
$hasta = $result_hasta->fetch_assoc();

if (!$hasta) {
    die("Bu hasta veritabanında bulunamadı.");
}


$sql_raporlar = "SELECT * FROM raporlar WHERE hasta_id = ? ORDER BY rapor_olusturma_tarihi DESC";
$stmt_raporlar = $conn->prepare($sql_raporlar);
$stmt_raporlar->bind_param("i", $hasta_id);
$stmt_raporlar->execute();
$result_raporlar = $stmt_raporlar->get_result();


$sql_muayene = "SELECT * FROM muayene_gecmisi WHERE hasta_id = ? ORDER BY muayene_tarihi DESC";
$stmt_muayene = $conn->prepare($sql_muayene);
$stmt_muayene->bind_param("i", $hasta_id);
$stmt_muayene->execute();
$result_muayene = $stmt_muayene->get_result();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta Bilgileri</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        #footer{
            position: fixed;
            padding: 5px;
            bottom: 0;
            width: 100%;
            height: 50px;
            color: white;
        }
    </style>
</head>
<body class="bg-gray-100">

<div class="flex flex-col min-h-screen bg-gray-100">
    <div class="flex flex-grow">
        <aside class="w-64 bg-gray-800 text-white">
            <div class="p-4">
                <h2 class="text-2xl font-bold"><a href="doktor_anasayfa.php" class="block px-4 py-2 hover:bg-gray-700">Doktor Paneli</a></h2>
            </div>
            <nav class="mt-4">
                <a href="s/sekreter_listesi.php" class="block px-4 py-2 hover:bg-gray-700">Sekreter Yönetimi</a>
                <a href="h/hasta_listesi.php" class="block px-4 py-2 hover:bg-gray-700">Hasta Yönetimi</a>
                <a href="randevular.php" class="block px-4 py-2 hover:bg-gray-700">Randevularım</a>
                <a href="../cikis.php" class="block px-4 py-2 text-red-400 hover:text-red-600">Çıkış Yap</a>
            </nav>
        </aside>

        <div class="container mx-auto p-6">
            <h1 class="text-3xl font-bold mb-6">Hasta Geçmişi: <?= htmlspecialchars($hasta['ad_soyad']) ?></h1>

            <h2 class="text-2xl mb-4">Hasta Bilgileri</h2>
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Ad Soyad</th>
                        <th class="border px-4 py-2">Email</th>
                        <th class="border px-4 py-2">Telefon</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border px-4 py-2"><?= htmlspecialchars($hasta['ad_soyad']) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($hasta['email']) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($hasta['telefon']) ?></td>
                    </tr>
                </tbody>
            </table>

            <h2 class="text-2xl mb-4 mt-8">Raporlar</h2>
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Rapor Tarihi</th>
                        <th class="border px-4 py-2">Doktor</th>
                        <th class="border px-4 py-2">Rapor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_raporlar->fetch_assoc()): ?>
                        <tr>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['rapor_olusturma_tarihi']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['doktor_id']) ?></td>
                            <td class="border px-4 py-2">
                                <?php if ($row['rapor_dosyasi']): ?>
                                    <a href="<?= htmlspecialchars($row['rapor_dosyasi']) ?>" target="_blank" class="text-blue-500">Raporu Görüntüle</a>
                                <?php else: ?>
                                    Rapor bulunmamaktadır.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <h2 class="text-2xl mb-4 mt-8">Muayene Geçmişi</h2>
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Muayene Tarihi</th>
                        <th class="border px-4 py-2">Doktor</th>
                        <th class="border px-4 py-2">Rapor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_muayene->fetch_assoc()): ?>
                        <tr>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['muayene_tarihi']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['doktor_id']) ?></td>
                            <td class="border px-4 py-2">
                                <?php if ($row['rapor_dosyasi']): ?>
                                    <a href="<?= htmlspecialchars($row['rapor_dosyasi']) ?>" target="_blank" class="text-blue-500">Raporu Görüntüle</a>
                                <?php else: ?>
                                    Rapor bulunmamaktadır.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="bg-gray-800 text-white text-center py-4" id="footer">
        <p>&copy; 2024 Hastane Yönetim Sistemi. Tüm hakları saklıdır.</p>
    </footer>
</div>

</body>
</html>
