<?php
require '../../vt.php';

if (!isset($_GET['rapor_id']) || empty($_GET['rapor_id'])) {
    exit("Rapor ID eksik.");
}

$rapor_id = $_GET['rapor_id'];

$sql_sil = "DELETE FROM raporlar WHERE rapor_id = ?";
$stmt_sil = $conn->prepare($sql_sil);
$stmt_sil->bind_param("i", $rapor_id);

if ($stmt_sil->execute()) {
    echo "Rapor başarıyla silindi.";
} else {
    echo "Rapor silinirken bir hata oluştu.";
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>
