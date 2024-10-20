<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$amount = (int) $_POST['amount']; 

$stmt = $db->prepare("SELECT balance FROM users WHERE id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $new_balance = $user['balance'] + $amount;
    $update_stmt = $db->prepare("UPDATE users SET balance = :new_balance WHERE id = :user_id");
    $update_stmt->bindParam(':new_balance', $new_balance, PDO::PARAM_INT);
    $update_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $update_stmt->execute();

    echo json_encode([
        'success' => true,
        'message' => 'Bakiye başarıyla eklendi.',
        'new_balance' => $new_balance
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Bakiye yüklenemedi. Lütfen tekrar deneyin.'
    ]);
}
?>
