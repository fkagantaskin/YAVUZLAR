<?php
include 'db.php';

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php'); 
    exit();
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    die("Kullanıcı ID'si bulunamadı.");
}

try {
    $stmt = $db->prepare("SELECT * FROM submissions WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $scores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Skorlar alınırken bir hata oluştu: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Puan Tablosu</title>
    <style>
        body {
            background-color: black;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 20px;
        }
        h1 {
            display: inline; 
            margin: 0; 
        }
        .header {
            margin-bottom: 20px; 
        }
        .back-button {
            color: red; 
            text-decoration: none; 
            padding: 5px 10px; 
            border: 1px solid red; 
            border-radius: 5px; 
            margin-left: 10px; 
        }
        table {
            width: 80%; 
            margin: 0 auto; 
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: green; 
            color: white; 
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Puan Tablosu</h1>
        <a href="user_dashboard.php" class="back-button">Geri Dön</a>
    </div>
    
    <?php if (empty($scores)): ?>
        <p>Henüz bir quiz çözmediniz.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Doğru Cevap</th>
                <th>Toplam Soru</th>
                <th>Skor (%)</th>
            </tr>
            <?php foreach ($scores as $score): ?>
                <tr>
                    <td><?php echo htmlspecialchars($score['correct_answers']); ?></td>
                    <td><?php echo htmlspecialchars($score['total_questions']); ?></td>
                    <td><?php echo htmlspecialchars($score['score']); ?>%</td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
