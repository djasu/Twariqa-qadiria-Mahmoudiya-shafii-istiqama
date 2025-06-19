<?php
require_once '../contenus/config.php';

// Vérifier si l'étudiant est connecté
if (!isset($_SESSION['etudiant_id'])) {
    header('Location: ../login.php');
    exit;
}

// Vérifier si l'ID de la leçon est présent
if (!isset($_POST['lecon_id'])) {
    header('Location: mes_formations.php');
    exit;
}

$lecon_id = (int)$_POST['lecon_id'];

// Récupérer les informations de la leçon
$lecon = $pdo->prepare("
    SELECT l.module_id, m.formation_id 
    FROM lecons l
    JOIN modules m ON l.module_id = m.id
    WHERE l.id = ?
");
$lecon->execute([$lecon_id]);
$lecon = $lecon->fetch();

if (!$lecon) {
    header('Location: mes_formations.php');
    exit;
}

// Marquer la leçon comme terminée
$stmt = $pdo->prepare("
    INSERT INTO progression (etudiant_id, formation_id, module_id, lecon_id, statut, date_fin)
    VALUES (?, ?, ?, ?, 'termine', NOW())
    ON DUPLICATE KEY UPDATE statut = VALUES(statut), date_fin = NOW()
");
$stmt->execute([
    $_SESSION['etudiant_id'],
    $lecon['formation_id'],
    $lecon['module_id'],
    $lecon_id
]);

// Rediriger vers la leçon
header("Location: lecon.php?id=$lecon_id");
exit;