<?php
include 'config.php';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); 
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $correct_option = $_POST['correct_option'];

    try {
        $stmt = $db->prepare("UPDATE questions SET question = ?, option1 = ?, option2 = ?, option3 = ?, option4 = ?, correct_option = ? WHERE id = ?");
        $stmt->execute([$question, $option1, $option2, $option3, $option4, $correct_option, $id]);
        header('Location: admin.php'); 
        exit();
    } catch (PDOException $e) {
        die("Soru güncellenemedi: " . $e->getMessage());
    }
}

$id = $_GET['id'] ?? null;
if ($id) {
    try {
        $stmt = $db->prepare("SELECT * FROM questions WHERE id = ?");
        $stmt->execute([$id]);
        $questionData = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Soru alınamadı: " . $e->getMessage());
    }
} else {
    die("Soru ID'si belirtilmemiş.");
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soru Düzenle</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body style="background-color: black; color: white;">
    <div style="max-width: 800px; margin: auto; padding: 20px;">
        <h1 style="text-align: center;">Soru Düzenle</h1>
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $questionData['id']; ?>">
            <label for="question">Soru:</label>
            <input type="text" id="question" name="question" value="<?php echo htmlspecialchars($questionData['question']); ?>" required style="width: 100%; padding: 10px; margin: 10px 0;">
            
            <label for="option1">Seçenek 1:</label>
            <input type="text" id="option1" name="option1" value="<?php echo htmlspecialchars($questionData['option1']); ?>" required style="width: 100%; padding: 10px; margin: 10px 0;">
            
            <label for="option2">Seçenek 2:</label>
            <input type="text" id="option2" name="option2" value="<?php echo htmlspecialchars($questionData['option2']); ?>" required style="width: 100%; padding: 10px; margin: 10px 0;">
            
            <label for="option3">Seçenek 3:</label>
            <input type="text" id="option3" name="option3" value="<?php echo htmlspecialchars($questionData['option3']); ?>" required style="width: 100%; padding: 10px; margin: 10px 0;">
            
            <label for="option4">Seçenek 4:</label>
            <input type="text" id="option4" name="option4" value="<?php echo htmlspecialchars($questionData['option4']); ?>" required style="width: 100%; padding: 10px; margin: 10px 0;">
            
            <label for="correct_option">Doğru Seçenek (1-4):</label>
            <input type="number" id="correct_option" name="correct_option" min="1" max="4" value="<?php echo htmlspecialchars($questionData['correct_option']); ?>" required style="width: 100%; padding: 10px; margin: 10px 0;">
            
            <input type="submit" value="Soru Güncelle" style="padding: 10px; border: none; border-radius: 4px; background-color: #4CAF50; color: white; cursor: pointer; width: 100%;">
        </form>
        <a href="admin.php" style="display: block; text-align: center; margin-top: 20px; color: white;">Geri Dön</a>
    </div>
</body>
</html>
