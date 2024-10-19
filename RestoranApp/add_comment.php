<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yorum Yap</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Yorum Yap</h2>
        <form action="add_comment.php" method="POST">
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

            <label for="score">Puan (1-10):</label>
            <input type="number" id="score" name="score" min="1" max="10" required>

            <label for="comment">Yorumunuz:</label>
            <textarea id="comment" name="comment" rows="4" required></textarea>

            <button type="submit">Yorum Yap</button>
        </form>
    </div>
</body>
</html>


<?php
session_start();
include 'db.php';

// Kullanıcı ID'sini ve rolünü kontrol et
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Customer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $restaurant_id = (int)$_POST['restaurant_id'];
    $score = (int)$_POST['score'];
    $comment = htmlspecialchars(trim($_POST['comment']));
    $created_at = date('Y-m-d H:i:s');

    // Yorum ve puanı veritabanına ekle
    $stmt = $db->prepare("INSERT INTO comments (user_id, restaurant_id, score, description, created_at) VALUES (:user_id, :restaurant_id, :score, :description, :created_at)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':restaurant_id', $restaurant_id);
    $stmt->bindParam(':score', $score);
    $stmt->bindParam(':description', $comment);
    $stmt->bindParam(':created_at', $created_at);

    if ($stmt->execute()) {
        echo "Yorumunuz başarıyla eklendi!";
    } else {
        echo "Yorum eklenirken bir hata oluştu.";
    }

    header("Location: restaurant_comments.php?id=" . $restaurant_id);
    exit();
}
?>

