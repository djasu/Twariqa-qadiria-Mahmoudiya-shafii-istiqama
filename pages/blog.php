<?php
require_once '../includes/config.php';

// Pagination
$per_page = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $per_page;

// Compter le nombre total d'articles
$total = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$total_pages = ceil($total / $per_page);

// Récupérer les articles paginés
$stmt = $pdo->prepare("
    SELECT a.*, u.username as author 
    FROM articles a 
    JOIN admins u ON a.author_id = u.id 
    ORDER BY a.created_at DESC 
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h1 class="mb-4">Blog de la Qadiriyya</h1>

    <div class="row">
        <?php foreach ($articles as $article): ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <?php if ($article['image_url']): ?>
                        <img src="/assets/images/uploads/<?= $article['image_url'] ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($article['title']) ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h2 class="card-title h4"><?= htmlspecialchars($article['title']) ?></h2>
                        <p class="card-text">
                            <?= substr(strip_tags($article['content']), 0, 200) ?>...
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                Par <?= htmlspecialchars($article['author']) ?> le 
                                <?= format_date($article['created_at']) ?>
                            </small>
                            <a href="/pages/blog-detail.php?id=<?= $article['id'] ?>" 
                               class="btn btn-sm btn-primary">
                                Lire plus
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<?php include '../includes/footer.php'; ?>