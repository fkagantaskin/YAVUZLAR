<?php
session_start();
include 'db.php';

// Restoran ID'sini al
$restaurant_id = (int)$_GET['id'];

// Restoranın yorumlarını ve puanlarını çek
$query = "SELECT comments.description, comments.score, users.username, comments.created_at 
          FROM comments 
          JOIN users ON comments.user_id = users.id 
          WHERE comments.restaurant_id = :restaurant_id AND comments.deleted_at IS NULL";
$stmt = $db->prepare($query);
$stmt->bindParam(':restaurant_id', $restaurant_id);
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ortalama puanı hesapla
$query_avg = "SELECT AVG(score) AS average_score 
              FROM comments 
              WHERE restaurant_id = :restaurant_id AND deleted_at IS NULL";
$stmt_avg = $db->prepare($query_avg);
$stmt_avg->bindParam(':restaurant_id', $restaurant_id);
$stmt_avg->execute();
$average = $stmt_avg->fetch(PDO::FETCH_ASSOC);
$average_score = number_format($average['average_score'], 1);

echo "<h2>Yorumlar</h2>";
echo "<h3>Ortalama Puan: " . $average_score . "/10</h3>";

if (count($comments) > 0) {
    foreach ($comments as $comment) {
        echo "<div class='comment'>";
        echo "<h4>" . $comment['username'] . " - Puan: " . $comment['score'] . "/10</h4>";
        echo "<p>" . $comment['description'] . "</p>";
        echo "<p>Tarih: " . $comment['created_at'] . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>Henüz bu restorana yapılmış bir yorum bulunmamaktadır.</p>";
}
?>
