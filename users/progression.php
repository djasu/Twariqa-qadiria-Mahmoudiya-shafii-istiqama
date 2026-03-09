<?php
require_once '../contenus/config.php';

// Vérifier si l'étudiant est connecté
if (!isset($_SESSION['etudiant_id'])) {
    header('Location: ../login.php');
    exit;
}

// Récupérer les informations de l'étudiant
$etudiant = $pdo->prepare("SELECT * FROM etudiants WHERE id = ?");
$etudiant->execute([$_SESSION['etudiant_id']]);
$etudiant = $etudiant->fetch();

// Récupérer la progression globale
$progression = $pdo->prepare("
    SELECT 
        f.id as formation_id,
        f.titre as formation_titre,
        f.image as formation_image,
        COUNT(DISTINCT m.id) as total_modules,
        COUNT(DISTINCT l.id) as total_lecons,
        SUM(CASE WHEN p.statut = 'termine' THEN 1 ELSE 0 END) as lecons_terminees,
        MIN(p.date_debut) as date_debut,
        MAX(p.date_fin) as date_fin
    FROM inscriptions i
    JOIN formations f ON i.formation_id = f.id
    LEFT JOIN modules m ON f.id = m.formation_id
    LEFT JOIN lecons l ON m.id = l.module_id
    LEFT JOIN progression p ON p.lecon_id = l.id AND p.etudiant_id = ?
    WHERE i.etudiant_id = ? AND i.statut = 'accepté'
    GROUP BY f.id
    ORDER BY MAX(p.date_fin) DESC
");
$progression->execute([$_SESSION['etudiant_id'], $_SESSION['etudiant_id']]);
$progression_data = $progression->fetchAll();

include 'include/head.php';
?>
<style>



</style>

<body class="progression-page">

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
                    <h1 class="h2">Progression</h1>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Résumé Global</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                $stats = $pdo->prepare("
                                SELECT 
                                    COUNT(DISTINCT i.formation_id) as total_formations,
                                    COUNT(DISTINCT l.id) as total_lecons,
                                    SUM(CASE WHEN p.statut = 'termine' THEN 1 ELSE 0 END) as lecons_terminees
                                FROM inscriptions i
                                JOIN formations f ON i.formation_id = f.id
                                LEFT JOIN modules m ON f.id = m.formation_id
                                LEFT JOIN lecons l ON m.id = l.module_id
                                LEFT JOIN progression p ON p.lecon_id = l.id AND p.etudiant_id = ?
                                WHERE i.etudiant_id = ? AND i.statut = 'accepté'
                            ");
                                $stats->execute([$_SESSION['etudiant_id'], $_SESSION['etudiant_id']]);
                                $stats = $stats->fetch();
                                $global_progress = $stats['total_lecons'] > 0
                                    ? round(($stats['lecons_terminees'] / $stats['total_lecons']) * 100)
                                    : 0;
                                ?>

                                <div class="text-center mb-4">
                                    <div class="progress-circle" data-progress="<?= $global_progress ?>">
                                        <span class="progress-circle-value"><?= $global_progress ?>%</span>
                                    </div>
                                    <h5 class="mt-3">Progression Globale</h5>
                                </div>

                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Formations suivies
                                        <span
                                            class="badge badge-primary badge-pill"><?= $stats['total_formations'] ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Leçons terminées
                                        <span
                                            class="badge badge-success badge-pill"><?= $stats['lecons_terminees'] ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Leçons restantes
                                        <span
                                            class="badge badge-secondary badge-pill"><?= $stats['total_lecons'] - $stats['lecons_terminees'] ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Détail par Formation</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($progression_data)): ?>
                                    <div class="alert alert-info">
                                        Vous n'avez aucune progression enregistrée.
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Formation</th>
                                                    <th>Progression</th>
                                                    <th>Modules</th>
                                                    <th>Leçons</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($progression_data as $formation):
                                                    $progress = $formation['total_lecons'] > 0
                                                        ? round(($formation['lecons_terminees'] / $formation['total_lecons']) * 100)
                                                        : 0;
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <?php if ($formation['formation_image']): ?>
                                                                    <img src="../assets/images/formations/<?= htmlspecialchars($formation['formation_image']) ?>"
                                                                        class="rounded mr-3" width="40" height="40"
                                                                        alt="<?= htmlspecialchars($formation['formation_titre']) ?>">
                                                                <?php endif; ?>
                                                                <div>
                                                                    <strong><?= htmlspecialchars($formation['formation_titre']) ?></strong><br>
                                                                    <small class="text-muted">
                                                                        <?= $formation['date_debut'] ? date('d/m/Y', strtotime($formation['date_debut'])) : 'Non commencé' ?>
                                                                        -
                                                                        <?= $formation['date_fin'] ? date('d/m/Y', strtotime($formation['date_fin'])) : 'En cours' ?>
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="progress" style="height: 20px;">
                                                                <div class="progress-bar bg-success" role="progressbar"
                                                                    style="width: <?= $progress ?>%"
                                                                    aria-valuenow="<?= $progress ?>" aria-valuemin="0"
                                                                    aria-valuemax="100">
                                                                    <?= $progress ?>%
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><?= $formation['total_modules'] ?></td>
                                                        <td>
                                                            <?= $formation['lecons_terminees'] ?> /
                                                            <?= $formation['total_lecons'] ?>
                                                        </td>
                                                        <td>
                                                            <a href="formation_details.php?id=<?= $formation['formation_id'] ?>"
                                                                class="btn btn-sm btn-outline-primary" title="Voir les détails">
                                                                <!-- <i class="fas fa-eye"></i> -->voir
                                                            </a>
                                                            <a href="formation.php?id=<?= $formation['formation_id'] ?>"
                                                                class="btn btn-sm btn-primary" title="Continuer">
                                                                <!-- <i class="fas fa-play"></i> -->continue
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </main>

    <script>
        document.querySelectorAll('.progress-circle').forEach(circle => {
            const progress = circle.getAttribute('data-progress');
            circle.style.setProperty('--progress', `${progress}%`);
        });
    </script>
</body>

</html>