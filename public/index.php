<?php
// public/index.php
require_once __DIR__ . '/../includes/header.php'; // Oturum kontrolü ve bağlantılar buradan gelir
?>

<div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Restoran Mutfak Yönetim Sistemine Hoş Geldiniz!</h1>
        <p class="col-md-8 fs-4">Bu uygulama ile restoranınızın mutfak süreçlerini kolayca yönetebilirsiniz. Tariflerinizi ekleyin, güncelleyin, silin ve tüm malzemeleri takip edin.</p>
        <hr class="my-4">
        <p>Hızlıca tariflerinize göz atmak veya yeni bir tarif eklemek için aşağıdaki bağlantıları kullanabilirsiniz.</p>
        <a class="btn btn-primary btn-lg me-2" href="tarifler.php" role="button">Tarifleri Görüntüle</a>
        <a class="btn btn-success btn-lg" href="tarif_ekle.php" role="button">Yeni Tarif Ekle</a>
    </div>
</div>

<div class="row align-items-md-stretch">
    <div class="col-md-6">
        <div class="h-100 p-5 text-bg-dark rounded-3">
            <h2>Tariflerinizi Yönetin</h2>
            <p>Tüm yemek tariflerinizi detaylarıyla birlikte kaydedebilir, düzenleyebilir ve silebilirsiniz. Malzemelerle birlikte eksiksiz tarifler oluşturun.</p>
            <a class="btn btn-outline-light" href="tarifler.php" type="button">Tarifler Sayfasına Git</a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="h-100 p-5 bg-light border rounded-3">
            <h2>Mutfak Envanteri</h2>
            <p>Envanterinizdeki malzemeleri yönetmek ve güncel stok durumunu görmek için aşağıdaki butonu kullanabilirsiniz.</p>
            <a href="malzemeler.php" class="btn btn-primary btn-lg mt-3">Malzeme Yönetimine Git</a>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>