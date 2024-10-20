<?php
session_start(); // Oturum başlatılıyor

// $_SESSION'in tanımlı olup olmadığını kontrol et
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Eğer oturum açılmadıysa, login sayfasına yönlendir
    exit();
}

include 'db.php';

// Müşteri yetkisi kontrolü
if ($_SESSION['role'] !== 'Customer') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Müşteri Paneli</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .balance-form {
            display: none; /* Başlangıçta gizli */
            margin-top: 20px;
        }

        .balance-message {
            margin-top: 20px;
            color: green;
        }

        .balance-error {
            margin-top: 20px;
            color: red;
        }
    </style>
    <script>
        function toggleBalanceForm() {
            var form = document.getElementById("balance-form");
            if (form.style.display === "none" || form.style.display === "") {
                form.style.display = "block"; // Formu göster
            } else {
                form.style.display = "none"; // Formu gizle
            }
        }

        function addBalance(event) {
            event.preventDefault(); // Sayfanın yeniden yüklenmesini engelle

            var amount = document.getElementById('amount').value;

            // Ajax isteği ile add_balance.php dosyasına veri gönder
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'add_balance.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Başarı mesajını göster
                        document.getElementById('balance-message').innerText = response.message + " Yeni bakiyeniz: ₺" + response.new_balance;
                        document.getElementById('balance-message').style.display = 'block';
                    } else {
                        // Hata mesajını göster
                        document.getElementById('balance-error').innerText = response.message;
                        document.getElementById('balance-error').style.display = 'block';
                    }
                }
            };

            xhr.send('amount=' + amount); // Form verilerini gönder
        }
    </script>
</head>
<body>
    <div class="customer-container">
        <h2>Müşteri Paneli</h2>
        <p>Hoş geldiniz, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p> <!-- Burada $_SESSION kullanıyoruz -->

        <div class="customer-links">
            <a href="order_history.php" class="button">Sipariş Geçmişinizi Görüntüleyin</a>
            <a href="profile.php" class="button">Profil Bilgilerinizi Güncelleyin</a>
            <a href="restaurants.php" class="button">Restoranları Görüntüleyin ve Sipariş Verin</a>
            <a href="#" class="button" onclick="toggleBalanceForm()">Bakiye Yükle</a>
            <a href="logout.php" class="button logout">Çıkış Yap</a>
        </div>

        <!-- Bakiye Yükleme Formu -->
        <div id="balance-form" class="balance-form">
            <h2>Bakiye Yükle</h2>
            <form id="balanceForm" onsubmit="addBalance(event)">
                <div class="form-group">
                    <label for="amount">Yükleme Tutarı (₺):</label>
                    <input type="number" id="amount" name="amount" min="1" required>
                </div>
                <button type="submit" class="button">Onayla</button>
            </form>
            <p id="balance-message" class="balance-message" style="display: none;"></p>
            <p id="balance-error" class="balance-error" style="display: none;"></p>
        </div>
    </div>
</body>
</html>
