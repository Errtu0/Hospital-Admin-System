<?php
require '../../vt.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad_soyad = $_POST['ad_soyad'];
    $telefon = $_POST['telefon'];
    $email = $_POST['email'];

   
    $sql = "SELECT * FROM hasta WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<p class='text-red-600'>Bu e-posta adresi zaten kayıtlı.</p>";
    } else {
     
        $sql = "INSERT INTO hasta (ad_soyad, telefon, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $ad_soyad, $telefon, $email);

        if ($stmt->execute()) {
            echo "<p class='text-green-600'>Hasta başarıyla eklendi.</p>";
            header("Location: hasta_yonetimi.php");
            exit;
        } else {
            echo "<p class='text-red-600'>Bir hata oluştu: " . $conn->error . "</p>";
        }
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
    <h1 class="text-3xl font-bold mb-6">Yeni Hasta Ekle</h1>
    <form method="POST">
        <div class="mb-4">
            <label for="ad_soyad" class="block text-sm font-medium text-gray-700">Ad Soyad</label>
            <input type="text" id="ad_soyad" name="ad_soyad" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="mb-4">
            <label for="telefon" class="block text-sm font-medium text-gray-700">Telefon</label>
            <input type="text" id="telefon" name="telefon" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">E-posta</label>
            <input type="email" id="email" name="email" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Hasta Ekle</button>
    </form>
</div>
</body>
</html>
