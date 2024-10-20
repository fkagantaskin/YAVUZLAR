<?php
session_start();
include 'db.php';

// Admin yetkisi kontrolü
if ($_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <h2>Admin Paneli</h2>
        <p>Hoş geldiniz, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <div class="admin-links">
            <a href="list_coupons.php" class="admin-button">Kupon Yönetimi</a>
            <a href="add_restaurant.php" class="admin-button">Restoran Ekle</a>
            <a href="list_users.php" class="admin-button">Kullanıcı Yönetimi</a>
            <a href="logout.php" class="admin-button logout">Çıkış Yap</a>
        </div>
    </div>
</body>
</html>
