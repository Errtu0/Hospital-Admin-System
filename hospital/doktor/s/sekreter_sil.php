<?php
require '../../vt.php';
session_start(); 

if (!isset($_SESSION['kul_id'])) {
    header("Location: ../../giris.php"); 
    exit;
}

$doktor_id = $_SESSION['kul_id']; 


if (isset($_GET['kul_id'])) {
    $id = $_GET['kul_id'];

   
    $sql = "SELECT * FROM kullanicilar WHERE kul_id = ? AND rol = 'sekreter' AND doktor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $doktor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    
    if ($result->num_rows > 0) {
        
        $sql_delete = "DELETE FROM kullanicilar WHERE kul_id = ? AND rol = 'sekreter' AND doktor_id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("ii", $id, $doktor_id);

        if ($stmt_delete->execute()) {
            echo "<p class='text-green-600'>Sekreter başarıyla silindi.</p>";
            header("Location: sekreter_listesi.php");
            exit;
        } else {
            echo "<p class='text-red-600'>Bir hata oluştu: " . $conn->error . "</p>";
        }
    } else {
        echo "<p class='text-red-600'>Bu sekreteri silmeye yetkiniz yok veya sekreter bulunamadı.</p>";
    }
} else {
    echo "<p class='text-red-600'>Geçersiz istek. Sekreter ID'si eksik.</p>";
}
?>
