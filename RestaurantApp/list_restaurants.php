<?php
session_start();
include 'db.php';

if ($_SESSION['role'] !== 'Company') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT id, name, description, image_path FROM restaurant WHERE company_id = :user_id AND deleted_at IS NULL";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$restaurants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoranlarınız</title>
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
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            color: #e05555;
            margin-bottom: 30px;
        }

        .restaurant {
            background-color: white;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center; 
        }

        .restaurant img {
            width: 120px; 
            height: 120px; 
            object-fit: cover; 
            border-radius: 10px; 
            margin-right: 20px; 
        }

        .restaurant-info {
            flex-grow: 1; 
        }

        .restaurant h3 {
            margin: 0 0 10px;
            font-size: 24px;
        }

        .restaurant p {
            margin: 0 0 10px;
            font-size: 16px;
        }

        .restaurant a {
            background-color: #e05555;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .restaurant a:hover {
            background-color: #d94343;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="restaurant-container">
        <h2>Restoranlarınız</h2>
        <?php if (count($restaurants) > 0): ?>
            <?php foreach ($restaurants as $restaurant): ?>
                <div class="restaurant">
                    <?php
                    $imagePath = strpos($restaurant['image_path'], 'images/') === 0 ? htmlspecialchars($restaurant['image_path']) : 'images/' . htmlspecialchars($restaurant['image_path']);
                    ?>
                    <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($restaurant['name']); ?> resmi">
                    
                    <div class="restaurant-info">
                        <h3><?php echo htmlspecialchars($restaurant['name']); ?></h3>
                        <p><?php echo htmlspecialchars($restaurant['description']); ?></p>
                        <a href="restaurant.php?id=<?php echo $restaurant['id']; ?>">Detaylar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Henüz eklediğiniz bir restoran bulunmuyor.</p>
        <?php endif; ?>
    </div>
</body>
</html>
