<?php
// Récupérer toutes les galeries avec le titre de l'événement lié
$stmt = $pdo->query("
    SELECT g.*, 
           e.titre AS evenement_titre
    FROM galerie g
    INNER JOIN evenements e ON g.evenement_id = e.id
    ORDER BY g.created_on DESC
");
$galeries = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                <th>Événement</th>
                <th>Type</th>
                <th>Description</th>
                <th>Fichier</th>
                <th>Ajouté le</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($galeries as $item): ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><?= htmlspecialchars($item['evenement_titre']) ?></td>
                    <td><?= htmlspecialchars($item['type']) ?></td>
                    <td><?= htmlspecialchars($item['description']) ?></td>
                    <td>
                        <?php if (in_array($item['type'], ['photo', 'image'])): ?>
                            <img src="../assets/images/galeries/<?= $item['fichier'] ?>" alt="Image" width="80">
                        <?php elseif ($item['type'] === 'video'): ?>
                            <video width="120" controls>
                                <source src="../assets/images/galeries/<?= $item['fichier'] ?>" type="video/mp4">
                                Votre navigateur ne supporte pas la vidéo.
                            </video>
                        <?php elseif ($item['type'] === 'audio'): ?>
                            <audio controls>
                                <source src="../assets/images/galeries/<?= $item['fichier'] ?>" type="audio/mpeg">
                                Votre navigateur ne supporte pas l'audio.
                            </audio>
                        <?php else: ?>
                            <a href="../assets/images/galeries/<?= $item['fichier'] ?>" target="_blank">Voir le fichier</a>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($item['created_on']) ?></td>
                    <td>
                        <a href="edit_galerie.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">éditer</a>
                        <a href="delete_galerie.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-danger delete-btn">suppr.</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
