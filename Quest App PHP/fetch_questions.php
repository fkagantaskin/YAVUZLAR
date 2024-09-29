<?php
include 'config.php';

header('Content-Type: application/json'); 

try {
    $stmt = $db->query("SELECT id, question, option1, option2, option3, option4, correct_option FROM questions");
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($questions);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Soru verileri çekilemedi.']);
}
?>
