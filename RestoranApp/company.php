<?php
session_start();
include 'db.php';

// Şirket sahibi yetkisi kontrolü
if ($_SESSION['role'] !== 'Company') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şirket Paneli</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Container stilini düzenleme */
        .company-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            font-size: 36px;
            font-weight: bold;
            color: #e05555;
            margin-bottom: 30px;
        }

        p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .button {
            background-color: #e05555;
            color: white;
            padding: 10px 20px;
            margin: 10px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            max-width: 250px;
            width: 100%;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .button:hover {
            background-color: #d94343;
            transform: translateY(-2px);
        }

        .logout-button {
            background-color: #ffffff;
            color: #e05555;
            padding: 10px 20px;
            border: 2px solid #e05555;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            max-width: 250px;
            width: 100%;
            text-align: center;
        }

        .logout-button:hover {
            background-color: #e05555;
            color: white;
        }
    </style>
</head>
<body>
    <div class="company-container">
        <h2>Şirket Paneli</h2>
        <p>Hoş geldiniz, <?php echo htmlspecialchars($_SESSION['username']); ?></p>

        <div class="button-container">
            <a href="add_restaurant.php" class="button">Restoran Ekle</a>
            <a href="company_orders.php" class="button">Siparişleri Yönet</a>
            <a href="list_restaurants.php" class="button">Restoranlarınızı Görüntüleyin</a>
            <a href="logout.php" class="logout-button">Çıkış Yap</a>
        </div>
    </div>
</body>
</html>
