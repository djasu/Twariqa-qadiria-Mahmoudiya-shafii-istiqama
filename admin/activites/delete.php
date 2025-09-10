<?php
require_once '../../contenus/config.php';
require_once '../../contenus/functions.php';
require_once '../../contenus/auth.php';
admin_only();
$evenement_id = $_GET['id'] ?? 0;

// Vérifier que l'activite existe
$stmt = $pdo->prepare("SELECT * FROM evenements WHERE id = ?");
$stmt->execute([$evenement_id]);
$evenement = $stmt->fetch();

if (!$evenement) {
    $_SESSION['error'] = "Activite introuvable";
    redirect('../activite.php');
}
// Supprimer l'article
$stmt = $pdo->prepare("DELETE FROM evenements WHERE id = ?");
$stmt->execute([$evenement_id]);

$_SESSION['success'] = "Activite supprimée avec succès";
redirect('../activite.php');
?>