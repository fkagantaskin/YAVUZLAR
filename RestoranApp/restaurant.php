<?php
session_start();
include 'db.php';

// Restoran ID'sini al
$restaurant_id = (int)$_GET['id'];

// Restoran bilgilerini çek
$stmt = $db->prepare("SELECT * FROM restaurant WHERE id = :restaurant_id AND deleted_at IS NULL");
$stmt->bindParam(':restaurant_id', $restaurant_id, PDO::PARAM_INT);
$stmt->execute();
$restaurant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$restaurant) {
    echo "Restoran bulunamadı.";
    exit();
}

// Restorana ait yemekleri çek
$stmt_food = $db->prepare("SELECT * FROM food WHERE restaurant_id = :restaurant_id AND deleted_at IS NULL");
$stmt_food->bindParam(':restaurant_id', $restaurant_id, PDO::PARAM_INT);
$stmt_food->execute();
$foods = $stmt_food->fetchAll(PDO::FETCH_ASSOC);

// Restorana ait yorumlar ve puanlar
$stmt_comments = $db->prepare("SELECT users.username, comments.comment, comments.score, comments.created_at 
                               FROM comments 
                               JOIN users ON comments.user_id = users.id 
                               WHERE comments.restaurant_id = :restaurant_id
                               ORDER BY comments.created_at DESC");
$stmt_comments->bindParam(':restaurant_id', $restaurant_id, PDO::PARAM_INT);
$stmt_comments->execute();
$comments = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($restaurant['name']); ?> - Yemekler</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .restaurant-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #e05555;
        }

        .restaurant-description {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .food-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .food-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 30%;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        .food-card h4 {
            margin-bottom: 10px;
            color: #e05555;
        }

        .food-card p {
            margin: 10px 0;
            color: #555;
        }

        .food-form {
            margin-top: 10px;
        }

        .button {
            background-color: #e05555;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .button:hover {
            background-color: #d94343;
            transform: translateY(-2px);
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #555;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 25px;
        }

        .back-button:hover {
            background-color: #333;
        }

        .no-food {
            text-align: center;
            font-size: 18px;
            color: #555;
        }

        .comments-section {
            margin-top: 40px;
        }

        .comment {
            background-color: #fff;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .comment h4 {
            margin-bottom: 5px;
            color: #e05555;
        }

        .comment p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="restaurant-container">
        <h2><?php echo htmlspecialchars($restaurant['name']); ?></h2>
        <p class="restaurant-description"><?php echo htmlspecialchars($restaurant['description']); ?></p>

        <h3>Menü</h3>
        <div class="food-list">
            <?php if (count($foods) > 0): ?>
                <?php foreach ($foods as $food): ?>
                    <div class="food-card">
                        <h4><?php echo htmlspecialchars($food['name']); ?></h4>
                        <p class="food-price">Fiyat: ₺<?php echo number_format($food['price'], 2); ?></p>
                        <form action="add_to_basket.php" method="POST" class="food-form">
                            <input type="hidden" name="food_id" value="<?php echo $food['id']; ?>">
                            <label for="quantity">Adet:</label>
                            <input type="number" name="quantity" min="1" value="1">
                            <button type="submit" class="button">Sepete Ekle</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-food">Bu restoranda henüz yemek eklenmemiş.</p>
            <?php endif; ?>
        </div>

        <!-- Yorumlar ve Puanlar -->
        <div class="comments-section">
            <h3>Yorumlar ve Puanlar</h3>
            <?php if (count($comments) > 0): ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <h4><?php echo htmlspecialchars($comment['username']); ?> - Puan: <?php echo htmlspecialchars($comment['score']); ?>/5</h4>
                        <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                        <p><small><?php echo htmlspecialchars($comment['created_at']); ?></small></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Bu restoran için henüz yorum yapılmamış.</p>
            <?php endif; ?>
        </div>

        <a href="restaurants.php" class="button back-button">Geri</a>
    </div>
</body>
</html>
