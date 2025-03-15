<?php
$host = 'localhost';
$kullanici = 'root';
$sifre = '';
$veritabani = 'hastane_sistemi';


$conn = new mysqli($host, $kullanici, $sifre, $veritabani);


if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}
?>





