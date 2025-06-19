<!DOCTYPE html>
<html lang="en">
<?php require_once 'contenus/config.php';
$evenements = $pdo->query("
    SELECT e.*,g.fichier as image FROM evenements e
    right join galerie g on e.id = g.evenement_id
    ORDER BY created_on DESC 
")->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include 'contenus/head.php'; ?>

<body class="about-page">

    <?php include 'contenus/header.php'; ?>

    <main class="main">
                <!-- Page Title -->
        <div class="page-title light-background">
            <div class="container d-lg-flex justify-content-between align-items-center">
                <h1 class="mb-2 mb-lg-0">Galerie</h1>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="index.php">Accuiel</a></li>
                        <li class="current">Galerie</li>
                    </ol>
                </nav>
            </div>
        </div><!-- End Page Title -->
        <section id="portfolio" class="portfolio section">
            <div class="album py-5 bg-body-tertiary">
                <div class="container">

                    <div class="isotope-layout" data-default-filter="*" data-layout="masonry"
                        data-sort="original-order">

                        <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">
                            <?php foreach ($evenements as $evenement): ?>
                                <div class="col-lg-4 col-md-6 d-flex portfolio-item isotope-item filter-product">
                                    <?php if ($evenement['image']): ?>
                                        <img src="assets/images/galeries/<?= $evenement['image'] ?>" class="img-fluid"
                                            width="100%" height="225" alt="<?= htmlspecialchars($evenement['titre']) ?>">
                                    <?php endif; ?>
                                    <div class="portfolio-info">
                                        <a href="assets/images/galeries/<?= $evenement['image'] ?>"
                                            title="<?= htmlspecialchars($evenement['titre']) ?>"
                                            data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i
                                                class="bi bi-zoom-in"></i></a>
                                    </div>
                                </div>
                                <!-- End Portfolio Item -->
                            <?php endforeach; ?>
                        </div><!-- End Portfolio Container -->

                    </div>

                </div>
            </div>
        </section>

    </main>

    <?php include 'contenus/footer.php'; ?>

    <?php include 'contenus/script.php'; ?>

</body>

</html>