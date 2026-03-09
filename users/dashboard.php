<?php
require_once '../contenus/config.php';

// Vérifier si l'étudiant est connecté
if (!isset($_SESSION['etudiant_id'])) {
    header('Location: ../login.php?redirect=dashboard');
    exit;
}

// Récupérer les informations de l'étudiant
$stmt = $pdo->prepare("SELECT * FROM etudiants WHERE id = ?");
$stmt->execute([$_SESSION['etudiant_id']]);
$etudiant = $stmt->fetch();

// Récupérer les formations inscrites
$formations = $pdo->prepare("
    SELECT f.*, i.date_inscription, i.statut 
    FROM formations f
    JOIN inscriptions i ON f.id = i.formation_id
    WHERE i.etudiant_id = ?
    ORDER BY i.date_inscription DESC
");
$formations->execute([$_SESSION['etudiant_id']]);
$formations = $formations->fetchAll();

// Récupérer la progression globale
$progression = $pdo->prepare("
    SELECT 
        COUNT(DISTINCT l.id) AS total_lecons,
        SUM(CASE WHEN p.statut = 'termine' THEN 1 ELSE 0 END) AS lecons_terminees
    FROM inscriptions i
    JOIN formations f ON i.formation_id = f.id
    JOIN modules m ON f.id = m.formation_id
    JOIN lecons l ON m.id = l.module_id
    LEFT JOIN progression p ON p.lecon_id = l.id AND p.etudiant_id = ?
    WHERE i.etudiant_id = ?
");
$progression->execute([$_SESSION['etudiant_id'], $_SESSION['etudiant_id']]);
$prog_data = $progression->fetch();
$pourcentage = $prog_data['total_lecons'] > 0 ? round(($prog_data['lecons_terminees'] / $prog_data['total_lecons']) * 100) : 0;

// Récupérer les formations récentes (limite 3)
$formations_recentes = $pdo->prepare("
    SELECT f.* 
    FROM formations f
    JOIN inscriptions i ON f.id = i.formation_id
    WHERE i.etudiant_id = ?
    ORDER BY i.date_inscription DESC
    LIMIT 3
");
$formations_recentes->execute([$_SESSION['etudiant_id']]);
$formations_recentes = $formations_recentes->fetchAll();
?>
<?php include 'include/head.php'; ?>
<body class="dashboard-page">
    <main class="main">
        <div class="container-fluid py-4">
            <div class="row">
                <!-- Sidebar -->
                   <?php include 'include/sidebar.php'; ?>

                <!-- Main content -->
                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Tableau de bord</h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <div class="btn-group mr-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary">Partager</button>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card text-white bg-primary mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title">Formations</h5>
                                            <h2 class="mb-0"><?= count($formations) ?></h2>
                                        </div>
                                        <i class="fas fa-book fa-3x"></i>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="mes_formations.php" class="text-white">Voir toutes <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-white bg-success mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title">Progression</h5>
                                            <h2 class="mb-0"><?= $pourcentage ?>%</h2>
                                        </div>
                                        <i class="fas fa-chart-line fa-3x"></i>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="progression.php" class="text-white">Détails <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-white bg-info mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title">Certificats</h5>
                                            <h2 class="mb-0"><?= count($formations_recentes) ?></h2>
                                        </div>
                                        <i class="fas fa-certificate fa-3x"></i>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="certificats.php" class="text-white">Voir <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formations récentes -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Mes formations récentes</h5>
                        </div>
                        <div class="card-body">
                            <?php if (count($formations_recentes) > 0): ?>
                                <div class="row">
                                    <?php foreach ($formations_recentes as $formation): ?>
                                        <div class="col-md-4 mb-4">
                                            <div class="card h-100">
                                                <?php if ($formation['image']): ?>
                                                    <img src="../assets/images/formations/<?= htmlspecialchars($formation['image']) ?>" 
                                                         class="card-img-top" alt="<?= htmlspecialchars($formation['titre']) ?>">
                                                <?php endif; ?>
                                                <div class="card-body">
                                                    <h5 class="card-title"><?= htmlspecialchars($formation['titre']) ?></h5>
                                                    <p class="card-text text-muted">
                                                        <?= date('d/m/Y', strtotime($formation['date_debut'])) ?> - 
                                                        <?= date('d/m/Y', strtotime($formation['date_fin'])) ?>
                                                    </p>
                                                </div>
                                                <div class="card-footer bg-white">
                                                    <a href="formation.php?id=<?= $formation['id'] ?>" class="btn btn-primary btn-sm">
                                                        Continuer
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    Vous n'êtes inscrit à aucune formation. 
                                    <a href="formations.php">Parcourir les formations disponibles</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Progression récente -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Ma progression</h5>
                                </div>
                                <div class="card-body">
                                    <div class="progress mb-3">
                                        <div class="progress-bar bg-success" 
                                             role="progressbar" 
                                             style="width: <?= $pourcentage ?>%" 
                                             aria-valuenow="<?= $pourcentage ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            <?= $pourcentage ?>%
                                        </div>
                                    </div>
                                    <p>
                                        <span class="badge badge-success">Terminé</span> 
                                        <?= $prog_data['lecons_terminees'] ?? 0 ?> leçons
                                    </p>
                                    <p>
                                        <span class="badge badge-secondary">Restant</span> 
                                        <?= ($prog_data['total_lecons'] ?? 0) - ($prog_data['lecons_terminees'] ?? 0) ?> leçons
                                    </p>
                                    <a href="progression.php" class="btn btn-outline-primary btn-sm">
                                        Voir le détail
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Activité récente</h5>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $activites = $pdo->prepare("
                                        SELECT l.titre, f.titre as formation_titre, p.date_fin
                                        FROM progression p
                                        JOIN lecons l ON p.lecon_id = l.id
                                        JOIN modules m ON l.module_id = m.id
                                        JOIN formations f ON m.formation_id = f.id
                                        WHERE p.etudiant_id = ?
                                        ORDER BY p.date_fin DESC
                                        LIMIT 3
                                    ");
                                    $activites->execute([$_SESSION['etudiant_id']]);
                                    $activites = $activites->fetchAll();
                                    ?>

                                    <?php if (count($activites) > 0): ?>
                                        <ul class="list-group list-group-flush">
                                            <?php foreach ($activites as $activite): ?>
                                                <li class="list-group-item">
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <strong><?= htmlspecialchars($activite['titre']) ?></strong>
                                                            <small class="d-block text-muted">
                                                                <?= htmlspecialchars($activite['formation_titre']) ?>
                                                            </small>
                                                        </div>
                                                        <small>
                                                            <?= date('d/m H:i', strtotime($activite['date_fin'])) ?>
                                                        </small>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <p class="text-muted">Aucune activité récente</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </main>
    <script>
        // Script pour le tableau de bord
        $(document).ready(function() {
            // Activer les tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            // Gestion du menu responsive
            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>
</body>
</html>