<?php
// public/tarifler.php
require_once __DIR__ . '/../includes/header.php'; // header.php içinde $tarifObj oluşturuluyor

// Tüm tarifleri getir
$tarifler = $tarifObj->getTarifler(); // BU SATIRI DÜZELTTİK! (tarifleriGetir() yerine getTarifler())

// URL'den gelen success/error mesajlarını göster
$success_message = '';
$error_message = '';
if (isset($_GET['success'])) {
    $success_message = htmlspecialchars($_GET['success']);
}
if (isset($_GET['error'])) {
    $error_message = htmlspecialchars($_GET['error']);
}
?>

<h1 class="mb-4">Tarifler</h1>

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

<div class="mb-3 text-end">
    <a href="tarif_ekle.php" class="btn btn-primary">Yeni Tarif Ekle</a>
</div>

<?php if (empty($tarifler)): ?>
    <div class="alert alert-info" role="alert">
        Henüz hiç tarif eklenmemiş. Yeni bir tarif eklemek için yukarıdaki butonu kullanabilirsiniz.
    </div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($tarifler as $tarif): ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($tarif['ad']); ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted">Ekleyen: <?php echo htmlspecialchars($tarif['username']); ?></h6>
                        <p class="card-text text-truncate"><?php echo htmlspecialchars($tarif['aciklama']); ?></p>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item p-1 ps-0 bg-transparent border-0">Hazırlık: <strong><?php echo htmlspecialchars($tarif['hazirlik_suresi']); ?></strong></li>
                            <li class="list-group-item p-1 ps-0 bg-transparent border-0">Pişirme: <strong><?php echo htmlspecialchars($tarif['pisirme_suresi']); ?></strong></li>
                            <li class="list-group-item p-1 ps-0 bg-transparent border-0">Servis: <strong><?php echo htmlspecialchars($tarif['servis_sayisi']); ?></strong></li>
                        </ul>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="tarif_detay.php?id=<?php echo $tarif['id']; ?>" class="btn btn-info btn-sm">Detayları Gör</a>
                            <div>
                                <a href="tarif_duzenle.php?id=<?php echo $tarif['id']; ?>" class="btn btn-warning btn-sm me-2">Düzenle</a>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $tarif['id']; ?>">Sil</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-muted small">
                        Ekleme Tarihi: <?php echo date('d.m.Y H:i', strtotime($tarif['created_at'])); ?>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deleteModal<?php echo $tarif['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $tarif['id']; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel<?php echo $tarif['id']; ?>">Tarifi Sil Onayı</h5>
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
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>