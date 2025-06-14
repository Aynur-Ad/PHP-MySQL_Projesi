<?php
// classes/Tarif.php

class Tarif {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // ... (Mevcut metotlar: tarifEkle, getTarifler, getTarifById, updateTarif, deleteTarif, getTarifMalzemeler, getTumMalzemeler)

    /**
     * Yeni bir tarif ekler.
     * @param int $kullanici_id Tarife ekleyen kullanıcının ID'si
     * @param string $ad Tarif adı
     * @param string $aciklama Tarifin hazırlanışı ve talimatları
     * @param string $hazirlik_suresi Hazırlık süresi
     * @param string $pisirme_suresi Pişirme süresi
     * @param int $servis_sayisi Kaç kişilik olduğu
     * @param array $malzemeler Tarifin malzemeleri (id, miktar)
     * @return bool İşlem başarılıysa true, değilse false
     */
    public function tarifEkle($kullanici_id, $ad, $aciklama, $hazirlik_suresi, $pisirme_suresi, $servis_sayisi, $malzemeler) {
        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("INSERT INTO tarifler (kullanici_id, ad, aciklama, hazirlik_suresi, pisirme_suresi, servis_sayisi) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$kullanici_id, $ad, $aciklama, $hazirlik_suresi, $pisirme_suresi, $servis_sayisi]);
            $tarif_id = $this->pdo->lastInsertId();

            if ($tarif_id && !empty($malzemeler)) {
                $stmt_malzeme = $this->pdo->prepare("INSERT INTO tarif_malzemeler (tarif_id, malzeme_id, miktar) VALUES (?, ?, ?)");
                foreach ($malzemeler as $malzeme) {
                    $stmt_malzeme->execute([$tarif_id, $malzeme['malzeme_id'], $malzeme['miktar']]);
                }
            }

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Tarif ekleme hatası: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Tüm tarifleri getirir.
     * @return array Tarifler listesi
     */
    public function getTarifler() {
        try {
            $stmt = $this->pdo->query("SELECT t.*, u.username FROM tarifler t JOIN users u ON t.kullanici_id = u.id ORDER BY t.created_at DESC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Tarifleri getirme hatası: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Belirli bir tarifi ID'sine göre getirir.
     * @param int $id Tarif ID'si
     * @return array|false Tarif bilgileri, bulunamazsa false
     */
    public function getTarifById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT t.*, u.username FROM tarifler t JOIN users u ON t.kullanici_id = u.id WHERE t.id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Tarif getirme hatası: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Bir tarifi günceller.
     * @param int $id Güncellenecek tarifin ID'si
     * @param int $kullanici_id Tarife ekleyen kullanıcının ID'si
     * @param string $ad Tarif adı
     * @param string $aciklama Tarifin hazırlanışı ve talimatları
     * @param string $hazirlik_suresi Hazırlık süresi
     * @param string $pisirme_suresi Pişirme süresi
     * @param int $servis_sayisi Kaç kişilik olduğu
     * @param array $malzemeler Tarifin yeni malzemeleri (id, miktar)
     * @return bool İşlem başarılıysa true, değilse false
     */
    public function updateTarif($id, $kullanici_id, $ad, $aciklama, $hazirlik_suresi, $pisirme_suresi, $servis_sayisi, $malzemeler) {
        try {
            $this->pdo->beginTransaction();

            // Tarif bilgilerini güncelle
            $stmt = $this->pdo->prepare("UPDATE tarifler SET kullanici_id = ?, ad = ?, aciklama = ?, hazirlik_suresi = ?, pisirme_suresi = ?, servis_sayisi = ? WHERE id = ?");
            $stmt->execute([$kullanici_id, $ad, $aciklama, $hazirlik_suresi, $pisirme_suresi, $servis_sayisi, $id]);

            // Mevcut malzemeleri sil
            $stmt_delete_malzemeler = $this->pdo->prepare("DELETE FROM tarif_malzemeler WHERE tarif_id = ?");
            $stmt_delete_malzemeler->execute([$id]);

            // Yeni malzemeleri ekle
            if (!empty($malzemeler)) {
                $stmt_malzeme = $this->pdo->prepare("INSERT INTO tarif_malzemeler (tarif_id, malzeme_id, miktar) VALUES (?, ?, ?)");
                foreach ($malzemeler as $malzeme) {
                    $stmt_malzeme->execute([$id, $malzeme['malzeme_id'], $malzeme['miktar']]);
                }
            }

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Tarif güncelleme hatası: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Bir tarifi siler.
     * @param int $id Silinecek tarifin ID'si
     * @return bool İşlem başarılıysa true, değilse false
     */
    public function deleteTarif($id) {
        try {
            // Foreign key (ON DELETE CASCADE) tarif_malzemeler tablosundaki ilişkili kayıtları otomatik siler.
            $stmt = $this->pdo->prepare("DELETE FROM tarifler WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Tarif silme hatası: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Bir tarife ait malzemeleri ve miktar bilgilerini getirir.
     * @param int $tarif_id Tarif ID'si
     * @return array Tarifin malzemeleri listesi
     */
    public function getTarifMalzemeler($tarif_id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT tm.miktar, m.id as malzeme_id, m.ad as malzeme_ad, m.birim
                FROM tarif_malzemeler tm
                JOIN malzemeler m ON tm.malzeme_id = m.id
                WHERE tm.tarif_id = ?
            ");
            $stmt->execute([$tarif_id]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Tarif malzemelerini getirme hatası: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Tüm malzemeleri (tarifte kullanılmak üzere) getirir.
     * @return array Malzemeler listesi (id, ad, birim)
     */
    public function getTumMalzemeler() {
        try {
            $stmt = $this->pdo->query("SELECT id, ad, birim FROM malzemeler ORDER BY ad ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Tüm malzemeleri getirme hatası: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Bir tarifteki malzemeleri envanterden düşer.
     * @param int $tarif_id Tüketilecek tarifin ID'si
     * @param int $kac_adet_yapildi Kaç porsiyon/adet tarifin yapıldığı (servis sayısı ile çarpılacak)
     * @return bool İşlem başarılıysa true, değilse false
     */
    public function malzemeleriEnvanterdenDus($tarif_id, $kac_adet_yapildi = 1) {
        try {
            $this->pdo->beginTransaction();

            // 1. Tarifin bilgilerini ve servis sayısını al
            $tarif = $this->getTarifById($tarif_id);
            if (!$tarif) {
                $this->pdo->rollBack();
                return false; // Tarif bulunamadı
            }

            $tarif_servis_sayisi = (float)$tarif['servis_sayisi'];
            if ($tarif_servis_sayisi <= 0) { // Servis sayısı 0 veya eksi ise hata ver
                $this->pdo->rollBack();
                error_log("Tarif ID: " . $tarif_id . " için geçersiz servis sayısı: " . $tarif_servis_sayisi);
                return false;
            }


            // 2. Tarife ait malzemeleri ve miktarlarını al
            $tarif_malzemeleri = $this->getTarifMalzemeler($tarif_id);

            // 3. Her bir malzeme için stoktan düşme işlemini yap
            foreach ($tarif_malzemeleri as $malzeme) {
                $malzeme_id = $malzeme['malzeme_id'];
                $tarif_miktar = (float)$malzeme['miktar']; // Tarifteki malzeme miktarı

                // Eğer tarif miktarı "adet" gibi sayısal bir değerse, doğrudan kullanabiliriz.
                // "2 su bardağı" gibi metinsel miktarları burada işlemeniz gerekiyorsa,
                // daha karmaşık bir parsing (çözümleme) mantığına ihtiyacınız olur.
                // Şimdilik, miktar sütununda sayısal bir değer bekliyoruz (örn: 200 gr, 5 adet).
                // Miktarın sadece sayısal kısmını alalım.
                preg_match('/\d+(\.\d+)?/', $malzeme['miktar'], $matches);
                $tarif_miktar = isset($matches[0]) ? (float)$matches[0] : 0;

                if ($tarif_miktar <= 0) {
                    error_log("Malzeme ID: " . $malzeme_id . " için geçersiz tarif miktarı: " . $malzeme['miktar']);
                    continue; // Geçersiz miktar için devam et
                }

                // Tüketilecek toplam miktar = (tarifteki miktar / tarifin servis sayısı) * yapılan_adet
                // Bu, 1 porsiyonluk miktarı bulup sonra yapılan adetle çarpar.
                $dusulecek_miktar = ($tarif_miktar / $tarif_servis_sayisi) * $kac_adet_yapildi;


                // Malzemenin güncel stok miktarını al (Malzeme sınıfından da çekebiliriz, ama burada basitçe direkt DB'den)
                $stmt_current_stock = $this->pdo->prepare("SELECT stok_miktari FROM malzemeler WHERE id = ?");
                $stmt_current_stock->execute([$malzeme_id]);
                $current_stock = $stmt_current_stock->fetchColumn();

                if ($current_stock === false) {
                    $this->pdo->rollBack();
                    error_log("Malzeme ID: " . $malzeme_id . " bulunamadı.");
                    return false; // Malzeme bulunamadı, işlemi geri al
                }

                $new_stock = $current_stock - $dusulecek_miktar;

                if ($new_stock < 0) {
                    // Yeterli stok yok, işlemi geri al
                    $this->pdo->rollBack();
                    error_log("Malzeme ID: " . $malzeme_id . " için yetersiz stok. Gerekli: " . $dusulecek_miktar . ", Mevcut: " . $current_stock);
                    return false; // Yetersiz stok
                }

                // Stok miktarını güncelle
                $stmt_update_stock = $this->pdo->prepare("UPDATE malzemeler SET stok_miktari = ? WHERE id = ?");
                $stmt_update_stock->execute([$new_stock, $malzeme_id]);
            }

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Envanterden düşme hatası: " . $e->getMessage());
            return false;
        }
    }
}
?>