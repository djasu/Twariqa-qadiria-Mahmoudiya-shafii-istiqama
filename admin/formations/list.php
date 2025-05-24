<?php
// Récupérer toutes les formations
$stmt = $pdo->query("
    SELECT *
    FROM formations
    ORDER BY created_on DESC
");
$formations = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                <th>Description</th>
                <th>Formateur</th>
                <th>Image</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($formations as $formation): ?>
                <tr>
                    <td><?= $formation['id'] ?></td>
                    <td><?= htmlspecialchars($formation['titre']) ?></td>
                    <td><?= htmlspecialchars($formation['description']) ?></td>
                    <td><?= htmlspecialchars($formation['formateur']) ?></td>
                    <td>
                        <?php if (!empty($formation['image'])): ?>
                            <img src="../assets/images/formations/<?= $formation['image'] ?>" alt="Image" width="80">
                        <?php else: ?>
                            <em>Aucune image</em>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($formation['date_debut']) ?></td>
                    <td><?= htmlspecialchars($formation['date_fin']) ?></td>
                    <td>
                        <a href="edit_formation.php?id=<?= $formation['id'] ?>" class="btn btn-sm btn-warning">éditer</a>
                        <a href="delete_formation.php?id=<?= $formation['id'] ?>" class="btn btn-sm btn-danger delete-btn">suppr.</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
