<?php
// public/malzeme_sil.php
session_start();

// Kullanıcı oturum açmamışsa yönlendir
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Malzeme.php'; // Malzeme sınıfını dahil et

$malzemeObj = new Malzeme($pdo); // Malzeme nesnesini oluştur

$malzeme_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($malzeme_id > 0) {
    if ($malzemeObj->deleteMalzeme($malzeme_id)) {
        header("Location: malzemeler.php?success=" . urlencode("Malzeme başarıyla silindi."));
        exit();
    } else {
        header("Location: malzemeler.php?error=" . urlencode("Malzeme silinirken bir hata oluştu."));
        exit();
    }
} else {
    header("Location: malzemeler.php?error=" . urlencode("Geçersiz malzeme ID'si."));
    exit();
}
?>