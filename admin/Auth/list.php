<?php
// Récupération des utilisateurs
$stmt = $pdo->query("SELECT * FROM utilisateurs ORDER BY created_on DESC");
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Image</th>
                <th>Ajouté le</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utilisateurs as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['nom']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td>
                        <?php if (!empty($user['image'])): ?>
                            <img src="../assets/images/users/<?= $user['image'] ?>" alt="Image" width="60" class="rounded-circle">
                        <?php else: ?>
                            <em>Aucune image</em>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($user['created_on']) ?></td>
                    <td>
                        <a href="edit_utilisateur.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning">éditer</a>
                        <a href="delete_utilisateur.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger delete-btn">suppr.</a>
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
            if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
                window.location.href = this.href;
            }
        });
    });
</script>