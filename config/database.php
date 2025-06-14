<?php
// config/database.php

// Canlı Ortam Veritabanı Ayarları 
define('DB_HOST', 'localhost'); // FTP Host IP'si yerine genellikle 'localhost' kullanılır
define('DB_NAME', 'dbstorage22360859008'); // Database Name
define('DB_USER', 'dbusr22360859008');     // Database User
define('DB_PASS', 'ocMlU6Xg7V3m');       // Database Password

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>