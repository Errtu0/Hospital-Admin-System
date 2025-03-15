<?php
require '../vt.php';
session_start();

if (!isset($_SESSION['doktor_id'])) {
    die("Lütfen giriş yapın.");
}

$doktor_id = $_SESSION['doktor_id']; 


$today = date('Y-m-d');
$dates = [];
for ($i = 0; $i < 30; $i++) {
    $dates[] = date('Y-m-d', strtotime("$today +$i days"));
}


$selected_date = isset($_GET['date']) ? $_GET['date'] : $today;

$sql_randevular = "SELECT r.randevu_tarihi, r.bitis_saati, h.ad_soyad AS hasta_ad_soyad
                  FROM randevular r
                  JOIN hasta h ON r.hasta_id = h.hasta_id
                  WHERE r.doktor_id = ? AND r.randevu_tarihi = ? 
                  ORDER BY r.randevu_tarihi ASC";
$stmt = $conn->prepare($sql_randevular);
$stmt->bind_param("is", $doktor_id, $selected_date);
$stmt->execute();
$randevu_result = $stmt->get_result();


$sql_yogun_saatler = "SELECT id, baslangic_saati, bitis_saati 
                       FROM doktor_yogun_saatler
                       WHERE doktor_id = ? AND tarih = ?"; 
$stmt_yogun = $conn->prepare($sql_yogun_saatler);
$stmt_yogun->bind_param("is", $doktor_id, $selected_date);
$stmt_yogun->execute();
$yogun_saatler_result = $stmt_yogun->get_result();


if (isset($_POST['ekle_yogun_saat'])) {
    $baslangic_saati = $_POST['baslangic_saati'];
    $bitis_saati = $_POST['bitis_saati'];
    $tarih = $_GET['date'];  

    $sql_insert = "INSERT INTO doktor_yogun_saatler (doktor_id, baslangic_saati, bitis_saati, tarih) 
                   VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("isss", $doktor_id, $baslangic_saati, $bitis_saati, $tarih);
    $stmt->execute();

    header("Location: randevular.php?date=$tarih");
    exit();
}


if (isset($_GET['delete_yogun_id'])) {
    $delete_yogun_id = $_GET['delete_yogun_id'];
    $selected_date = $_GET['date'];

    $sql_delete = "DELETE FROM doktor_yogun_saatler WHERE id = ? AND doktor_id = ? AND tarih = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("iis", $delete_yogun_id, $doktor_id, $selected_date);
    $stmt->execute();

    header("Location: randevular.php?date=$selected_date");
    exit();
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
                <h2 class="text-2xl font-bold"><a href="doktor_anasayfa.php" class="block px-4 py-2 hover:bg-gray-700">Doktor Paneli</a></h2>
            </div>
            <nav class="mt-4">
                <a href="s/sekreter_listesi.php" class="block px-4 py-2 hover:bg-gray-700">Sekreter Yönetimi</a>
                <a href="h/hasta_listesi.php" class="block px-4 py-2 hover:bg-gray-700">Hasta Yönetimi</a>
                <a href="randevular.php" class="block px-4 py-2 hover:bg-gray-700">Randevularım</a>
                <a href="../cikis.php" class="block px-4 py-2 text-red-400 hover:text-red-600">Çıkış Yap</a>
            </nav>
        </aside>

        <main class="flex-grow bg-gray-100 p-8">
            <h1 class="text-3xl font-bold mb-6">Randevular</h1>

            <div class="grid grid-cols-7 gap-4 mb-6">
                <?php foreach ($dates as $date): ?>
                    <a href="randevular.php?date=<?php echo $date; ?>" 
                       class="p-2 text-center border rounded hover:bg-blue-200 <?php echo ($date == $selected_date) ? 'bg-blue-500 text-white' : ''; ?>">
                        <?php echo date('d', strtotime($date)); ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="mb-4">
                <h2 class="text-xl font-semibold">Randevular - <?php echo date('d-m-Y', strtotime($selected_date)); ?></h2>
                <?php if ($randevu_result->num_rows > 0): ?>
                    <table class="table-auto w-full mt-4 border-collapse">
                        <thead>
                            <tr>
                                <th class="p-2 border-b">Hasta</th>
                                <th class="p-2 border-b">Saat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($randevu = $randevu_result->fetch_assoc()): ?>
                                <tr>
                                    <td class="p-2" align="center"><?= htmlspecialchars($randevu['hasta_ad_soyad']) ?></td>
                                    <td class="p-2" align="center"><?= $randevu['bitis_saati'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Bu tarihte herhangi bir randevu bulunmamaktadır.</p>
                <?php endif; ?>

                <h2 class="text-xl font-semibold mt-6">Yoğun Saatler - <?php echo date('d-m-Y', strtotime($selected_date)); ?></h2>
                <?php if ($yogun_saatler_result->num_rows > 0): ?>
                    <table class="table-auto w-full mt-4 border-collapse">
                        <thead>
                            <tr>
                                <th class="p-2 border-b">Başlangıç Saati</th>
                                <th class="p-2 border-b">Bitiş Saati</th>
                                <th class="p-2 border-b">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($yogun_saat = $yogun_saatler_result->fetch_assoc()): ?>
                                <tr>
                                    <td class="p-2" align="center"><?= $yogun_saat['baslangic_saati'] ?></td>
                                    <td class="p-2" align="center"><?= $yogun_saat['bitis_saati'] ?></td>
                                    <td class="p-2" align="center">
                                        <a href="randevular.php?date=<?php echo $selected_date; ?>&delete_yogun_id=<?php echo $yogun_saat['id']; ?>" 
                                        class="text-red-500 hover:text-red-700">Sil</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Bu tarihte herhangi bir yoğun saat bulunmamaktadır.</p>
                <?php endif; ?>

                
                <h3 class="text-xl font-semibold mt-6">Yoğun Saat Ekle</h3>
                <form method="POST" action="randevular.php?date=<?php echo $selected_date; ?>" class="mt-4">
                    <div class="mb-4">
                        <label for="baslangic_saati" class="block text-sm font-medium">Başlangıç Saati</label>
                        <input type="time" name="baslangic_saati" required class="w-full p-2 border rounded">
                    </div>
                    <div class="mb-4">
                        <label for="bitis_saati" class="block text-sm font-medium">Bitiş Saati</label>
                        <input type="time" name="bitis_saati" required class="w-full p-2 border rounded">
                    </div>
                    <button type="submit" name="ekle_yogun_saat" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Ekle</button>
                </form>
            </div>
        </main>
    </div>

    <footer class="bg-gray-800 text-white text-center py-4" id="footer">
        <p>&copy; 2024 Hastane Yönetim Sistemi</p>
    </footer>
</div>

</body>
</html>