<?php
session_start(); 

$db = new PDO('sqlite:quest_app.db');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, ?)');
    $stmt->execute([$username, $hashed_password, 'user']); 

    $successMessage = "Kullanıcı başarıyla eklendi! Giriş yapabilirsiniz.";
    
    header('Location: login.php'); 
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Kullanıcı Ekle</title>
</head>
<body>
    <h1>Yeni Kullanıcı Ekle</h1>
    <form method="POST" action="">
        <label for="username">Kullanıcı Adı:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Şifre:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <input type="submit" value="Kullanıcı Ekle">
    </form>

    <?php if (isset($successMessage)): ?>
        <p style="color: green;"><?php echo $successMessage; ?></p>
    <?php endif; ?>
</body>
</html>
