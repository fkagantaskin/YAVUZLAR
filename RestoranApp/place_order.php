<?php
session_start();
include 'db.php';

// Kullanıcı ID'sini al
$user_id = $_SESSION['user_id'];

// Sepet bilgilerini çek
$query = "SELECT food.id, food.price, basket.quantity, food.restaurant_id 
          FROM basket 
          JOIN food ON basket.food_id = food.id 
          WHERE basket.user_id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$basket_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Sepet boşsa işlem yapma
if (count($basket_items) === 0) {
    echo "Sepetiniz boş, lütfen sipariş vermek için ürün ekleyin.";
    exit();
}

// Toplam fiyatı hesapla ve restaurant_id'yi al (ilk üründen)
$total_price = 0;
$restaurant_id = null;
foreach ($basket_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
    
    // Restoran ID'sini al
    if ($restaurant_id === null) {
        $restaurant_id = $item['restaurant_id'];
    }
}

// Yeni bir sipariş oluştur, restaurant_id ile
$stmt = $db->prepare("INSERT INTO orders (user_id, restaurant_id, order_status, total_price, created_at) 
                      VALUES (:user_id, :restaurant_id, 'Hazırlanıyor', :total_price, datetime('now'))");
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindValue(':restaurant_id', $restaurant_id, PDO::PARAM_INT); // restaurant_id'yi ekledik
$stmt->bindValue(':total_price', $total_price, PDO::PARAM_STR);
$stmt->execute();

// Yeni oluşturulan sipariş ID'sini al
$order_id = $db->lastInsertId();

// Sipariş kalemlerini order_items tablosuna ekle
foreach ($basket_items as $item) {
    $stmt = $db->prepare("INSERT INTO order_items (order_id, food_id, quantity, price) 
                          VALUES (:order_id, :food_id, :quantity, :price)");
    $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->bindValue(':food_id', $item['id'], PDO::PARAM_INT);
    $stmt->bindValue(':quantity', $item['quantity'], PDO::PARAM_INT);
    $stmt->bindValue(':price', $item['price'], PDO::PARAM_STR);
    $stmt->execute();
}

// Sepeti temizle
$stmt = $db->prepare("DELETE FROM basket WHERE user_id = :user_id");
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

echo "Siparişiniz başarıyla oluşturuldu!";
header("Location: order_history.php");
exit();
?>
