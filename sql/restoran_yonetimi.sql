-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 13 Haz 2025, 15:24:34
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
-- Veritabanı: `restoran_yonetimi`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `malzemeler`
--

CREATE TABLE `malzemeler` (
  `id` int(11) NOT NULL,
  `ad` varchar(255) NOT NULL,
  `birim` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `stok_miktari` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `malzemeler`
--

INSERT INTO `malzemeler` (`id`, `ad`, `birim`, `created_at`, `stok_miktari`) VALUES
(1, 'Börülce', 'gr', '2025-06-12 13:46:50', 350.00),
(2, 'Yufka', 'adet', '2025-06-12 13:47:02', 2.50),
(3, 'Zeytinyağı', 'ml', '2025-06-12 13:47:18', 350.00);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tarifler`
--

CREATE TABLE `tarifler` (
  `id` int(11) NOT NULL,
  `kullanici_id` int(11) NOT NULL,
  `ad` varchar(255) NOT NULL,
  `aciklama` text DEFAULT NULL,
  `hazirlik_suresi` varchar(50) DEFAULT NULL,
  `pisirme_suresi` varchar(50) DEFAULT NULL,
  `servis_sayisi` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `tarifler`
--

INSERT INTO `tarifler` (`id`, `kullanici_id`, `ad`, `aciklama`, `hazirlik_suresi`, `pisirme_suresi`, `servis_sayisi`, `created_at`) VALUES
(1, 1, 'Gevrekli\'m', 'Yağ, börülce ve yufkayı kavur, ye.', '10 dk', '30 dk', 2, '2025-06-12 13:48:31');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tarif_malzemeler`
--

CREATE TABLE `tarif_malzemeler` (
  `tarif_id` int(11) NOT NULL,
  `malzeme_id` int(11) NOT NULL,
  `miktar` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `tarif_malzemeler`
--

INSERT INTO `tarif_malzemeler` (`tarif_id`, `malzeme_id`, `miktar`) VALUES
(1, 1, '100'),
(1, 2, '1'),
(1, 3, '100');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'Aynur_01', '$2y$10$KAdbwieYonzjRp9/N7etcu.cObMtzXO9Ultrt99WFZ6ZYExdQZb4q', '2025-06-12 13:37:45');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `malzemeler`
--
ALTER TABLE `malzemeler`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ad` (`ad`);

--
-- Tablo için indeksler `tarifler`
--
ALTER TABLE `tarifler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `tarif_malzemeler`
--
ALTER TABLE `tarif_malzemeler`
  ADD PRIMARY KEY (`tarif_id`,`malzeme_id`),
  ADD KEY `malzeme_id` (`malzeme_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `malzemeler`
--
ALTER TABLE `malzemeler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `tarifler`
--
ALTER TABLE `tarifler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `tarifler`
--
ALTER TABLE `tarifler`
  ADD CONSTRAINT `tarifler_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `tarif_malzemeler`
--
ALTER TABLE `tarif_malzemeler`
  ADD CONSTRAINT `tarif_malzemeler_ibfk_1` FOREIGN KEY (`tarif_id`) REFERENCES `tarifler` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tarif_malzemeler_ibfk_2` FOREIGN KEY (`malzeme_id`) REFERENCES `malzemeler` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
