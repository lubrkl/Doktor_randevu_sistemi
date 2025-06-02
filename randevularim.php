<?php
include 'includes/db.php';
session_start();

// Sadece giriş yapan hasta görebilir
if (!isset($_SESSION['id']) || $_SESSION['rol'] != 'hasta') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];

// Bu hastaya ait randevuları çek
$stmt = $conn->prepare("SELECT doktor_adi, tarih, saat, durum FROM randevular WHERE user_id = ? ORDER BY tarih DESC, saat DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$sonuc = $stmt->get_result();
?>

<?php include 'includes/header.php'; ?>

<h3 class="text-center mb-4">Randevularım</h3>

<?php if ($sonuc->num_rows > 0) : ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Doktor</th>
                    <th>Tarih</th>
                    <th>Saat</th>
                    <th>Durum</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $sonuc->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['doktor_adi']) ?></td>
                        <td><?= $row['tarih'] ?></td>
                        <td><?= $row['saat'] ?></td>
                        <td>
                            <?php
                            if ($row['durum'] == 'bekliyor') echo "<span class='badge bg-warning text-dark'>Bekliyor</span>";
                            elseif ($row['durum'] == 'onaylandı') echo "<span class='badge bg-success'>Onaylandı</span>";
                            else echo "<span class='badge bg-danger'>İptal</span>";
                            ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else : ?>
    <div class="alert alert-info">Henüz hiç randevunuz yok.</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
