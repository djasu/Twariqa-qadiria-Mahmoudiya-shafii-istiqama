<!DOCTYPE html>
<html lang="en">
<?php require_once 'contenus/config.php';
$formations = $pdo->query("
    SELECT * FROM formations 
    ORDER BY created_on DESC 
    LIMIT 12
")->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include 'contenus/head.php'; ?>

<style>
    .btn {
        border-radius: 50%;
        color: white;
    }
</style>

<body class="about-page">

    <?php include 'contenus/header.php'; ?>

    <main class="main">
        <!-- Page Title -->
        <div class="page-title light-background">
            <div class="container d-lg-flex justify-content-between align-items-center">
                <h1 class="mb-2 mb-lg-0">Formations</h1>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="index.php">Accuiel</a></li>
                        <li class="current">Formation</li>
                    </ol>
                </nav>
            </div>
        </div><!-- End Page Title -->
        <!-- Hero Section -->
        <section id="portfolio" class="portfolio section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Formation</h2>
                <p>Les Formations disponible</p>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

                    <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
                        <li data-filter="*" class="filter-active">Tout</li>
                        <li data-filter=".filter-app">Avenir</li>
                        <li data-filter=".filter-product">encours</li>
                        <li data-filter=".filter-branding">passer</li>
                    </ul><!-- End Portfolio Filters -->

                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 filter-product">
                        <?php foreach ($formations as $formation): ?>
                            <div class="col">
                                <div class="card shadow-sm">
                                    <?php if ($formation['image']): ?>
                                        <img src="assets/images/formations/<?= $formation['image'] ?>"
                                            class="bd-placeholder-img card-img-top" width="100%" height="225"
                                            alt="<?= htmlspecialchars($formation['titre']) ?>">
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h5 class="card-text"><?= htmlspecialchars($formation['titre']) ?></h5>
                                        <p class="card-text"><?= substr($formation['description'], 0, 100) ?></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-body-secondary">date debut:
                                                <?= htmlspecialchars($formation['date_debut']) ?></small>
                                            <small class="text-body-secondary">date fin:
                                                <?= htmlspecialchars($formation['date_fin']) ?></small>
                                        </div>

                                    </div>
                                    <div class="card-footer d-flex justify-content-between align-items-center">
                                        <span>formateur(trice): <small>
                                                <?= htmlspecialchars($formation['formateur']) ?></small></span>
                                        <form method="post" action="users/inscription_formation.php" class="d-inline">
                                            <input type="hidden" name="formation_id" value="<?= $formation['id'] ?>">
                                            <button type="submit" class="btn btn-warning">s'inscrire</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div><!-- End Portfolio Container -->

                </div>

            </div>

        </section>
    </main>

    <?php include 'contenus/footer.php'; ?>

    <?php include 'contenus/script.php'; ?>

</body>

</html>