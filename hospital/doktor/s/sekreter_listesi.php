<?php
require '../../vt.php';
session_start(); 


if (!isset($_SESSION['kul_id'])) {
    header("Location: ../../giris.php"); 
    exit;
}

$doktor_id = $_SESSION['kul_id'];


$sql = "SELECT * FROM kullanicilar WHERE rol = 'sekreter' AND doktor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doktor_id);
$stmt->execute();
$result = $stmt->get_result();
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
                <h2 class="text-2xl font-bold"><a href="../doktor_anasayfa.php" class="block px-4 py-2 hover:bg-gray-700">Doktor Paneli</a></h2>
            </div>
            <nav class="mt-4">
                <a href="sekreter_listesi.php" class="block px-4 py-2 hover:bg-gray-700">Sekreter Yönetimi</a>
                <a href="../h/hasta_listesi.php" class="block px-4 py-2 hover:bg-gray-700">Hasta Geçmişi</a>
                <a href="../randevular.php" class="block px-4 py-2 hover:bg-gray-700">Randevularım</a>
                <a href="../../cikis.php" class="block px-4 py-2 text-red-400 hover:text-red-600">Çıkış Yap</a>
            </nav>
        </aside>

        <div class="container mx-auto p-6">
            <h1 class="text-3xl font-bold mb-6">Sekreter Yönetimi</h1>
            <a href="sekreter_ekle.php" class="bg-blue-500 text-white py-2 px-4 rounded mb-4 inline-block">Sekreter Ekle</a>
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">Ad Soyad</th>
                        <th class="border px-4 py-2">E-posta</th>
                        <th class="border px-4 py-2">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['ad_soyad']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($row['email']) ?></td>
                            <td class="border px-4 py-2">
                                <a href="sekreter_duzenle.php?kul_id=<?= $row['kul_id'] ?>" class="text-blue-500">Düzenle</a>
                                <a href="sekreter_sil.php?kul_id=<?= $row['kul_id'] ?>" class="text-red-500 ml-4">Sil</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <footer class="bg-gray-800 text-white text-center py-4" id="footer">
            <p>&copy; 2024 Hastane Yönetim Sistemi. Tüm hakları saklıdır.</p>
        </footer>
    </div>
</div>
