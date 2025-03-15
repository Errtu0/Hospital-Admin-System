<?php
session_start();
require '../vt.php';

if (!isset($_SESSION['kul_id'])) {
    header("Location: ../giris.php");
    exit();
}

$doktor_id = $_SESSION['doktor_id'];


$sql = "SELECT * FROM randevular WHERE doktor_id = ? AND durum = 'beklemede'";
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
            <h2 class="text-2xl font-bold"><a href="sekreter_anasayfa.php" class="block px-4 py-2 hover:bg-gray-700">Sekreter Paneli</h2></a>
            </div>
            <nav class="mt-4">
                <a href="h/hasta_yonetimi.php" class="block px-4 py-2 hover:bg-gray-700">Hasta Yönetimi</a>
                <a href="r/randevu_planlama.php" class="block px-4 py-2 hover:bg-gray-700">Randevu Planlama</a>
                <a href="rapor_yukle.php" class="block px-4 py-2 hover:bg-gray-700">Raporlar</a>
                <a href="../cikis.php" class="block px-4 py-2 text-red-400 hover:text-red-600">Çıkış Yap</a>
            </nav>
        </aside>

        <main class="flex-grow bg-gray-100 p-8">
            <h1 class="text-3xl font-bold mb-4">Hoş Geldiniz, Sekreter!</h1>
            <p class="text-gray-700">Bu, sekreterlere özel kontrol panelidir. Randevuları yönetebilir ve doktorun zaman çizelgesini planlayabilirsiniz.</p>

        </main>
    </div>

    <footer class="bg-gray-800 text-white text-center py-4" id="footer">
        <p>&copy; 2024 Hastane Yönetim Sistemi. Tüm hakları saklıdır.</p>
    </footer>
</div>
</body>
</html>
