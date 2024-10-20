<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoranlar</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Restoranları Keşfedin başlığı daha belirgin */
        .restaurant-section h2 {
            font-size: 36px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
        }

        /* Arama satırının konumunu düzenleme */
        .search-form {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
        }

        /* Arama butonunun boyutunu düzenleme */
        .search-form .button {
            max-width: 150px;
            padding: 10px 15px;
            border-radius: 25px;
            background-color: #e05555;
            color: white;
            border: none;
            cursor: pointer;
            text-align: center;
        }

        .search-form .button:hover {
            background-color: #d94343;
        }

        /* Restoran listesi */
        .restaurant-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        /* Restoran kartı */
        .restaurant-card {
            width: 300px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .restaurant-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .restaurant-info {
            padding: 20px;
            text-align: center;
        }

        .restaurant-info h3 {
            margin: 0 0 10px;
            font-size: 24px;
            color: #e05555;
        }

        .restaurant-info p {
            margin: 0 0 15px;
            color: #555;
        }

        .restaurant-info .avg-rating {
            font-weight: bold;
            color: #e05555;
            margin-top: 10px;
        }

        .no-results {
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
            color: #555;
        }
    </style>
</head>
<body>
<?php session_start(); ?>

<header>
    <h1>Restoranlar</h1>
    <nav>
        <a href="index.php">Ana Sayfa</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="basket.php">Sepetim</a>
        <?php else: ?>
            <a href="login.php">Giriş Yap</a>
        <?php endif; ?>
    </nav>
</header>

<section class="restaurant-section">
    <h2>Restoranları Keşfedin</h2>
    <form method="GET" action="restaurants.php" class="search-form">
        <label for="search">Restoranları Bul:</label>
        <input type="text" id="search" name="search" placeholder="Restoran adı..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit" class="button">Ara</button>
    </form>

    <div class="restaurant-list">
        <?php
        include 'db.php';

        // Arama terimi varsa SQL sorgusunu güncelle
        $search = isset($_GET['search']) ? htmlspecialchars(trim($_GET['search'])) : '';
        $query = "SELECT restaurant.*, COALESCE(AVG(comments.score), 0) AS avg_rating
                  FROM restaurant
                  LEFT JOIN comments ON restaurant.id = comments.restaurant_id
                  WHERE restaurant.deleted_at IS NULL";

        if ($search) {
            $query .= " AND restaurant.name LIKE :search";
        }

        $query .= " GROUP BY restaurant.id ORDER BY restaurant.created_at DESC";

        $stmt = $db->prepare($query);

        if ($search) {
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        }

        $stmt->execute();
        $restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($restaurants) > 0) {
            foreach ($restaurants as $restaurant) {
                echo "<div class='restaurant-card'>";
                
                // Resim yolunu oluştururken tam yol olup olmadığını kontrol ediyoruz
                $imagePath = strpos($restaurant['image_path'], 'images/') === 0 ? htmlspecialchars($restaurant['image_path']) : 'images/' . htmlspecialchars($restaurant['image_path']);
                echo "<img src='" . $imagePath . "' alt='" . htmlspecialchars($restaurant['name']) . "' class='restaurant-image'>";
                echo "<div class='restaurant-info'>";
                echo "<h3>" . htmlspecialchars($restaurant['name']) . "</h3>";
                echo "<p>" . htmlspecialchars($restaurant['description']) . "</p>";
                echo "<p class='avg-rating'>Ortalama Puan: " . number_format($restaurant['avg_rating'], 1) . " / 5</p>";
                echo "<a href='restaurant.php?id=" . $restaurant['id'] . "' class='button'>Detaylar</a>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p class='no-results'>Aradığınız kriterlere uygun restoran bulunamadı.</p>";
        }
        ?>
    </div>
</section>
</body>
</html>
