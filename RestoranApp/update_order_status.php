<?php
session_start();
include 'db.php';

// Kullanıcı rolü kontrolü: Sadece Company rolüne sahip kullanıcılar bu sayfaya erişebilir.
if ($_SESSION['role'] !== 'Company') {
    header("Location: login.php");
    exit();
}

// Sipariş ID'sini ve yeni durumu al
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = (int)$_POST['order_id'];
    $new_status = htmlspecialchars(trim($_POST['order_status']));

    // Siparişin durumunu güncelle
    $stmt = $db->prepare("UPDATE orders SET order_status = :new_status WHERE id = :order_id");
    $stmt->bindParam(':new_status', $new_status, PDO::PARAM_STR);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Sipariş durumu başarıyla güncellendi!";
    } else {
        echo "Durum güncellenirken bir hata oluştu.";
    }

    header("Location: company_orders.php");
    exit();
}
?>
