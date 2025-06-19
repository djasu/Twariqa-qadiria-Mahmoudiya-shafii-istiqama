<?php $formations = $pdo->query("
    SELECT * FROM formations 
    ORDER BY created_on DESC 
    LIMIT 4
")->fetchAll(PDO::FETCH_ASSOC);

?>
<section id="services" class="services section">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h2>Formation</h2>
        <p>Formations neccessaires pour jeunes musulmans<br></p>
    </div><!-- End Section Title -->
    <div class="container d-flex">
        <div class="row gy-4">
            <?php foreach ($formations as $formation): ?>

                <div class="col-md-6 d-flex" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-item d-flex position-relative h-100">

                        <?php if ($formation['image']): ?>
                            <img src="assets/images/formations/<?= $formation['image'] ?>" class="icon flex-shrink-0 "
                                alt="<?= htmlspecialchars($formation['titre']) ?>">
                        <?php endif; ?>
                        <div>
                            <h4 class="title"><a href="#"
                                    class="stretched-link"><?= htmlspecialchars($formation['titre']) ?></a></h4>
                            <p class="description"><?= htmlspecialchars($formation['description']) ?></p>
                            <span>Formateur: <?= htmlspecialchars($formation['formateur']) ?></span>
                        </div>
                    </div>
                </div><!-- End Service Item -->
            <?php endforeach; ?>
        </div>

    </div>

</section>