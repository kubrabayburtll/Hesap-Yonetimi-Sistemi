<?php
// Medoo Kütüphanesi
require 'Medoo.php';
use Medoo\Medoo;

// Veritabanı Bağlantısı
try {
    $database = new Medoo([
        'type' => 'mysql',
        'host' => 'localhost',
        'database' => 'hesap', // Veritabanı adı
        'username' => 'root',  // MySQL Kullanıcı Adı
        'password' => '',      // MySQL Şifresi
        'charset' => 'utf8'
    ]);
} catch (Exception $e) {
    die("Veritabanına bağlanırken hata oluştu: " . $e->getMessage());
}

// Geçmiş Katkı Payları ve Bütçeye Etkisi
$query1 = "SELECT YEAR(katkı_tarihi) AS yil, SUM(katkı_miktari) AS katkı_payi
           FROM katkilar k
           GROUP BY YEAR(katkı_tarihi)";

$gecmisVeriler = $database->query($query1)->fetchAll();

// Şu Anki Bütçe Durumu
$query2 = "SELECT adi, butce
           FROM organizasyon";

$organizasyonVeriler = $database->query($query2)->fetchAll();

// Katkı miktarının toplam bütçeye oranı
$totalKatki = array_sum(array_column($gecmisVeriler, 'katkı_payi'));
$totalButce = array_sum(array_column($organizasyonVeriler, 'butce'));
$katkiOrani = $totalKatki / $totalButce * 100; // Yüzde olarak hesaplandı
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetici Paneli - Raporlar</title>
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
            margin-top:50px;
            margin-bottom:50px;
           
        }
        .raporlar-baslik {
        font-size: 3rem; /* Sadece "Raporlar" başlığı büyüsün */
        margin-bottom: 50px;
        text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);
        }

        h1, h3 {
            font-size: 2rem;
            margin-bottom: 50px;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);
        }

        .table-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 2px solid #6a3093;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 40px;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 15px;
            color: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.5);
            padding: 20px;
            width: 45%;
            min-height: 400px;
            margin-bottom: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.5);
        }

        .chart-container {
            width: 100%;
            height: 300px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 12px;
            text-align: center;
        }

        table th {
            background-color: #6a3093;
        }

        table td {
            background-color: rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 768px) {
            .card {
                width: 100%;
            }
        }
    </style>
</head>
<body>


    <div class="container text-center">
    <h1 class="raporlar-baslik">Raporlar</h1>

        <!-- Üst Kısım - Yazılı Rapor ve Tablo -->
        <div class="card-container">
            <div class="card">
                <h3><i class="fas fa-table"></i> Geçmiş Katkı Payları ve Bütçeye Etkisi</h3>
                <p>Geçmiş yıllarda yapılan katkı paylarının bütçeye etkisi aşağıdaki tabloda gösterilmektedir:</p>
                <div class="table-container">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Yıl</th>
                                <th>Katkı Payı</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($gecmisVeriler as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['yil']) ?></td>
                                    <td><?= htmlspecialchars($row['katkı_payi']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <h3><i class="fas fa-coins"></i> Şu Anki Bütçe Durumu</h3>
                <div class="table-container">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Organizasyon</th>
                                <th>Harcanan Bütçe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($organizasyonVeriler as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['adi']) ?></td>
                                    <td><?= htmlspecialchars($row['butce']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Alt Kısım - Grafikler -->
        <div class="card-container">
            <div class="card">
                <h3><i class="fas fa-chart-pie"></i> Katkı Payı ve Bütçeye Oranlar</h3>
                <div class="chart-container">
                    <canvas id="pastagraph"></canvas>
                </div>
            </div>

            <div class="card">
                <h3><i class="fas fa-chart-bar"></i> Şu Anki Bütçe Durumu</h3>
                <div class="chart-container">
                    <canvas id="budgetgraph"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const pastagraph = new Chart(document.getElementById('pastagraph'), {
            type: 'pie',
            data: {
                labels: ['Katkı Miktarı', 'Bütçe Kalan'],
                datasets: [{
                    data: [<?php echo $katkiOrani; ?>, 100 - <?php echo $katkiOrani; ?>],
                    backgroundColor: ['#A5C9CA', '#D6E4E5']
                }]
            },
            options: {
                plugins: {
                    legend: {
                        labels: {
                            color: 'black'
                        }
                    }
                }
            }
        });

        const budgetgraph = new Chart(document.getElementById('budgetgraph'), {
            type: 'bar',
            data: {
                labels: [<?php echo implode(',', array_map(fn($row) => "'{$row['adi']}'", $organizasyonVeriler)); ?>],
                datasets: [{
                    label: 'Harcanan Bütçe',
                    data: [<?php echo implode(',', array_column($organizasyonVeriler, 'butce')); ?>],
                    backgroundColor: ['#F0A07E', '#A5B4F7', '#FFD59E', '#90CAF4']
                }]
            },
            options: {
                plugins: {
                    legend: {
                        labels: {
                            color: 'black'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: 'black'
                        }
                    },
                    y: {
                        ticks: {
                            color: 'black'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>