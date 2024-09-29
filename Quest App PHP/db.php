<?php
include 'config.php';

function fetchQuestions() {
    global $db;
    try {
        $stmt = $db->prepare("SELECT * FROM questions");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Sorgu hatası: " . $e->getMessage());
    }
}
?>
