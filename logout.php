<?php
session_start();         // Oturumu başlat
session_unset();         // Tüm oturum değişkenlerini sil
session_destroy();       // Oturumu tamamen sonlandır

header("Location: login.php"); // Giriş ekranına yönlendir
exit();
?>
