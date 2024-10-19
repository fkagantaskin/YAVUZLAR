<?php
session_start();
include 'db.php';

// Kullanıcı oturumunu kontrol et
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Kullanıcı bilgilerini çek
$stmt = $db->prepare("SELECT * FROM users WHERE id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="profile-container">
        <h2>Profil Bilgileriniz</h2>
        <form action="update_profile.php" method="POST" class="profile-form">
            <div class="form-group">
                <label for="name">İsim:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="surname">Soyisim:</label>
                <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($user['surname']); ?>" required>
            </div>
            <div class="form-group">
                <label for="username">Kullanıcı Adı:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <button type="submit" class="button">Güncelle</button>
        </form>

        <h2>Şifre Değiştir</h2>
        <form action="change_password.php" method="POST" class="profile-form">
            <div class="form-group">
                <label for="old_password">Eski Şifre:</label>
                <input type="password" id="old_password" name="old_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">Yeni Şifre:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <button type="submit" class="button">Şifre Değiştir</button>
        </form>

        <a href="customer.php" class="button back-button">Geri</a>
    </div>
</body>
</html>
