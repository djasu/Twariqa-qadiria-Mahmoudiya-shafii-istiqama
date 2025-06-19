<!DOCTYPE html>
<html lang="en">
<?php require_once 'contenus/config.php';
$ressources = $pdo->query("
    SELECT * FROM ressources 
    ORDER BY created_on DESC 
    LIMIT 12
")->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include 'contenus/head.php'; ?>

<style>
    .ressource-item {
        transition: transform 0.2s ease;
    }

    .ressource-item:hover {
        transform: scale(1.02);
        cursor: pointer;
    }
</style>

<body class="about-page">

    <?php include 'contenus/header.php'; ?>

    <main class="main">
        <!-- Page Title -->
        <div class="page-title light-background">
            <div class="container d-lg-flex justify-content-between align-items-center">
                <h1 class="mb-2 mb-lg-0">Ressources</h1>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="index.php">Accuiel</a></li>
                        <li class="current">Ressources</li>
                    </ol>
                </nav>
            </div>
        </div><!-- End Page Title -->
        <!-- Hero Section -->
        <section id="e-learning-resources" class="py-5">
            <div class="container">
                <div class="row">
                    <!-- Liste des ressources -->
                    <div class="col-md-4 border-end" id="ressource-list">
                        <h4 class="mb-4">📚 Liste des ressources</h4>
                        <div class="mb-3">
                            <input type="text" id="searchInput" class="form-control"
                                placeholder="🔍 Rechercher une ressource...">

                        </div>
                        <?php foreach ($ressources as $ressource): ?>
                            <div class="mb-3 ressource-item"
                                data-fichier="assets/fichiers/ressources/<?= urlencode($ressource['fichier']) ?>"
                                data-titre="<?= htmlspecialchars($ressource['titre']) ?>"
                                data-auteur="<?= htmlspecialchars($ressource['auteur']) ?>"
                                data-image="assets/images/resources/<?= htmlspecialchars($ressource['image']) ?>">
                                <div class="card cursor-pointer shadow-sm">
                                    <div class="card-body p-3">
                                        <h6 class="mb-1"><?= htmlspecialchars($ressource['titre']) ?></h6>
                                        <small class="text-muted"><?= htmlspecialchars($ressource['type']) ?> |
                                            by: <?= htmlspecialchars($ressource['auteur']) ?></small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Lecteur -->
                    <div class="col-md-8" id="lecteur-container" style="display: none;">
                        <div class="d-flex align-items-center mb-3">
                            <img id="lecteur-image" src="" alt="image" class="me-3"
                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 10px; display: none;">
                            <div>
                                <h5 id="titre-lecture">Lecture</h5>
                                <small id="auteur-lecture" class="text-muted"></small>
                            </div>
                        </div>
                        <div class="mb-3 d-flex justify-content-between">
                            <a id="btn-telecharger" href="#" download class="btn btn-outline-success">⬇️ Télécharger</a>
                            <button class="btn btn-outline-danger btn-sm" id="fermer-lecteur">Fermer</button>
                        </div>
                        <iframe id="lecteur-fichier" src="" width="100%" height="600px"
                            style="border: 1px solid #ccc;"></iframe>
                    </div>
                </div>
            </div>
        </section>

        <?php include 'contenus/script_ressources.php'; ?>
    </main>

    <!-- Footer -->

    <?php include 'contenus/footer.php'; ?>

    <?php include 'contenus/script.php'; ?>

</body>

</html>