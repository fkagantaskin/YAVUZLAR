<?php 
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $correct_option = $_POST['correct_option'];

    try {
        $stmt = $db->prepare("INSERT INTO questions (question, option1, option2, option3, option4, correct_option) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$question, $option1, $option2, $option3, $option4, $correct_option]);
        echo "Soru başarıyla eklendi!";
        header('Location: admin.php'); 
        exit();
    } catch (PDOException $e) {
        die("Soru eklenemedi: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soru Ekle</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body style="background-color: black; color: white;">
    <div style="max-width: 800px; margin: auto; padding: 20px;">
        <h1 style="text-align: center;">Soru Ekle</h1>
        <form method="POST" action="">
            <label for="question">Soru:</label>
            <input type="text" id="question" name="question" required style="width: 100%; padding: 10px; margin: 10px 0;">
            
            <label for="option1">Seçenek 1:</label>
            <input type="text" id="option1" name="option1" required style="width: 100%; padding: 10px; margin: 10px 0;">
            
            <label for="option2">Seçenek 2:</label>
            <input type="text" id="option2" name="option2" required style="width: 100%; padding: 10px; margin: 10px 0;">
            
            <label for="option3">Seçenek 3:</label>
            <input type="text" id="option3" name="option3" required style="width: 100%; padding: 10px; margin: 10px 0;">
            
            <label for="option4">Seçenek 4:</label>
            <input type="text" id="option4" name="option4" required style="width: 100%; padding: 10px; margin: 10px 0;">
            
            <label for="correct_option">Doğru Seçenek (1-4):</label>
            <input type="number" id="correct_option" name="correct_option" min="1" max="4" required style="width: 100%; padding: 10px; margin: 10px 0;">
            
            <input type="submit" value="Soru Ekle" style="padding: 10px; border: none; border-radius: 4px; background-color: #4CAF50; color: white; cursor: pointer; width: 100%;">
        </form>
    </div>
</body>
</html>
