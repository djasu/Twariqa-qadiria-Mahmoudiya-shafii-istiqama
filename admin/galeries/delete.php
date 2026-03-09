<?php
require_once '../../contenus/config.php';
require_once '../../contenus/functions.php';
require_once '../../contenus/auth.php';
admin_only();
$galerie_id = $_GET['id'] ?? 0;

// Vérifier que l'activite existe
$stmt = $pdo->prepare("SELECT * FROM galerie WHERE id = ?");
$stmt->execute([$galerie_id]);
$galerie = $stmt->fetch();

if (!$galerie) {
    $_SESSION['error'] = "Galerie introuvable";
    redirect('../galerie.php');
}
// Supprimer l'article
$stmt = $pdo->prepare("DELETE FROM galerie WHERE id = ?");
$stmt->execute([$galerie_id]);

$_SESSION['success'] = "Photo supprimée avec succès";
redirect('../galerie.php');
?>