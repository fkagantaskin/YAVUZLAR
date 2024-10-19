<?php
session_start();
include 'db.php';

// Kullanıcı oturumunu kontrol et
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Sepet öğesini sepetten çıkar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $basket_id = (int)$_POST['basket_id'];

    // Sepetten öğeyi sil
    $stmt = $db->prepare("DELETE FROM basket WHERE id = :basket_id AND user_id = :user_id");
    $stmt->bindParam(':basket_id', $basket_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Ürün sepetten çıkarıldı.";
    } else {
        echo "Ürün sepetten çıkarılırken bir hata oluştu.";
    }

    header("Location: basket.php");
    exit();
}
?>
