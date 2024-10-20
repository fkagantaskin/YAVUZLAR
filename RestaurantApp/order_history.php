<?php
session_start();
include 'db.php';

// Kullanıcı ID'sini al
$user_id = $_SESSION['user_id'];

// Kullanıcının siparişlerini çek
$query = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC";
$stmt = $db->prepare($query);
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Geçmişi</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Yıldız puanlama stil ayarları */
        .star-rating {
            display: flex;
            direction: rtl;
            font-size: 2em;
            justify-content: center;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            color: #ccc;
            cursor: pointer;
        }

        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input:checked ~ label {
            color: #f5b301;
        }

        /* Form ve diğer stiller */
        .order-history-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .order-card {
            background-color: #f9f9f9;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .comment-form {
            margin-top: 20px;
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 10px;
        }

        .comment-form h4 {
            font-size: 1.5em;
            margin-bottom: 20px;
            color: #e05555;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-group textarea {
            width: 100%;
            height: 80px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .button {
            background-color: #e05555;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #d94343;
        }
    </style>
</head>
<body>
    <div class="order-history-container">
        <h2>Sipariş Geçmişiniz</h2>
        <?php if (count($orders) > 0): ?>
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <h3>Sipariş ID: <?php echo htmlspecialchars($order['id']); ?> - Durum: <?php echo htmlspecialchars($order['order_status']); ?></h3>
                    <p><strong>Toplam Fiyat:</strong> ₺<?php echo number_format($order['total_price'], 2); ?></p>
                    <p><strong>Sipariş Tarihi:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>

                    <?php
                    // Siparişin kalemlerini çek
                    $stmt_items = $db->prepare("SELECT food.name, order_items.quantity, order_items.price 
                                                FROM order_items 
                                                JOIN food ON order_items.food_id = food.id 
                                                WHERE order_items.order_id = :order_id");
                    $stmt_items->bindValue(':order_id', $order['id'], PDO::PARAM_INT);
                    $stmt_items->execute();
                    $items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

                    // Yorum yapılmış mı kontrol et
                    $stmt_comment_check = $db->prepare("SELECT * FROM comments WHERE order_id = :order_id");
                    $stmt_comment_check->bindValue(':order_id', $order['id'], PDO::PARAM_INT);
                    $stmt_comment_check->execute();
                    $comment_exists = $stmt_comment_check->fetch(PDO::FETCH_ASSOC);
                    ?>

                    <ul class="order-items">
                        <?php foreach ($items as $item): ?>
                            <li>
                                <?php echo htmlspecialchars($item['name']); ?> - 
                                <?php echo htmlspecialchars($item['quantity']); ?> adet - 
                                ₺<?php echo number_format($item['price'], 2); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <!-- Yorum yapma formu, sadece yorum yapılmamışsa gösterilecek -->
                    <?php if (!$comment_exists): ?>
                        <form action="submit_comment.php" method="POST" class="comment-form">
                            <h4>Yorum ve Puan Ver</h4>
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <input type="hidden" name="restaurant_id" value="<?php echo $order['restaurant_id']; ?>">
                            <div class="form-group">
                                <label for="comment">Yorum (İsteğe Bağlı):</label>
                                <textarea name="comment" id="comment"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="rating">Puan:</label>
                                <div class="star-rating">
                                    <input type="radio" id="star5-<?php echo $order['id']; ?>" name="rating" value="5" />
                                    <label for="star5-<?php echo $order['id']; ?>">★</label>
                                    <input type="radio" id="star4-<?php echo $order['id']; ?>" name="rating" value="4" />
                                    <label for="star4-<?php echo $order['id']; ?>">★</label>
                                    <input type="radio" id="star3-<?php echo $order['id']; ?>" name="rating" value="3" />
                                    <label for="star3-<?php echo $order['id']; ?>">★</label>
                                    <input type="radio" id="star2-<?php echo $order['id']; ?>" name="rating" value="2" />
                                    <label for="star2-<?php echo $order['id']; ?>">★</label>
                                    <input type="radio" id="star1-<?php echo $order['id']; ?>" name="rating" value="1" />
                                    <label for="star1-<?php echo $order['id']; ?>">★</label>
                                </div>
                            </div>
                            <button type="submit" class="button">Yorum Yap</button>
                        </form>
                    <?php else: ?>
                        <p>Bu sipariş için zaten yorum yaptınız.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-orders">Henüz bir siparişiniz bulunmamaktadır.</p>
        <?php endif; ?>
        <a href="customer.php" class="button back-button">Geri</a>
    </div>
</body>
</html>
