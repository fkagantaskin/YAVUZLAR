<?php
session_start();
include 'db.php';

// Kullanıcı oturumunu kontrol et
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Sepetteki ürünleri çek
$query = "SELECT basket.id AS basket_id, food.name, food.price, food.restaurant_id, basket.quantity 
          FROM basket 
          JOIN food ON basket.food_id = food.id 
          WHERE basket.user_id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$basket_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Toplam fiyatı hesapla ve restoran ID'sini ayarla
$total_price = 0;
$restaurant_id = null; // Restoran ID'si ilk üründen alınacak

if (count($basket_items) > 0) {
    foreach ($basket_items as $item) {
        $total_price += $item['price'] * $item['quantity'];
        
        // Restoran ID'sini ilk üründen al
        if ($restaurant_id === null) {
            $restaurant_id = $item['restaurant_id'];
        }
    }
} else {
    // Sepette ürün yoksa sipariş verilemez
    echo "<p>Sepetiniz boş, lütfen ürün ekleyin.</p>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sepetiniz</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .basket-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .basket-item {
            background-color: white;
            padding: 20px;
            margin: 10px 0;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .basket-item h3, .basket-item p {
            margin: 0;
            text-align: left;
        }

        .basket-summary {
            margin-top: 20px;
            text-align: center;
        }

        .basket-summary a.button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            border-radius: 25px;
            background-color: #e05555;
            color: white;
            text-align: center;
            font-weight: bold;
            max-width: 200px;
            width: 100%;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .basket-summary a.button:hover {
            background-color: #d94343;
            transform: translateY(-2px);
        }

        .back-button {
            background-color: #ffffff;
            color: #e05555;
            padding: 10px 20px;
            border: 2px solid #e05555;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            max-width: 200px;
            width: 100%;
            text-align: center;
        }

        .back-button:hover {
            background-color: #e05555;
            color: white;
        }

        .remove-button {
            background-color: #ff6b6b;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            border: none;
            text-decoration: none;
            font-weight: bold;
            max-width: 150px;
            width: 100%;
            text-align: center;
            cursor: pointer;
        }

        .remove-button:hover {
            background-color: #d94343;
        }
    </style>
</head>
<body>
    <div class="basket-container">
        <h2>Sepetiniz</h2>
        <?php if (count($basket_items) > 0): ?>
            <div class="basket-items">
                <?php foreach ($basket_items as $item): ?>
                    <div class="basket-item">
                        <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                        <p class="price">Fiyat: ₺<?php echo number_format($item['price'], 2); ?> x <?php echo $item['quantity']; ?></p>
                        <p class="total">Toplam: ₺<?php echo number_format($item['price'] * $item['quantity'], 2); ?></p>
                        <form action="remove_from_basket.php" method="POST" class="remove-form">
                            <input type="hidden" name="basket_id" value="<?php echo $item['basket_id']; ?>">
                            <button type="submit" class="button remove-button">Sepetten Çıkar</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="basket-summary">
                <h3>Genel Toplam: ₺<?php echo number_format($total_price, 2); ?></h3>
                <a href="place_order.php" class="button">Sipariş Ver</a>
                <a href="restaurants.php" class="button back-button">Alışverişe Devam Et</a>
            </div>
        <?php else: ?>
            <p class="empty-basket">Sepetinizde ürün bulunmamaktadır.</p>
            <a href="restaurants.php" class="button">Alışverişe Başla</a>
        <?php endif; ?>
    </div>
</body>
</html>
