# Hesap Yönetimi Sistemi

Bu proje, İşletmecilik dersi kapsamında takım arkadaşlarımla birlikte geliştirdiğimiz bir **Hesap Yönetimi Sistemi**dir.

Projenin amacı, kullanıcıların sisteme giriş yaparak kurumlara katkı sağlayabildiği, yöneticilerin ise kullanıcı, personel ve organizasyon işlemlerini yönetebildiği güvenli ve işlevsel bir hesap yönetimi sistemi geliştirmektir. Sistem; şifre güvenliği, veri yönetimi ve otomatik e-posta bildirimleri gibi özelliklerle kullanıcı dostu ve kurumsal bir yapıda tasarlanmıştır.

## Giriş Sistemi

- Sistem iki farklı kullanıcı türünü destekler:
  - **Kullanıcı**
  - **Yönetici**
- Tüm kullanıcılar e-posta ve şifre bilgileriyle sisteme giriş yapar.
- Şifreler **veritabanında hashlenmiş (şifrelenmiş)** şekilde saklanır. Bu sayede güvenlik artırılır; yöneticiler dahil olmak üzere kimse kullanıcı şifrelerini açıkça göremez.

## Kullanıcı İşlevleri

- Kullanıcılar sadece kendi hesaplarına ait işlemleri yapabilir.
- Kullanıcı panelinde, kuruma katkı sağlamak amacıyla özel **form alanları** bulunur.
- Bu alanlar üzerinden yaptıkları katkı bilgilerini sisteme girerler.
- Sisteme girilen katkılar her ay sonunda **kullanıcının e-posta adresine otomatik olarak gönderilir**. Bu sayede kullanıcılar katkı özetlerini takip edebilir.

## Yönetici Yetkileri

Yöneticiler sisteme tam erişim yetkisine sahiptir ve aşağıdaki işlemleri gerçekleştirebilir:

### Kullanıcı Hesap Yönetimi
- Yeni kullanıcı hesapları oluşturabilir.
- Mevcut kullanıcıların bilgilerini düzenleyebilir.
- Gerektiğinde kullanıcıları sistemden silebilir.
- **Not:** Yöneticiler kullanıcıları kaydederken, şifreleri göremez; şifreler güvenlik amacıyla hashlenmiş şekilde kaydedilir.

### Raporlama ve Analiz
- Sistem genelinde oluşturulan katkı verileri ve işlemler hakkında **detaylı raporları görüntüleyebilir**.
- Raporlar üzerinden istatistiksel analizler yaparak kurum performansı değerlendirilebilir.

### Organizasyon Yönetimi
- Sistemde tanımlı organizasyonların detaylarını görüntüleyebilir.
- Gerekli görülen organizasyonlara ait yönetimsel düzenlemeleri gerçekleştirebilir.

### Personel Yönetimi
- Sisteme yeni personel ekleyebilir.
- Mevcut personellerin listesini görüntüleyebilir.
- Silme ve güncelleme işlemlerini uygulayabilir.

## E-Posta Bildirim Sistemi

- Şifresini unutan kullanıcılara, e-posta yoluyla şifre sıfırlama bağlantısı gönderilir.
- Her ayın sonunda, kullanıcıların sisteme yaptığı katkıların özeti e-posta ile iletilir.

---

## Proje Ekibinin İş Zaman Çizelgesi



| No | Yapılacak İşin Adı ve Hedefleri       | Kim Tarafından Gerçekleştirileceği ve Kimlerin Destek Olacağı                                                                 | Zaman Aralığı |
|----|----------------------------------------|--------------------------------------------------------------------------------------------------------------------------------|----------------|
| 1  | Proje Planlama                         | Gerçekleştiren kişi: Metehan Ünal<br>Destek olan kişi(ler): Melike Karaman, Edanur Terzi, Kübra Bayburtlı, Emre Yayla         | 1. hafta       |
| 2  | Veritabanı Yapılandırma                | Gerçekleştiren kişi: Edanur Terzi<br>Destek olan kişi(ler): Kübra Bayburtlı                                                   | 1–2. hafta     |
| 3  | Arayüz Tasarımı                        | Gerçekleştiren kişi: Melike Karaman<br>Destek olan kişi(ler): Kübra Bayburtlı, Metehan Ünal                                  | 1–2. hafta     |
| 4  | Frontend Geliştirme                    | Gerçekleştiren kişi: Metehan Ünal<br>Destek olan kişi(ler): Melike Karaman, Kübra Bayburtlı                                  | 2–3. hafta     |
| 5  | Backend Geliştirme                     | Gerçekleştiren kişi: Kübra Bayburtlı<br>Destek olan kişi(ler): Edanur Terzi, Metehan Ünal                                    | 2–3. hafta     |
| 6  | Makbuz E-posta Gönderimi               | Gerçekleştiren kişi: Emre Yayla<br>Destek olan kişi(ler): Melike Karaman                                                     | 2–3. hafta     |
| 7  | Son Testler ve Kontroller              | Gerçekleştiren kişi: Edanur Terzi<br>Destek olan kişi(ler): Kübra Bayburtlı                                                  | 3. hafta       |

Takım arkadaşarıma katkılarından dolayı teşekkür ediyorum  

