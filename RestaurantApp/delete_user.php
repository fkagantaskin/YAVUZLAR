<?php
session_start();
include 'db.php';

// Admin yetkisi kontrolü
if ($_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Kullanıcı ID'sini al ve soft delete yap
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($user_id > 0) {
    $deleted_at = date('Y-m-d H:i:s');

    // Kullanıcıyı soft delete yap
    $stmt = $db->prepare("UPDATE users SET deleted_at = :deleted_at WHERE id = :user_id");
    $stmt->bindParam(':deleted_at', $deleted_at);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: list_users.php?status=deleted");
        exit();
    } else {
        $error = "Kullanıcı silinirken bir hata oluştu.";
        header("Location: list_users.php?status=error");
        exit();
    }
} else {
    echo "Geçersiz kullanıcı ID.";
    exit();
}
?>
