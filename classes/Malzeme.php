<?php
// classes/Malzeme.php

class Malzeme {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Yeni bir malzeme ekler.
     * @param string $ad Malzeme adı
     * @param string $birim Malzeme birimi (örn: gr, kg, adet, litre)
     * @param float $stok_miktari Malzemenin başlangıç stok miktarı
     * @return bool İşlem başarılıysa true, değilse false
     */
    public function malzemeEkle($ad, $birim, $stok_miktari = 0) { // Varsayılan değer 0
        // Malzeme adının benzersizliğini kontrol et
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM malzemeler WHERE ad = ?");
        $stmt->execute([$ad]);
        if ($stmt->fetchColumn() > 0) {
            return false; // Malzeme adı zaten mevcut
        }

        try {
            $stmt = $this->pdo->prepare("INSERT INTO malzemeler (ad, birim, stok_miktari) VALUES (?, ?, ?)");
            return $stmt->execute([$ad, $birim, $stok_miktari]);
        } catch (PDOException $e) {
            error_log("Malzeme ekleme hatası: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Tüm malzemeleri getirir.
     * @return array Malzemeler listesi
     */
    public function getMalzemeler() {
        try {
            // stok_miktari sütununu da çekiyoruz
            $stmt = $this->pdo->query("SELECT id, ad, birim, stok_miktari, created_at FROM malzemeler ORDER BY ad ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Malzemeleri getirme hatası: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Belirli bir malzemeyi ID'sine göre getirir.
     * @param int $id Malzeme ID'si
     * @return array|false Malzeme bilgileri, bulunamazsa false
     */
    public function getMalzemeById($id) {
        try {
            // stok_miktari sütununu da çekiyoruz
            $stmt = $this->pdo->prepare("SELECT id, ad, birim, stok_miktari, created_at FROM malzemeler WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Malzeme getirme hatası: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Bir malzemeyi günceller.
     * @param int $id Güncellenecek malzemenin ID'si
     * @param string $ad Yeni malzeme adı
     * @param string $birim Yeni malzeme birimi
     * @param float $stok_miktari Yeni stok miktarı
     * @return bool İşlem başarılıysa true, değilse false
     */
    public function updateMalzeme($id, $ad, $birim, $stok_miktari) { // stok_miktari parametresi eklendi
        // Güncellenecek malzemenin adını kontrol et (kendi hariç diğerleriyle çakışmasın)
        $stmt_check = $this->pdo->prepare("SELECT COUNT(*) FROM malzemeler WHERE ad = ? AND id != ?");
        $stmt_check->execute([$ad, $id]);
        if ($stmt_check->fetchColumn() > 0) {
            return false; // Başka bir malzeme aynı isimde zaten mevcut
        }

        try {
            $stmt = $this->pdo->prepare("UPDATE malzemeler SET ad = ?, birim = ?, stok_miktari = ? WHERE id = ?"); // SQL sorgusu güncellendi
            return $stmt->execute([$ad, $birim, $stok_miktari, $id]); // Parametreler güncellendi
        } catch (PDOException $e) {
            error_log("Malzeme güncelleme hatası: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Bir malzemeyi siler.
     * @param int $id Silinecek malzemenin ID'si
     * @return bool İşlem başarılıysa true, değilse false
     */
    public function deleteMalzeme($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM malzemeler WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Malzeme silme hatası: " . $e->getMessage());
            return false;
        }
    }
}
?>