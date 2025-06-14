<?php
// public/tarif_ekle.php
require_once __DIR__ . '/../includes/header.php'; // header.php içinde $tarifObj ve $malzemeObj oluşturuluyor

$error_message = '';
$success_message = '';

// Tüm malzemeleri çek (malzeme seçimi için)
$tum_malzemeler = $malzemeObj->getMalzemeler();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = trim($_POST['ad']);
    $aciklama = trim($_POST['aciklama']);
    $hazirlik_suresi = trim($_POST['hazirlik_suresi']);
    $pisirme_suresi = trim($_POST['pisirme_suresi']);
    $servis_sayisi = isset($_POST['servis_sayisi']) ? (int)$_POST['servis_sayisi'] : 1; // Varsayılan değer 1
    $malzemeler = isset($_POST['malzemeler']) ? $_POST['malzemeler'] : []; // Malzemeler dizisi

    // Malzeme verilerini temizle ve filtrele
    $gecerli_malzemeler = [];
    foreach ($malzemeler as $malzeme) {
        $malzeme_id = (int)$malzeme['malzeme_id'];
        $miktar = trim($malzeme['miktar']); // Miktarı string olarak alıp sonra kontrol edeceğiz

        // Malzeme ID'si ve miktarın geçerli olup olmadığını kontrol et
        // Malzeme ID'si sıfırdan büyük olmalı ve miktarı boş olmamalı
        if ($malzeme_id > 0 && !empty($miktar)) {
            $gecerli_malzemeler[] = [
                'malzeme_id' => $malzeme_id,
                'miktar' => $miktar // Miktarı şimdilik string olarak saklıyoruz (örn: "2 adet", "200 gr")
            ];
        }
    }

    if (empty($ad) || empty($aciklama) || empty($hazirlik_suresi) || empty($pisirme_suresi) || $servis_sayisi <= 0) {
        $error_message = "Lütfen tüm alanları doldurunuz ve geçerli bir servis sayısı giriniz.";
    } elseif (empty($gecerli_malzemeler)) {
        $error_message = "Lütfen tarife en az bir malzeme ekleyiniz.";
    } else {
        if ($tarifObj->tarifEkle($_SESSION['user_id'], $ad, $aciklama, $hazirlik_suresi, $pisirme_suresi, $servis_sayisi, $gecerli_malzemeler)) {
            $success_message = "Tarif başarıyla eklendi!";
            // Formu temizlemek için POST verilerini sıfırla
            $_POST = array();
            // Yönlendirme yapmadan önce JS ile temizleme için flag koyabiliriz veya sayfa yenilenir.
            // header("Location: tarifler.php?success=" . urlencode("Tarif başarıyla eklendi!"));
            // exit();
        } else {
            $error_message = "Tarif eklenirken bir hata oluştu.";
        }
    }
}
?>

<h1 class="mb-4">Yeni Tarif Ekle</h1>

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

<div class="card p-4">
    <form action="tarif_ekle.php" method="POST">
        <div class="mb-3">
            <label for="ad" class="form-label">Tarif Adı</label>
            <input type="text" class="form-control" id="ad" name="ad" required value="<?php echo htmlspecialchars($_POST['ad'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label for="aciklama" class="form-label">Açıklama/Hazırlanışı</label>
            <textarea class="form-control" id="aciklama" name="aciklama" rows="5" required><?php echo htmlspecialchars($_POST['aciklama'] ?? ''); ?></textarea>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="hazirlik_suresi" class="form-label">Hazırlık Süresi</label>
                <input type="text" class="form-control" id="hazirlik_suresi" name="hazirlik_suresi" required value="<?php echo htmlspecialchars($_POST['hazirlik_suresi'] ?? ''); ?>">
            </div>
            <div class="col-md-4">
                <label for="pisirme_suresi" class="form-label">Pişirme Süresi</label>
                <input type="text" class="form-control" id="pisirme_suresi" name="pisirme_suresi" required value="<?php echo htmlspecialchars($_POST['pisirme_suresi'] ?? ''); ?>">
            </div>
             <div class="col-md-4">
                <label for="servis_sayisi" class="form-label">Servis Sayısı</label>
                <input type="number" class="form-control" id="servis_sayisi" name="servis_sayisi" required min="1" value="<?php echo htmlspecialchars($_POST['servis_sayisi'] ?? 1); ?>">
            </div>
        </div>

        <h4 class="mt-4">Gerekli Malzemeler</h4>
        <div id="malzeme_listesi" class="mb-3">
            <?php
            // Eğer form hata verdiyse ve post verileri duruyorsa, malzemeleri yeniden doldur
            if (!empty($_POST['malzemeler'] ?? [])) {
                foreach ($_POST['malzemeler'] as $index => $malzeme_data) {
                    if (isset($malzeme_data['malzeme_id']) && isset($malzeme_data['miktar'])) {
                        $selected_malzeme_id = (int)$malzeme_data['malzeme_id'];
                        $malzeme_miktar = htmlspecialchars($malzeme_data['miktar']);
                        ?>
                        <div class="row g-2 mb-2 malzeme-item">
                            <div class="col-md-6">
                                <select class="form-select" name="malzemeler[<?php echo $index; ?>][malzeme_id]" required>
                                    <option value="">Malzeme Seçin</option>
                                    <?php foreach ($tum_malzemeler as $malzeme_option): ?>
                                        <option value="<?php echo htmlspecialchars($malzeme_option['id']); ?>" <?php echo ($selected_malzeme_id == $malzeme_option['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($malzeme_option['ad'] . ' (' . $malzeme_option['birim'] . ')'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="malzemeler[<?php echo $index; ?>][miktar]" placeholder="Miktar (örn: 200gr, 2 adet)" value="<?php echo $malzeme_miktar; ?>" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger w-100 remove-malzeme">Kaldır</button>
                            </div>
                        </div>
                        <?php
                    }
                }
            } else {
                // Varsayılan olarak bir boş malzeme alanı göster
                ?>
                <div class="row g-2 mb-2 malzeme-item">
                    <div class="col-md-6">
                        <select class="form-select" name="malzemeler[0][malzeme_id]" required>
                            <option value="">Malzeme Seçin</option>
                            <?php foreach ($tum_malzemeler as $malzeme_option): ?>
                                <option value="<?php echo htmlspecialchars($malzeme_option['id']); ?>">
                                    <?php echo htmlspecialchars($malzeme_option['ad'] . ' (' . $malzeme_option['birim'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="malzemeler[0][miktar]" placeholder="Miktar (örn: 200gr, 2 adet)" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger w-100 remove-malzeme">Kaldır</button>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <button type="button" id="add_malzeme" class="btn btn-secondary mb-4">Malzeme Ekle</button>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Tarif Ekle</button>
            <a href="tarifler.php" class="btn btn-secondary btn-lg">İptal</a>
        </div>
    </form>
</div>

<?php
// Footer'dan önce JavaScript kodları
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let malzemeIndex = <?php echo count($_POST['malzemeler'] ?? []) > 0 ? count($_POST['malzemeler']) : 1; ?>; // Malzeme satırı için sayaç

    document.getElementById('add_malzeme').addEventListener('click', function() {
        const malzemeListesi = document.getElementById('malzeme_listesi');
        const newMalzemeItem = document.createElement('div');
        newMalzemeItem.classList.add('row', 'g-2', 'mb-2', 'malzeme-item');
        newMalzemeItem.innerHTML = `
            <div class="col-md-6">
                <select class="form-select" name="malzemeler[${malzemeIndex}][malzeme_id]" required>
                    <option value="">Malzeme Seçin</option>
                    <?php foreach ($tum_malzemeler as $malzeme_option): ?>
                        <option value="<?php echo htmlspecialchars($malzeme_option['id']); ?>">
                            <?php echo htmlspecialchars($malzeme_option['ad'] . ' (' . $malzeme_option['birim'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" name="malzemeler[${malzemeIndex}][miktar]" placeholder="Miktar (örn: 200gr, 2 adet)" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 remove-malzeme">Kaldır</button>
            </div>
        `;
        malzemeListesi.appendChild(newMalzemeItem);
        malzemeIndex++;
    });

    // Malzeme kaldırma butonu için event delegasyonu
    document.getElementById('malzeme_listesi').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-malzeme')) {
            // Sadece bir malzeme kaldıysa kaldırmayı engelle (isteğe bağlı)
            if (malzemeListesi.querySelectorAll('.malzeme-item').length > 1) {
                e.target.closest('.malzeme-item').remove();
            } else {
                alert("Tarif için en az bir malzeme bulunmalıdır.");
            }
        }
    });
});
</script>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>