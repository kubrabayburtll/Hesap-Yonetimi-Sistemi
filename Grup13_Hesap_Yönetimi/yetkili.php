<?php
session_start();
include("db_con.php");

if (isset($_POST["giris"])) {
    $email = $_POST["email"];
    $sifre = $_POST["sifre"];

    // Veritabanından yetkiliyi bul
    $yetkili = $database->select("yetkili", "*", [
        "email" => $email
    ]);

    if (count($yetkili) > 0) {
        $ilgilikayit = $yetkili[0];
        $kayitlisifre = $ilgilikayit["sifre"];

        // Şifre kontrolü
        if ($sifre === $kayitlisifre) {
            // Oturum bilgilerini sakla
            $_SESSION["email"] = $ilgilikayit["email"];
            $_SESSION["ad_soyad"] = $ilgilikayit["ad_soyad"];
            $_SESSION["personel_id"] = $ilgilikayit["personel_id"];
            $_SESSION["organizasyon_id"] = $ilgilikayit["organizasyon_id"];
            
            // Yönlendirme
            header("Location: yönetici.html");
            exit();
        } else {
            $error = "Email veya şifre yanlış.";
        }
    } else {
        $error = "Email veya şifre yanlış.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yetkili Girişi</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
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
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-container h2 {
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
            border-radius: 50px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .btn:hover {
            background-color: #6c3483;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .small-btn {
            background-color: #f39c12;
            color: white;
            border: none;
            border-radius: 50%;
            padding: 10px;
            width: 45px;
            height: 45px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .small-btn:hover {
            background-color: #d68910;
        }

        .new-user {
            background-color: #2f164e;
            color: white;
            padding: 15px;
            font-size: 18px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .new-user:hover {
            background-color: #2980b9;
        }

        footer {
            position: absolute;
            bottom: 10px;
            width: 100%;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Yetkili Girişi</h2>
        <form action="yetkili.php" method="POST">
            <div class="input-group">
                <input type="email" name="email" placeholder="Email Adresinizi Giriniz" required>
            </div>
            <div class="input-group">
                <input type="password" name="sifre" placeholder="Şifrenizi Giriniz" required>
            </div>
            <button type="submit" name="giris" class="btn">Giriş Yap</button>
        </form>
        <?php if (isset($error)) { echo "<div class='error'>$error</div>"; } ?>
    </div>
</body>

</html>