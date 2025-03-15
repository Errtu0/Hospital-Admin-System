<?php
require '../../vt.php';

$sql = "SELECT * FROM hasta";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasta Listesi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        #footer {
            position: fixed;
            padding: 5px;
            bottom: 0;
            width: 100%;
            height: 50px;
            color: white;
        }
        .content {
            min-height: calc(100vh - 50px);
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

<div class="flex flex-grow">
    <aside class="w-64 bg-gray-800 text-white">
        <div class="p-4">
            <h2 class="text-2xl font-bold"><a href="../doktor_anasayfa.php" class="block px-4 py-2 hover:bg-gray-700">Doktor Paneli</h2></a>
        </div>
        <nav class="mt-4">
            <a href="../s/sekreter_listesi.php" class="block px-4 py-2 hover:bg-gray-700">Sekreter Yönetimi</a>
            <a href="hasta_listesi.php" class="block px-4 py-2 hover:bg-gray-700">Hasta Yönetimi</a>
            <a href="../randevular.php" class="block px-4 py-2 hover:bg-gray-700">Randevularım</a>
            <a href="../cikis.php" class="block px-4 py-2 text-red-400 hover:text-red-600">Çıkış Yap</a>
        </nav>
    </aside>

<div class="flex flex-col min-h-screen bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Hasta Listesi</h1>
        
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Hasta Adı</th>
                    <th class="border px-4 py-2">Detaylar</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="border px-4 py-2"><?= htmlspecialchars($row['ad_soyad']) ?></td>
                        <td class="border px-4 py-2">
                            <a href="../hasta_gecmisi.php?hasta_id=<?= $row['hasta_id'] ?>" class="text-blue-500">Raporlar</a>
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
</body>
</html>
