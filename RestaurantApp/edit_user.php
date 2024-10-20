<?php
session_start();
include 'db.php';

// Admin yetkisi kontrolü
if ($_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Kullanıcı ID'sini al
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Kullanıcı bilgilerini getir
$stmt = $db->prepare("SELECT * FROM users WHERE id = :id AND deleted_at IS NULL");
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Kullanıcı bulunamadı.";
    exit();
}

// Kullanıcıyı güncelleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $surname = htmlspecialchars(trim($_POST['surname']));
    $username = htmlspecialchars(trim($_POST['username']));
    $role = htmlspecialchars(trim($_POST['role']));

    $update_stmt = $db->prepare("UPDATE users SET name = :name, surname = :surname, username = :username, role = :role WHERE id = :id");
    $update_stmt->bindParam(':name', $name);
    $update_stmt->bindParam(':surname', $surname);
    $update_stmt->bindParam(':username', $username);
    $update_stmt->bindParam(':role', $role);
    $update_stmt->bindParam(':id', $user_id);

    if ($update_stmt->execute()) {
        header("Location: list_users.php?status=updated");
        exit();
    } else {
        $error = "Güncelleme sırasında bir hata oluştu.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Düzenle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <h2>Kullanıcı Düzenle</h2>
        <?php if (!empty($error)) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="edit_user.php?id=<?php echo $user_id; ?>" method="POST" class="edit-form">
            <div class="form-group">
                <label for="name">Ad:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="surname">Soyad:</label>
                <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($user['surname']); ?>" required>
            </div>
            <div class="form-group">
                <label for="username">Kullanıcı Adı:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="role">Rol:</label>
                <select id="role" name="role" required>
                    <option value="Admin" <?php if ($user['role'] == 'Admin') echo 'selected'; ?>>Admin</option>
                    <option value="Company" <?php if ($user['role'] == 'Company') echo 'selected'; ?>>Company</option>
                    <option value="Customer" <?php if ($user['role'] == 'Customer') echo 'selected'; ?>>Customer</option>
                </select>
            </div>
            <button type="submit" class="button">Güncelle</button>
            <a href="list_users.php" class="button cancel">İptal</a>
        </form>
    </div>
</body>
</html>
