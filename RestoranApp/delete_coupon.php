<?php
session_start();
include 'db.php';

// Admin yetkisi kontrolü
if ($_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Kupon ID'sini GET parametresinden al
$coupon_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($coupon_id > 0) {
    $deleted_at = date('Y-m-d H:i:s');

    // Kuponu soft delete yap
    $stmt = $db->prepare("UPDATE cupon SET deleted_at = :deleted_at WHERE id = :coupon_id");
    $stmt->bindParam(':deleted_at', $deleted_at);
    $stmt->bindParam(':coupon_id', $coupon_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Başarılıysa kupon silindi mesajı
        header("Location: list_coupons.php?status=deleted");
        exit();
    } else {
        // Hata mesajı
        header("Location: list_coupons.php?status=error");
        exit();
    }
} else {
    echo "Geçersiz kupon ID.";
    exit();
}
?>
