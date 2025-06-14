<?php
// includes/header.php
session_start();

// Global veritabanı bağlantısı ve sınıf nesneleri
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../classes/Tarif.php';
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Malzeme.php'; // BU SATIRI EKLEYİN

$tarifObj = new Tarif($pdo);
$userObj = new User($pdo);
$malzemeObj = new Malzeme($pdo); // BU SATIRI EKLEYİN

// Kullanıcı oturum açmamışsa giriş sayfasına yönlendir
if (!$userObj->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Bootstrap CDN (Zaten mevcut)
$bootstrap_cdn = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css';
$bootstrap_js_cdn = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran Mutfak Yönetimi Sistemi</title>
    <link href="<?php echo $bootstrap_cdn; ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: 700;
        }
        .container {
            margin-top: 30px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .btn {
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Restoran Mutfak Sistemi</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="tarifler.php">Tarifler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tarif_ekle.php">Tarif Ekle</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="malzemeler.php">Malzemeler</a> </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link text-white">Hoş geldiniz, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light" href="logout.php">Çıkış Yap</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">