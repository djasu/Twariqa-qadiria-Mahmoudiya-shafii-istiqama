<?php
require_once 'config.php';

// Vérifier si l'utilisateur est connecté
function is_logged_in() {
    return isset($_SESSION['admin_id']);
}

// Protéger les pages admin
function admin_only() {
    if (!is_logged_in()) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        redirect('../admin/login.php');
    }
}

// Nettoyer les entrées
function clean_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>