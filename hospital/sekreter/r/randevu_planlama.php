<?php
require '../../vt.php';
session_start();

$doktor_id = $_SESSION['doktor_id']; 

$today = date('Y-m-d');
$dates = [];
for ($i = 0; $i < 30; $i++) {
    $dates[] = date('Y-m-d', strtotime("$today +$i days"));
}


$sql_randevular = "SELECT r.randevu_tarihi, r.bitis_saati, h.ad_soyad, r.randevu_id
                  FROM randevular r
                  JOIN hasta h ON r.hasta_id = h.hasta_id
                  WHERE r.doktor_id = ? AND r.randevu_tarihi >= CURDATE() ORDER BY r.randevu_tarihi";
$stmt = $conn->prepare($sql_randevular);
$stmt->bind_param("i", $doktor_id);
$stmt->execute();
$randevular_result = $stmt->get_result();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'add') {
        
        $randevu_tarihi = $_POST['randevu_tarihi'];
        $hasta_id = $_POST['hasta_id']; 
        $bitis_saati = $_POST['bitis_saati'];


        $sql_check = "SELECT hasta_id FROM hasta WHERE hasta_id = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("i", $hasta_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            
            $sql_insert = "INSERT INTO randevular (doktor_id, hasta_id, randevu_tarihi, bitis_saati) 
                           VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("iiss", $doktor_id, $hasta_id, $randevu_tarihi, $bitis_saati);
            $stmt_insert->execute();

            header("Location: randevu_planlama.php?date=" . $randevu_tarihi); 
            exit();
        } else {
            
            echo "Hata: Hasta bulunamadı!";
        }
    } elseif ($_POST['action'] == 'delete') {
       
        $randevu_id = $_POST['randevu_id'];

        $sql_delete = "DELETE FROM randevular WHERE randevu_id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $randevu_id);
        $stmt_delete->execute();

        header("Location: randevu_planlama.php?date=" . $_POST['randevu_tarihi']);
        exit();
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
                <h2 class="text-2xl font-bold"><a href="../sekreter_anasayfa.php" class="block px-4 py-2 hover:bg-gray-700">Sekreter Paneli</a></h2>
            </div>
            <nav class="mt-4">
                <a href="../h/hasta_yonetimi.php" class="block px-4 py-2 hover:bg-gray-700">Hasta Yönetimi</a>
                <a href="randevu_planlama.php" class="block px-4 py-2 hover:bg-gray-700">Randevu Planlama</a>
                <a href="../rapor_yukle.php" class="block px-4 py-2 hover:bg-gray-700">Raporlar</a>
                <a href="../../cikis.php" class="block px-4 py-2 text-red-400 hover:text-red-600">Çıkış Yap</a>
            </nav>
        </aside>

        <main class="flex-grow bg-gray-100 p-8">
            <h1 class="text-3xl font-bold mb-6">Randevu Planlama</h1>
            
            <div class="mb-4">
                <h2 class="text-xl font-semibold">30 Günlük Takvim</h2>
                <div class="grid grid-cols-7 gap-4 mb-6">
                    <?php foreach ($dates as $date): ?>
                        <a href="randevu_planlama.php?date=<?php echo $date; ?>" 
                        class="p-2 text-center border rounded hover:bg-blue-200 <?php echo (isset($_GET['date']) && $_GET['date'] == $date) ? 'bg-blue-500 text-white' : ''; ?>">
                            <?php echo date('d', strtotime($date)); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php
            if (isset($_GET['date'])) {
                $selected_date = $_GET['date'];
                
          
                $sql_selected_date_busy_hours = "SELECT baslangic_saati, bitis_saati 
                                                 FROM doktor_yogun_saatler 
                                                 WHERE doktor_id = ? AND tarih = ? ORDER BY baslangic_saati";
                $stmt_selected_date_busy = $conn->prepare($sql_selected_date_busy_hours);
                $stmt_selected_date_busy->bind_param("is", $doktor_id, $selected_date);
                $stmt_selected_date_busy->execute();
                $busy_hours_result = $stmt_selected_date_busy->get_result();
                
               
                $sql_selected_date_appointments = "SELECT r.randevu_id, r.randevu_tarihi, r.bitis_saati, h.ad_soyad
                                                   FROM randevular r
                                                   JOIN hasta h ON r.hasta_id = h.hasta_id
                                                   WHERE r.doktor_id = ? AND r.randevu_tarihi = ?
                                                   ORDER BY r.bitis_saati";
                $stmt_appointments = $conn->prepare($sql_selected_date_appointments);
                $stmt_appointments->bind_param("is", $doktor_id, $selected_date);
                $stmt_appointments->execute();
                $appointments_result = $stmt_appointments->get_result();
            ?>
            
            <h2 class="text-xl font-semibold mb-4">Randevular - <?php echo date('d-m-Y', strtotime($selected_date)); ?></h2>
            
          
            <h3 class="text-lg font-semibold mb-4">Meşgul Saatler</h3>
            <?php if ($busy_hours_result->num_rows > 0): ?>
                <ul>
                    <?php while ($busy_hour = $busy_hours_result->fetch_assoc()): ?>
                        <li><?= $busy_hour['baslangic_saati']; ?> - <?= $busy_hour['bitis_saati']; ?></li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Bu tarihte herhangi bir meşgul saat bulunmamaktadır.</p>
            <?php endif; ?>

            
            <h3 class="text-lg font-semibold mt-6">Mevcut Randevular</h3>
            <?php if ($appointments_result->num_rows > 0): ?>
                <ul>
                    <?php while ($appointment = $appointments_result->fetch_assoc()): ?>
                        <li>
                            <form method="POST" action="randevu_planlama.php" class="flex justify-between items-center">
                                <input type="hidden" name="randevu_tarihi" value="<?= $selected_date ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="randevu_id" value="<?= $appointment['randevu_id'] ?>">
                                <?= $appointment['ad_soyad']; ?> - <?= $appointment['bitis_saati']; ?>
                                <button type="submit" class="bg-red-500 text-white px-4 py-1 rounded hover:bg-red-600">Sil</button>
                            </form>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Bu tarihte herhangi bir randevu bulunmamaktadır.</p>
            <?php endif; ?>

            <h3 class="text-lg font-semibold mt-6">Yeni Randevu Ekle</h3>
            <form method="POST" action="randevu_planlama.php">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="randevu_tarihi" value="<?= $selected_date ?>">

                <div class="mb-4">
                    <label for="hasta_id" class="block text-sm font-medium text-gray-700">Hasta Seçin</label>
                    <select id="hasta_id" name="hasta_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Hasta Seçin</option>
                        <?php
                        $sql_hasta = "SELECT hasta_id, ad_soyad FROM hasta";
                        $result_hasta = $conn->query($sql_hasta);
                        while ($hasta = $result_hasta->fetch_assoc()) {
                            echo "<option value='" . $hasta['hasta_id'] . "'>" . htmlspecialchars($hasta['ad_soyad']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="bitis_saati" class="block text-sm font-medium text-gray-700">Randevu Saati</label>
                    <input type="time" id="bitis_saati" name="bitis_saati" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>

                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Randevu Ekle</button>
            </form>

            <?php } ?>
        </main>
    </div>

    <footer id="footer" class="bg-gray-800 text-white text-center">
        <p>© 2024 Hastane Yönetim Sistemi</p>
    </footer>
</div>

</body>
</html>
