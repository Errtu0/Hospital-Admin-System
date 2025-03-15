<?php
session_start();
require '../../vt.php';


if (!isset($_SESSION['doktor_id'])) {
    header("Location: ../../giris.php"); 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad_soyad = $_POST['ad_soyad'];
    $email = $_POST['email'];
    $sifre = password_hash($_POST['sifre'], PASSWORD_DEFAULT); 
    $rol = 'sekreter';

  
    $doktor_id = $_SESSION['doktor_id']; 

   
    $sql = "INSERT INTO kullanicilar (ad_soyad, email, sifre, rol, doktor_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $ad_soyad, $email, $sifre, $rol, $doktor_id);

    if ($stmt->execute()) {
        echo "<p class='text-green-600'>Sekreter başarıyla eklendi.</p>";
        header("Location: sekreter_listesi.php");
        exit;
    } else {
        echo "<p class='text-red-600'>Bir hata oluştu: " . $conn->error . "</p>";
    }
}
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
                <h2 class="text-2xl font-bold"><a href="../doktor_anasayfa.php" class="block px-4 py-2 hover:bg-gray-700">Doktor Paneli</h2></a>
            </div>
            <nav class="mt-4">
                <a href="sekreter_listesi.php" class="block px-4 py-2 hover:bg-gray-700">Sekreter Yönetimi</a>
                <a href="../h/hasta_listesi.php" class="block px-4 py-2 hover:bg-gray-700">Hasta Geçmişi</a>
                <a href="../randevular.php" class="block px-4 py-2 hover:bg-gray-700">Randevularım</a>
                <a href="../../cikis.php" class="block px-4 py-2 text-red-400 hover:text-red-600">Çıkış Yap</a>
            </nav>
        </aside>

        <main class="flex-grow bg-gray-100 p-8">
            <h1 class="text-3xl font-bold mb-6">Sekreter Ekle</h1>
            <form method="POST">
                <div class="mb-4">
                    <label for="ad_soyad" class="block text-sm font-medium text-gray-700">Ad Soyad</label>
                    <input type="text" id="ad_soyad" name="ad_soyad" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">E-posta</label>
                    <input type="email" id="email" name="email" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="sifre" class="block text-sm font-medium text-gray-700">Şifre</label>
                    <input type="password" id="sifre" name="sifre" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Sekreter Ekle</button>
            </form>
        </main>
    </div>

    <footer class="bg-gray-800 text-white text-center py-4" id="footer">
        <p>&copy; 2024 Hastane Yönetim Sistemi. Tüm hakları saklıdır.</p>
    </footer>
</div>
</body>
</html>
