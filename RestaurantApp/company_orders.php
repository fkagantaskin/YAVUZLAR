<?php
session_start();
include 'db.php';

// Kullanıcı rolü kontrolü: Sadece Company rolüne sahip kullanıcılar bu sayfaya erişebilir.
if ($_SESSION['role'] !== 'Company') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$company_id = $_SESSION['company_id']; // Bu oturumda giriş yapmış olan şirketin ID'si

// Bu kullanıcının (şirketin) restoranlarına gelen siparişleri çekmek için SQL sorgusu
$query = "SELECT orders.id AS order_id, orders.order_status, orders.total_price, orders.created_at, users.name AS customer_name
          FROM orders
          JOIN users ON orders.user_id = users.id
          JOIN restaurant ON restaurant.id = orders.restaurant_id
          WHERE restaurant.company_id = :company_id
          ORDER BY orders.created_at DESC";

$stmt = $db->prepare($query);
$stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);

// Sorguyu çalıştır
$stmt->execute();

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Yönetimi</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        h2 {
            color: #e05555;
            text-align: center;
            margin-bottom: 20px;
        }

        .order {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .order h3 {
            color: #e05555;
            margin-bottom: 10px;
        }

        .order p {
            margin: 5px 0;
        }

        form {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #e05555;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #d94343;
        }

        .no-orders {
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
            color: #555;
        }

    </style>
</head>
<body>

<div class="container">
    <h2>Siparişler</h2>
    <?php if (count($orders) > 0): ?>
        <?php foreach ($orders as $order): ?>
            <div class="order">
                <h3>Sipariş ID: <?php echo htmlspecialchars($order['order_id']); ?> - Müşteri: <?php echo htmlspecialchars($order['customer_name']); ?></h3>
                <p><strong>Toplam Fiyat:</strong> ₺<?php echo htmlspecialchars($order['total_price']); ?></p>
                <p><strong>Durum:</strong> <?php echo htmlspecialchars($order['order_status']); ?></p>
                <p><strong>Sipariş Tarihi:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>

                <!-- Sipariş durumu güncelleme formu -->
                <form action="update_order_status.php" method="POST">
                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                    <select name="order_status">
                        <option value="Hazırlanıyor" <?php echo ($order['order_status'] == 'Hazırlanıyor') ? 'selected' : ''; ?>>Hazırlanıyor</option>
                        <option value="Yola Çıktı" <?php echo ($order['order_status'] == 'Yola Çıktı') ? 'selected' : ''; ?>>Yola Çıktı</option>
                        <option value="Teslim Edildi" <?php echo ($order['order_status'] == 'Teslim Edildi') ? 'selected' : ''; ?>>Teslim Edildi</option>
                    </select>
                    <button type="submit">Güncelle</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-orders">Henüz bir sipariş bulunmamaktadır.</p>
    <?php endif; ?>
</div>

</body>
</html>
