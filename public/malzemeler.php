<?php
// public/malzemeler.php
require_once __DIR__ . '/../includes/header.php'; // header.php içinde $malzemeObj oluşturuluyor

$error_message = '';
$success_message = '';

// Yeni malzeme ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['malzeme_ekle'])) {
    $ad = trim($_POST['ad']);
    $birim = trim($_POST['birim']);
    // Stok miktarı için gelen değeri kontrol et ve sayıya dönüştür
    $stok_miktari = isset($_POST['stok_miktari']) ? (float)$_POST['stok_miktari'] : 0;

    if (empty($ad) || empty($birim)) {
        $error_message = "Lütfen malzeme adı ve birimini doldurunuz.";
    } elseif (!is_numeric($stok_miktari) || $stok_miktari < 0) { // Stok miktarının geçerliliğini kontrol et
        $error_message = "Geçerli bir stok miktarı giriniz. (Sıfır veya pozitif sayı)";
    } else {
        if ($malzemeObj->malzemeEkle($ad, $birim, $stok_miktari)) { // stok_miktari parametresini geçiyoruz
            $success_message = "Malzeme başarıyla eklendi!";
            // Formu temizlemek için POST verilerini sıfırla
            $_POST = array();
        } else {
            $error_message = "Malzeme eklenirken bir hata oluştu veya bu isimde bir malzeme zaten mevcut.";
        }
    }
}

// Tüm malzemeleri getir
$malzemeler = $malzemeObj->getMalzemeler();
?>

<h1 class="mb-4">Malzeme Yönetimi</h1>

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
    <h3 class="mb-3">Yeni Malzeme Ekle</h3>
    <form action="malzemeler.php" method="POST">
        <input type="hidden" name="malzeme_ekle" value="1">
        <div class="row g-3">
            <div class="col-md-5">
                <label for="ad" class="form-label">Malzeme Adı</label>
                <input type="text" class="form-control" id="ad" name="ad" required value="<?php echo htmlspecialchars($_POST['ad'] ?? ''); ?>">
            </div>
            <div class="col-md-3">
                <label for="birim" class="form-label">Birim (örn: gr, kg, adet)</label>
                <input type="text" class="form-control" id="birim" name="birim" required value="<?php echo htmlspecialchars($_POST['birim'] ?? ''); ?>">
            </div>
            <div class="col-md-2">
                <label for="stok_miktari" class="form-label">Stok Miktarı</label>
                <input type="number" step="0.01" class="form-control" id="stok_miktari" name="stok_miktari" required value="<?php echo htmlspecialchars($_POST['stok_miktari'] ?? 0); ?>">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Ekle</button>
            </div>
        </div>
    </form>
</div>

<h3 class="mb-3">Mevcut Malzemeler</h3>
<?php if (empty($malzemeler)): ?>
    <div class="alert alert-info" role="alert">
        Henüz hiç malzeme eklenmemiş. Yukarıdaki formu kullanarak ilk malzemenizi ekleyebilirsiniz.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Malzeme Adı</th>
                    <th>Birim</th>
                    <th>Stok Miktarı</th> <th>Ekleme Tarihi</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($malzemeler as $malzeme): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($malzeme['id']); ?></td>
                        <td><?php echo htmlspecialchars($malzeme['ad']); ?></td>
                        <td><?php echo htmlspecialchars($malzeme['birim']); ?></td>
                        <td><?php echo htmlspecialchars(number_format($malzeme['stok_miktari'], 2)); ?></td> <td><?php echo date('d.m.Y H:i', strtotime($malzeme['created_at'])); ?></td>
                        <td>
                            <a href="malzeme_duzenle.php?id=<?php echo $malzeme['id']; ?>" class="btn btn-warning btn-sm me-2">Düzenle</a>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $malzeme['id']; ?>">Sil</button>

                            <div class="modal fade" id="deleteModal<?php echo $malzeme['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $malzeme['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel<?php echo $malzeme['id']; ?>">Malzemeyi Sil Onayı</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            "<?php echo htmlspecialchars($malzeme['ad']); ?>" adlı malzemeyi silmek istediğinizden emin misiniz? Bu malzeme, mevcut tariflerde kullanılıyorsa, tariflerdeki bağlantısı da silinecektir.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                                            <a href="malzeme_sil.php?id=<?php echo $malzeme['id']; ?>" class="btn btn-danger">Sil</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>