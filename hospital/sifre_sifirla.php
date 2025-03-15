<?php
session_start();
require 'vt.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];


    $sql = "SELECT * FROM kullanicilar WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      echo "<p class='text-green-600'>Şifre sıfırlama linki e-posta adresinize gönderildi.</p>";
    } else {
        echo "<p class='text-red-600'>Bu e-posta adresi ile kayıtlı bir kullanıcı bulunamadı.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifre Sıfırlama</title>
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
            <h1 class="text-2xl font-bold text-center mb-4">Şifremi Unuttum</h1>
            <form method="POST">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">E-posta</label>
                    <input type="email" id="email" name="email" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Şifre Sıfırlama Linki Gönder</button>
            </form>
        </div>
    </div>
</body>
</html>
