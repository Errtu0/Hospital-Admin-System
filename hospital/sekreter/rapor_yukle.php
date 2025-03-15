<?php
require '../vt.php';
session_start();


if (!isset($_SESSION['doktor_id'])) {
    exit("Doktor kimliği bulunamadı.");
}
$doktor_id = $_SESSION['doktor_id'];


$sql_hasta = "SELECT * FROM hasta";
$result_hasta = $conn->query($sql_hasta);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hasta_id = $_POST['hasta_id'];
    $tablo_turu = $_POST['tablo_turu'];
    $rapor_dosyasi = $_FILES['rapor']['name'];
    $dosya_temizle = $_FILES['rapor']['tmp_name'];

    $dosya_yolu = "../raporlar/" . basename($rapor_dosyasi);

    if (move_uploaded_file($dosya_temizle, $dosya_yolu)) {
        if ($tablo_turu === "raporlar") {
            
            $sql = "INSERT INTO raporlar (hasta_id, rapor_dosyasi, doktor_id, rapor_olusturma_tarihi) 
                    VALUES (?, ?, ?, NOW())";
        } elseif ($tablo_turu === "muayene_gecmisi") {
       
            $sql = "INSERT INTO muayene_gecmisi (hasta_id, doktor_id, muayene_tarihi, rapor_dosyasi) 
                    VALUES (?, ?, NOW(), ?)";
        } else {
            die("Geçersiz tablo seçimi.");
        }

        $stmt = $conn->prepare($sql);
        if ($tablo_turu === "raporlar") {
            $stmt->bind_param("isi", $hasta_id, $dosya_yolu, $doktor_id);
        } elseif ($tablo_turu === "muayene_gecmisi") {
            $stmt->bind_param("iis", $hasta_id, $doktor_id, $dosya_yolu);
        }

        if ($stmt->execute()) {
            echo "<p class='text-green-600'>Dosya başarıyla yüklendi.</p>";
            header("Location: h/hasta_yonetimi.php");
            
        } else {
            echo "<p class='text-red-600'>Bir hata oluştu: " . $conn->error . "</p>";
        }
    } else {
        echo "<p class='text-red-600'>Dosya yüklenirken bir sorun oluştu.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapor Yükleme</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Rapor Yükleme</h1>
    
    <form method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded-lg shadow-md">
        <div class="mb-4">
            <label for="hasta_id" class="block text-sm font-medium text-gray-700">Hasta Seçin</label>
            <select id="hasta_id" name="hasta_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Hasta Seçin</option>
                <?php while ($hasta = $result_hasta->fetch_assoc()): ?>
                    <option value="<?= $hasta['hasta_id'] ?>"><?= htmlspecialchars($hasta['ad_soyad']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-4">
            <label for="tablo_turu" class="block text-sm font-medium text-gray-700">Tablo Seçin</label>
            <select id="tablo_turu" name="tablo_turu" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Tablo Seçin</option>
                <option value="raporlar">Raporlar</option>
                <option value="muayene_gecmisi">Muayene Geçmişi</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="rapor" class="block text-sm font-medium text-gray-700">Rapor Dosyasını Yükleyin</label>
            <input type="file" id="rapor" name="rapor" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Rapor Yükle</button>
    </form>
</div>

</body>
</html>
