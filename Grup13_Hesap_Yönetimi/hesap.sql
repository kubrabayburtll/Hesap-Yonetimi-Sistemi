-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 19 Ara 2024, 01:53:11
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
-- Veritabanı: `hesap`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `katkilar`
--

CREATE TABLE `katkilar` (
  `id` int(11) NOT NULL,
  `personel_id` int(11) NOT NULL,
  `katkı_miktari` decimal(10,2) NOT NULL,
  `katkı_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `katkilar`
--

INSERT INTO `katkilar` (`id`, `personel_id`, `katkı_miktari`, `katkı_tarihi`) VALUES
(18, 30, 1000.00, '2024-12-18 22:13:15');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `organizasyon`
--

CREATE TABLE `organizasyon` (
  `id` int(11) NOT NULL,
  `adi` varchar(255) NOT NULL,
  `açıklama` text NOT NULL,
  `tarih` date NOT NULL,
  `butce` int(11) NOT NULL,
  `yetkili_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `organizasyon`
--

INSERT INTO `organizasyon` (`id`, `adi`, `açıklama`, `tarih`, `butce`, `yetkili_id`) VALUES
(3, 'parti', 'eğlence', '2024-01-17', 120000, 1),
(13, 'piknik', 'piknik skswsoıafoıe', '2024-12-27', 15000, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `personeller`
--

CREATE TABLE `personeller` (
  `id` int(11) NOT NULL,
  `ad` varchar(255) NOT NULL,
  `soyad` varchar(255) NOT NULL,
  `departman` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `kayit_tarihi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `personeller`
--

INSERT INTO `personeller` (`id`, `ad`, `soyad`, `departman`, `email`, `sifre`, `kayit_tarihi`) VALUES
(18, 'edanur', 'terzi', 'veri', 'eda@eda', '$2y$10$tNrwNNI4rqkKswT1URrqO.1g6iZ./4fE8yQTWEU79Fy4QUFxEEIQm', '2024-12-11 23:32:26'),
(30, 'MELİKE', 'KARAMAN', 'Pazarlama', 'melikekaraman61@gmail.com', '$2y$10$plZza5Oj21J88iEHRrzsku.pztOPhIhbniONB3ED4ronGk/iWgqcm', '2024-12-19 00:12:00');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yetkili`
--

CREATE TABLE `yetkili` (
  `id` int(11) NOT NULL,
  `ad_soyad` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `personel_id` int(11) NOT NULL,
  `organizasyon_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `yetkili`
--

INSERT INTO `yetkili` (`id`, `ad_soyad`, `email`, `sifre`, `personel_id`, `organizasyon_id`) VALUES
(1, 'Ali Veli', 'aliveli@gmail.com', 'ktu123', 24, 7);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `katkilar`
--
ALTER TABLE `katkilar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `personel_id` (`personel_id`);

--
-- Tablo için indeksler `organizasyon`
--
ALTER TABLE `organizasyon`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `personeller`
--
ALTER TABLE `personeller`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Tablo için indeksler `yetkili`
--
ALTER TABLE `yetkili`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `katkilar`
--
ALTER TABLE `katkilar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Tablo için AUTO_INCREMENT değeri `organizasyon`
--
ALTER TABLE `organizasyon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Tablo için AUTO_INCREMENT değeri `personeller`
--
ALTER TABLE `personeller`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Tablo için AUTO_INCREMENT değeri `yetkili`
--
ALTER TABLE `yetkili`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
