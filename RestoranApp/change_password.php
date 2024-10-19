<?php
session_start();
include 'db.php';

// Kullanıcı oturumunu kontrol et
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    // Eski şifreyi doğrula
    $stmt = $db->prepare("SELECT password FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (password_verify($old_password, $user['password'])) {
        // Yeni şifreyi hash'le ve güncelle
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET password = :password WHERE id = :user_id");
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Şifreniz başarıyla güncellendi!";
        } else {
            echo "Şifre güncellenirken bir hata oluştu.";
        }
    } else {
        echo "Eski şifre yanlış. Lütfen tekrar deneyin.";
    }

    header("Location: profile.php");
    exit();
}
?>
