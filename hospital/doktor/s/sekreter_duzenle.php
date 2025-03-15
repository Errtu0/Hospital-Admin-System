<?php
require '../../vt.php';
session_start(); 


if (!isset($_SESSION['kul_id'])) {
    header("Location: ../../giris.php"); 
    exit;
}


$doktor_id = $_SESSION['kul_id']; 


$id = $_GET['kul_id'];


$sql = "SELECT * FROM kullanicilar WHERE kul_id = ? AND rol = 'sekreter' AND doktor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $doktor_id); 
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows === 0) {
    echo "Sekreter bulunamadı veya bu sekreterin düzenleme yetkiniz yok.";
    exit;
}


$sekreter = $result->fetch_assoc();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad_soyad = $_POST['ad_soyad'];
    $email = $_POST['email'];
    $sifre = password_hash($_POST['sifre'], PASSWORD_DEFAULT);

    
    $sql = "UPDATE kullanicilar SET ad_soyad = ?, email = ?, sifre = ? WHERE kul_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $ad_soyad, $email, $sifre, $id);

    
    if ($stmt->execute()) {
        echo "<p class='text-green-600'>Sekreter başarıyla güncellendi.</p>";
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

<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Sekreter Güncelle</h1>
    <form method="POST">
        <div class="mb-4">
            <label for="ad_soyad" class="block text-sm font-medium text-gray-700">Ad Soyad</label>
            <input type="text" id="ad_soyad" name="ad_soyad" value="<?= htmlspecialchars($sekreter['ad_soyad']) ?>" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">E-posta</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($sekreter['email']) ?>" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="mb-4">
            <label for="sifre" class="block text-sm font-medium text-gray-700">Şifre</label>
            <input type="password" id="sifre" name="sifre" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Sekreter Güncelle</button>
    </form>
</div>

<footer class="bg-gray-800 text-white text-center py-4" id="footer">
    <p>&copy; 2024 Hastane Yönetim Sistemi. Tüm hakları saklıdır.</p>
</footer>
</body>
</html>
