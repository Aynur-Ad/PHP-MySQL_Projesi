<?php
// public/tarif_detay.php
require_once __DIR__ . '/../includes/header.php'; // header.php içinde $tarifObj oluşturuluyor

$tarif_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$tarif = $tarifObj->getTarifById($tarif_id);
$tarif_malzemeler = $tarifObj->getTarifMalzemeler($tarif_id);

$error_message = '';
$success_message = '';

// Tarif bulunamadıysa yönlendir
if (!$tarif) {
    header("Location: tarifler.php?error=" . urlencode("Tarif bulunamadı."));
    exit();
}

// "Tarifi Tüket" işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tuket_tarif'])) {
    $kac_adet_yapildi = isset($_POST['kac_adet_yapildi']) ? (int)$_POST['kac_adet_yapildi'] : 1;

    if ($kac_adet_yapildi <= 0) {
        $error_message = "Tüketilecek adet sıfırdan büyük olmalıdır.";
    } else {
        if ($tarifObj->malzemeleriEnvanterdenDus($tarif_id, $kac_adet_yapildi)) {
            $success_message = $kac_adet_yapildi . " adet '" . htmlspecialchars($tarif['ad']) . "' tarifinin malzemeleri envanterden düşüldü.";
        } else {
            // Hata mesajını daha spesifik yapabiliriz
            $error_message = "Tarif malzemeleri envanterden düşülürken bir hata oluştu veya yetersiz stok var. Lütfen envanteri kontrol edin.";
            // Gerçek projede, yetersiz stoğun hangi malzeme için olduğunu da göstermek iyi olur.
        }
    }
}

// URL'den gelen success/error mesajlarını göster
if (isset($_GET['success'])) {
    $success_message = htmlspecialchars($_GET['success']);
}
if (isset($_GET['error'])) {
    $error_message = htmlspecialchars($_GET['error']);
}
?>

<h1 class="mb-4">Tarif Detayı: <?php echo htmlspecialchars($tarif['ad']); ?></h1>

<?php if ($success_message): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $success_message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($error_message): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $error_message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card p-4 mb-4">
    <div class="row">
        <div class="col-md-8">
            <h3>Açıklama/Hazırlanışı</h3>
            <p><?php echo nl2br(htmlspecialchars($tarif['aciklama'])); ?></p>
        </div>
        <div class="col-md-4">
            <h4>Tarif Bilgileri</h4>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Hazırlık Süresi: <strong><?php echo htmlspecialchars($tarif['hazirlik_suresi']); ?></strong></li>
                <li class="list-group-item">Pişirme Süresi: <strong><?php echo htmlspecialchars($tarif['pisirme_suresi']); ?></strong></li>
                <li class="list-group-item">Servis Sayısı: <strong><?php echo htmlspecialchars($tarif['servis_sayisi']); ?></strong></li>
                <li class="list-group-item">Ekleyen Kullanıcı: <strong><?php echo htmlspecialchars($tarif['username']); ?></strong></li>
                <li class="list-group-item">Ekleme Tarihi: <strong><?php echo date('d.m.Y H:i', strtotime($tarif['created_at'])); ?></strong></li>
            </ul>
        </div>
    </div>

    <h4 class="mt-4">Gerekli Malzemeler</h4>
    <?php if (empty($tarif_malzemeler)): ?>
        <div class="alert alert-warning" role="alert">
            Bu tarife henüz malzeme eklenmemiş.
        </div>
    <?php else: ?>
        <ul class="list-group mb-3">
            <?php foreach ($tarif_malzemeler as $malzeme): ?>
                <li class="list-group-item">
                    <?php echo htmlspecialchars($malzeme['miktar']); ?>
                    <?php echo htmlspecialchars($malzeme['birim']); ?>
                    <?php echo htmlspecialchars($malzeme['malzeme_ad']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <hr>
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <a href="tarif_duzenle.php?id=<?php echo $tarif['id']; ?>" class="btn btn-warning me-2">Tarifi Düzenle</a>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTarifModal">Tarifi Sil</button>
        </div>
        <form action="tarif_detay.php?id=<?php echo $tarif_id; ?>" method="POST" class="d-flex align-items-center">
            <label for="kac_adet_yapildi" class="form-label me-2 mb-0">Kaç Adet Yapıldı?</label>
            <input type="number" class="form-control me-2" id="kac_adet_yapildi" name="kac_adet_yapildi" value="1" min="1" style="width: 80px;" required>
            <button type="submit" name="tuket_tarif" class="btn btn-primary">Tarifi Tüket (Stoktan Düş)</button>
        </form>
    </div>

</div>

<div class="modal fade" id="deleteTarifModal" tabindex="-1" aria-labelledby="deleteTarifModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTarifModalLabel">Tarifi Sil Onayı</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                "<?php echo htmlspecialchars($tarif['ad']); ?>" adlı tarifi silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <a href="tarif_sil.php?id=<?php echo $tarif['id']; ?>" class="btn btn-danger">Sil</a>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>