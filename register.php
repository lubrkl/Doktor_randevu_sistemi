<?php
include 'includes/db.php';

$mesaj = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $email = $_POST['email'];
    $sifre = $_POST['sifre'];
    $rol = $_POST['rol'];

    $stmt = $conn->prepare("INSERT INTO users (ad, soyad, email, sifre, rol) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $ad, $soyad, $email, $sifre, $rol);

    if ($stmt->execute()) {
        $mesaj = "Kayıt başarılı! Giriş yapabilirsiniz.";
    } else {
        $mesaj = "Hata: " . $stmt->error;
    }
    $stmt->close();
}
?>

<?php include 'includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h3 class="text-center mb-4">Kayıt Ol</h3>
        <?php if ($mesaj) echo "<div class='alert alert-info'>$mesaj</div>"; ?>
        <form method="POST" class="border p-4 shadow rounded bg-light">
            <div class="mb-3">
                <label>Adınız</label>
                <input type="text" name="ad" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Soyadınız</label>
                <input type="text" name="soyad" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Şifre</label>
                <input type="password" name="sifre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Rol</label>
                <select name="rol" class="form-select">
                    <option value="hasta">Hasta</option>
                    <option value="doktor">Doktor</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Kayıt Ol</button>
            <p class="text-center mt-3">Zaten hesabın var mı? <a href="login.php">Giriş yap</a></p>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
