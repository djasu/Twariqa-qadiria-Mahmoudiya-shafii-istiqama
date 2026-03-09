<?php
require_once '../../contenus/config.php';
require_once '../../contenus/functions.php';
require_once '../../contenus/auth.php';
admin_only();
$formation_id = $_GET['id'] ?? 0;

// Vérifier que la formation existe
$stmt = $pdo->prepare("SELECT * FROM formations WHERE id = ?");
$stmt->execute([$formation_id]);
$formation = $stmt->fetch();

if (!$formation) {
    $_SESSION['error'] = "Formation introuvable";
    redirect('../formation.php');
}
// Supprimer l'article
$stmt = $pdo->prepare("DELETE FROM formations WHERE id = ?");
$stmt->execute([$formation_id]);

$_SESSION['success'] = "formation supprimée avec succès";
redirect('../formation.php');
?>