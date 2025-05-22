<?php
require_once '../../includes/auth.php';
admin_only();

$article_id = $_GET['id'] ?? 0;

// Vérifier que l'article existe
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$article_id]);
$article = $stmt->fetch();

if (!$article) {
    $_SESSION['error'] = "Article introuvable";
    redirect('/admin/articles/list.php');
}

// Supprimer l'image associée si elle existe
if ($article['image_url'] && file_exists("../../assets/images/uploads/{$article['image_url']}")) {
    unlink("../../assets/images/uploads/{$article['image_url']}");
}

// Supprimer l'article
$stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
$stmt->execute([$article_id]);

$_SESSION['success'] = "Article supprimé avec succès";
redirect('/admin/articles/list.php');
?>