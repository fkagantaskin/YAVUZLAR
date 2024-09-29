<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php'); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Paneli</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body style="background-color: black; color: white;">
    <div style="max-width: 800px; margin: auto; padding: 20px;">
        <h1 style="text-align: center;">Hoş Geldin, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p style="text-align: center;">Burada quiz çözebilir ve puanlarını görebilirsin.</p>
        <div style="text-align: center; margin-top: 20px;">
            <a href="index.php" style="padding: 10px 20px; margin: 10px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px;">Quiz Çöz</a>
            <a href="scoreboard.php" style="padding: 10px 20px; margin: 10px; background-color: #2196F3; color: white; text-decoration: none; border-radius: 4px;">Puanlarını Görüntüle</a>
            <a href="logout.php" style="padding: 10px 20px; margin: 10px; background-color: #f44336; color: white; text-decoration: none; border-radius: 4px;">Çıkış Yap</a>
        </div>
    </div>
</body>
</html>
