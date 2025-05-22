<?php
require_once '../includes/auth.php';
admin_only();

// Récupérer les statistiques
$articles_count = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$activities_count = $pdo->query("SELECT COUNT(*) FROM activities")->fetchColumn();
$unread_contacts = $pdo->query("SELECT COUNT(*) FROM contacts WHERE is_read = 0")->fetchColumn();
?>

<?php include '../includes/header.php'; ?>
<h2 class="mb-4">Tableau de Bord</h2>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Articles</h5>
                <p class="card-text"><?= $articles_count ?> publiés</p>
                <a href="/admin/articles/list.php" class="btn btn-light">Gérer</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Activités</h5>
                <p class="card-text"><?= $activities_count ?> planifiées</p>
                <a href="/admin/activities/list.php" class="btn btn-light">Gérer</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Messages</h5>
                <p class="card-text"><?= $unread_contacts ?> non lus</p>
                <a href="/admin/contacts/" class="btn btn-light">Voir</a>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>