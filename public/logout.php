<?php
// public/logout.php
session_start(); // Oturumu başlat
session_unset(); // Tüm oturum değişkenlerini temizle
session_destroy(); // Oturumu yok et

// Giriş sayfasına yönlendir
header("Location: login.php");
exit();
?>