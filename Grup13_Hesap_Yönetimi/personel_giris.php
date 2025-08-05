

<?php
session_start();
include("db_con.php");

if (isset($_POST["giris"])) {
    $email = $_POST["email"];
    $sifre = $_POST["sifre"];

    // Medoo ile email kontrolü
    $personel = $database->select("personeller", "*", [
        "email" => $email
    ]);

    if (count($personel) > 0) {
        $ilgilikayit = $personel[0];
        $hashlisifre = $ilgilikayit["sifre"];

        if (password_verify($sifre, $hashlisifre)) {
            $_SESSION["email"] = $ilgilikayit["email"];
            header("Location: kullanici.php");
            exit();
        } else {
            echo '<div class="alert alert-danger" role="alert">Email veya şifre yanlış.</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Email veya şifre yanlış.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap - Hesap Yönetimi</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #3498db, #8e44ad);
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
            border-color: #3498db;
            background-color: #f3f3f3;
        }

        .btn {
            background-color: #3498db;
            color: white;
            padding: 15px;
            font-size: 18px;
            border: none;
            border-radius: 50px;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .forgot-password {
            margin-top: 15px;
            font-size: 14px;
        }

        .forgot-password a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: #3498db;
        }

        .signup-link {
            margin-top: 20px;
            font-size: 14px;
        }

        .signup-link a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .signup-link a:hover {
            color: #2980b9;
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
        <h2>Personel Girişi</h2>
        <form action="personel_giris.php" method="POST">
            <div class="input-group">
                <input type="email" name="email" placeholder="Email Adresinizi Giriniz" required>
            </div>
            <div class="input-group">
                <input type="password" name="sifre" placeholder="Şifreniz" required>
            </div>
            <button type="submit" name="giris" class="btn">Giriş Yap</button>
        </form>
    </div>

    <footer>
        <p>© 2024 Hesap Yönetimi Projesi. Tüm hakları saklıdır.</p>
    </footer>
</body>

</html>
