<?php
session_start();
include('db_con.php'); // Veritabanı bağlantısı

// Veritabanından organizasyonları çekme
$organizasyonlar = $database->select("organizasyon", "*");

// Organizasyon ekleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ekleOrganizasyon'])) {
    $adi = $_POST['organizasyonAdi'];
    $aciklama = $_POST['organizasyonAciklama'];
    $tarih = $_POST['organizasyonTarih'];
    $butce = $_POST['organizasyonButce'];

    $database->insert("organizasyon", [
        "adi" => $adi,
        "açıklama" => $aciklama,
        "tarih" => $tarih,
        "butce" => $butce,
        "yetkili_id" => 1 // Sabit bir değer
    ]);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Organizasyon silme işlemi
if (isset($_POST['silOrganizasyon'])) {
    $org_id = $_POST['silOrganizasyon'];

    $database->delete("organizasyon", [
        "id" => $org_id
    ]);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizasyon Yönetimi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #3a6186, #89253e);
            color: white;
            font-family: 'Arial', sans-serif;
            margin-top:36px;
        }
        .content {
            padding: 20px;
            margin-bottom:50px;
        }
        .card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            color: white;
            padding: 20px;
            margin-bottom: 20px;
            margin-top:24px;
        }
        .equal-section {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }
        .equal-section > div {
            flex: 1;
            min-width: 45%;
        }
        .chart-container {
            width: 80%;
            margin: 0 auto;
        }
        footer {
            margin-top: 20px;
            text-align: center;
            font-size: 1.0rem;
            color: rgba(255, 255, 255, 0.7);
        }
    
    </style>
</head>
<body>
    <div class="container content">
        <h1 class="text-center">Organizasyon Yönetimi</h1>

        <!-- Organizasyon Tablosu -->
        <div class="card">
            <h3>Mevcut Organizasyonlar</h3>
            <table class="table table-hover text-white">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Adı</th>
                        <th>Açıklama</th>
                        <th>Tarih</th>
                        <th>Bütçe</th>
                        <th>Sil</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($organizasyonlar as $org): ?>
                    <tr>
                        <td><?= $org['id'] ?></td>
                        <td><?= $org['adi'] ?></td>
                        <td><?= $org['açıklama'] ?></td>
                        <td><?= $org['tarih'] ?></td>
                        <td><?= $org['butce'] ?>₺</td>
                        <td>
                            <form method="POST" action="" onsubmit="return confirm('Bu organizasyonu silmek istediğinize emin misiniz?');">
                                <button class="btn btn-danger btn-sm" name="silOrganizasyon" value="<?= $org['id'] ?>">Sil</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Organizasyon Ekleme Formu -->
        <div class="equal-section">
            <div class="card">
                <h3>Yeni Organizasyon Ekle</h3>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="organizasyonAdi" class="form-label">Organizasyon Adı</label>
                        <input type="text" class="form-control" id="organizasyonAdi" name="organizasyonAdi" required>
                    </div>
                    <div class="mb-3">
                        <label for="organizasyonAciklama" class="form-label">Açıklama</label>
                        <textarea class="form-control" id="organizasyonAciklama" name="organizasyonAciklama" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="organizasyonTarih" class="form-label">Tarih</label>
                        <input type="date" class="form-control" id="organizasyonTarih" name="organizasyonTarih" required>
                    </div>
                    <div class="mb-3">
                        <label for="organizasyonButce" class="form-label">Bütçe</label>
                        <input type="number" class="form-control" id="organizasyonButce" name="organizasyonButce" required>
                    </div>
                    <button type="submit" name="ekleOrganizasyon" class="btn btn-primary">Ekle</button>
                </form>
            </div>

            <!-- Organizasyon Bütçesi Grafiği -->
            <div class="card">
                <h3>Bütçe Grafiği</h3>
                <div class="chart-container">
                    <canvas id="budgetChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    

    <footer>
        © 2024 Organizasyon Yönetimi. Tüm hakları saklıdır.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const ctx = document.getElementById('budgetChart').getContext('2d');
        let budgetChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_column($organizasyonlar, 'adı')); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_column($organizasyonlar, 'butce')); ?>,
                    backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe', '#ffce56'],
                }]
            }
        });
    </script>
</body>
</html>
