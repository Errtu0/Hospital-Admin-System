<?php
require '../../vt.php';


if (!isset($_GET['rapor_id']) || empty($_GET['rapor_id'])) {
    die("Rapor ID parametresi eksik.");
}

$rapor_id = $_GET['rapor_id'];


$sql_rapor = "SELECT * FROM raporlar WHERE rapor_id = ?";
$stmt_rapor = $conn->prepare($sql_rapor);
$stmt_rapor->bind_param("i", $rapor_id);
$stmt_rapor->execute();
$result_rapor = $stmt_rapor->get_result();
$rapor = $result_rapor->fetch_assoc();

if (!$rapor) {
    die("Bu rapor bulunamadı.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rapor_tarihi = $_POST['rapor_tarihi'];
    $rapor_dosyasi = $_FILES['rapor_dosyasi'];

 
    if ($rapor_dosyasi['error'] === UPLOAD_ERR_OK) {
        $uploads_dir = '../../raporlar';
        $file_name = basename($rapor_dosyasi['name']);
        
        
        $unique_file_name = $rapor['doktor_id'] . "_" . $file_name;
        $target_path = $uploads_dir . '/' . $unique_file_name;

        if (move_uploaded_file($rapor_dosyasi['tmp_name'], $target_path)) {
            $rapor_dosyasi_path = '../raporlar/' . $unique_file_name;
        } else {
            die("Dosya yüklenirken bir hata oluştu.");
        }
    } else {
        
        $rapor_dosyasi_path = $rapor['rapor_dosyasi'];
    }

    
    $doktor_id = $rapor['doktor_id'];
    $sql_guncelle = "UPDATE raporlar SET rapor_olusturma_tarihi = ?, doktor_id = ?, rapor_dosyasi = ? WHERE rapor_id = ?";
    $stmt_guncelle = $conn->prepare($sql_guncelle);
    $stmt_guncelle->bind_param("sssi", $rapor_tarihi, $doktor_id, $rapor_dosyasi_path, $rapor_id);

    if ($stmt_guncelle->execute()) {
        echo "Rapor başarıyla güncellendi.";
    } else {
        echo "Rapor güncellenirken bir hata oluştu.";
    }

    
    header("Location: rapor_duzenle.php?hasta_id=" . $rapor['hasta_id']);
    exit;
}
?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapor Güncelle</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Rapor Güncelle</h1>

    <form action="" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md">
        <div class="mb-4">
            <label for="rapor_tarihi" class="block text-gray-700 font-bold mb-2">Rapor Tarihi</label>
            <input type="date" id="rapor_tarihi" name="rapor_tarihi" value="<?= htmlspecialchars($rapor['rapor_olusturma_tarihi']) ?>" class="w-full border border-gray-300 p-2 rounded">
        </div>

        <div class="mb-4">
            <label for="rapor_dosyasi" class="block text-gray-700 font-bold mb-2">Rapor Dosyası</label>
            <input type="file" id="rapor_dosyasi" name="rapor_dosyasi" class="w-full border border-gray-300 p-2 rounded">
            <p class="text-sm text-gray-500 mt-2">Mevcut Dosya: <a href="../../raporlar/<?= htmlspecialchars(basename($rapor['rapor_dosyasi'])) ?>" target="_blank" class="text-blue-500">Raporu Görüntüle</a></p>
        </div>

        <div>
            <button type="submit" class="bg-blue-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Kaydet</button>
            <a href="rapor_duzenle.php?hasta_id=<?= $rapor['hasta_id'] ?>" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">İptal</a>
        </div>
    </form>
</div>
</body>
</html>
