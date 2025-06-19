<?php
require_once '../contenus/config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Vérifier les données POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['formation_id']) || !isset($_POST['module_id']) || !isset($_POST['status'])) {
    header('Location: mes_formations.php');
    exit();
}

$formation_id = (int)$_POST['formation_id'];
$module_id = (int)$_POST['module_id'];
$status = $_POST['status'];
$user_id = (int)$_SESSION['user_id'];

// Vérifier que l'utilisateur est bien inscrit à cette formation
$stmt = $pdo->prepare("SELECT 1 FROM inscriptions WHERE user_id = ? AND formation_id = ?");
$stmt->execute([$user_id, $formation_id]);
$is_registered = $stmt->fetch();

if (!$is_registered) {
    $_SESSION['error'] = "Vous n'êtes pas inscrit à cette formation.";
    header("Location: formations.php");
    exit();
}

// Vérifier que le module appartient bien à cette formation
$stmt = $pdo->prepare("SELECT 1 FROM modules WHERE id = ? AND formation_id = ?");
$stmt->execute([$module_id, $formation_id]);
$module_exists = $stmt->fetch();

if (!$module_exists) {
    $_SESSION['error'] = "Module introuvable dans cette formation.";
    header("Location: ma_formation.php?id=".$formation_id);
    exit();
}

// Vérifier si une entrée de progression existe déjà
$stmt = $pdo->prepare("SELECT id FROM progression WHERE user_id = ? AND formation_id = ? AND module_id = ?");
$stmt->execute([$user_id, $formation_id, $module_id]);
$existing_progress = $stmt->fetch();

try {
    if ($existing_progress) {
        // Mettre à jour l'entrée existante
        $stmt = $pdo->prepare("UPDATE progression SET statut = ?, updated_at = NOW() 
                              WHERE id = ?");
        $stmt->execute([$status, $existing_progress['id']]);
    } else {
        // Créer une nouvelle entrée
        $stmt = $pdo->prepare("INSERT INTO progression (user_id, formation_id, module_id, statut, created_at) 
                              VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$user_id, $formation_id, $module_id, $status]);
    }
    
    $_SESSION['success'] = "Progression mise à jour avec succès!";
    header("Location: ma_formation.php?id=".$formation_id);
    exit();
    
} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur lors de la mise à jour de la progression: " . $e->getMessage();
    header("Location: ma_formation.php?id=".$formation_id);
    exit();
}