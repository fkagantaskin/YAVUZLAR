<?php 
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];

    // Veritabanında kullanıcıyı bulma
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Kullanıcı bulunduysa ve şifre doğruysa giriş yap
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Eğer kullanıcı Company rolündeyse company_id'yi oturuma ekle
        if ($user['role'] === 'Company') {
            $company_stmt = $db->prepare("SELECT company_id FROM users WHERE id = :user_id");
            $company_stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
            $company_stmt->execute();
            $company = $company_stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['company_id'] = $company['company_id'];  // company_id oturuma ekleniyor
        }

        // Role göre yönlendirme yapalım
        if ($_SESSION['role'] === 'Admin') {
            header("Location: admin.php");
        } elseif ($_SESSION['role'] === 'Company') {
            header("Location: company.php");
        } else {
            header("Location: customer.php");
        }
        exit();
    } else {
        $error = "Kullanıcı adı veya şifre yanlış.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>Giriş Yap</h2>
        <?php if (!empty($error)) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST" class="login-form">
            <div class="form-group">
                <label for="username">Kullanıcı Adı:</label>
                <input type="text" id="username" name="username" placeholder="Kullanıcı adınızı girin" required>
            </div>
            <div class="form-group">
                <label for="password">Şifre:</label>
                <input type="password" id="password" name="password" placeholder="Şifrenizi girin" required>
            </div>
            <button type="submit" class="button">Giriş Yap</button>
        </form>
        <p>Hesabınız yok mu? <a href="register.php">Kayıt Ol</a></p>
    </div>
</body>
</html>
