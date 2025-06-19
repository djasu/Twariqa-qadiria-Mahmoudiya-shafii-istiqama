<?php
require_once '../contenus/config.php';

// Vérifier si l'étudiant est connecté
if (!isset($_SESSION['etudiant_id'])) {
    header('Location: ../login.php');
    exit;
}

// Récupérer les informations de l'étudiant
$stmt = $pdo->prepare("SELECT * FROM etudiants WHERE id = ?");
$stmt->execute([$_SESSION['etudiant_id']]);
$etudiant = $stmt->fetch();

// Récupérer les formations avec progression
$formations = $pdo->prepare("
    SELECT 
        f.*,
        i.date_inscription,
        i.statut as statut_inscription,
        COUNT(DISTINCT m.id) as total_modules,
        COUNT(DISTINCT l.id) as total_lecons,
        SUM(CASE WHEN p.statut = 'termine' THEN 1 ELSE 0 END) as lecons_terminees
    FROM formations f
    JOIN inscriptions i ON f.id = i.formation_id
    LEFT JOIN modules m ON f.id = m.formation_id
    LEFT JOIN lecons l ON m.id = l.module_id
    LEFT JOIN progression p ON p.lecon_id = l.id AND p.etudiant_id = ?
    WHERE i.etudiant_id = ?
    GROUP BY f.id
    ORDER BY i.date_inscription DESC
");
$formations->execute([$_SESSION['etudiant_id'], $_SESSION['etudiant_id']]);
$formations = $formations->fetchAll();

include 'include/head.php';
?>

<body class="mes-formations-page">
    <main class="main">
        <div class="container-fluid py-4">
            <div class="row">
                <?php include 'include/sidebar.php'; ?>
            </div>
        </div>
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">

            <div class="container-fluid">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Mes Formations</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="../formations.php" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-plus"></i> S'inscrire à une nouvelle formation
                        </a>
                    </div>
                </div>

                <?php if (empty($formations)): ?>
                    <div class="alert alert-info">
                        Vous n'êtes inscrit à aucune formation.
                        <a href="formations.php">Parcourir les formations disponibles</a>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($formations as $formation):
                            $pourcentage = $formation['total_lecons'] > 0
                                ? round(($formation['lecons_terminees'] / $formation['total_lecons']) * 100)
                                : 0;
                            ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100">
                                    <?php if ($formation['image']): ?>
                                        <img src="../assets/images/formations/<?= htmlspecialchars($formation['image']) ?>"
                                            class="card-img-top" alt="<?= htmlspecialchars($formation['titre']) ?>">
                                    <?php endif; ?>

                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h5 class="card-title"><?= htmlspecialchars($formation['titre']) ?></h5>
                                            <span class="badge badge-<?=
                                                $formation['statut_inscription'] == 'accepté' ? 'success' :
                                                ($formation['statut_inscription'] == 'en_attente' ? 'warning' : 'secondary')
                                                ?>">
                                                <?= htmlspecialchars($formation['statut_inscription']) ?>
                                            </span>
                                        </div>

                                        <p class="card-text text-muted small">
                                            <i class="far fa-calendar-alt"></i>
                                            <?= date('d/m/Y', strtotime($formation['date_debut'])) ?> -
                                            <?= date('d/m/Y', strtotime($formation['date_fin'])) ?>
                                        </p>

                                        <p class="card-text">
                                            <?= substr(htmlspecialchars($formation['description']), 0, 100) ?>...
                                        </p>

                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between small mb-1">
                                                <span>Progression</span>
                                                <span><?= $pourcentage ?>%</span>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: <?= $pourcentage ?>%" aria-valuenow="<?= $pourcentage ?>"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between small text-muted">
                                            <div>
                                                <i class="fas fa-book-open"></i>
                                                <?= $formation['total_modules'] ?> modules
                                            </div>
                                            <div>
                                                <i class="fas fa-check-circle"></i>
                                                <?= $formation['lecons_terminees'] ?>/<?= $formation['total_lecons'] ?> leçons
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-footer bg-white">
                                        <div class="d-flex justify-content-between">
                                            <a href="formation_details.php?id=<?= $formation['id'] ?>"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-info-circle"></i> Détails
                                            </a>
                                            <a href="ma_formation.php?id=<?= $formation['id'] ?>"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-play"></i> Continuer
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </main>

    <script>
        // Script pour le filtrage des formations
        $(document).ready(function () {
            // Initialisation des tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Filtre par statut
            $('.filter-status').click(function (e) {
                e.preventDefault();
                const status = $(this).data('status');

                $('.formation-card').show();
                if (status !== 'all') {
                    $('.formation-card').not(`.status-${status}`).hide();
                }

                $('.filter-status').removeClass('active');
                $(this).addClass('active');
            });
        });
    </script>
</body>

</html>