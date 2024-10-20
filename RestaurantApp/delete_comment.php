<?php
session_start();
include 'db.php';

// Admin yetkisi kontrolü
if ($_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Yorum ID'sini al ve soft delete yap
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comment_id = (int)$_POST['comment_id'];
    $deleted_at = date('Y-m-d H:i:s');

    // Yorumu sil
    $stmt = $db->prepare("UPDATE comments SET deleted_at = :deleted_at WHERE id = :comment_id");
    $stmt->bindParam(':deleted_at', $deleted_at);
    $stmt->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Yorum başarıyla silindi.";
    } else {
        echo "Yorum silinirken bir hata oluştu.";
    }

    header("Location: restaurant_comments.php?id=" . $_POST['restaurant_id']);
    exit();
}
?>
