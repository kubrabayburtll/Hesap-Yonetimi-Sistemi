<?php
session_start();
include("db_con.php");

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$alertMessage = '';  // Başarı ve hata mesajını burada tutacağız.

if (isset($_POST['kaydet'])) {
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $email = $_POST['email'];
    $departman = $_POST['departman'];

    // E-posta benzersizlik kontrolü
    $existingUser = $database->get("personeller", "email", ["email" => $email]);

    if ($existingUser) {
        echo "Bu e-posta adresi zaten kayıtlı.";
        exit;
    }

    // Rastgele 10 haneli şifre üretimi
    $rastgele_sifre = substr(bin2hex(random_bytes(5)), 0, 10);

    // Şifrenin bcrypt ile şifrelenmesi
    $sifre_hash = password_hash($rastgele_sifre, PASSWORD_BCRYPT);

    // Veritabanına kayıt işlemi
    $database->insert("personeller", [
        "ad" => $ad,
        "soyad" => $soyad,
        "email" => $email,
        "sifre" => $sifre_hash,
        "departman" => $departman
    ]);

    // E-posta gönderimi
    $mail = new PHPMailer(true);

    try {
        // Sunucu ayarları
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // SMTP sunucusu
        $mail->SMTPAuth = true;
        $mail->Username = 'vabcvabcvabc@gmail.com'; // SMTP kullanıcı adı
        $mail->Password = 'kslvsnkvhfxbezet'; // SMTP şifresi
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Alıcılar
        $mail->setFrom($email, 'Personel Kayıt');
        $mail->addAddress($email, "$ad $soyad");

        $mail->CharSet = 'UTF-8';

        // İçerik
        $mail->isHTML(true);
        $mail->Subject = 'Kayıt İşleminiz Tamamlandı';
        $mail->Body    = "<p>Sayın $ad $soyad,</p>
                          <p>Kayıt işleminiz tamamlandı. Giriş şifreniz: <strong>$rastgele_sifre</strong></p>
                          <p>İyi günler dileriz.</p>";

        $mail->send();
        $alertMessage = '<div class="alert alert-light" role="alert">Şifre çalışanın E-posta adresine gönderildi. Çalışan şifresi ile giriş yapabilir.</div>';
    } catch (Exception $e) {
        $alertMessage = "E-posta gönderim hatası: {$mail->ErrorInfo}";
    }
}

// Silme işlemi
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Veritabanından silme işlemi
    $database->delete("personeller", [
        "id" => $delete_id
    ]);

    // Silme işlemi başarılı mesajı
    $alertMessage = '<div class="alert alert-danger" role="alert">Personel başarıyla silindi.</div>';
}

// Veritabanından personel listesini çek
$personeller = $database->select("personeller", ["id", "ad", "soyad", "email", "departman"]);
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaydol - Hesap Yönetimi Projesi</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #8e44ad, #3498db);
            color: #fff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
        }

        .signup-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }

        .signup-container h2 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #fff;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            background: #fff;
            color: #333;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            border-color: #8e44ad;
            background-color: #f3f3f3;
        }

        .btn {
            background-color: #8e44ad;
            color: white;
            padding: 15px;
            font-size: 18px;
            border: none;
            border-radius: 20px;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #6c3483;
        }

        .table {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 40px;
        }

        footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #fff;
        }
        
        /* Tabloların daha şık görünmesi için stil eklemeleri */
.table {
    width: 100%;
    margin-top: 20px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.table th,
.table td {
    padding: 12px 15px;
    text-align: left;
    font-size: 16px;
}

.table th {
    background-color: #8e44ad;
    color: white;
    font-weight: bold;
    text-transform: uppercase;
}

.table td {
    background-color: rgba(255, 255, 255, 0.3);
    color: #fff;
    border-bottom: 1px solid #ddd;
}

.table tr:nth-child(even) {
    background-color: rgba(255, 255, 255, 0.1);
}

.table tr:hover {
    background-color: rgba(255, 255, 255, 0.1);
    color: #fff;
    cursor: pointer;
}

.table .table-hover tbody tr:hover {
    background-color: #8e44ad;
    color: white;
}

/* Header ve card stilini iyileştirme */
.card {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    color: white;
    padding: 20px;
    margin-bottom: 20px;
    margin-top: 24px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.card h3 {
    font-size: 24px;
    margin-bottom: 20px;
    color: white;
}
    </style>
</head>

<body>

    <!-- Alert Message (Above Form) -->
    <?php 
    if (isset($alertMessage)) {
        echo $alertMessage;
    }
    ?>

    <!-- Yeni Personel Kayıt Formu -->
    <div class="signup-container">
        <h2>Yeni Personel Kayıt</h2>
        <form action="personel_kayit.php" method="POST">
            <div class="input-group">
                <input type="text" name="ad" placeholder="Ad" required>
            </div>
            <div class="input-group">
                <input type="text" name="soyad" placeholder="Soyad" required>
            </div>
            <div class="input-group">
                <input type="text" name="departman" placeholder="Departman" required>
            </div>
            <div class="input-group">
                <input type="email" name="email" placeholder="E-posta" required>
            </div>
            <button type="submit" name="kaydet" class="btn">Kaydet</button>
        </form>
    </div>

    <!-- Personel Listesi Tablosu -->
    <div class="card">
        <h3>Personel Listesi</h3>
        <table class="table table-hover text-white">
            <thead>
                <tr>
                    <th>Ad</th>
                    <th>Soyad</th>
                    <th>E-posta</th>
                    <th>Departman</th>
                    <th>İşlemler</th> <!-- Yeni sütun ekledik -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($personeller as $personel): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($personel['ad']); ?></td>
                        <td><?php echo htmlspecialchars($personel['soyad']); ?></td>
                        <td><?php echo htmlspecialchars($personel['email']); ?></td>
                        <td><?php echo htmlspecialchars($personel['departman']); ?></td>
                        <td>
                            <!-- Silme işlemi için buton -->
                            <a href="?delete_id=<?php echo $personel['id']; ?>" class="btn btn-danger btn-sm">Sil</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <footer>
        <p>© 2024 Hesap Yönetimi Projesi. Tüm hakları saklıdır.</p>
    </footer>

    <script