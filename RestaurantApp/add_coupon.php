<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Coupon</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Kupon Ekle</h2>
        <form action="add_coupon.php" method="POST">
            <label for="name">Kupon Adı:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="discount">İndirim Oranı (%):</label>
            <input type="number" id="discount" name="discount" min="0" max="100" required>
            
            <label for="restaurant_id">Restoran Seçin:</label>
            <select id="restaurant_id" name="restaurant_id" required>
                <?php
                // Restoranların listesini çekeriz
                include 'db.php';
                $stmt = $db->query("SELECT id, name FROM restaurant WHERE deleted_at IS NULL");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                }
                ?>
            </select>
            
            <button type="submit">Kupon Ekle</button>
        </form>
    </div>
</body>
</html>


<?php
session_start();
include 'db.php';

// Admin yetkisi kontrolü
if ($_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Formdan gelen verileri al ve kuponu ekle
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $discount = (int)$_POST['discount'];
    $restaurant_id = (int)$_POST['restaurant_id'];
    $created_at = date('Y-m-d H:i:s');

    // Kuponu veritabanına ekle
    $stmt = $db->prepare("INSERT INTO cupon (name, discount, restaurant_id, created_at) VALUES (:name, :discount, :restaurant_id, :created_at)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':discount', $discount);
    $stmt->bindParam(':restaurant_id', $restaurant_id);
    $stmt->bindParam(':created_at', $created_at);

    if ($stmt->execute()) {
        echo "Kupon başarıyla eklendi!";
    } else {
        echo "Kupon eklenirken bir hata oluştu.";
    }

    header("Location: list_coupons.php");
    exit();
}
?>

