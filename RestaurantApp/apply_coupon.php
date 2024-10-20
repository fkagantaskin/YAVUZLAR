<form action="apply_coupon.php" method="POST">
    <label for="coupon_code">Kupon Kodu:</label>
    <input type="text" id="coupon_code" name="coupon_code" required>
    <button type="submit">Kuponu Uygula</button>
</form>


<?php
session_start();
include 'db.php';

// Kullanıcı ID'sini al
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $coupon_code = htmlspecialchars(trim($_POST['coupon_code']));

    // Kuponun geçerli olup olmadığını kontrol et
    $stmt = $db->prepare("SELECT * FROM cupon WHERE name = :name AND deleted_at IS NULL");
    $stmt->bindParam(':name', $coupon_code);
    $stmt->execute();
    $coupon = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($coupon) {
        $discount = $coupon['discount'];
        $restaurant_id = $coupon['restaurant_id'];

        // Kullanıcının sepetindeki toplam fiyatı al
        $query = "SELECT SUM(food.price * basket.quantity) AS total_price 
                  FROM basket 
                  JOIN food ON basket.food_id = food.id 
                  WHERE basket.user_id = :user_id AND food.restaurant_id = :restaurant_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':restaurant_id', $restaurant_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_price = $result['total_price'];

        // İndirimli fiyatı hesapla
        $discounted_price = $total_price - ($total_price * ($discount / 100));

        echo "Kupon başarıyla uygulandı! İndirimli Fiyat: $" . number_format($discounted_price, 2);
    } else {
        echo "Geçersiz kupon kodu.";
    }

    header("Location: basket.php");
    exit();
}
?>
