<?php
session_start();
include("db_con.php");

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Eğer oturum açılmamışsa giriş sayfasına yönlendir
if (!isset($_SESSION["email"])) {
    header("Location: personel_giris.php");
    exit();
}

// Oturum açmış kullanıcının bilgilerini almak için email ile sorgulama
$personel = $database->select("personeller", "*", [
    "email" => $_SESSION["email"]
]);

if (count($personel) > 0) {
    $ilgilikayit = $personel[0];
    $ad = $ilgilikayit["ad"];
    $soyad = $ilgilikayit["soyad"];
    $departman = $ilgilikayit["departman"] ?? "Departman Bilinmiyor"; // Varsayılan değer
    $kayit_tarihi = $ilgilikayit["kayit_tarihi"] ?? "1970-01-01"; // Varsayılan değer
    $personel_id = $ilgilikayit["id"];
} else {
    echo "Kullanıcı bilgileri bulunamadı.";
    exit();
}

// Katkı ekleme işlemi
if (isset($_POST['katki_miktari'])) {
    $katki_miktari = floatval($_POST['katki_miktari']);

    // Katkıyı veritabanına ekle
    $database->insert("katkilar", [
        "personel_id" => $personel_id,
        "katkı_miktari" => $katki_miktari,
        "katkı_tarihi" => date("Y-m-d H:i:s")
    ]);

    // E-posta gönderme işlemi
    $mail = new PHPMailer(true);

    try {
        // SMTP Ayarları
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // SMTP sunucusu
        $mail->SMTPAuth = true;
        $mail->Username = 'vabcvabcvabc@gmail.com'; // Gönderici e-posta adresi
        $mail->Password = 'kslvsnkvhfxbezet'; // Şifre
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Alıcı ve Gönderici
        $mail->setFrom('vabcvabcvabc@gmail.com', 'Katkı Bildirimi');
        $mail->addAddress($_SESSION["email"]);

        $mail->CharSet = 'UTF-8';

       // E-posta İçeriği
       $mail->isHTML(true);
       $mail->Subject = 'Katkı Bildirimi';
       $mail->Body = "Sayın $ad $soyad, <br> Bir kez daha biz olduğumuzu hissettirip şirket içi organizasyonlarımıza 
       <b>$katki_miktari TL</b> tutarında katkı sağladığınız için teşekkür ederiz. <br> Abc Şirketi Adına Tüm Çalışanlarımıza";

       $mail->send();
       $basari_mesaji = "Katkınız eklendi.";
       $mail->Body = '
       <html lang="tr">
       <head>
           <style>
               .bubble {
                   background-color: #1f5d76;
                   color: white;
                   padding: 20px;
                   border-radius: 15px;
                   width: 400px;
                   margin: 20px auto;
                   font-family: Arial, sans-serif;
                   box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                   text-align: center;
               }
               .bubble h2 {
                   margin: 10px 0;
               }
               .bubble p {
                   margin: 10px 0;
               }
           </style>
       </head>
       <body>
           <div class="bubble">
               <h2>Sayın ' . htmlspecialchars($ad) . ' ' . htmlspecialchars($soyad) . '</h2>
               <p>
                   Şirket içi organizasyonlarımıza 
                   <b>' . number_format($katki_miktari, 2, ',', '.') . ' TL</b> 
                   tutarında katkı sağladığınız için teşekkür ederiz.
               </p>
               <p>ABC Şirketi Adına Tüm Çalışanlarımız</p>
           </div>
       </body>
       </html>';
       $mail->send();
       $basari_mesaji = "Katkınız eklendi.";
   } catch (Exception $e) {
       $hata_mesaji = "E-posta gönderimi başarısız oldu. Hata: {$mail->ErrorInfo}";
   }
   

}

// Kullanıcının önceki katkılarını veritabanından çekme
$katkilar = $database->select("katkilar", "*", [
    "personel_id" => $personel_id
]) ?? []; // Varsayılan olarak boş bir dizi

// Bu ayın toplam katkısını al
$toplam_katkilar = $database->sum("katkilar", "katkı_miktari", [
    "personel_id" => $personel_id,
    "katkı_tarihi[>=]" => date("Y-m-01"), // Bu ayın ilk günü
    "katkı_tarihi[<=]" => date("Y-m-t")  // Bu ayın son günü
]);
// Bu yılın toplam katkısını al
$toplam_yillik_katkilar = $database->sum("katkilar", "katkı_miktari", [
    "personel_id" => $personel_id,
    "katkı_tarihi[>=]" => date("Y-01-01"), // Bu yılın ilk günü
    "katkı_tarihi[<=]" => date("Y-12-31")  // Bu yılın son günü
]);
// Katkı yapılmamışsa pop-up için flag ayarla
$katki_eksik = ($toplam_katkilar <= 0);


?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personel Profili</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .alert {
    width: 30%; /* Genişliği %80 yaparak daha küçük hale getirebiliriz */
    margin: 20px auto; /* Üstten 20px mesafe, ortalanmış */
    padding: 10px 20px; /* Paddingi küçültüyoruz */
    font-size: 0.9rem; /* Yazı boyutunu küçültüyoruz */
    text-align: center; /* Yazıyı ortalayalım */
    border-radius: 8px; /* Köşeleri yuvarlak yapalım */
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); /* Hafif gölge ekleyelim */
        }
        body {
            background: linear-gradient(135deg, #4c4177, #2a5470);
            color: white;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
    }
        .popup {
            background: rgba(240, 240, 240, 0.9);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.5);
        }
        .popup button {
            margin: 10px;
        }
        .popup h3 {
        margin-bottom: 15px;
        color: black; /* Yazı rengini siyah yap */
        }

        .popup p {
            color: #333; /* Yazı rengini koyu gri yap */
        }
        
        .profile-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.4);
            position: relative;
            max-width: 400px;
            margin: auto;
        }
        .profile-card img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid rgba(255, 255, 255, 0.6);
            margin-bottom: 20px;
        }
        .profile-card h2 {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .badge-role {
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-top: 5px;
            display: inline-block;
        }
        .data-box {
            background: rgba(255, 255, 255, 0.2);
            padding: 15px;
            border-radius: 15px;
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            font-weight: bold;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.4);
        }
        footer {
            margin-top: 30px;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
        }
        .contribution-form {
            background: rgba(255, 255, 255, 0.2);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.4);
            margin-top: 30px;
        }
        .contribution-form h3 {
            color: white;
            margin-bottom: 20px;
        }

        .btn-transparent {
            position: absolute;
            top: 10px; /* Daha yukarı taşındı */
            right: 18px;
            background: rgba(255, 255, 255, 0.1); /* Daha koyu arka plan */
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.5);
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 1rem;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            transition:0.2s, box-shadow 0.2s, transform 0.2s;
        }
        .btn-transparent:hover {
            background: rgba(0, 0, 0, 0.6); /* Hover sırasında daha koyu */
            color: white;
            transform: scale(1.1);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body>
    <!-- Çıkış Yap Butonu -->
    <button class="btn btn-transparent" onclick="window.location.href='personel_giris.php'">Çıkış Yap</button>

<!-- Alert Message (Above Form) -->
    <?php 
        if (isset($alertMessage)) {
            echo $alertMessage;  // Success or error message will be displayed here
        }
        ?>
    
    <div class="container py-5">
    <div class="profile-card mb-4">
            <img src="sirketlogo.png" alt="Profil Fotoğrafı">
            <h1 class="text-center mb-4">Hoşgeldiniz, <br> <?php echo $ad . ' ' . $soyad; ?></h1>
            <span class="badge-role"><?php echo $departman; ?></span> <br>
            <p><strong>İşe Giriş Tarihi:</strong> <?php echo date("d.m.Y", strtotime($kayit_tarihi)); ?></p>
        </div>
        <!-- Aylık Katkılar -->
        <div class="data-box">
            <span>Aylık Toplam Katkı</span>
            <span>₺<?php 
    // Değişkenin sayısal olup olmadığını kontrol edin
    echo number_format(is_numeric($toplam_katkilar) ? (float)$toplam_katkilar : 0, 2, ',', '.'); 
?></span>

        </div>
        <!-- Yıllık Katkılar -->
        <div class="data-box">
            <span>Yıllık Toplam Katkı</span>
            <span>₺<?php 
    // Değişkenin sayısal olup olmadığını kontrol edin
    echo number_format(is_numeric($toplam_katkilar) ? (float)$toplam_katkilar : 0, 2, ',', '.'); 
?></span>

        </div>

        <!-- Katkı Ekleme Formu -->
        <div class="contribution-form mb-5">
            <h3>Yeni Katkıda Bulun</h3>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="katki_miktari" class="form-label">Katkı Miktarı (₺)</label>
                    <input type="number" id="katki_miktari" name="katki_miktari" class="form-control" placeholder="Katkı miktarını girin" required>
                </div>
                <button type="submit" name="katki_ekle" class="btn btn-success w-100">Katkı Ekle</button>
            </form>
        </div>

        <!-- Önceki Katkılar -->
        <div class="card bg-transparent border-0 mt-5">
            <h3>Önceki Katkılar</h3>
            <table class="table text-white">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tarih</th>
                        <th>Tutar</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($katkilar as $katki) {
                        echo "<tr>
                                <td>{$katki['id']}</td>
                                <td>{$katki['katkı_tarihi']}</td>
                                <td>₺" . number_format($katki['katkı_miktari'], 2, ',', '.') . "</td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
     <!-- Pop-Up -->
<?php if ($katki_eksik): ?>
<div class="popup-overlay" id="popup">
    <div class="popup">
        <h3>Katkı Payınız Henüz Ödenmemiştir!</h3>
        <p>Lütfen ödeme yapınız.</p>
        <button class="btn btn-success" onclick="closePopup()">Ödeme Yap</button>
        <button class="btn btn-secondary" onclick="closePopup()">Daha Sonra Hatırlat</button>
    </div>
</div>
<?php endif; ?>

<script>
function closePopup() {
    const popup = document.getElementById('popup');
    if (popup) {
        popup.style.display = 'none'; // Pop-up'ı gizle
    }
    const overlay = document.querySelector('.popup-overlay');
    if (overlay) {
        overlay.style.display = 'none'; // Arka planı da gizle
    }
}
</script>


    <footer class="text-center">
        © 2024 Hesap Yönetimi Projesi. Tüm hakları saklıdır.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>