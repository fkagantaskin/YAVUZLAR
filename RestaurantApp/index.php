<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemek Yönetim Sistemi - Ana Sayfa</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #ff6b6b;
            color: white;
            padding: 20px;
            text-align: center;
        }
        nav {
            margin-top: 10px;
        }
        nav a {
            background-color: #e05555;
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            border: none;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin: 0 10px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            max-width: 150px;
            text-align: center;
        }
        nav a:hover {
            background-color: #d94343;
            transform: translateY(-2px);
        }
        .container {
            padding: 20px;
        }
        .welcome {
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 100px 20px;
        }
        .welcome h1 {
            font-size: 48px;
            margin: 0;
        }
        .welcome p {
            font-size: 20px;
            margin: 10px 0 20px;
        }
        .button {
            background-color: #e05555;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            border: none;
            text-decoration: none;
            display: inline-block;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .button:hover {
            background-color: #d94343;
            transform: translateY(-2px);
        }
        nav .button {
            background-color: #ffffff;
            color: #ff6b6b;
            padding: 10px 20px;
            border-radius: 25px;
            border: 2px solid #ff6b6b;
            margin: 0 10px;
        }
        nav .button:hover {
            background-color: #ff6b6b;
            color: #ffffff;
        }
        .card {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            text-align: center;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }
        .card h3 {
            margin: 15px 0 10px;
            font-size: 24px;
            color: #e05555;
        }
        .card p {
            color: #555;
        }
        .avg-rating {
            font-weight: bold;
            color: #e05555;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<header>
    <h1>Aafiyet.com'a Hoşgeldin</h1>
    <nav>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="basket.php" class="button">Sepetim</a>
        <?php else: ?>
            <a href="login.php" class="button">Giriş Yap</a>
            <a href="register.php" class="button">Kayıt Ol</a>
        <?php endif; ?>
    </nav>
</header>

<div class="welcome">
    <h1>Lezzetli Yemeklere Bir Tık Uzaklıktasın!</h1>
    <p>En sevdiğin restoranlardan sipariş ver, hemen kapına gelsin.</p>
    <a class="button" href="restaurants.php">Restoranları Keşfedin</a>
</div>

<div class="container">
    <h2>En Yüksek Puanlı Restoranlar</h2>
    <?php
    session_start();
    include 'db.php';

    $stmt = $db->query("SELECT restaurant.*, COALESCE(AVG(comments.score), 0) AS avg_rating
                        FROM restaurant
                        LEFT JOIN comments ON restaurant.id = comments.restaurant_id
                        WHERE restaurant.deleted_at IS NULL
                        GROUP BY restaurant.id
                        ORDER BY avg_rating DESC
                        LIMIT 5");
    $popular_restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($popular_restaurants) > 0) {
        foreach ($popular_restaurants as $restaurant) {
            echo "<div class='card'>";
            $imagePath = strpos($restaurant['image_path'], 'images/') === 0 ? htmlspecialchars($restaurant['image_path']) : 'images/' . htmlspecialchars($restaurant['image_path']);
            echo "<img src='" . $imagePath . "' alt='" . htmlspecialchars($restaurant['name']) . "'>";
            echo "<h3>" . htmlspecialchars($restaurant['name']) . "</h3>";
            echo "<p>" . htmlspecialchars($restaurant['description']) . "</p>";
            echo "<p class='avg-rating'>Ortalama Puan: " . number_format($restaurant['avg_rating'], 1) . " / 5</p>";
            echo "<a class='button' href='restaurant.php?id=" . $restaurant['id'] . "'>Detaylar</a>";
            echo "</div>";
        }
    } else {
        echo "<p>Henüz popüler restoran bulunmuyor. Hemen keşfedin!</p>";
    }
    ?>
</div>
</body>
</html>
