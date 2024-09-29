<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$db = new PDO('sqlite:quest_app.db');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $user['role']; 
        $_SESSION['user_id'] = $user['id']; 

        if ($user['role'] === 'admin') {
            header('Location: admin.php');
        } else {
            header('Location: user_dashboard.php'); 
        }
        exit(); 
    } else {
        $error = 'Geçersiz kullanıcı adı veya şifre';
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body style="background-color: black; color: white;">
    <div style="max-width: 400px; margin: auto; padding: 20px; border: 1px solid white; border-radius: 8px; background-color: #1a1a1a;">
        <h1 style="text-align: center;">Giriş Yap</h1>
        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="display: flex; flex-direction: column;">
            <label for="username">Kullanıcı Adı:</label>
            <input type="text" id="username" name="username" required style="padding: 10px; margin: 20px 10px; border: none; border-radius: 4px; width: calc(100% - 40px);"> 
            
            <label for="password">Şifre:</label>
            <input type="password" id="password" name="password" required style="padding: 10px; margin: 20px 10px; border: none; border-radius: 4px; width: calc(100% - 40px);"> 
            
            <input type="submit" value="Giriş Yap" style="padding: 10px; border: none; border-radius: 4px; background-color: #4CAF50; color: white; cursor: pointer; margin: 20px;">
        </form>

        <p style="text-align: center; margin-top: 20px;">
            <a href="register.php" style="text-decoration: none;">
                <button style="padding: 10px; border: none; border-radius: 4px; background-color: #2196F3; color: white; cursor: pointer;">Kayıt Ol</button>
            </a>
        </p>
    </div>
</body>
</html>
