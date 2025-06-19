<?php
require_once 'contenus/config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['etudiant_id'])) {
    $_SESSION['error'] = "Vous devez être connecté pour vous inscrire à une formation.";
    header('Location: login.php?redirect=formation');
    exit();
}

// Vérifier si l'ID de la formation est présent
if (!isset($_POST['formation_id'])) {
    header('Location: formations.php');
    exit();
}

$formation_id = (int)$_POST['formation_id'];
$user_id = (int)$_SESSION['etudiant_id'];
// Préparer la connexion à la base de données

// Vérifier si la formation existe
$stmt = $pdo->prepare("SELECT * FROM formations WHERE id = ?");
$stmt->execute([$formation_id]);
$formation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$formation) {
    $_SESSION['error'] = "Formation introuvable.";
    header('Location: formations.php');
    exit();
}

// Vérifier si l'utilisateur est déjà inscrit
$stmt = $pdo->prepare("SELECT * FROM inscriptions WHERE etudiant_id = ? AND formation_id = ?");
$stmt->execute([$user_id, $formation_id]);
$already_registered = $stmt->fetch();

if ($already_registered) {
    $_SESSION['error'] = "Vous êtes déjà inscrit à cette formation.";
    header('Location: formations.php');
    exit();
}

// Enregistrer l'inscription
try {
    $stmt = $pdo->prepare("INSERT INTO inscriptions (etudiant_id, formation_id, date_inscription, statut) 
                          VALUES (?, ?, NOW(), 'en cours')");
    $stmt->execute([$user_id, $formation_id]);
    
    $_SESSION['success'] = "Inscription réussie! Vous pouvez maintenant accéder à la formation.";
    header("Location: ma_formation.php?id=".$formation_id);
    exit();
    
} catch (PDOException $e) {
    $_SESSION['error'] = "Une erreur est survenue lors de l'inscription: " . $e->getMessage();
    header('Location: formations.php');
    exit();
}