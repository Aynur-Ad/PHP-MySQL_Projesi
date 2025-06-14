<?php
// public/tarif_sil.php
session_start();

// Kullanıcı oturum açmamışsa veya yetkisizse yönlendir
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Tarif.php';

$tarifObj = new Tarif($pdo);

$tarif_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$kullanici_id = $_SESSION['user_id'];

if ($tarif_id > 0) {
    if ($tarifObj->tarifSil($tarif_id, $kullanici_id)) {
        header("Location: tarifler.php?success=" . urlencode("Tarif başarıyla silindi."));
        exit();
    } else {
        header("Location: tarifler.php?error=" . urlencode("Tarif silinirken bir hata oluştu veya bu tarifi silmeye yetkiniz yok."));
        exit();
    }
} else {
    header("Location: tarifler.php?error=" . urlencode("Geçersiz tarif ID'si."));
    exit();
}
?>