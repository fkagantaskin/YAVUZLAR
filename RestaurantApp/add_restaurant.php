<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restoran Ekle</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Form stilleri */
        .add-restaurant-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 32px;
            font-weight: bold;
            color: #e05555;
            text-align: center;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 10px;
        }

        input[type="text"], input[type="file"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .button {
            background-color: #e05555;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            border: none;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .button:hover {
            background-color: #d94343;
            transform: translateY(-2px);
        }

        .button-container {
            text-align: center;
        }

        /* Restoran eklendi mesajı */
        .feedback-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background-color: #e0f4e0; /* Hafif yeşil arka plan */
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 2px solid #28a745; /* Başarı renginde sınır */
        }

        .feedback-message h2 {
            font-size: 28px;
            color: #28a745; /* Yeşil renk */
            margin-bottom: 20px;
        }

        .feedback-message .button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            border-radius: 25px;
            text-align: center;
        }

        .feedback-message .button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="add-restaurant-container">
        <h2>Yeni Restoran Ekle</h2>
        <form action="add_restaurant.php" method="POST" enctype="multipart/form-data">
            <label for="name">Restoran Adı:</label>
            <input type="text" id="name" name="name" required>

            <label for="description">Restoran Açıklaması:</label>
            <input type="text" id="description" name="description" required>

            <label for="image_path">Resim Ekle:</label>
            <input type="file" id="image_path" name="image_path">

            <div class="button-container">
                <button type="submit" class="button">Restoranı Ekle</button>
            </div>
        </form>
    </div>
</body>
</html>



<?php
session_start();
include 'db.php';

// Admin veya Şirket sahibi yetkisi kontrolü
if ($_SESSION['role'] !== 'Admin' && $_SESSION['role'] !== 'Company') {
    header("Location: login.php");
    exit();
}

// Formdan gelen verileri al ve restoranı ekle
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $description = htmlspecialchars(trim($_POST['description']));
    
    // Resim yükleme işlemi
    if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] == 0) {
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["image_path"]["name"]);
        move_uploaded_file($_FILES["image_path"]["tmp_name"], $target_file);
        $image_path = $target_file;
    } else {
        $image_path = ''; // Varsayılan değer
    }

    $company_id = $_SESSION['user_id'];
    $created_at = date('Y-m-d H:i:s');

    // Restoranı veritabanına ekle
    $stmt = $db->prepare("INSERT INTO restaurant (company_id, name, description, image_path, created_at) VALUES (:company_id, :name, :description, :image_path, :created_at)");
    $stmt->bindParam(':company_id', $company_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':image_path', $image_path);
    $stmt->bindParam(':created_at', $created_at);

    if ($stmt->execute()) {
        // Başarı mesajı ve geri dön butonu
        $success_message = "Restoran başarıyla eklendi!";
        $redirect_url = ($_SESSION['role'] === 'Admin') ? 'admin.php' : 'company.php';
        $back_button = "<a href='$redirect_url' class='button'>Geri Dön</a>";
    } else {
        $success_message = "Restoran eklenirken bir hata oluştu.";
        $back_button = "<a href='add_restaurant.php' class='button'>Tekrar Dene</a>";
    }

    // Estetik geri bildirim ekranı
    echo "
        <div class='feedback-container'>
            <div class='feedback-message'>
                <h2>$success_message</h2>
                $back_button
            </div>
        </div>";
    exit();
}
?>
