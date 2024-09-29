<?php
include 'config.php';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); 
    exit();
}

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $db->prepare("DELETE FROM questions WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: admin.php'); 
        exit();
    } catch (PDOException $e) {
        die("Soru silinemedi: " . $e->getMessage());
    }
} else {
    die("Soru ID'si belirtilmemiş.");
}
