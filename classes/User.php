<?php
// classes/User.php

class User {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Yeni bir kullanıcı kaydı yapar.
     * @param string $username Kullanıcı adı
     * @param string $password Şifre (hash'lenecek)
     * @return bool İşlem başarılıysa true, değilse false
     */
    public function register($username, $password) {
        // Kullanıcı adının benzersizliğini kontrol et
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetchColumn() > 0) {
            return false; // Kullanıcı adı zaten mevcut
        }

        // Şifreyi hash'le
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $this->pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            return $stmt->execute([$username, $hashed_password]);
        } catch (PDOException $e) {
            error_log("Kullanıcı kayıt hatası: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kullanıcı girişi yapar ve oturumu başlatır.
     * @param string $username Kullanıcı adı
     * @param string $password Girilen şifre
     * @return bool İşlem başarılıysa true (oturum başlatılır), değilse false
     */
    public function login($username, $password) {
        $stmt = $this->pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Şifre doğru, oturumu başlat
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            return true;
        }
        return false;
    }

    /**
     * Kullanıcının oturum açmış olup olmadığını kontrol eder.
     * @return bool Oturum açıksa true, değilse false
     */
    public function isLoggedIn() {
        // session_start() zaten header.php'de çağrılıyor olmalı
        return isset($_SESSION['user_id']);
    }

    /**
     * Kullanıcı oturumunu kapatır.
     */
    public function logout() {
        session_start(); // Oturum açıksa
        session_unset(); // Tüm oturum değişkenlerini temizle
        session_destroy(); // Oturumu yok et
    }
}
?>