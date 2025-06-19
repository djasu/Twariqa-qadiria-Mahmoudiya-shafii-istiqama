<!DOCTYPE html>
<html lang="en">
<?php require_once 'contenus/config.php';
$articles = $pdo->query("
    SELECT a.*,u.nom FROM articles a
    inner join utilisateurs u on a.auteur_id = u.id 
    ORDER BY id DESC 
    LIMIT 2
")->fetchAll(PDO::FETCH_ASSOC);

$req = $pdo->query("SELECT id, titre, image, date_publication FROM articles ORDER BY date_publication DESC LIMIT 5");
$recent_articles = $req->fetchAll(PDO::FETCH_ASSOC);

$articlesParPage = 2; // Nombre d'articles par page
// Vérification de la page actuelle
$pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($pageActuelle < 1) {
    $pageActuelle = 1; // Assurez-vous que la page actuelle est au moins 1
}
$offset = ($pageActuelle - 1) * $articlesParPage;
$totalArticles = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$totalPages = ceil($totalArticles / $articlesParPage);
$articles = $pdo->query("
    SELECT a.*, u.nom 
    FROM articles a
    INNER JOIN utilisateurs u ON a.auteur_id = u.id 
    ORDER BY a.id DESC 
    LIMIT $offset, $articlesParPage
")->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include 'contenus/head.php'; ?>

<body class="about-page">

    <?php include 'contenus/header.php'; ?>

    <main class="main">
        <!-- Page Title -->
        <div class="page-title light-background">
            <div class="container d-lg-flex justify-content-between align-items-center">
                <h1 class="mb-2 mb-lg-0">Article</h1>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="index.html">Accueil</a></li>
                        <li class="current">
                            <Article></Article>
                        </li>
                    </ol>
                </nav>
            </div>
        </div><!-- End Page Title -->

        <div class="container">
            <div class="row">

                <div class="col-lg-8">

                    <!-- Blog Posts Section -->
                    <section id="blog-posts" class="blog-posts section">
                        <div class="container">
                            <div class="row gy-4">
                                <?php foreach ($articles as $article): ?>
                                    <div class="col-lg-12">
                                        <article>
                                            <div class="post-img">
                                                <img src="assets/images/articles/<?= htmlspecialchars($article['image']) ?>"
                                                    alt="<?= htmlspecialchars($article['titre']) ?>" class="img-fluid">
                                            </div>

                                            <h2 class="title">
                                                <a
                                                    href="blog-details.php?id=<?= $article['id'] ?>"><?= htmlspecialchars($article['titre']) ?></a>
                                            </h2>

                                            <div class="meta-top">
                                                <ul>
                                                    <li class="d-flex align-items-center"><i class="bi bi-person"></i> <a
                                                            href="#"><?= htmlspecialchars($article['nom']) ?></a></li>
                                                    <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <a
                                                            href="#"><time
                                                                datetime="<?= $article['date_publication'] ?>"><?= date('d M Y', strtotime($article['date_publication'])) ?></time></a>
                                                    </li>
                                                    <!-- Commentaires désactivés pour le moment -->
                                                </ul>
                                            </div>

                                            <div class="content">
                                                <p>
                                                    <?= substr(strip_tags($article['contenu']), 0, 250) ?>...
                                                </p>

                                                <div class="read-more">
                                                    <a href="blog-details.php?id=<?= $article['id'] ?>">Lire plus</a>
                                                </div>
                                            </div>

                                        </article>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </section>
                    <!-- /Blog Posts Section -->
                    <!-- Blog Pagination Section -->
                    <section id="blog-pagination" class="blog-pagination section">
                        <div class="container">
                            <div class="d-flex justify-content-center">
                                <ul>
                                    <?php if ($pageActuelle > 1): ?>
                                        <li><a href="?page=<?= $pageActuelle - 1 ?>"><i class="bi bi-chevron-left"></i></a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li><a href="?page=<?= $i ?>"
                                                class="<?= $i == $pageActuelle ? 'active' : '' ?>"><?= $i ?></a></li>
                                    <?php endfor; ?>

                                    <?php if ($pageActuelle < $totalPages): ?>
                                        <li><a href="?page=<?= $pageActuelle + 1 ?>"><i class="bi bi-chevron-right"></i></a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </section>

                </div>
                <div class="col-lg-4 sidebar">

                    <div class="widgets-container">

                        <!-- Search Widget -->
                        <div class="search-widget widget-item">

                            <h3 class="widget-title">Search</h3>
                            <form action="">
                                <input type="text">
                                <button type="submit" title="Search"><i class="bi bi-search"></i></button>
                            </form>

                        </div><!--/Search Widget -->

                        <!-- Categories Widget -->
                        <div class="categories-widget widget-item">

                            <h3 class="widget-title">Categories</h3>
                            <ul class="mt-3">
                                <li><a href="#">General <span>(25)</span></a></li>
                                <li><a href="#">Lifestyle <span>(12)</span></a></li>
                            </ul>

                        </div><!--/Categories Widget -->

                        <!-- Recent Posts Widget -->
                        <div class="recent-posts-widget widget-item">
                            <h3 class="widget-title">Recent Posts</h3>

                            <?php foreach ($recent_articles as $article): ?>
                                <div class="post-item d-flex mb-3">
                                    <img src="assets/images/articles/<?= htmlspecialchars($article['image']) ?>"
                                        alt="<?= htmlspecialchars($article['titre']) ?>" class="flex-shrink-0"
                                        style="width: 80px; height: 80px; object-fit: cover; margin-right: 10px;">
                                    <div>
                                        <h4 class="mb-1" style="font-size: 15px;">
                                            <a
                                                href="blog-details.php?id=<?= $article['id'] ?>"><?= htmlspecialchars($article['titre']) ?></a>
                                        </h4>
                                        <time
                                            datetime="<?= $article['date_publication'] ?>"><?= date('M d, Y', strtotime($article['date_publication'])) ?></time>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!--/Recent Posts Widget -->

                        <!-- Tags Widget -->
                        <div class="tags-widget widget-item">

                            <h3 class="widget-title">Tags</h3>
                            <ul>
                                <li><a href="#">App</a></li>
                                <li><a href="#">IT</a></li>
                            </ul>

                        </div><!--/Tags Widget -->

                    </div>

                </div>

            </div>
        </div>
    </main>

    <?php include 'contenus/footer.php'; ?>

    <?php include 'contenus/script.php'; ?>

</body>

</html>