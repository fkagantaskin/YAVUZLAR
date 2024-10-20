<?php
session_start();
include 'db.php';

// Admin yetkisi kontrolü
if ($_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Silinmemiş kuponları getir
$stmt = $db->query("SELECT cupon.id, cupon.code, cupon.discount, cupon.expiry_date FROM cupon WHERE cupon.deleted_at IS NULL");
$coupons = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kupon Yönetimi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="admin-container">
    <h2>Kupon Yönetimi</h2>
    <a href="admin.php" class="button back-button">Geri</a>
    <table class="styled-table">
        <thead>
            <tr>
                <th>Kod</th>
                <th>İndirim</th>
                <th>Son Kullanma Tarihi</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($coupons) > 0): ?>
                <?php foreach ($coupons as $coupon) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($coupon['code']); ?></td>
                        <td><?php echo htmlspecialchars($coupon['discount']); ?>%</td>
                        <td><?php echo htmlspecialchars($coupon['expiry_date']); ?></td>
                        <td>
                            <a href="edit_coupon.php?id=<?php echo $coupon['id']; ?>" class="button edit">Düzenle</a>
                            <a href="delete_coupon.php?id=<?php echo $coupon['id']; ?>" class="button delete">Sil</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="no-data">Henüz kupon eklenmemiş.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
