<?php
require_once '../../contenus/config.php';
require_once '../../contenus/functions.php';
require_once '../../contenus/auth.php';
admin_only();
$article_id = $_GET['id'] ?? 0;

// Vérifier que l'article existe
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$article_id]);
$article = $stmt->fetch();

if (!$article) {
    $_SESSION['error'] = "Article introuvable";
    redirect('../article.php');
}

// Supprimer l'image associée si elle existe
if ($article['image'] && file_exists("../../assets/images/articles/{$article['image']}")) {
    unlink("../../assets/images/articles/{$article['image']}");
}

// Supprimer l'article
$stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
$stmt->execute([$article_id]);

$_SESSION['success'] = "Article supprimé avec succès";
redirect('../article.php');
?>