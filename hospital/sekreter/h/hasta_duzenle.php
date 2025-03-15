<?php
require '../../vt.php';


$hasta_id = $_GET['hasta_id'] ?? null;

if ($hasta_id === null) {
    die("Hasta ID bulunamadı!");
}


$sql = "SELECT * FROM hasta WHERE hasta_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $hasta_id);
$stmt->execute();
$result = $stmt->get_result();
$hasta = $result->fetch_assoc();

if (!$hasta) {
    die("Hasta bulunamadı!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad_soyad = $_POST['ad_soyad'];
    $telefon = $_POST['telefon'];
    $email = $_POST['email'];

    $sql = "SELECT * FROM hasta WHERE email = ? AND hasta_id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $email, $hasta_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<p class='text-red-600'>Bu e-posta adresi başka bir hasta tarafından kullanılmaktadır.</p>";
    } else {
        
        $sql = "UPDATE hasta SET ad_soyad = ?, telefon = ?, email = ? WHERE hasta_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $ad_soyad, $telefon, $email, $hasta_id);

        if ($stmt->execute()) {
            echo "<p class='text-green-600'>Hasta başarıyla güncellendi.</p>";
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
    <h1 class="text-3xl font-bold mb-6">Hasta Düzenle</h1>
    
    <form method="POST" class="bg-white p-4 rounded-lg shadow-md">
        <div class="mb-4">
            <label for="ad_soyad" class="block text-sm font-medium text-gray-700">Ad Soyad</label>
            <input type="text" id="ad_soyad" name="ad_soyad" value="<?= htmlspecialchars($hasta['ad_soyad']) ?>" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="mb-4">
            <label for="telefon" class="block text-sm font-medium text-gray-700">Telefon</label>
            <input type="text" id="telefon" name="telefon" value="<?= htmlspecialchars($hasta['telefon']) ?>" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">E-posta</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($hasta['email']) ?>" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Güncelle</button>
    </form>
</div>

</body>
</html>
