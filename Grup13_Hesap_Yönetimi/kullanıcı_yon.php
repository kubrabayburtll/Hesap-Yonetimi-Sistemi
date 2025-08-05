<?php
require 'Medoo.php'; // Medoo'yu ekleyin

use Medoo\Medoo;

// Medoo veritabanı bağlantısı
try {
    $database = new Medoo([
        'type' => 'mysql',
        'host' => 'localhost',       // Veritabanı sunucunuzun adresi
        'database' => 'hesap',       // Veritabanı adı
        'username' => 'root',        // Kullanıcı adı
        'password' => '',            // Parola
        'charset' => 'utf8'
    ]);

    // Toplam personel sayısı
    $totalPersonnel = $database->count("personeller");

    // Toplam katkı miktarı
    $totalContribution = $database->sum("katkilar", "katkı_miktari") ?? 0;

    // Personel listesi (katkı miktarını almak için LEFT JOIN)
    $personnelList = $database->select("personeller", [
        "[>]katkilar" => ["id" => "personel_id"]
    ], [
        "personeller.ad(name)",
        "personeller.soyad(surname)",
        "personeller.departman(department)",
        "personeller.kayit_tarihi(start_date)",
        "katkilar.katkı_miktari(monthly_contribution)"
    ]);

    // Veriyi JSON formatına hazırlayın
    $data = [
        "totalPersonnel" => $totalPersonnel,
        "totalContribution" => $totalContribution,
        "personnelList" => $personnelList
    ];

} catch (Exception $e) {
    echo "Veritabanı Bağlantı Hatası: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Yönetimi</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #3a6186, #89253e);
            color: white;
            font-family: 'Arial', sans-serif;
            margin-top:50px;
            
        }
        h1 {
            font-size: 3rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);
        }
        .stats-card {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            color: white;
            margin-bottom: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.5);
        }
        .table-container {
            background: rgba(0, 0, 0, 0.3);
            padding: 20px;
            border-radius: 15px;
        }
        .table img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
        footer {
            margin-top: 30px;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
        }

        
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Kullanıcı Yönetimi</h1>

        <!-- İstatistik Kartları -->
        <div class="row text-center justify-content-center">
            <div class="col-md-4">
                <div class="stats-card">
                    <h3>Toplam Personel</h3>
                    <p id="totalPersonnel">0</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <h3>Aylık Toplam Katkı (TL)</h3>
                    <p id="monthlyContribution">0</p>
                </div>
            </div>
        </div>

        <!-- Personel Tablosu -->
        <div class="table-container mt-4">
            <h2 class="text-center">Personel Listesi</h2>
            <table class="table table-hover table-dark">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Profil</th>
                        <th>Adı</th>
                        <th>Soyadı</th>
                        <th>Departman</th>
                        <th>İşe Başlama Tarihi</th>
                        <th>Aylık Katkı</th>
                    </tr>
                </thead>
                <tbody id="personnelTable">
                    <!-- Dinamik Veri -->
                </tbody>
            </table>
        </div>
    </div>

    <footer class="text-center">
        © 2024 Kullanıcı Yönetimi. Tüm hakları saklıdır.
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // PHP'den gelen veriyi al
            const data = <?php echo json_encode($data); ?>;

            // Toplam personel ve katkıyı güncelle
            document.getElementById('totalPersonnel').textContent = data.totalPersonnel;
            document.getElementById('monthlyContribution').textContent = 
                parseFloat(data.totalContribution).toLocaleString('tr-TR') + ' TL';

            // Personel tablosunu güncelle
            const tableBody = document.getElementById('personnelTable');
            data.personnelList.forEach((person, index) => {
                const monthlyContribution = person.monthly_contribution ? 
                    parseFloat(person.monthly_contribution).toLocaleString('tr-TR') + ' TL' : '0.00 TL';

                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td><img src="sirketlogo.png" alt="Profil Resmi"></td>
                        <td>${person.name}</td>
                        <td>${person.surname}</td>
                        <td>${person.department}</td>
                        <td>${person.start_date}</td>
                        <td>${monthlyContribution}</td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        });
    </script>
</body>
</html>
