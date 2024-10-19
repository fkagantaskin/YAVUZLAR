<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $surname = htmlspecialchars(trim($_POST['surname']));
    $username = htmlspecialchars(trim($_POST['username']));

    $stmt = $db->prepare("UPDATE users SET name = :name, surname = :surname, username = :username WHERE id = :user_id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':surname', $surname);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Profiliniz başarıyla güncellendi!";
    } else {
        echo "Profil güncellenirken bir hata oluştu.";
    }

    header("Location: profile.php");
    exit();
}
?>
