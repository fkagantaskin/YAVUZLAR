<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $surname = htmlspecialchars(trim($_POST['surname']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];
    $role = "Customer"; // Yeni kayıt olan kullanıcılara varsayılan olarak "Customer" rolü atanıyor.
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $created_at = date('Y-m-d H:i:s');

    // Kullanıcıyı veritabanına ekle
    $stmt = $db->prepare("INSERT INTO users (name, surname, username, password, role, created_at) VALUES (:name, :surname, :username, :password, :role, :created_at)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':surname', $surname);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':created_at', $created_at);

    if ($stmt->execute()) {
        // Başarılı kayıt sonrası kullanıcıyı login sayfasına yönlendir.
        header("Location: login.php");
        exit;
    } else {
        $error = "Kayıt sırasında bir hata oluştu.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="register-container">
        <h2>Kayıt Ol</h2>
        <?php if (!empty($error)) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="register.php" method="POST" class="register-form">
            <div class="form-group">
                <label for="name">İsim:</label>
                <input type="text" id="name" name="name" placeholder="İsminizi girin" required>
            </div>
            <div class="form-group">
                <label for="surname">Soyisim:</label>
                <input type="text" id="surname" name="surname" placeholder="Soyisminizi girin" required>
            </div>
            <div class="form-group">
                <label for="username">Kullanıcı Adı:</label>
                <input type="text" id="username" name="username" placeholder="Kullanıcı adınızı girin" required>
            </div>
            <div class="form-group">
                <label for="password">Şifre:</label>
                <input type="password" id="password" name="password" placeholder="Şifrenizi girin" required>
            </div>
            <button type="submit" class="button">Kayıt Ol</button>
        </form>
        <p>Zaten bir hesabınız var mı? <a href="login.php">Giriş Yap</a></p>
    </div>
</body>
</html>
