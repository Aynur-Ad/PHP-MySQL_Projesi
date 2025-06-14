<?php
// public/login.php
session_start(); // Oturumu başlat

require_once __DIR__ . '/../config/database.php'; // Veritabanı bağlantısı
require_once __DIR__ . '/../classes/User.php';     // User sınıfı

$userObj = new User($pdo);
$error_message = '';

// Zaten oturum açmışsa ana sayfaya yönlendir
if ($userObj->isLoggedIn()) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error_message = "Lütfen kullanıcı adı ve şifrenizi giriniz.";
    } else {
        if ($userObj->login($username, $password)) {
            // Başarılı giriş, ana sayfaya yönlendir
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Kullanıcı adı veya şifre hatalı.";
        }
    }
}

// Bootstrap CDN
$bootstrap_cdn = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap - Restoran Mutfak Yönetim Sistemi</title>
    <link href="<?php echo $bootstrap_cdn; ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            background-color: #fff;
        }
        .form-label {
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center mb-4">Giriş Yap</h2>

        <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Kullanıcı Adı</label>
                <input type="text" class="form-control" id="username" name="username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Şifre</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="d-grid gap-2 mb-3">
                <button type="submit" class="btn btn-primary btn-lg">Giriş Yap</button>
            </div>
            <p class="text-center">Hesabınız yok mu? <a href="register.php">Kayıt Ol</a></p>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>