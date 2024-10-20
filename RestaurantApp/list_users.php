<?php
session_start();
include 'db.php';

// Admin yetkisi kontrolü
if ($_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Kullanıcıları getir (soft delete yapılmış kullanıcıları hariç tutarak)
$stmt = $db->query("SELECT id, username, name, surname, role FROM users WHERE deleted_at IS NULL");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Yönetimi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <h2>Kullanıcı Yönetimi</h2>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Kullanıcı Adı</th>
                    <th>Ad</th>
                    <th>Soyad</th>
                    <th>Rol</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($users) > 0): ?>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['surname']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="button edit">Düzenle</a>
                                <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="button delete">Sil</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-data">Henüz kullanıcı eklenmemiş.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="admin.php" class="button back-button">Geri</a>
    </div>
</body>
</html>
