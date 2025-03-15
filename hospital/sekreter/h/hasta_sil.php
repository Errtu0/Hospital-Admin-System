<?php
require '../../vt.php';

$hasta_id = $_GET['hasta_id'] ?? null;

if ($hasta_id === null) {
    die("Hasta ID bulunamadı!");
}


$sql_delete_raporlar = "DELETE FROM raporlar WHERE hasta_id = ?";
$stmt_raporlar = $conn->prepare($sql_delete_raporlar);
$stmt_raporlar->bind_param("i", $hasta_id);
$stmt_raporlar->execute();


$sql_delete_muayene = "DELETE FROM muayene_gecmisi WHERE hasta_id = ?";
$stmt_muayene = $conn->prepare($sql_delete_muayene);
$stmt_muayene->bind_param("i", $hasta_id);
$stmt_muayene->execute();


$sql_delete_hasta = "DELETE FROM hasta WHERE hasta_id = ?";
$stmt_hasta = $conn->prepare($sql_delete_hasta);
$stmt_hasta->bind_param("i", $hasta_id);

if ($stmt_hasta->execute()) {
    header("Location: hasta_yonetimi.php");
    exit;
} else {
    echo "<p class='text-red-600'>Bir hata oluştu: " . $conn->error . "</p>";
}
?>
