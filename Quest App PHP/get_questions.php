<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$db = new PDO('sqlite:quest_app.db');

$stmt = $db->query("SELECT * FROM questions");
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($questions);
?>
