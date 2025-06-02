<?php
include 'includes/db.php';

session_start(); // Oturum başlat

$mesaj = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $sifre = $_POST['sifre'];

    // Veritabanında kullanıcıyı arıyoruz
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND sifre=?");
    $stmt->bind_param("ss", $email, $sifre);
    $stmt->execute();
    $sonuc = $stmt->get_result();

    if ($sonuc->num_rows == 1) {
        $kullanici = $sonuc->fetch_assoc();
        $_SESSION['id'] = $kullanici['id'];
        $_SESSION['ad'] = $kullanici['ad'];
        $_SESSION['rol'] = $kullanici['rol'];

        // Rola göre yönlendirme
        if ($kullanici['rol'] == 'doktor') {
            header("Location: admin_panel.php");
        } else {
            header("Location: anasayfa.php");
        }
        exit();
    } else {
        $mesaj = "Email veya şifre yanlış!";
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h3 class="text-center mb-4">Giriş Yap</h3>
        <?php if (!empty($mesaj)) : ?>
            <div class='alert alert-danger'><?= $mesaj ?></div>
        <?php endif; ?>
        <form method="POST" class="border p-4 shadow rounded bg-light">
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Şifre</label>
                <input type="password" name="sifre" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Giriş Yap</button>
            <p class="text-center mt-3">Hesabınız yok mu? <a href="register.php">Kayıt Ol</a></p>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
