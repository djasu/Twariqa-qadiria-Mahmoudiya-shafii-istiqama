<?php
// Récupérer toutes les ressources
$stmt = $pdo->query("
    SELECT *
    FROM ressources
    ORDER BY created_on DESC
");
$ressources = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                <th>Auteur</th>
                <th>Image</th>
                <th>Fichier</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ressources as $item): ?>
                <tr>
                    <td><?= $item['id'] ?></td>
                    <td><?= htmlspecialchars($item['titre']) ?></td>
                    <td><?= htmlspecialchars($item['type']) ?></td>
                    <td><?= htmlspecialchars($item['auteur']) ?></td>
                    <td>
                        <?php if (!empty($item['image'])): ?>
                            <img src="../assets/images/resources/<?= $item['image'] ?>" alt="Image" width="80">
                        <?php else: ?>
                            <em>Aucune image</em>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($item['fichier'])): ?>
                            <a href="../assets/fichiers/ressources/<?= $item['fichier'] ?>" target="_blank">Voir</a>
                        <?php else: ?>
                            <em>Aucun fichier</em>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit_ressource.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">éditer</a>
                        <a href="delete_ressource.php?id=<?= $item['id'] ?>" class="btn btn-sm btn-danger delete-btn">suppr.</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    document.querySelectorAll('.delete-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            if (confirm('Êtes-vous sûr de vouloir supprimer cette ressource ?')) {
                window.location.href = this.href;
            }
        });
    });
</script>