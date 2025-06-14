<?php
// config/database.php - Örnek (örnek değerlerle)

// Veritabanı bağlantı ayarlarını kendi canlı ortam bilgilerinle değiştirmelisin.
// Bu dosya gerçek veritabanı bilgilerini içermemelidir.

define('DB_HOST', 'localhost');
define('DB_NAME', 'restoran_yonetimi');
define('DB_USER', 'kullanici_adi');
define('DB_PASS', 'sifre');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
