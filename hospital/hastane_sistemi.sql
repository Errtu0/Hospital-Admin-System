-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 23 Ara 2024, 16:12:20
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `hastane_sistemi`
--
CREATE DATABASE IF NOT EXISTS `hastane_sistemi` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `hastane_sistemi`;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `doktor_yogun_saatler`
--

CREATE TABLE `doktor_yogun_saatler` (
  `id` int(11) NOT NULL,
  `doktor_id` int(11) NOT NULL,
  `baslangic_saati` time NOT NULL,
  `bitis_saati` time NOT NULL,
  `tarih` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `doktor_yogun_saatler`
--

INSERT INTO `doktor_yogun_saatler` (`id`, `doktor_id`, `baslangic_saati`, `bitis_saati`, `tarih`) VALUES
(18, 1, '10:00:00', '15:00:00', '2025-01-01'),
(19, 1, '10:00:00', '15:00:00', '2025-01-03'),
(20, 16, '09:00:00', '13:00:00', '2024-12-30'),
(21, 16, '09:00:00', '13:00:00', '2025-01-01');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hasta`
--

CREATE TABLE `hasta` (
  `hasta_id` int(11) NOT NULL,
  `ad_soyad` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `dogum_tarihi` date DEFAULT NULL,
  `cinsiyet` enum('Erkek','Kadın','Diğer') DEFAULT NULL,
  `olusturulma_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `hasta`
--

INSERT INTO `hasta` (`hasta_id`, `ad_soyad`, `email`, `telefon`, `dogum_tarihi`, `cinsiyet`, `olusturulma_tarihi`) VALUES
(6, 'Hasta1', 'h1@hasta.com', '1111111111', NULL, NULL, '2024-12-23 14:43:51'),
(7, 'Hasta2', 'h2@hasta.com', '2222222222', NULL, NULL, '2024-12-23 14:55:08'),
(8, 'Hasta3', 'h3@hasta.com', '3333333333', NULL, NULL, '2024-12-23 14:57:56'),
(9, 'Hasta4', 'h4@hasta.com', '4444444444', NULL, NULL, '2024-12-23 14:59:25');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanicilar`
--

CREATE TABLE `kullanicilar` (
  `kul_id` int(11) NOT NULL,
  `ad_soyad` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `rol` enum('doktor','sekreter') NOT NULL,
  `olusturulma_tarihi` timestamp NOT NULL DEFAULT current_timestamp(),
  `doktor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `kullanicilar`
--

INSERT INTO `kullanicilar` (`kul_id`, `ad_soyad`, `email`, `sifre`, `rol`, `olusturulma_tarihi`, `doktor_id`) VALUES
(1, 'Test1', 'test1@doktor.com', '$2y$10$QorLYMZKSeHMjxcUXyRILe/K0nyKWyN6KjtWmeWwIxbs5e2qNa/Am', 'doktor', '2024-12-18 18:15:48', 1),
(16, 'Test2', 'test2@doktor.com', '$2y$10$y0OJbBPISw2SQ3u3i/doteuph60raFaIdR/1AENfg.6nqCCPhrJPm', 'doktor', '2024-12-23 14:31:39', 16),
(18, 'test1', 'test1@sekreter.com', '$2y$10$nv3batRAkzWGEmwyzc03ZugnzNlv.9IY8NlxNehpumLR6GhpEJdxi', 'sekreter', '2024-12-23 14:38:14', 1),
(19, 'test2', 'test2@sekreter.com', '$2y$10$YEpFdBcyFSsEiWfU2eto1Oo7cF1EkdmzfECUP0D7wXtezeSzH6wWO', 'sekreter', '2024-12-23 14:38:26', 1),
(20, 'test3', 'test3@sekreter.com', '$2y$10$yugN9/9CJYw1ZcUgCGlnIey1PwBcLB34F6gXezYAEfouFh/d1HOcm', 'sekreter', '2024-12-23 14:42:22', 16),
(21, 'test4', 'test4@sekreter.com', '$2y$10$V0Dco6Oi/FUXdUAWFvdwBuzwTVsn6IE.HYxDQxJDH6CMpK7aVipfa', 'sekreter', '2024-12-23 14:42:33', 16);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `muayene_gecmisi`
--

CREATE TABLE `muayene_gecmisi` (
  `muayene_id` int(11) NOT NULL,
  `hasta_id` int(11) NOT NULL,
  `doktor_id` int(11) NOT NULL,
  `muayene_tarihi` timestamp NOT NULL DEFAULT current_timestamp(),
  `rapor_dosyasi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `muayene_gecmisi`
--

INSERT INTO `muayene_gecmisi` (`muayene_id`, `hasta_id`, `doktor_id`, `muayene_tarihi`, `rapor_dosyasi`) VALUES
(8, 6, 1, '2024-12-23 14:53:48', '../raporlar/muayene.pdf'),
(9, 7, 1, '2024-12-23 14:55:30', '../raporlar/muayene.pdf'),
(10, 8, 16, '2024-12-23 14:58:41', '../raporlar/muayene.pdf'),
(11, 9, 16, '2024-12-23 14:59:55', '../raporlar/muayene.pdf');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `randevular`
--

CREATE TABLE `randevular` (
  `randevu_id` int(11) NOT NULL,
  `doktor_id` int(11) DEFAULT NULL,
  `randevu_tarihi` datetime NOT NULL,
  `hasta_id` int(11) DEFAULT NULL,
  `durum` enum('beklemede','onayli','iptal') DEFAULT 'beklemede',
  `bitis_saati` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `randevular`
--

INSERT INTO `randevular` (`randevu_id`, `doktor_id`, `randevu_tarihi`, `hasta_id`, `durum`, `bitis_saati`) VALUES
(38, 1, '2025-01-01 00:00:00', 6, 'beklemede', '09:00:00'),
(39, 1, '2024-12-29 00:00:00', 6, 'beklemede', '16:00:00'),
(40, 1, '2024-12-25 00:00:00', 7, 'beklemede', '16:00:00'),
(41, 1, '2025-01-14 00:00:00', 7, 'beklemede', '08:00:00'),
(42, 16, '2025-01-21 00:00:00', 8, 'beklemede', '06:00:00'),
(43, 16, '2025-01-02 00:00:00', 8, 'beklemede', '19:00:00'),
(44, 16, '2025-01-03 00:00:00', 9, 'beklemede', '17:00:00'),
(45, 16, '2025-01-08 00:00:00', 9, 'beklemede', '15:00:00');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `raporlar`
--

CREATE TABLE `raporlar` (
  `rapor_id` int(11) NOT NULL,
  `hasta_id` int(11) NOT NULL,
  `doktor_id` int(11) NOT NULL,
  `rapor_dosyasi` varchar(255) DEFAULT NULL,
  `rapor_olusturma_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `raporlar`
--

INSERT INTO `raporlar` (`rapor_id`, `hasta_id`, `doktor_id`, `rapor_dosyasi`, `rapor_olusturma_tarihi`) VALUES
(12, 6, 1, '../raporlar/rapor.pdf', '2024-12-23 14:53:40'),
(13, 7, 1, '../raporlar/rapor.pdf', '2024-12-23 14:55:20'),
(14, 8, 16, '../raporlar/rapor.pdf', '2024-12-23 14:58:36'),
(17, 9, 16, '../raporlar/rapor.pdf', '2024-12-23 15:01:38');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `doktor_yogun_saatler`
--
ALTER TABLE `doktor_yogun_saatler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doktor_id` (`doktor_id`);

--
-- Tablo için indeksler `hasta`
--
ALTER TABLE `hasta`
  ADD PRIMARY KEY (`hasta_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Tablo için indeksler `kullanicilar`
--
ALTER TABLE `kullanicilar`
  ADD PRIMARY KEY (`kul_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_doktor` (`doktor_id`);

--
-- Tablo için indeksler `muayene_gecmisi`
--
ALTER TABLE `muayene_gecmisi`
  ADD PRIMARY KEY (`muayene_id`),
  ADD KEY `hasta_id` (`hasta_id`),
  ADD KEY `doktor_id` (`doktor_id`);

--
-- Tablo için indeksler `randevular`
--
ALTER TABLE `randevular`
  ADD PRIMARY KEY (`randevu_id`),
  ADD KEY `randevular_ibfk_1` (`doktor_id`),
  ADD KEY `randevular_ibfk_2` (`hasta_id`);

--
-- Tablo için indeksler `raporlar`
--
ALTER TABLE `raporlar`
  ADD PRIMARY KEY (`rapor_id`),
  ADD KEY `hasta_id` (`hasta_id`),
  ADD KEY `doktor_id` (`doktor_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `doktor_yogun_saatler`
--
ALTER TABLE `doktor_yogun_saatler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Tablo için AUTO_INCREMENT değeri `hasta`
--
ALTER TABLE `hasta`
  MODIFY `hasta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `kullanicilar`
--
ALTER TABLE `kullanicilar`
  MODIFY `kul_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Tablo için AUTO_INCREMENT değeri `muayene_gecmisi`
--
ALTER TABLE `muayene_gecmisi`
  MODIFY `muayene_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Tablo için AUTO_INCREMENT değeri `randevular`
--
ALTER TABLE `randevular`
  MODIFY `randevu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Tablo için AUTO_INCREMENT değeri `raporlar`
--
ALTER TABLE `raporlar`
  MODIFY `rapor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `doktor_yogun_saatler`
--
ALTER TABLE `doktor_yogun_saatler`
  ADD CONSTRAINT `doktor_yogun_saatler_ibfk_1` FOREIGN KEY (`doktor_id`) REFERENCES `kullanicilar` (`kul_id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `kullanicilar`
--
ALTER TABLE `kullanicilar`
  ADD CONSTRAINT `fk_doktor` FOREIGN KEY (`doktor_id`) REFERENCES `kullanicilar` (`kul_id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `muayene_gecmisi`
--
ALTER TABLE `muayene_gecmisi`
  ADD CONSTRAINT `muayene_gecmisi_ibfk_1` FOREIGN KEY (`hasta_id`) REFERENCES `hasta` (`hasta_id`),
  ADD CONSTRAINT `muayene_gecmisi_ibfk_2` FOREIGN KEY (`doktor_id`) REFERENCES `kullanicilar` (`kul_id`);

--
-- Tablo kısıtlamaları `randevular`
--
ALTER TABLE `randevular`
  ADD CONSTRAINT `randevular_ibfk_1` FOREIGN KEY (`doktor_id`) REFERENCES `kullanicilar` (`kul_id`),
  ADD CONSTRAINT `randevular_ibfk_2` FOREIGN KEY (`hasta_id`) REFERENCES `hasta` (`hasta_id`);

--
-- Tablo kısıtlamaları `raporlar`
--
ALTER TABLE `raporlar`
  ADD CONSTRAINT `raporlar_ibfk_1` FOREIGN KEY (`hasta_id`) REFERENCES `hasta` (`hasta_id`),
  ADD CONSTRAINT `raporlar_ibfk_2` FOREIGN KEY (`doktor_id`) REFERENCES `kullanicilar` (`kul_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
