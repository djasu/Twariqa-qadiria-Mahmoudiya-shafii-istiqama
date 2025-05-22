<?php
require_once '../includes/config.php';

// Récupérer les 3 derniers articles
$articles = $pdo->query("
    SELECT a.*, u.username as author 
    FROM articles a 
    JOIN admins u ON a.author_id = u.id 
    ORDER BY a.created_at DESC 
    LIMIT 3
")->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les prochaines activités
$activities = $pdo->query("
    SELECT * FROM activities 
    WHERE activity_date >= NOW() 
    ORDER BY activity_date ASC 
    LIMIT 3
")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero bg-primary text-white py-5 mb-4">
    <div class="container text-center">
        <h1 class="display-4">Bienvenue à la Qadiriyya</h1>
        <p class="lead">Confrérie soufie suivant l'enseignement de Cheikh Abdelkader Al-Jilani</p>
    </div>
</section>

<div class="container">
    <!-- Derniers Articles -->
    <section class="mb-5">
        <h2 class="mb-4 border-bottom pb-2">Derniers Articles</h2>
        <div class="row">
            <?php foreach ($articles as $article): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if ($article['image_url']): ?>
                            <img src="/assets/images/uploads/<?= $article['image_url'] ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($article['title']) ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($article['title']) ?></h5>
                            <p class="card-text">
                                <?= substr(strip_tags($article['content']), 0, 100) ?>...
                            </p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <small class="text-muted">
                                Par <?= htmlspecialchars($article['author']) ?> le 
                                <?= format_date($article['created_at']) ?>
                            </small>
                            <a href="/pages/blog-detail.php?id=<?= $article['id'] ?>" 
                               class="btn btn-sm btn-primary float-end">
                                Lire plus
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-3">
            <a href="/pages/blog.php" class="btn btn-outline-primary">
                Voir tous les articles
            </a>
        </div>
    </section>

    <!-- Prochaines Activités -->
    <section class="mb-5">
        <h2 class="mb-4 border-bottom pb-2">Prochaines Activités</h2>
        <div class="row">
            <?php foreach ($activities as $activity): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if ($activity['image_url']): ?>
                            <img src="/assets/images/uploads/<?= $activity['image_url'] ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($activity['title']) ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($activity['title']) ?></h5>
                            <p class="card-text">
                                <?= substr($activity['description'], 0, 100) ?>...
                            </p>
                            <p class="text-muted">
                                <i class="bi bi-calendar"></i> 
                                <?= format_date($activity['activity_date']) ?>
                                <br>
                                <i class="bi bi-geo-alt"></i> 
                                <?= htmlspecialchars($activity['location']) ?>
                            </p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="/pages/activity-detail.php?id=<?= $activity['id'] ?>" 
                               class="btn btn-sm btn-primary">
                                Voir détails
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-3">
            <a href="/pages/activities.php" class="btn btn-outline-primary">
                Voir toutes les activités
            </a>
        </div>
    </section>
</div>

<?php include '../includes/footer.php'; ?>