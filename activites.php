<!DOCTYPE html>
<html lang="en">
<?php require_once 'contenus/config.php';

// Récupérer les statistiques
$activities_count = $pdo->query("SELECT COUNT(*) FROM evenements")->fetchColumn();
?>
<?php $evenements = $pdo->query("
    SELECT e.*,g.fichier as image,g.type as typef FROM evenements e
    right join galerie g on e.id = g.evenement_id
    ORDER BY created_on DESC 
    LIMIT 6
")->fetchAll(PDO::FETCH_ASSOC);

?>
<?php include 'contenus/head.php'; ?>

<body class="about-page">

    <?php include 'contenus/header.php'; ?>

    <main class="main">
        <!-- Page Title -->
        <div class="page-title light-background">
            <div class="container d-lg-flex justify-content-between align-items-center">
                <h1 class="mb-2 mb-lg-0">Activites</h1>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="index.php">Accuiel</a></li>
                        <li class="current">Activites</li>
                    </ol>
                </nav>
            </div>
        </div><!-- End Page Title -->
        <!-- Hero Section -->
        <section id="portfolio" class="portfolio section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Activites</h2>
                <p>Les Activites disponible</p>
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
                        <?php foreach ($evenements as $evenement): ?>
                            <div class="col">
                                <div class="card shadow-sm">
                                    <?php if (in_array($evenement['typef'], ['photo', 'image'])): ?>
                                        <img src="assets/images/galeries/<?= $evenement['image'] ?>"
                                            class="bd-placeholder-img card-img-top" width="100%" height="225"
                                            alt="<?= htmlspecialchars($evenement['titre']) ?>">
                                    <?php elseif ($evenement['typef'] ==='video'): ?>
                                        <video width="100%" controls>
                                            <source src="assets/images/galeries/<?= $evenement['image'] ?>" type="video/mp4">
                                            Votre navigateur ne supporte pas la vidéo.
                                        </video>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <p class="card-text"><?= substr($evenement['description'], 0, 100) ?></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="btn-group">
                                            </div>
                                            <small class="text-body-secondary">9 mins</small>
                                        </div>
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