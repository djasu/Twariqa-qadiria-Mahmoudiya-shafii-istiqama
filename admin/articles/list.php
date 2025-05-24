<?php

// Récupérer tous les articles
$stmt = $pdo->query("
    SELECT a.*, u.nom as auteur 
    FROM articles a 
    JOIN utilisateurs u ON a.auteur_id = u.id 
    ORDER BY a.created_on DESC
");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                <th>ID</th>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $article): ?>
                <tr>
                    <td><?= $article['id'] ?></td>
                    <td><?= htmlspecialchars($article['titre']) ?></td>
                    <td><?= htmlspecialchars($article['auteur']) ?></td>
                    <td><?= htmlspecialchars($article['created_on']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $article['id'] ?>" class="btn btn-sm btn-warning">
                            edit
                        </a>
                        <a href="delete.php?id=<?= $article['id'] ?>" class="btn btn-sm btn-danger delete-btn">
                            del
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>