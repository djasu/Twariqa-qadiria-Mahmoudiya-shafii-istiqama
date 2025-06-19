<?php
require_once '../contenus/config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['etudiant_id'])) {
    $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page.";
    header('Location: ../login.php?redirect=ma_formation');
    exit();
}

// Vérifier si l'ID de la formation est présent
if (!isset($_GET['id'])) {
    header('Location: mes_formations.php');
    exit();
}

$formation_id = (int) $_GET['id'];
$user_id = (int) $_SESSION['etudiant_id'];

// Vérifier si l'utilisateur est bien inscrit à cette formation
$stmt = $pdo->prepare("SELECT f.*, i.date_inscription, i.statut as inscription_statut 
                      FROM formations f
                      JOIN inscriptions i ON f.id = i.formation_id
                      WHERE i.etudiant_id = ? AND f.id = ?");
$stmt->execute([$user_id, $formation_id]);
$formation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$formation) {
    $_SESSION['error'] = "Vous n'êtes pas inscrit à cette formation ou elle n'existe pas.";
    header('Location: lecon.php');
    exit();
}

// Récupérer les modules de la formation
$modules = $pdo->prepare("SELECT * FROM modules WHERE formation_id = ? ORDER BY ordre");
$modules->execute([$formation_id]);
$modules = $modules->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la progression de l'utilisateur
$progression = [];
$stmt = $pdo->prepare("SELECT module_id, statut FROM progression WHERE etudiant_id = ? AND formation_id = ?");
$stmt->execute([$user_id, $formation_id]);
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $progression[$row['module_id']] = $row['statut'];
}

?>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>qadiriyya</title>
  <meta name="description" content="">
  <meta name="keywords" content="musulmam,islam,qadiriyya">

  <!-- Favicons -->
  <link href="../assets//images/img/images (1).png" rel="icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/user/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/user/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/user/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../assets/user/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../assets/user/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="../assets/user/assets/css/main.css" rel="stylesheet">
</head>


<style>
    .progress-container {
        background: #f1f1f1;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .progress-bar {
        height: 20px;
        background: #4CAF50;
        border-radius: 5px;
        text-align: center;
        line-height: 20px;
        color: white;
    }

    .module-card {
        transition: all 0.3s;
        margin-bottom: 15px;
    }

    .module-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .completed {
        border-left: 5px solid #28a745;
    }

    .in-progress {
        border-left: 5px solid #ffc107;
    }

    .not-started {
        border-left: 5px solid #6c757d;
    }
</style>

<body class="formation-page">
    <?php include '../contenus/header.php'; ?>

    <main class="main">
        <!-- Page Title -->
        <div class="page-title light-background"
            style="background-image: url('../assets/images/formations/<?= htmlspecialchars($formation['image']) ?>'); background-size: cover;">
            <div class="container d-lg-flex justify-content-between align-items-center py-5"
                style="background-color: rgba(0,0,0,0.7);">
                <div class="text-white">
                    <h1 class="mb-2 mb-lg-0"><?= htmlspecialchars($formation['titre']) ?></h1>
                    <p class="lead mb-0"><?= htmlspecialchars($formation['sous_titre'] ?? '') ?></p>
                </div>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="index.php">Accueil</a></li>
                        <li><a href="mes_formations.php">Mes Formations</a></li>
                        <li class="current"><?= htmlspecialchars($formation['titre']) ?></li>
                    </ol>
                </nav>
            </div>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h3 class="card-title">Description de la formation</h3>
                                <p class="card-text"><?= nl2br(htmlspecialchars($formation['description'])) ?></p>

                                <h4 class="mt-4">Objectifs</h4>
                                <ul>
                                    <?php foreach (explode("\n", $formation['objectifs']) as $objectif): ?>
                                        <?php if (trim($objectif)): ?>
                                            <li><?= htmlspecialchars(trim($objectif)) ?></li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>

                        <h3 class="mb-4">Modules de formation</h3>

                        <?php if (count($modules) > 0): ?>
                            <div class="accordion" id="modulesAccordion">
                                <?php foreach ($modules as $index => $module): ?>
                                    <?php
                                    $module_status = $progression[$module['id']] ?? 'not-started';
                                    $status_class = '';
                                    $status_text = '';

                                    switch ($module_status) {
                                        case 'completed':
                                            $status_class = 'completed';
                                            $status_text = 'Terminé';
                                            break;
                                        case 'in-progress':
                                            $status_class = 'in-progress';
                                            $status_text = 'En cours';
                                            break;
                                        default:
                                            $status_class = 'not-started';
                                            $status_text = 'Non commencé';
                                    }
                                    ?>
                                    <div class="card module-card <?= $status_class ?>">
                                        <div class="card-header" id="moduleHeading<?= $index ?>">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link btn-block text-left" type="button"
                                                    data-toggle="collapse" data-target="#moduleCollapse<?= $index ?>"
                                                    aria-expanded="true" aria-controls="moduleCollapse<?= $index ?>">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span>
                                                            <i class="fas fa-book mr-2"></i>
                                                            Module <?= $index + 1 ?>: <?= htmlspecialchars($module['titre']) ?>
                                                        </span>
                                                        <span class="badge badge-<?=
                                                            $module_status == 'completed' ? 'success' :
                                                            ($module_status == 'in-progress' ? 'warning' : 'secondary')
                                                            ?>">
                                                            <?= $status_text ?>
                                                        </span>
                                                    </div>
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="moduleCollapse<?= $index ?>" class="collapse <?= $index === 0 ? 'show' : '' ?>"
                                            aria-labelledby="moduleHeading<?= $index ?>" data-parent="#modulesAccordion">
                                            <div class="card-body">
                                                <p><?= nl2br(htmlspecialchars($module['description'])) ?></p>

                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h5 class="mb-0">Contenu du module</h5>
                                                    <?php if ($module_status != 'completed'): ?>
                                                        <form method="post" action="update_progression.php">
                                                            <input type="hidden" name="formation_id" value="<?= $formation_id ?>">
                                                            <input type="hidden" name="module_id" value="<?= $module['id'] ?>">
                                                            <input type="hidden" name="status"
                                                                value="<?= $module_status == 'in-progress' ? 'completed' : 'in-progress' ?>">
                                                            <button type="submit"
                                                                class="btn btn-sm btn-<?= $module_status == 'in-progress' ? 'success' : 'primary' ?>">
                                                                <?= $module_status == 'in-progress' ? 'Marquer comme terminé' : 'Commencer ce module' ?>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>

                                                <?php
                                                // Récupérer les leçons pour ce module
                                                $lecons = $pdo->prepare("SELECT * FROM lecons WHERE module_id = ? ORDER BY ordre");
                                                $lecons->execute([$module['id']]);
                                                $lecons = $lecons->fetchAll(PDO::FETCH_ASSOC);
                                                ?>

                                                <?php if (count($lecons) > 0): ?>
                                                    <ul class="list-group">
                                                        <?php foreach ($lecons as $lecon): ?>
                                                            <li
                                                                class="list-group-item d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <i class="fas fa-<?=
                                                                        $lecon['type'] == 'video' ? 'video' :
                                                                        ($lecon['type'] == 'quiz' ? 'question-circle' : 'file-alt')
                                                                        ?> mr-2"></i>
                                                                    <?= htmlspecialchars($lecon['titre']) ?>
                                                                </div>
                                                                <a href="lecon.php?id=<?= $lecon['id'] ?>"
                                                                    class="btn btn-sm btn-outline-primary">
                                                                    <?= $lecon['type'] == 'video' ? 'Regarder' : ($lecon['type'] == 'quiz' ? 'Passer le quiz' : 'Consulter') ?>
                                                                </a>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                <?php else: ?>
                                                    <div class="alert alert-info">Aucune leçon disponible pour ce module pour le
                                                        moment.</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">Aucun module disponible pour cette formation pour le moment.</div>
                        <?php endif; ?>
                    </div>

                    <div class="col-lg-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h4 class="card-title">Progression</h4>

                                <?php
                                // Calculer la progression globale
                                $total_modules = count($modules);
                                $completed_modules = 0;

                                foreach ($progression as $status) {
                                    if ($status === 'completed') {
                                        $completed_modules++;
                                    }
                                }

                                $progress_percent = $total_modules > 0 ? round(($completed_modules / $total_modules) * 100) : 0;
                                ?>

                                <div class="progress-container">
                                    <div class="progress-bar" style="width: <?= $progress_percent ?>%">
                                        <?= $progress_percent ?>%
                                    </div>
                                </div>

                                <p class="mb-0">
                                    <?= $completed_modules ?> sur <?= $total_modules ?> modules terminés
                                </p>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-body">
                                <h4 class="card-title">Détails de la formation</h4>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Formateur:</span>
                                        <strong><?= htmlspecialchars($formation['formateur']) ?></strong>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Date de début:</span>
                                        <strong><?= date('d/m/Y', strtotime($formation['date_debut'])) ?></strong>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Date de fin:</span>
                                        <strong><?= date('d/m/Y', strtotime($formation['date_fin'])) ?></strong>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Durée estimée:</span>
                                        <strong><?= htmlspecialchars($formation['duree_totale']) ?> heures</strong>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Niveau:</span>
                                        <strong><?= htmlspecialchars($formation['niveau']) ?></strong>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>Date d'inscription:</span>
                                        <strong><?= date('d/m/Y', strtotime($formation['date_inscription'])) ?></strong>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Ressources</h4>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <a href="#" class="d-flex align-items-center">
                                            <i class="fas fa-file-pdf mr-2 text-danger"></i>
                                            <span>PDF du cours</span>
                                        </a>
                                    </li>
                                    <li class="list-group-item">
                                        <a href="#" class="d-flex align-items-center">
                                            <i class="fas fa-link mr-2 text-primary"></i>
                                            <span>Ressources supplémentaires</span>
                                        </a>
                                    </li>
                                    <li class="list-group-item">
                                        <a href="#" class="d-flex align-items-center">
                                            <i class="fas fa-users mr-2 text-success"></i>
                                            <span>Groupe de discussion</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include '../contenus/footer.php'; ?>
    <?php include '../contenus/script.php'; ?>
</body>

</html>