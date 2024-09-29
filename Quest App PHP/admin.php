<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); 
    exit();
}

include 'config.php'; 

try {
    $stmt = $db->query("SELECT * FROM questions");
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Sorular alınamadı: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color: black; color: white;">
    <div style="max-width: 800px; margin: auto; padding: 20px;">
        <h1 style="text-align: center;">Hoş Geldin, Admin <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p style="text-align: center;">Soru Paneline Hoşgeldin!</p>
        <p style="text-align: center;">
            <a href="add_questions.php" style="text-decoration: none;">
                <button style="padding: 10px; border: none; border-radius: 4px; background-color: #4CAF50; color: white; cursor: pointer;">Soru Ekle</button>
            </a>
        </p>
        
        <h2 style="text-align: center;">Soruları Yönet</h2>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="border: 1px solid white; padding: 10px;">ID</th>
                    <th style="border: 1px solid white; padding: 10px;">Soru</th>
                    <th style="border: 1px solid white; padding: 10px;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($questions as $question): ?>
                    <tr>
                        <td style="border: 1px solid white; padding: 10px;"><?php echo htmlspecialchars($question['id']); ?></td>
                        <td style="border: 1px solid white; padding: 10px;"><?php echo htmlspecialchars($question['question']); ?></td>
                        <td style="border: 1px solid white; padding: 10px;">
                            <a href="edit_question.php?id=<?php echo htmlspecialchars($question['id']); ?>" style="color: #2196F3; text-decoration: none;">Düzenle</a>
                            <span style="margin: 0 5px;">|</span>
                            <a href="delete_question.php?id=<?php echo htmlspecialchars($question['id']); ?>" style="color: #F44336; text-decoration: none;">Sil</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p style="text-align: center; margin-top: 20px;">
            <a href="logout.php" style="text-decoration: none;">
                <button style="padding: 10px; border: none; border-radius: 4px; background-color: #F44336; color: white; cursor: pointer;">Çıkış Yap</button>
            </a>
        </p>
    </div>
</body>
</html>
