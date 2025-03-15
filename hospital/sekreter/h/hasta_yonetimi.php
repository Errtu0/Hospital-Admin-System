<?php

require '../../vt.php';


$sql_hasta = "SELECT * FROM hasta";
$result_hasta = $conn->query($sql_hasta);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hastane Yönetim Sistemi</title>
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
    </style>
</head>
<body class="bg-gray-100">

<div class="flex flex-col min-h-screen bg-gray-100">
    <div class="flex flex-grow">
        <aside class="w-64 bg-gray-800 text-white">
            <div class="p-4">
                <h2 class="text-2xl font-bold"><a href="../sekreter_anasayfa.php" class="block px-4 py-2 hover:bg-gray-700">Sekreter Paneli</h2></a>
            </div>
            <nav class="mt-4">
                <a href="hasta_yonetimi.php" class="block px-4 py-2 hover:bg-gray-700">Hasta Yönetimi</a>
                <a href="../r/randevu_planlama.php" class="block px-4 py-2 hover:bg-gray-700">Randevu Planlama</a>
                <a href="../rapor_yukle.php" class="block px-4 py-2 hover:bg-gray-700">Raporlar</a>
                <a href="../../cikis.php" class="block px-4 py-2 text-red-400 hover:text-red-600">Çıkış Yap</a>
            </nav>
        </aside>


    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Hasta Yönetimi</h1>
        <a href="hasta_ekle.php" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 mb-4 inline-block">Yeni Hasta Ekle</a>

        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr>
                    <th class="border px-4 py-2">Hasta ID</th>
                    <th class="border px-4 py-2">Ad Soyad</th>
                    <th class="border px-4 py-2">E-posta</th>
                    <th class="border px-4 py-2">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($hasta = $result_hasta->fetch_assoc()): ?>
                    <tr>
                        <td class="border px-4 py-2"><?= htmlspecialchars($hasta['hasta_id']) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($hasta['ad_soyad']) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($hasta['email']) ?></td>
                        <td class="border px-4 py-2">
                            <a href="hasta_duzenle.php?hasta_id=<?= $hasta['hasta_id'] ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Düzenle</a> | 
                            <a href="rapor_duzenle.php?hasta_id=<?= $hasta['hasta_id'] ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Raporları Görüntüle</a> |
                            <a href="hasta_sil.php?hasta_id=<?= $hasta['hasta_id'] ?>" class="bg-red-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Sil</a>  

                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <footer class="bg-gray-800 text-white text-center py-4" id="footer">
        <p>&copy; 2024 Hastane Yönetim Sistemi. Tüm hakları saklıdır.</p>
    </footer>
    </div>


</body>
</html>
