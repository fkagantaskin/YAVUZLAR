<?php
session_start();
session_destroy(); // Tüm oturumları sonlandır
header("Location: login.php"); // Giriş sayfasına yönlendir
exit();
?>
