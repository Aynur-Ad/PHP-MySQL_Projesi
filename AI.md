# Yapay Zeka Yardım Dosyası

Bu dosya, proje geliştirme sürecinde yapay zeka asistanı Gemini'den ve ChatGPT'den alınan yardımları ve çözüme kavuşturulan konuları özetlemektedir.

## Alınan Yardımlar ve Çözülen Sorunlar (CHATGPT)

### 1. Projeye Nasıl Başlanacağı Konusunda Yardım Alınması

Soru 1: Restoran Mutfak Yönetim Sistemi konusu ile ilgili bir PHP & MySQL tabanlı bir projem var.( E-kampüste paylaşılan proje bilgileri gönderildi) 
Bu projeyi nasıl yapacağım konusunda bana yardımcı olur musun?

Cevap 1: Elbette! “Restoran Mutfak Yönetim Sistemi” konusuyla PHP & MySQL tabanlı bu projeyi nasıl yapabileceğini adım adım açıklayayım. 
Bu sistem, bir restoranın mutfak operasyonlarını (yemek tarifleri, malzeme stoğu gibi) yönetmeye yönelik olacak.

1. Proje Konseptinin Belirlenmesi
Proje Adı: Restoran Mutfak Yönetim Sistemi
Amaç:

Yeni yemek tarifi veya malzeme eklenebilecek

Malzeme stoğu güncellenebilecek

Kayıtlar(tarif, malzeme) listelenip düzenlenip silinebilecek

2. Veritabanı Tasarımı (MySQL)
En az 3–4 tablo yeterli olacaktır.

3. Proje Yapısı (Klasörleme)

/restaurant-kitchen-management/
│
├── /css/              → Bootstrap (veya CDN)
├── /js/               → (İsteğe bağlı JavaScript dosyaları)
├── /includes/         → Veritabanı bağlantısı ve oturum kontrolleri
│   └── db.php
│   └── auth.php
├── /pages/            → İç sayfalar (tarif ekle, listele vs.)
├── index.php          → Giriş ekranı
├── register.php       → Kayıt ekranı
├── dashboard.php      → Yönetim paneli
├── logout.php         → Oturum kapatma
├── README.md
└── AI.md              → Yapay zeka ile konuşmaların dökümü

4. Kullanıcı Girişi ve Kayıt (Login & Register)

Şifre Hashleme (PHP)

$password = $_POST['password'];
$hashed = password_hash($password, PASSWORD_DEFAULT);

Girişte Doğrulama

if (password_verify($inputPassword, $storedHashedPassword)) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
}

5. Sayfalar ve İşlevler (CRUD)

| İşlev          | Sayfa Adı                      | Açıklama                                      |
| -------------- | ------------------------------ | --------------------------------------------- |
| Kayıt          | `register.php`                 | Yeni kullanıcı oluşturur                      |
| Giriş          | `index.php`                    | Kullanıcı oturum açar                         |
| Ana Panel      | `dashboard.php`                | Tarif, görev, stok yönetimi linkleri içerir   |
| Tarif Ekle     | `pages/add_recipe.php`         | Tarif formu (name, ingredients, instructions) |
| Tarif Liste    | `pages/list_recipes.php`       | Tüm tarifleri listeler                        |
| Tarif Sil      | `pages/delete_recipe.php?id=1` | GET ile gelen ID’yi siler                     |
| Tarif Güncelle | `pages/edit_recipe.php?id=1`   | Formla güncelleme yapar                       |
| Malzeme        | `pages/ingredients.php`        | Stok ekle/sil/güncelleme sayfası              |

6. CSS & Arayüz
Bootstrap 5 (CDN ile kullanabilirsin):

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

7. Session Kullanımı

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

Giriş yaptıktan sonra bu kontrol ile yetkisiz erişimler engellenmeli.

8. README.md Örneği

# Restoran Mutfak Yönetim Sistemi

Bu uygulama bir restoranın mutfağında tarifleri, görevleri ve malzemeleri yönetmek için geliştirilmiştir.

## Özellikler:
- Kullanıcı kaydı ve giriş
- Tarif ekleme, listeleme, silme, güncelleme
- Aşçı görevleri yönetimi
- Malzeme stok takibi

## Ekran Görüntüleri:
![Giriş Ekranı](screenshots/login.png)
![Tarif Listesi](screenshots/recipes.png)

## Tanıtım Videosu:
[YouTube Video Linki](https://youtube.com/example)

## Geliştirici:
Adınız – 2025 Bahar

9. AI.md Örneği

### Kullanıcı:
CRUD işlemleri nasıl yapılır?

### ChatGPT:
PHP'de CRUD işlemleri için...

10. Canlıya Alma ve Hosting

$host = "localhost";
$dbname = "dbname";
$username = "user";
$password = "pass";

phpMyAdmin üzerinden veritabanını dışa aktar ve hostinge yükle

.htaccess kullanma!


## Alınan Yardımlar ve Çözülen Sorunlar (GEMINI)

### 1. "Mutfak Envanteri" Bölümünün Aktif Edilmesi

* Ana sayfadaki pasif durumda olan "Mutfak Envanteri" bölümünün, mevcut `malzemeler.php` sayfasına yönlendirme yapacak aktif bir butona dönüştürülmesi sağlandı.
    * `envanter.php` gibi ayrı bir dosya olmadığı anlaşıldı ve HTML kodunun doğrudan başka bir dosyada (`index.php`) bulunduğu tespit edildi.

    **Uygulanan HTML Değişikliği (Örnek):**
    ```html
    <a href="malzemeler.php" class="btn btn-primary btn-lg mt-3">Malzeme Yönetimine Git</a>
    ```

### 2. Canlı Ortama Dağıtım (Hosting) Süreci

* Projenin yerel sunucudan (XAMPP) canlı hosting ortamına nasıl aktarılacağına dair adım adım bir kılavuz hazırlandı.
    * `database.php` dosyasının canlı veritabanı bilgileriyle güncellenmesi ve bu dosyanın GitHub'a yüklenmemesi gerektiği vurgulandı.

    **`database.php` Güncelleme Örneği:**
    ```php
    <?php
    // database.php

    // Canlı Ortam Veritabanı Ayarları (Sağlanan bilgilere göre)
    define('DB_HOST', 'localhost'); // Genellikle localhost
    define('DB_NAME', ''); // Veritabanı adı
    define('DB_USER', '');     // Veritabanı kullanıcısı
    define('DB_PASS', '');       // Veritabanı şifresi
    ?>
    ```

    * Veritabanının dışa/içe aktarımı ve FTP/SFTP (FileZilla/WinSCP) ile dosya yükleme adımları detaylandırıldı.
    * `.htaccess` dosyası kullanmama kısıtlaması nedeniyle dosya yapısının nasıl düzenleneceği konusunda bilgi verildi.
    * `.gitignore` kullanarak `database.php` dosyasının GitHub'a yüklenmemesi gerektiği vurgulandı.

    **`.gitignore` Dosyası İçeriği:**
    ```
    config/database.php
    ```

---