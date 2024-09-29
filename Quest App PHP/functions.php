<?php
$dbFile = 'C:\Users\F.Kağan\OneDrive\Masaüstü\PHP Quest\quest_app.db';

try {
    $db = new PDO('sqlite:' . $dbFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Veritabanı bağlantı hatası: " . $e->getMessage();
}
?>
