<?php
require 'Medoo.php'; // Medoo'yu ekleyin
use Medoo\Medoo;

// Medoo veritabanı bağlantısı
try {
    $database = new Medoo([
        'type' => 'mysql',
        'host' => 'localhost',
        'database' => 'hesap',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8'
    ]);

    // Dinamik değerler
    $activeUsers = $database->count("personeller"); // Kullanıcı tablosundaki toplam kullanıcı sayısı
    $totalBudget = $database->sum("katkilar", "katkı_miktari") ?? 0;
    $successfulOrganizations = $database->count("organizasyon");

} catch (Exception $e) {
    echo "Veritabanı Bağlantı Hatası: " . $e->getMessage();
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hesap Yönetimi Sistemi</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #3a6186, #89253e);
            color: white;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            margin-top: 20px;
        }
        h1 {
            font-size: 3.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);
            animation: fadeInDown 1.5s ease-in-out;
        }
        p.subheading {
            font-size: 1.5rem;
            margin-bottom: 30px;
            animation: fadeIn 2s ease-in-out;
        }
        .btn-custom {
            margin: 10px;
            padding: 15px 30px;
            font-size: 1.2rem;
            color: white;
            background: #6a3093;
            border: none;
            border-radius: 50px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-custom:hover {
            transform: scale(1.1);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.5);
            background: #a044ff;
        }
        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 15px;
            color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.5);
        }
        .stats {
            font-size: 2rem;
            font-weight: bold;
            animation: countUp 2s ease-in-out;
        }
        .icon {
            font-size: 3rem;
            margin-bottom: 10px;
        }
        footer {
            margin-top: 30px;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
        }
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <h1>Hesap Yönetimi Sistemi</h1>
        <p class="subheading">Bütçenizi yönetin, organizasyonunuzu takip edin.</p>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card p-4">
                    <i class="fas fa-user icon"></i>
                    <h3>Kullanıcı Girişi</h3>
                    <p>Bütçe detaylarını görüntülemek için giriş yapın.</p>
                    <a href="personel_giris.php" class="btn btn-custom" id="userLogin">Giriş Yap</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-4">
                    <i class="fas fa-user-shield icon"></i>
                    <h3>Yönetici Girişi</h3>
                    <p>Sistemi yönetmek ve yeni veriler eklemek için giriş yapın.</p>
                    <a href="yetkili.php" class="btn btn-custom" id="adminLogin">Giriş Yap</a>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-4">
                <h2 class="stats" id="stat1">0</h2>
                <p>Aktif Kullanıcı</p>
            </div>
            <div class="col-md-4">
                <h2 class="stats" id="stat2">0</h2>
                <p>Yönetilen Bütçe (TL)</p>
            </div>
            <div class="col-md-4">
                <h2 class="stats" id="stat3">0</h2>
                <p>Başarılı Organizasyon</p>
            </div>
        </div>
    </div>
    <footer class="text-center">
        © 2024 Hesap Yönetimi Sistemi. Tüm hakları saklıdır.
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script>
        // PHP'den gelen değerler
        const counters = [
            { id: 'stat1', target: <?php echo $activeUsers; ?> },
            { id: 'stat2', target: <?php echo $totalBudget; ?> },
            { id: 'stat3', target: <?php echo $successfulOrganizations; ?> }
        ];

        // Dinamik sayaçlar
        counters.forEach(counter => {
            const element = document.getElementById(counter.id);
            let count = 0;
            const updateCounter = () => {
                if (count < counter.target) {
                    count += Math.ceil(counter.target / 100);
                    element.textContent = count;
                    setTimeout(updateCounter, 50);
                } else {
                    element.textContent = counter.target;
                }
            };
            updateCounter();
        });
    </script>
</body>
</html>