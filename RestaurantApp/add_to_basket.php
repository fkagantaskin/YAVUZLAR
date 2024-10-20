<?php
session_start();
include 'db.php';

// Kullanıcı oturumunu kontrol et
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$food_id = isset($_POST['food_id']) ? (int)$_POST['food_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

// Geçerli bir yemek ve miktar kontrolü
if ($food_id > 0 && $quantity > 0) {
    // Aynı yemek daha önce sepete eklenmiş mi kontrol et
    $query = "SELECT quantity FROM basket WHERE user_id = :user_id AND food_id = :food_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':food_id', $food_id, PDO::PARAM_INT);
    $stmt->execute();
    $existing_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_item) {
        // Sepette aynı yemek varsa miktarı artır
        $new_quantity = $existing_item['quantity'] + $quantity;
        $update_query = "UPDATE basket SET quantity = :new_quantity WHERE user_id = :user_id AND food_id = :food_id";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bindParam(':new_quantity', $new_quantity, PDO::PARAM_INT);
        $update_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $update_stmt->bindParam(':food_id', $food_id, PDO::PARAM_INT);
        $update_stmt->execute();
    } else {
        // Sepette aynı yemek yoksa yeni bir giriş yap
        $insert_query = "INSERT INTO basket (user_id, food_id, quantity) VALUES (:user_id, :food_id, :quantity)";
        $insert_stmt = $db->prepare($insert_query);
        $insert_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $insert_stmt->bindParam(':food_id', $food_id, PDO::PARAM_INT);
        $insert_stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $insert_stmt->execute();
    }
}

// Kullanıcıyı sepete geri yönlendir
header("Location: basket.php");
exit();
