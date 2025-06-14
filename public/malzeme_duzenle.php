<?php
// public/malzeme_duzenle.php
require_once __DIR__ . '/../includes/header.php'; // header.php içinde $malzemeObj oluşturuluyor

$malzeme_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$malzeme = $malzemeObj->getMalzemeById($malzeme_id);
$error_message = '';

// Malzeme bulunamadıysa yönlendir
if (!$malzeme) {
    header("Location: malzemeler.php?error=" . urlencode("Malzeme bulunamadı."));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = trim($_POST['ad']);
    $birim = trim($_POST['birim']);
    // Stok miktarı için gelen değeri kontrol et ve sayıya dönüştür
    $stok_miktari = isset($_POST['stok_miktari']) ? (float)$_POST['stok_miktari'] : 0;

    if (empty($ad) || empty($birim)) {
        $error_message = "Lütfen malzeme adı ve birimini doldurunuz.";
    } elseif (!is_numeric($stok_miktari) || $stok_miktari < 0) { // Stok miktarının geçerliliğini kontrol et
        $error_message = "Geçerli bir stok miktarı giriniz. (Sıfır veya pozitif sayı)";
    } else {
        if ($malzemeObj->updateMalzeme($malzeme_id, $ad, $birim, $stok_miktari)) { // stok_miktari parametresini geçiyoruz
            header("Location: malzemeler.php?success=" . urlencode("Malzeme başarıyla güncellendi!"));
            exit();
        } else {
            $error_message = "Malzeme güncellenirken bir hata oluştu veya bu isimde bir malzeme zaten mevcut.";
        }
    }
}
?>

<h1 class="mb-4">Malzeme Düzenle: <?php echo htmlspecialchars($malzeme['ad']); ?></h1>

<?php if ($error_message): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $error_message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card p-4">
    <form action="malzeme_duzenle.php?id=<?php echo $malzeme_id; ?>" method="POST">
        <div class="mb-3">
            <label for="ad" class="form-label">Malzeme Adı</label>
            <input type="text" class="form-control" id="ad" name="ad" required value="<?php echo htmlspecialchars($malzeme['ad']); ?>">
        </div>
        <div class="mb-3">
            <label for="birim" class="form-label">Birim (örn: gr, kg, adet, litre)</label>
            <input type="text" class="form-control" id="birim" name="birim" required value="<?php echo htmlspecialchars($malzeme['birim']); ?>">
        </div>
        <div class="mb-3">
            <label for="stok_miktari" class="form-label">Stok Miktarı</label>
            <input type="number" step="0.01" class="form-control" id="stok_miktari" name="stok_miktari" required value="<?php echo htmlspecialchars($malzeme['stok_miktari']); ?>">
        </div>
        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-success btn-lg">Malzemeyi Güncelle</button>
            <a href="malzemeler.php" class="btn btn-secondary btn-lg">Geri Dön</a>
        </div>
    </form>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>