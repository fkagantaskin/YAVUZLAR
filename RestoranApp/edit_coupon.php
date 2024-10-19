<?php
session_start();
include 'db.php';

// Admin yetkisi kontrolü
if ($_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Kupon ID'sini al
$coupon_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Kupon bilgilerini getir
$stmt = $db->prepare("SELECT * FROM cupon WHERE id = :id AND deleted_at IS NULL");
$stmt->bindParam(':id', $coupon_id);
$stmt->execute();
$coupon = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$coupon) {
    echo "Kupon bulunamadı.";
    exit();
}

// Kuponu güncelleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = htmlspecialchars(trim($_POST['code']));
    $discount = (int)$_POST['discount'];
    $expiry_date = $_POST['expiry_date'];

    $update_stmt = $db->prepare("UPDATE cupon SET code = :code, discount = :discount, expiry_date = :expiry_date WHERE id = :id");
    $update_stmt->bindParam(':code', $code);
    $update_stmt->bindParam(':discount', $discount);
    $update_stmt->bindParam(':expiry_date', $expiry_date);
    $update_stmt->bindParam(':id', $coupon_id);

    if ($update_stmt->execute()) {
        header("Location: list_coupons.php");
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
    <title>Kupon Düzenle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <h2>Kupon Düzenle</h2>
        <?php if (!empty($error)) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="edit_coupon.php?id=<?php echo $coupon_id; ?>" method="POST" class="edit-form">
            <div class="form-group">
                <label for="code">Kupon Kodu:</label>
                <input type="text" id="code" name="code" value="<?php echo htmlspecialchars($coupon['code']); ?>" required>
            </div>
            <div class="form-group">
                <label for="discount">İndirim (%):</label>
                <input type="number" id="discount" name="discount" value="<?php echo htmlspecialchars($coupon['discount']); ?>" required>
            </div>
            <div class="form-group">
                <label for="expiry_date">Son Kullanma Tarihi:</label>
                <input type="date" id="expiry_date" name="expiry_date" value="<?php echo htmlspecialchars($coupon['expiry_date']); ?>" required>
            </div>
            <button type="submit" class="button">Güncelle</button>
            <a href="list_coupons.php" class="button cancel">İptal</a>
        </form>
    </div>
</body>
</html>
