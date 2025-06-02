<?php
include 'includes/db.php';
session_start();

if (!isset($_SESSION['id']) || $_SESSION['rol'] != 'hasta') {
    header("Location: login.php");
    exit();
}

$mesaj = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['id'];
    $doktor_adi = $_POST['doktor_adi'];
    $tarih = $_POST['tarih'];
    $saat = $_POST['saat'];
    $notlar = $_POST['notlar'];

    // Çift rezervasyon kontrolü
    $kontrol = $conn->prepare("SELECT * FROM randevular WHERE doktor_adi=? AND tarih=? AND saat=?");
    $kontrol->bind_param("sss", $doktor_adi, $tarih, $saat);
    $kontrol->execute();
    $varmi = $kontrol->get_result();

    if ($varmi->num_rows > 0) {
        $mesaj = "❗ Bu saat için zaten randevu alınmış. Lütfen başka bir saat seçin.";
    } else {
        $stmt = $conn->prepare("INSERT INTO randevular (user_id, doktor_adi, tarih, saat, notlar) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $doktor_adi, $tarih, $saat, $notlar);

        if ($stmt->execute()) {
            $mesaj = "✅ Randevu başarıyla alındı!";
        } else {
            $mesaj = "❌ Hata oluştu: " . $stmt->error;
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h3 class="text-center mb-4">Randevu Al</h3>
        <?php if (!empty($mesaj)) : ?>
            <div class="alert alert-info"><?= $mesaj ?></div>
        <?php endif; ?>

        <form method="POST" class="border p-4 shadow rounded bg-light">
            <div class="mb-3">
    <label>Doktor Seçiniz</label>
    <select name="doktor_adi" class="form-select" required>
        <option value="">-- Doktor Seçin --</option>
        <?php
        $doktorlar = $conn->query("SELECT ad FROM doktorlar ORDER BY ad");
        while ($d = $doktorlar->fetch_assoc()) {
            echo "<option value='{$d['ad']}'>{$d['ad']}</option>";
        }
        ?>
             </select>
         </div>


            <div class="mb-3">
                <label>Notunuz (isteğe bağlı)</label>
                <textarea name="notlar" class="form-control" rows="3" placeholder="Belirti, sebep, açıklama yazabilirsiniz."></textarea>
            </div>

            <div class="mb-3">
                <label>Tarih</label>
                <input type="date" name="tarih" class="form-control" required min="<?= date('Y-m-d') ?>">
            </div>

            <div class="mb-3">
                <label>Saat</label>
                <select name="saat" class="form-select" required>
                    <?php
                    $saatler = [
                        "09:00", "10:00", "11:00", "12:00",
                        "13:00", "14:00", "15:00", "16:00"
                    ];
                    foreach ($saatler as $s) {
                        echo "<option value='$s'>$s</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Randevu Oluştur</button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
