<?php
require 'vt.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad_soyad = $_POST['ad_soyad'];
    $email = $_POST['email'];
    $sifre = password_hash($_POST['sifre'], PASSWORD_DEFAULT); 
    $rol = 'doktor'; 


    $sql_check = "SELECT * FROM kullanicilar WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "<p class='text-center text-red-600'>Bu e-posta adresi zaten kayıtlı.</p>";
    } else {
       
        $sql_insert_user = "INSERT INTO kullanicilar (ad_soyad, email, sifre, rol) VALUES (?, ?, ?, ?)";
        $stmt_user = $conn->prepare($sql_insert_user);
        $stmt_user->bind_param("ssss", $ad_soyad, $email, $sifre, $rol);

        if ($stmt_user->execute()) {
           
            $kul_id = $conn->insert_id;

           
            $sql_update_doktor_id = "UPDATE kullanicilar SET doktor_id = ? WHERE kul_id = ?";
            $stmt_update = $conn->prepare($sql_update_doktor_id);
            $stmt_update->bind_param("ii", $kul_id, $kul_id);

            if ($stmt_update->execute()) {
                echo "<p class='text-center text-green-600'>Kayıt başarıyla tamamlandı! Giriş yapabilirsiniz.</p>";
                header("Refresh: 2; url=giris.php");
                exit;
            } else {
                echo "<p class='text-center text-red-600'>Doktor ID güncellenirken bir hata oluştu: " . $conn->error . "</p>";
            }
        } else {
            echo "<p class='text-center text-red-600'>Kullanıcı kaydı başarısız: " . $conn->error . "</p>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hastane Sistemi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <header class="bg-blue-600 text-white py-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Hastane Sistemi</h1>
            <nav>
                <a href="kayit.php" class="text-white hover:text-gray-200 px-4">Kayıt Ol</a>
                <a href="giris.php" class="text-white hover:text-gray-200 px-4">Giriş Yap</a>
            </nav>
        </div>
</header>

<div class="flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-md rounded-lg p-6 w-full max-w-sm">
        <h1 class="text-2xl font-bold text-center mb-4">Doktor Kayıt Ol</h1>
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
            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Kayıt Ol</button>
        </form>
        <p class="text-sm text-center mt-4">
            Zaten hesabınız var mı? <a href="giris.php" class="text-blue-500 hover:underline">Giriş Yap</a>
        </p>
    </div>
</div>
