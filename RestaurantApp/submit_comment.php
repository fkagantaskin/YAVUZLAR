<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $restaurant_id = $_POST['restaurant_id'];
    $comment = !empty($_POST['comment']) ? $_POST['comment'] : null; // Yorum boş olabilir
    $rating = (int) $_POST['rating'];
    $user_id = $_SESSION['user_id'];

    // Yorum veya sadece puanı veritabanına ekle
    $stmt = $db->prepare("INSERT INTO comments (user_id, restaurant_id, order_id, comment, score) 
                          VALUES (:user_id, :restaurant_id, :order_id, :comment, :score)");
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':restaurant_id', $restaurant_id, PDO::PARAM_INT);
    $stmt->bindValue(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->bindValue(':comment', $comment, PDO::PARAM_STR); // Yorum boş olabilir
    $stmt->bindValue(':score', $rating, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        echo "Yorum ve/veya puan başarıyla eklendi!";
    } else {
        echo "Bir hata oluştu.";
    }
    
    header("Location: order_history.php");
    exit();
}
?>
