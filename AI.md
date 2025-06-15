# Yapay Zeka Yardım Dosyası

Bu dosya, proje geliştirme sürecinde yapay zeka asistanı Gemini'den ve ChatGPT'den alınan yardımları ve çözüme kavuşturulan konuları özetlemektedir.

## Alınan Yardımlar ve Çözülen Sorunlar (CHATGPT)

### Soru 1: Restoran Mutfak Yönetim Sistemi konusu ile ilgili PHP & MySQL tabanlı bir projem var.( E-kampüste paylaşılan proje bilgileri gönderildi) Bu projeyi nasıl yapacağım konusunda bana yardımcı olur musun?

### Cevap 1: Elbette! “Restoran Mutfak Yönetim Sistemi” konusuyla PHP & MySQL tabanlı bu projeyi nasıl yapabileceğini adım adım açıklayayım. Bu sistem, bir restoranın mutfak operasyonlarını (yemek tarifleri, malzeme stoğu gibi) yönetmeye yönelik olacak.

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

Restoran Mutfak Yönetim Sistemi

Bu uygulama bir restoranın mutfağında tarifleri, görevleri ve malzemeleri yönetmek için geliştirilmiştir.

Özellikler:
- Kullanıcı kaydı ve giriş
- Tarif ekleme, listeleme, silme, güncelleme
- Aşçı görevleri yönetimi
- Malzeme stok takibi

Ekran Görüntüleri:
![Giriş Ekranı](screenshots/login.png)
![Tarif Listesi](screenshots/recipes.png)

Tanıtım Videosu:
[YouTube Video Linki](https://youtube.com/example)

Geliştirici:
Adınız – 2025 Bahar

9. AI.md Örneği

Kullanıcı:
CRUD işlemleri nasıl yapılır?

ChatGPT:
PHP'de CRUD işlemleri için...

10. Canlıya Alma ve Hosting

$host = "localhost";
$dbname = "dbname";
$username = "user";
$password = "pass";

phpMyAdmin üzerinden veritabanını dışa aktar ve hostinge yükle

.htaccess kullanma!

### Soru 2: Chat, config/database.php dosyamı githuba yüklerken gizlemek istiyorum, içinde önemli bilgiler var. Nasıl yapacağım?

### Cevap 2: Gitignore dosyası, Git’e hangi dosya veya klasörleri “izlememesi, yani sürüm kontrolüne dahil etmemesi” gerektiğini söyleyen basit bir metin dosyasıdır. İşte adım adım nasıl yapacağın:

1. .gitignore dosyasını oluştur

Projenin ana dizinine (repo köküne) .gitignore adında bir dosya oluştur.

Windows’ta:

Not Defteri aç, içine ignore etmek istediğin dosya/klasörleri yaz, “Farklı Kaydet” yaparken dosya adı olarak .gitignore yaz ve “Tüm Dosyalar” olarak kaydet.

Terminal/komut satırında:

Proje klasörüne git ve şu komutu çalıştır:

touch .gitignore

2. .gitignore dosyasına ignore etmek istediğin dosya veya klasörleri yaz

Örneğin sadece config/database.php dosyasını hariç tutmak için:

/config/database.php

3. Değişiklikleri Git’e bildir

Eğer config/database.php dosyası önceden git tarafından takip ediliyorsa, .gitignore yazman tek başına yeterli olmaz. Dosyayı git takibinden çıkarmalısın.

Bunu yapmak için terminal veya komut satırında:

git rm --cached config/database.php

Ardından değişiklikleri commit et:

git commit -m "Remove config/database.php from tracking"

Sonra push yap:

git push

4. Artık config/database.php dosyan Git tarafından takip edilmeyecek ve GitHub’a gönderilmeyecek.

Özet:

.gitignore dosyasını oluşturup içine /config/database.php yaz.

Önceden takip edilen dosyaları git rm --cached komutuyla git takibinden çıkar.

Commit ve push yap.

Yardımcı olmamı istediğin başka bir şey olursa söyle, adım adım destek olurum!


## Alınan Yardımlar ve Çözülen Sorunlar (GEMINI)

### Soru 1: Merhaba senden bir projem hakkında yardım alabilir miyim? Web Tabanlı Programlama dersinde PHP & MySQL tabanlı bir projem var. Benim konum restoran mutfak yönetim sistemi. Şu ana kadar users, malzemeler ve tarifler adlı tablolarımı restoran_yonetimi veritabanında oluşturdum ve tarifler için olan kodlarda kaldım yardımcı olur musun?

### Cevap 1: Harika! Restoran Mutfak Yönetim Sistemi projenizde tarifler kısmında kaldığınızı anladım. Mevcut users, malzemeler ve tarifler tablolarınızın olduğunu da belirttiniz. Bu aşamada, tarifler için CRUD (Oluşturma, Okuma, Güncelleme, Silme) işlemlerini nasıl yapacağınızı ve malzemeler ile bağlantıyı nasıl kuracağınızı adım adım göstereyim.

Veritabanı Tasarımı Önerisi:
Tarifler ve malzemeler arasında çoktan çoğa ilişki olduğu için tarif_malzemeler adında bir ara tablo eklemelisiniz.

Veritabanı Şeması (Özet):

users

malzemeler

tarifler

tarif_malzemeler

Projede Kullanılacak Dosya Yapısı Önerisi:

restoran_yonetim/
├── config/
├── classes/
├── public/
├── includes/
└── README.md

Kodlamada Dikkat Edilmesi Gerekenler:

Şifreler password_hash() ile saklanmalı

Giriş/çıkış işlemleri $_SESSION ile yapılmalı

CRUD işlemleri için ayrı sınıflar kullanılmalı (örneğin Tarif.php, User.php)

Ekstra Tavsiyeler:

Tarif ekleme ekranı ile birlikte malzeme seçimi (çoklu seçim)

Tarif detaylarında kullanılan malzemeleri listeleme

Bootstrap ile görsel uyumluluk

AI.md ve README.md dosyalarının eksiksiz hazırlanması

### Soru 2: Ana sayfada mutfak envanteri kısmındaki malzeme yönetimine git butonu pasif halde kalmış. Çünkü bu butonun kodu envanter.php dosyasındaymış gibi yazılmış. Ama böyle bir dosya yok. Kod index.php içinde. Bunu nasıl düzeltelim?

### Cevap 2: Bu butonun HTML kodu `envanter.php` gibi ayrı bir dosyada bulunmuyor haklısın, HTML kodu doğrudan başka bir dosyada (`index.php`) bulunduğu tespit ettim. Ufak bir değişiklikle bunu halledebiliriz!

    **Uygulanan HTML Değişikliği (Örnek):**
    ```html
    <a href="malzemeler.php" class="btn btn-primary btn-lg mt-3">Malzeme Yönetimine Git</a>
    ```
### Soru 3: Peki, projemi yerel ortamdan nasıl canlıya alacağım konusunda yardımcı olur musun?

### Cevap 3: Projeni yerel sunucudan (XAMPP) canlı hosting ortamına nasıl aktaracağına dair adım adım bir kılavuz hazırladım! Burada en çok dikkat etmen gereken yer `database.php` dosyasının canlı veritabanı bilgileriyle güncellenmesi durumu ve bu dosyanın GitHub'a yüklenmemesi gerekliliği.

Projeyi Canlı Ortama Alma Adımları

1. Projenizi ve Veritabanınızı Yedekleyin (Lokalde)

Canlıya geçmeden önce her zaman yerel projenizin ve veritabanınızın bir yedeğini alın.

Veritabanı Yedeği:

phpMyAdmin'e (genellikle http://localhost/phpmyadmin) gidin.
restoran_yonetimi veritabanınızı seçin.
Üst menüden Dışa Aktar (Export) sekmesine tıklayın.
Hızlı (Quick) dışa aktarma yöntemini seçin ve SQL formatında indirmek için Git düğmesine tıklayın. Bu, restoran_yonetimi.sql gibi bir dosya indirecektir.

Proje Dosyaları Yedeği:

restoran_yonetimi klasörünüzü (içindeki tüm klasörler ve dosyalarla birlikte) bir yere kopyalayın veya sıkıştırın (.zip yapmak iyi bir fikirdir).

2. database.php Dosyasını Düzenleyin (Canlı Ortam İçin)

Bu adım çok önemli ve Ekampüs'teki uyarıyı ("sunucu bilgilerini PHP kodlarınızda düzelttiğinizden emin olunuz") doğrudan ilgilendiriyor.

restoran_yonetimi/config/ database.php dosyasını açın.

Ekampüs'ten size sağlanacak olan canlı hosting veritabanı bilgilerini (DB Host, DB Name, DB User, DB Pass) bu dosyaya girin.

PHP

<?php
// config/ database.php

// Canlı Ortam Veritabanı Ayarları (Ekampüs'ten alacağınız bilgiler)
define('DB_HOST', 'sizin_hosting_db_hostunuz'); // Örn: localhost, veya mysql.alanadiniz.com
define('DB_NAME', 'sizin_hosting_db_adiniz');
define('DB_USER', 'sizin_hosting_db_kullanici_adiniz');
define('DB_PASS', 'sizin_hosting_db_sifreniz');

// Bu dosya GitHub'a yüklenirken dikkat edilmelidir!
Unutmayın: Bu bilgileri Ekampüs'ten aldığınızda bu dosyayı güncelleyeceksiniz.

3. Hosting Alanınızda Veritabanı Oluşturun ve Verileri Yükleyin

Ekampüs'ten hosting bilgilerinizi aldıktan sonra:

Hosting Kontrol Panelinize Giriş Yapın: (cPanel, Plesk veya benzeri bir arayüz olacaktır).
MySQL Veritabanları Bölümünü Bulun: Genellikle "Databases" veya "Veritabanları" başlığı altında yer alır.
Yeni Bir Veritabanı Oluşturun: restoran_yonetimi gibi bir isim verin.
Yeni Bir Veritabanı Kullanıcısı Oluşturun: Bu veritabanına bağlanacak bir kullanıcı adı ve şifre belirleyin ve bu kullanıcıyı oluşturduğunuz veritabanına atayın (gerekli izinlerle, genellikle tüm izinler).
Veritabanına Verileri Yükleyin:
Kontrol panelinizde (phpMyAdmin'e benzer) bir veritabanı yönetim aracı olacaktır. Buradan oluşturduğunuz yeni veritabanını seçin.
İçe Aktar (Import) sekmesine tıklayın.
İlk adımda yerelden dışa aktardığınız restoran_yonetimi.sql dosyasını seçin ve "Git" veya "İçe Aktar" düğmesine tıklayarak verileri yükleyin.
4. Proje Dosyalarını Hosting Alanına Yükleyin (FTP/SFTP Kullanarak)
Size sağlanacak olan FTP/SFTP bilgileriyle (Host, Kullanıcı Adı, Şifre, Port) bir FTP istemcisi (örn: FileZilla, WinSCP) kullanarak dosyalarınızı yükleyeceksiniz.

FTP İstemcinizi Açın ve Bağlanın: Size verilen FTP/SFTP bilgileriyle hostinginize bağlanın.

public_html (veya www, htdocs) Klasörünü Bulun: Bu, web sitenizin ana kök dizinidir.

Proje Klasörünü Yükleyin: Yerel bilgisayarınızdaki tüm restoran_yonetimi klasörünü (içindeki classes, public, includes, database.php vb. dahil) public_html klasörünün içine yükleyin.

Neden public içini doğrudan public_html'e atmıyoruz? Çünkü ".htaccess" kullanamadığınız için, public klasör yapısını korumak, dosyalarınız arasındaki require_once __DIR__ . '/../includes/header.php'; gibi yolların düzgün çalışmaya devam etmesini sağlar ve minimum kod değişikliği gerektirir. Aksi takdirde, her PHP dosyasındaki yol tanımlamalarını manuel olarak değiştirmeniz gerekirdi ki bu, büyük bir iş yükü olurdu.
5. Projenize Web Üzerinden Erişim
Dosyaları yükledikten sonra, projenize aşağıdaki URL'den erişebilirsiniz:

http://alanadiniz.com/restoran_yonetimi/public/

(Buradaki alanadiniz.com yerine size verilen alan adı veya geçici URL gelecektir.)

6. GitHub'a Yükleme Yaparken Hassas Bilgilere Dikkat!
Bu madde Ekampüs'teki uyarıyı doğrudan ilgilendiriyor:

database.php dosyasını GitHub'a YÜKLEMEYİN! Canlı sunucuya özel veritabanı şifreleri ve kullanıcı adları gibi hassas bilgiler içerdiği için bu dosyayı GitHub deponuza yüklememelisiniz.

.gitignore Kullanımı: GitHub'a hassas dosyaların yanlışlıkla yüklenmesini engellemek için, proje ana dizininize .gitignore adında bir dosya oluşturup içine config/database.php yazabilirsiniz.

* .gitignore dosya içeriği
database.php
Bu, Git'in database.php dosyasını takip etmesini ve deponuza eklemesini engeller.

API Anahtarları (Eğer Kullandıysanız): Projenizde başka API anahtarları veya benzeri hassas bilgiler varsa, bunları da config.php içine koyun veya başka bir şekilde güvenli bir şekilde yönetin ve asla GitHub'a yüklemeyin.

7. .htaccess Dosyası Kullanmama Uyarısı
Size verilen uyarıya göre Apache sunucu ayarlarını içeren .htaccess dosyalarını kullanmamalısınız. Projenizde bu dosyayı zaten kullanmadık, bu yüzden bu konuda ek bir işlem yapmanıza gerek yok. Mevcut dosya yapınız bu kısıtlamayı zaten karşılıyor.

Bu adımları takip ederek projenizi sorunsuz bir şekilde canlı ortama taşıyabilirsiniz. Başarılar dilerim!

---
