<?php
include 'includes/db.php';
session_start();

// Oturum kontrolü
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$ad = $_SESSION['ad'];
$rol = $_SESSION['rol'];
?>

<?php include 'includes/header.php'; ?>

<div class="text-center">
    <h2>Hoş geldin, <strong><?= htmlspecialchars($ad) ?></strong>!</h2>
    <p class="lead">Rolünüz: <?= $rol == 'doktor' ? 'Doktor' : 'Hasta' ?></p>

    <?php if ($rol == 'hasta') : ?>
        <a href="randevu_al.php" class="btn btn-outline-primary btn-lg m-2">Randevu Al</a>
        <a href="randevularim.php" class="btn btn-outline-success btn-lg m-2">Randevularım</a>
    <?php elseif ($rol == 'doktor') : ?>
        <a href="admin_panel.php" class="btn btn-outline-warning btn-lg m-2">Randevu Listesi</a>
    <?php endif; ?>

    <a href="logout.php" class="btn btn-outline-danger btn-lg m-2">Çıkış Yap</a>
</div>

<?php include 'includes/footer.php'; ?>
