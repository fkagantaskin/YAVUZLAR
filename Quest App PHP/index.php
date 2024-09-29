<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php'); 
    exit();
}


include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correctAnswers = $_POST['correctAnswers']; 
    $totalQuestions = $_POST['totalQuestions']; 

    if ($totalQuestions > 0) {
        $score = ($correctAnswers / $totalQuestions) * 100;
    } else {
        $score = 0;
    }

    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $db->prepare("INSERT INTO submissions (user_id, correct_answers, total_questions, score) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $correctAnswers, $totalQuestions, $score]);
    } catch (PDOException $e) {
        die("Skor kaydedilirken bir hata oluştu: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Bilgi Yarışması</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="quizQontainer">
        <div id="welcomeScreen">
            <h1>Yavuzlar Bilgi Yarışmasına Hoş Geldiniz</h1>
            <button id="startQuizButton" onclick="startQuiz()">Yarışmaya Başla</button>
        </div>
        <div id="quizScreen" style="display:none; justify-content: center; align-items: center;">
            <div id="questionContainer">
                <p id="questionText"></p>
                <ul id="answersList">
                </ul>
            </div>
            <div id="feedback" style="display:none;">
                <p id="feedbackText"></p>
                <button id="nextQuestionButton" style="display:none;">Sıradaki Soru</button>
            </div>
        </div>
        <div id="resultScreen" style="display:none; justify-content: center; align-items: center; padding: 3px; margin: 3px;">
            <p id="scoreText" style="color: white;"></p>
            <button id="retryButton" style="text-align: center;" onclick="retryQuiz()">Tekrar Dene</button>
        </div>
    </div>
    <script src="quiz.js"></script>
    <script>
        function startQuiz() {
            <?php 
            $_SESSION['current_question'] = 0; 
            $_SESSION['correctAnswers'] = 0; 
            ?>
            document.getElementById('welcomeScreen').style.display = 'none';
            document.getElementById('quizScreen').style.display = 'flex';
            loadQuestion();
        }

        function retryQuiz() {
            location.reload();
        }

        function loadQuestion() {
        }
    </script>
</body>
</html>
