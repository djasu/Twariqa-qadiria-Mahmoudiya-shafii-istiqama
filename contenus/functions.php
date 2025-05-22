<?php
// Formater une date
function format_date($date) {
    return date('d/m/Y H:i', strtotime($date));
}

// Générer un slug pour les URLs
function generate_slug($string) {
    $slug = strtolower($string);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

// Uploader un fichier
function upload_file($file, $target_dir) {
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $extension;
    $target_file = $target_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return $filename;
    }
    return false;
}
?>