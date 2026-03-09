<?php $evenements = $pdo->query("
    SELECT e.*,g.fichier as image FROM evenements e
    right join galerie g on e.id = g.evenement_id
    ORDER BY created_on DESC 
    LIMIT 3
")->fetchAll(PDO::FETCH_ASSOC);

?>
<?php $evenementsch_futur = $pdo->query("
    SELECT * FROM evenements
    ORDER BY created_on DESC 
    LIMIT 4
")->fetchAll(PDO::FETCH_ASSOC);

?>
<section id="portfolio" class="portfolio section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>Activites</h2>
        <p>Les Activites disponible</p>
    </div><!-- End Section Title -->

    <div class="container">

        <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

            <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
                <li data-filter="*" class="filter-active"><a href="activites.php" style="color:white;">Voir plus</a>
                </li>
            </ul><!-- End Portfolio Filters -->

            <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">
                <?php foreach ($evenements as $evenement): ?>
                    <div class="col-lg-4 col-md-6 d-flex portfolio-item isotope-item filter-product">
                        <?php if ($evenement['image']): ?>
                            <img src="assets/images/galeries/<?= $evenement['image'] ?>" class="img-fluid"
                                alt="<?= htmlspecialchars($evenement['titre']) ?>">
                        <?php endif; ?>
                        <div class="portfolio-info">
                            <h4><?= htmlspecialchars($evenement['titre']) ?></h4>
                            <p><?= htmlspecialchars($evenement['description']) ?></p>
                            <a href="assets/images/galeries/<?= $evenement['image'] ?>"
                                title="<?= htmlspecialchars($evenement['titre']) ?>"
                                data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i
                                    class="bi bi-zoom-in"></i></a>
                            <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                    class="bi bi-link-45deg"></i></a>
                        </div>
                    </div>
                    <!-- End Portfolio Item -->
                <?php endforeach; ?>
            </div><!-- End Portfolio Container -->

        </div>

    </div>

</section>