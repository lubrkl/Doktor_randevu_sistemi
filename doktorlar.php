<?php
include 'includes/db.php';
include 'includes/header.php';

$sql = "SELECT * FROM doktorlar";
$result = $conn->query($sql);
?>

<div class="container mt-4">
    <h3 class="text-center mb-4">Doktorlarımız</h3>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php while ($row = $result->fetch_assoc()) : ?>
            <div class="col">
                <div class="card h-100 shadow rounded-3">
                    <img src="<?= $row['resim'] ?>" class="card-img-top" alt="Doktor Resmi" style="height: 250px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= $row['ad'] ?></h5>
                        <h6 class="text-muted"><?= $row['uzmanlik'] ?></h6>
                        <p class="card-text"><?= nl2br(htmlspecialchars($row['ozgecmis'])) ?></p>
                    </div>
                    <div class="card-footer bg-white">
                        <small class="text-muted"><i class="bi bi-envelope"></i> <?= $row['email'] ?></small><br>
                        <small class="text-muted"><i class="bi bi-telephone"></i> <?= $row['telefon'] ?></small>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
