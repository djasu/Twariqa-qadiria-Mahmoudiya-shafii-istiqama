<?php
require_once '../includes/config.php';

$article_id = $_GET['id'] ?? 0;

// Récupérer l'article
$stmt = $pdo->prepare("
    SELECT a.*, u.username as author 
    FROM articles a 
    JOIN admins u ON a.author_id = u.id 
    WHERE a.id = ?
");
$stmt->execute([$article_id]);
$article = $stmt->fetch();

if (!$article) {
    header("HTTP/1.0 404 Not Found");
    include '../pages/404.php';
    exit;
}

// Récupérer les articles similaires (même auteur)
$similar_articles = $pdo->prepare("
    SELECT id, title 
    FROM articles 
    WHERE author_id = ? AND id != ? 
    ORDER BY created_at DESC 
    LIMIT 3
");
$similar_articles->execute([$article['author_id'], $article_id]);
$similar_articles = $similar_articles->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <article class="blog-post">
        <!-- Fil d'Ariane -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                <li class="breadcrumb-item"><a href="/pages/blog.php">Blog</a></li>
                <li class="breadcrumb-item active" aria-current="page">Article</li>
            </ol>
        </nav>

        <!-- Image de l'article -->
        <?php if ($article['image_url']): ?>
            <div class="mb-4 text-center">
                <img src="/assets/images/uploads/<?= $article['image_url'] ?>" 
                     class="img-fluid rounded" 
                     alt="<?= htmlspecialchars($article['title']) ?>">
            </div>
        <?php endif; ?>

        <!-- Titre et métadonnées -->
        <h1 class="mb-3"><?= htmlspecialchars($article['title']) ?></h1>
        <div class="d-flex justify-content-between mb-4">
            <div>
                <span class="text-muted">
                    <i class="bi bi-person"></i> 
                    <?= htmlspecialchars($article['author']) ?>
                </span>
                <span class="text-muted ms-3">
                    <i class="bi bi-calendar"></i> 
                    <?= format_date($article['created_at']) ?>
                </span>
            </div>
            <div class="social-share">
                <!-- Boutons de partage sociaux -->
                <a href="#" class="text-muted me-2"><i class="bi bi-facebook"></i></a>
                <a href="#" class="text-muted me-2"><i class="bi bi-twitter"></i></a>
                <a href="#" class="text-muted"><i class="bi bi-whatsapp"></i></a>
            </div>
        </div>

        <!-- Contenu de l'article -->
        <div class="article-content mb-5">
            <?= $article['content'] ?>
        </div>

        <!-- Articles similaires -->
        <?php if (!empty($similar_articles)): ?>
            <div class="similar-articles mt-5 pt-4 border-top">
                <h3 class="h4 mb-4">Autres articles du même auteur</h3>
                <ul class="list-unstyled">
                    <?php foreach ($similar_articles as $similar): ?>
                        <li class="mb-2">
                            <a href="/pages/blog-detail.php?id=<?= $similar['id'] ?>">
                                <?= htmlspecialchars($similar['title']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </article>
</div>

<?php include '../includes/footer.php'; ?>