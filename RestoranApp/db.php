<?php
try {
    // SQLite veritabanına bağlanıyoruz
    $db = new PDO('sqlite:./restaurantApp.db');
    // Hata modunu ayarla
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Veritabanı bağlantı hatası: " . $e->getMessage();
}
?>
