<?php
// Récupérer tous les événements
$stmt = $pdo->query("
    SELECT * FROM evenements
    ORDER BY created_on DESC
");
$evenements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Titre</th>
                <th>Type</th>
                <th>Lieu</th>
                <th>Date</th>
                <th>Ajouté le</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($evenements as $event): ?>
                <tr>
                    <td><?= $event['id'] ?></td>
                    <td><?= htmlspecialchars($event['titre']) ?></td>
                    <td><?= htmlspecialchars($event['type']) ?></td>
                    <td><?= htmlspecialchars($event['lieu']) ?></td>
                    <td><?= htmlspecialchars($event['date']) ?></td>
                    <td><?= htmlspecialchars($event['created_on']) ?></td>
                    <td>
                        <a href="edit_evenement.php?id=<?= $event['id'] ?>" class="btn btn-sm btn-warning">éditer</a>
                        <a href="delete_evenement.php?id=<?= $event['id'] ?>" class="btn btn-sm btn-danger delete-btn">suppr.</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>