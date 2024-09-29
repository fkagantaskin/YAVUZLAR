<?php
$db = new PDO('sqlite:quest_app.db');

$username = 'admin';
$password = password_hash('admin123', PASSWORD_DEFAULT); 
$role = 'admin';

$stmt = $db->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, ?)');
$stmt->execute([$username, $password, $role]);

echo "Kullanıcı başarıyla eklendi!";
?>
