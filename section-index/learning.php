<?php $formations = $pdo->query("
    SELECT * FROM formations 
    ORDER BY created_on DESC 
    LIMIT 4
")->fetchAll(PDO::FETCH_ASSOC);

?>
<section id="services" class="services section">
    <div class="container section-title" data-aos="fade-up">
        <h2>Formations</h2>
        <p>Formations nécessaires pour jeunes musulmans</p>
    </div>

    <div class="container">
        <div class="row gy-4">
            <?php foreach ($formations as $formation): ?>
                <div class="col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-item d-flex align-items-center gap-3 position-relative w-100 h-100 p-3"
                        style="border: 1px solid #ddd; border-radius: 5px;">
                        <?php if ($formation['image']): ?>
                            <img src="assets/images/formations/<?= htmlspecialchars($formation['image']) ?>"
                                style="width: 80px; height: 80px; object-fit: cover;"
                                alt="<?= htmlspecialchars($formation['titre']) ?>">
                        <?php endif; ?>
                        <div>
                            <h4><a href="#" class="stretched-link"><?= htmlspecialchars($formation['titre']) ?></a></h4>
                            <p><?= htmlspecialchars($formation['description']) ?></p>
                            <span>Formateur : <?= htmlspecialchars($formation['formateur']) ?></span>
                        </div>
                    </div>
                </div>


            <?php endforeach; ?>
        </div>
    </div>
</section>