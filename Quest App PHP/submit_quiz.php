<?php
session_start();
include 'db.php'; 

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php'); 
    exit();
}

$user_id = $_SESSION['user_id'];
$correct_answers = $_POST['correct_answers']; 
$total_questions = $_POST['total_questions']; 
$score = ($correct_answers / $total_questions) * 100; 


try {
    $stmt = $db->prepare("INSERT INTO submissions (user_id, quiz_date, correct_answers, total_questions, score) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $quiz_date, $correct_answers, $total_questions, $score]);
    
    header('Location: scoreboard.php'); 
    exit();
} catch (PDOException $e) {
    die("Sonuç kaydedilirken bir hata oluştu: " . $e->getMessage());
}
?>
