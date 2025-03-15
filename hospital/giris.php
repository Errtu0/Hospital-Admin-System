<?php
session_start();
require 'vt.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $sifre = $_POST['sifre'];

    $sql = "SELECT * FROM kullanicilar WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($sifre, $user['sifre'])) {
            $_SESSION['kul_id'] = $user['kul_id'];

            if ($user['rol'] == 'doktor') {
             
                $_SESSION['doktor_id'] = $user['kul_id']; 
                header("Location: doktor/doktor_anasayfa.php");
            } elseif ($user['rol'] == 'sekreter') {
             
                if (!empty($user['doktor_id'])) {
                    $_SESSION['doktor_id'] = $user['doktor_id'];
                    header("Location: sekreter/sekreter_anasayfa.php");
                } else {
                    echo "<p class='text-red-600'>Bir doktora atanmadınız!</p>";
                    exit();
                }
            } else {
                echo "<p class='text-red-600'>Geçersiz kullanıcı rolü!</p>";
                exit();
            }
            exit();
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
            <h1 class="text-2xl font-bold text-center mb-4">Giriş Yap</h1>
            <form method="POST">
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
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Giriş Yap</button>
            </form>

            <div class="mt-4 text-center">
                <a href="sifre_sifirla.php" class="text-blue-600 hover:text-blue-800">Şifremi unuttum</a>
            </div>
        </div>
    </div>
</body>
</html>

