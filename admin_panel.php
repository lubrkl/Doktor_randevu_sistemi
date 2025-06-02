<?php
include 'includes/db.php';
session_start();

// Sadece doktorlar erişebilir
if (!isset($_SESSION['id']) || $_SESSION['rol'] != 'doktor') {
    header("Location: login.php");
    exit();
}

$mesaj = "";

// Bugünkü tarih ve doktor adı
$bugun = date('Y-m-d');
$doktor_adi = "Dr. " . $_SESSION['ad'];

// Bugünkü toplam randevu sayısı
$stmt_say = $conn->prepare("SELECT COUNT(*) AS toplam FROM randevular WHERE doktor_adi = ? AND tarih = ?");
$stmt_say->bind_param("ss", $doktor_adi, $bugun);
$stmt_say->execute();
$result = $stmt_say->get_result();
$row = $result->fetch_assoc();
$toplam_randevu = $row['toplam'];

// Güncelleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['randevu_id'], $_POST['islem'])) {
    $randevu_id = $_POST['randevu_id'];
    $islem = $_POST['islem'];

    if (in_array($islem, ['onaylandı', 'iptal'])) {
        $stmt = $conn->prepare("UPDATE randevular SET durum=? WHERE id=?");
        $stmt->bind_param("si", $islem, $randevu_id);
        $stmt->execute();
        $mesaj = "Randevu durumu güncellendi.";
    }
}

// Randevu listesini al
$stmt = $conn->prepare("SELECT r.id, u.ad, u.soyad, r.tarih, r.saat, r.durum FROM randevular r 
                        JOIN users u ON r.user_id = u.id
                        WHERE r.doktor_adi = ?
                        ORDER BY r.tarih DESC, r.saat DESC");
$stmt->bind_param("s", $doktor_adi);
$stmt->execute();
$sonuc = $stmt->get_result();
?>

<?php include 'includes/header.php'; ?>

<h3 class="text-center mb-4">Randevu Yönetimi</h3>

<div class="alert alert-primary text-center fs-5 fw-semibold">
    <i class="bi bi-calendar-check"></i> Bugün için toplam <strong><?= $toplam_randevu ?></strong> randevunuz var.
</div>

<?php if (!empty($mesaj)) : ?>
    <div class="alert alert-success"><?= $mesaj ?></div>
<?php endif; ?>

<?php if ($sonuc->num_rows > 0) : ?>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Hasta Adı</th>
                    <th>Tarih</th>
                    <th>Saat</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $sonuc->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['ad'] . " " . $row['soyad']) ?></td>
                        <td><?= $row['tarih'] ?></td>
                        <td><?= $row['saat'] ?></td>
                        <td>
                            <?php
                            if ($row['durum'] == 'bekliyor')
                                echo "<span class='badge bg-warning text-dark'><i class='bi bi-hourglass-split'></i> Bekliyor</span>";
                            elseif ($row['durum'] == 'onaylandı')
                                echo "<span class='badge bg-success'><i class='bi bi-check-circle'></i> Onaylandı</span>";
                            else
                                echo "<span class='badge bg-danger'><i class='bi bi-x-circle'></i> İptal</span>";
                            ?>
                        </td>
                        <td>
                            <?php if ($row['durum'] == 'bekliyor') : ?>
                                <form method="POST" class="d-flex gap-2">
                                    <input type="hidden" name="randevu_id" value="<?= $row['id'] ?>">
                                    <button name="islem" value="onaylandı" class="btn btn-success btn-sm">
                                        <i class="bi bi-check2-circle"></i> Onayla
                                    </button>
                                    <button name="islem" value="iptal" class="btn btn-danger btn-sm">
                                        <i class="bi bi-x-circle"></i> İptal
                                    </button>
                                </form>
                            <?php else : ?>
                                <em>İşlem yapılmış</em>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else : ?>
    <div class="alert alert-info">Henüz size ait randevu bulunmamaktadır.</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
