<?php
require_once '../contenus/config.php';
// Vérifier si l'étudiant est connecté
if (!isset($_SESSION['etudiant_id'])) {
    header('Location: ../login.php');
    exit;
}

// Récupérer l'ID de la leçon
$lecon_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupérer les informations de la leçon
$lecon = $pdo->prepare("
    SELECT l.*, m.titre as module_titre, f.titre as formation_titre, f.id as formation_id
    FROM lecons l
    JOIN modules m ON l.module_id = m.id
    JOIN formations f ON m.formation_id = f.id
    WHERE l.id = ?
");
$lecon->execute([$lecon_id]);
$lecon = $lecon->fetch();

// Vérifier si l'étudiant est inscrit à cette formation
$est_inscrit = $pdo->prepare("
    SELECT 1 FROM inscriptions 
    WHERE etudiant_id = ? AND formation_id = ? AND statut = 'accepté'
");
$est_inscrit->execute([$_SESSION['etudiant_id'], $lecon['formation_id']]);
$est_inscrit = $est_inscrit->fetch();

if (!$lecon || !$est_inscrit) {
    header('Location: mes_formations.php');
    exit;
}

// Marquer la leçon comme "en cours" dans la progression
$progression = $pdo->prepare("
    INSERT INTO progression (etudiant_id, formation_id, module_id, lecon_id, statut, date_debut)
    VALUES (?, ?, ?, ?, 'en_cours', NOW())
    ON DUPLICATE KEY UPDATE statut = VALUES(statut), date_debut = IF(date_debut IS NULL, NOW(), date_debut)
");
$progression->execute([
    $_SESSION['etudiant_id'],
    $lecon['formation_id'],
    $lecon['module_id'],
    $lecon_id
]);

// Récupérer les questions du quiz si c'est une leçon de type quiz
$questions = [];
if ($lecon['type'] == 'quiz') {
    $questions = $pdo->prepare("
        SELECT * FROM quiz_questions 
        WHERE lecon_id = ?
        ORDER BY ordre
    ");
    $questions->execute([$lecon_id]);
    $questions = $questions->fetchAll();

    // Récupérer les réponses pour chaque question
    foreach ($questions as &$question) {
        $reponses = $pdo->prepare("
            SELECT * FROM quiz_reponses
            WHERE question_id = ?
            ORDER BY ordre
        ");
        $reponses->execute([$question['id']]);
        $question['reponses'] = $reponses->fetchAll();
    }
}

// Traitement de la soumission du quiz
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $lecon['type'] == 'quiz') {
    $score = 0;
    $total_questions = count($questions);
    
    foreach ($questions as $question) {
        $reponse_etudiant = $_POST['question_'.$question['id']] ?? '';
        $reponse_correcte = '';
        
        // Trouver la bonne réponse
        foreach ($question['reponses'] as $reponse) {
            if ($reponse['est_correcte']) {
                $reponse_correcte = $reponse['id'];
                break;
            }
        }
        
        if ($reponse_etudiant == $reponse_correcte) {
            $score++;
        }
    }
    
    // Enregistrer le résultat
    $resultat = $pdo->prepare("
        INSERT INTO quiz_resultats (etudiant_id, lecon_id, score, total_questions)
        VALUES (?, ?, ?, ?)
    ");
    $resultat->execute([
        $_SESSION['etudiant_id'],
        $lecon_id,
        $score,
        $total_questions
    ]);
    
    // Marquer la leçon comme terminée
    $pdo->prepare("
        UPDATE progression 
        SET statut = 'termine', date_fin = NOW(), score = ?
        WHERE etudiant_id = ? AND lecon_id = ?
    ")->execute([
        ($score / $total_questions) * 100,
        $_SESSION['etudiant_id'],
        $lecon_id
    ]);
    
    $_SESSION['quiz_result'] = [
        'score' => $score,
        'total' => $total_questions,
        'pourcentage' => round(($score / $total_questions) * 100)
    ];
    
    header("Location: lecon.php?id=$lecon_id");
    exit;
}

include '../contenus/head.php';
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

<body class="lecon-page">
    <?php include '../contenus/header.php'; ?>

    <main class="main">
        <div class="container-fluid">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="mes_formations.php">Mes Formations</a></li>
                    <li class="breadcrumb-item"><a href="formation.php?id=<?= $lecon['formation_id'] ?>"><?= htmlspecialchars($lecon['formation_titre']) ?></a></li>
                    <li class="breadcrumb-item"><a href="#"><?= htmlspecialchars($lecon['module_titre']) ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($lecon['titre']) ?></li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h2 class="mb-0"><?= htmlspecialchars($lecon['titre']) ?></h2>
                            <small class="text-muted">
                                Module: <?= htmlspecialchars($lecon['module_titre']) ?> | 
                                Durée: <?= $lecon['duree'] ?> minutes
                            </small>
                        </div>
                        <div class="card-body">
                            <?php if (isset($_SESSION['quiz_result'])): ?>
                                <div class="alert alert-<?= $_SESSION['quiz_result']['pourcentage'] >= 50 ? 'success' : 'danger' ?>">
                                    <h4>Résultat du Quiz</h4>
                                    <p>
                                        Vous avez obtenu <?= $_SESSION['quiz_result']['score'] ?>/<?= $_SESSION['quiz_result']['total'] ?> 
                                        (<?= $_SESSION['quiz_result']['pourcentage'] ?>%)
                                    </p>
                                    <?php if ($_SESSION['quiz_result']['pourcentage'] >= 70): ?>
                                        <p class="mb-0">Félicitations! Vous avez réussi ce quiz.</p>
                                    <?php else: ?>
                                        <p class="mb-0">Vous pouvez réessayer ce quiz pour améliorer votre score.</p>
                                    <?php endif; ?>
                                </div>
                                <?php unset($_SESSION['quiz_result']); ?>
                            <?php endif; ?>

                            <?php if ($lecon['type'] == 'video' && $lecon['url_video']): ?>
                                <div class="embed-responsive embed-responsive-16by9 mb-4">
                                    <iframe class="embed-responsive-item" src="<?= htmlspecialchars($lecon['url_video']) ?>" allowfullscreen></iframe>
                                </div>
                            <?php elseif ($lecon['type'] == 'document' && $lecon['fichier_joint']): ?>
                                <div class="text-center py-4">
                                    <a href="../assets/documents/<?= htmlspecialchars($lecon['fichier_joint']) ?>" 
                                       class="btn btn-primary btn-lg" download>
                                        <i class="fas fa-download mr-2"></i>Télécharger le document
                                    </a>
                                    <p class="mt-3"><?= htmlspecialchars($lecon['contenu']) ?></p>
                                </div>
                            <?php elseif ($lecon['type'] == 'quiz'): ?>
                                <form method="POST">
                                    <?php foreach ($questions as $index => $question): ?>
                                        <div class="card mb-3">
                                            <div class="card-header">
                                                <h5 class="mb-0">Question <?= $index + 1 ?>: <?= htmlspecialchars($question['question']) ?></h5>
                                                <small class="text-muted"><?= $question['points'] ?> point(s)</small>
                                            </div>
                                            <div class="card-body">
                                                <?php if ($question['type'] == 'choix_multiple'): ?>
                                                    <?php foreach ($question['reponses'] as $reponse): ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" 
                                                                   name="question_<?= $question['id'] ?>" 
                                                                   id="reponse_<?= $reponse['id'] ?>" 
                                                                   value="<?= $reponse['id'] ?>">
                                                            <label class="form-check-label" for="reponse_<?= $reponse['id'] ?>">
                                                                <?= htmlspecialchars($reponse['reponse']) ?>
                                                            </label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php elseif ($question['type'] == 'vrai_faux'): ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" 
                                                               name="question_<?= $question['id'] ?>" 
                                                               id="reponse_vrai_<?= $question['id'] ?>" 
                                                               value="1">
                                                        <label class="form-check-label" for="reponse_vrai_<?= $question['id'] ?>">
                                                            Vrai
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" 
                                                               name="question_<?= $question['id'] ?>" 
                                                               id="reponse_faux_<?= $question['id'] ?>" 
                                                               value="0">
                                                        <label class="form-check-label" for="reponse_faux_<?= $question['id'] ?>">
                                                            Faux
                                                        </label>
                                                    </div>
                                                <?php elseif ($question['type'] == 'reponse_courte'): ?>
                                                    <div class="form-group">
                                                        <textarea class="form-control" 
                                                                  name="question_<?= $question['id'] ?>" 
                                                                  rows="3"></textarea>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane mr-2"></i>Soumettre le quiz
                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="lecon-content">
                                    <?= nl2br(htmlspecialchars($lecon['contenu'])) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between">
                                <?php if ($lecon['ordre'] > 1): ?>
                                    <a href="#" class="btn btn-outline-primary">
                                        <i class="fas fa-arrow-left mr-2"></i>Leçon précédente
                                    </a>
                                <?php else: ?>
                                    <span></span>
                                <?php endif; ?>
                                
                                <?php if ($lecon['type'] != 'quiz'): ?>
                                    <form method="POST" action="marquer_complete.php">
                                        <input type="hidden" name="lecon_id" value="<?= $lecon_id ?>">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check mr-2"></i>Marquer comme terminée
                                        </button>
                                    </form>
                                <?php endif; ?>
                                
                                <a href="#" class="btn btn-outline-primary">
                                    Leçon suivante<i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">À propos de cette leçon</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong>Type:</strong> 
                                    <?= ucfirst(htmlspecialchars($lecon['type'])) ?>
                                </li>
                                <li class="list-group-item">
                                    <strong>Durée estimée:</strong> 
                                    <?= $lecon['duree'] ?> minutes
                                </li>
                                <li class="list-group-item">
                                    <strong>Module:</strong> 
                                    <?= htmlspecialchars($lecon['module_titre']) ?>
                                </li>
                                <li class="list-group-item">
                                    <strong>Formation:</strong> 
                                    <?= htmlspecialchars($lecon['formation_titre']) ?>
                                </li>
                                <?php if ($lecon['type'] == 'quiz'): ?>
                                    <li class="list-group-item">
                                        <strong>Nombre de questions:</strong> 
                                        <?= count($questions) ?>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Votre progression</h5>
                        </div>
                        <div class="card-body">
                            <?php
                            $prog = $pdo->prepare("
                                SELECT statut, score FROM progression 
                                WHERE etudiant_id = ? AND lecon_id = ?
                            ");
                            $prog->execute([$_SESSION['etudiant_id'], $lecon_id]);
                            $prog = $prog->fetch();
                            ?>
                            
                            <div class="progress mb-3">
                                <div class="progress-bar bg-<?= $prog['statut'] == 'termine' ? 'success' : 'warning' ?>" 
                                     role="progressbar" 
                                     style="width: <?= $prog['statut'] == 'termine' ? '100' : '50' ?>%" 
                                     aria-valuenow="<?= $prog['statut'] == 'termine' ? '100' : '50' ?>" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    <?= ucfirst(htmlspecialchars($prog['statut'])) ?>
                                </div>
                            </div>
                            
                            <?php if ($prog['statut'] == 'termine' && $lecon['type'] == 'quiz'): ?>
                                <p class="mb-0">
                                    <strong>Score:</strong> <?= $prog['score'] ?>%
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include '../contenus/footer.php'; ?>
    <?php include '../contenus/script.php'; ?>

    <style>
        .lecon-page .lecon-content {
            line-height: 1.8;
            font-size: 1.1rem;
        }
        
        .lecon-page .lecon-content img {
            max-width: 100%;
            height: auto;
            margin: 1rem 0;
            border-radius: 5px;
        }
        
        .embed-responsive {
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
    </style>
</body>
</html>