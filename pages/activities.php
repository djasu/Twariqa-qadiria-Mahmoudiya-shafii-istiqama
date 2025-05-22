<?php
require_once '../includes/config.php';

$stmt = $pdo->query("SELECT * FROM activities WHERE activity_date >= NOW() ORDER BY activity_date ASC");
$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>
<h1 class="mb-4">Nos Activités</h1>

<div class="row">
    <?php foreach ($activities as $activity): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <?php if ($activity['image_url']): ?>
                    <img src="/assets/images/uploads/<?= $activity['image_url'] ?>" class="card-img-top" alt="<?= $activity['title'] ?>">
                <?php endif; ?>
                
                <div class="card-body">
                    <h5 class="card-title"><?= $activity['title'] ?></h5>
                    <p class="card-text"><?= substr($activity['description'], 0, 100) ?>...</p>
                    <p class="text-muted">
                        <i class="bi bi-calendar"></i> <?= format_date($activity['activity_date']) ?>
                        <br>
                        <i class="bi bi-geo-alt"></i> <?= $activity['location'] ?>
                    </p>
                </div>
                
                <div class="card-footer">
                    <a href="/pages/activity-details.php?id=<?= $activity['id'] ?>" class="btn btn-primary">Voir détails</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include '../includes/footer.php'; ?>