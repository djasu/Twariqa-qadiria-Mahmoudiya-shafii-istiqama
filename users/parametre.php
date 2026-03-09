<?php
require_once '../contenus/config.php';

// Vérifier si l'étudiant est connecté
if (!isset($_SESSION['etudiant_id'])) {
    header('Location: ../login.php');
    exit;
}

// Récupérer les informations actuelles de l'étudiant
$stmt = $pdo->prepare("SELECT * FROM etudiants WHERE id = ?");
$stmt->execute([$_SESSION['etudiant_id']]);
$etudiant = $stmt->fetch();

$errors = [];
$success = false;

// Traitement du formulaire d'informations personnelles
if (isset($_POST['update_profile'])) {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $date_naissance = $_POST['date_naissance'] ?? '';
    $adresse = trim($_POST['adresse'] ?? '');
    $ville = trim($_POST['ville'] ?? '');
    $pays = trim($_POST['pays'] ?? '');

    // Validation
    if (empty($nom)) $errors['profile'][] = "Le nom est requis";
    if (empty($prenom)) $errors['profile'][] = "Le prénom est requis";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['profile'][] = "Email invalide";
    }

    // Vérifier si l'email est déjà utilisé par un autre étudiant
    $stmt = $pdo->prepare("SELECT id FROM etudiants WHERE email = ? AND id != ?");
    $stmt->execute([$email, $_SESSION['etudiant_id']]);
    if ($stmt->fetch()) {
        $errors['profile'][] = "Cet email est déjà utilisé par un autre compte";
    }

    // Gestion de l'upload de photo
    $photo = $etudiant['photo'];
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        
        if (!in_array($ext, $allowed)) {
            $errors['profile'][] = "Format de fichier non supporté (seuls JPG, JPEG, PNG sont autorisés)";
        } elseif ($_FILES['photo']['size'] > 2 * 1024 * 1024) {
            $errors['profile'][] = "La taille du fichier ne doit pas dépasser 2MB";
        } else {
            $photo = 'profile_' . $_SESSION['etudiant_id'] . '_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], '../assets/images/profiles/' . $photo);
            
            // Supprimer l'ancienne photo si ce n'est pas la photo par défaut
            if ($etudiant['photo'] !== 'default_profile.jpg') {
                @unlink('../assets/images/profiles/' . $etudiant['photo']);
            }
        }
    }

    if (empty($errors['profile'])) {
        try {
            $stmt = $pdo->prepare("UPDATE etudiants SET 
                nom = ?, prenom = ?, email = ?, telephone = ?, date_naissance = ?,
                adresse = ?, ville = ?, pays = ?, photo = ?, updated_at = NOW()
                WHERE id = ?");
            
            $stmt->execute([
                $nom, $prenom, $email, $telephone, $date_naissance,
                $adresse, $ville, $pays, $photo, $_SESSION['etudiant_id']
            ]);

            // Mettre à jour les données de session
            $_SESSION['etudiant_nom'] = $nom;
            $_SESSION['etudiant_email'] = $email;
            
            $success = "Profil mis à jour avec succès";
            
            // Recharger les données de l'étudiant
            $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE id = ?");
            $stmt->execute([$_SESSION['etudiant_id']]);
            $etudiant = $stmt->fetch();
            
        } catch (PDOException $e) {
            $errors['profile'][] = "Erreur lors de la mise à jour: " . $e->getMessage();
        }
    }
}

// Traitement du formulaire de changement de mot de passe
if (isset($_POST['update_password'])) {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($current_password)) {
        $errors['password'][] = "Le mot de passe actuel est requis";
    } elseif (!password_verify($current_password, $etudiant['mot_de_passe'])) {
        $errors['password'][] = "Le mot de passe actuel est incorrect";
    }
    
    if (strlen($new_password) < 8) {
        $errors['password'][] = "Le nouveau mot de passe doit contenir au moins 8 caractères";
    }
    
    if ($new_password !== $confirm_password) {
        $errors['password'][] = "Les nouveaux mots de passe ne correspondent pas";
    }

    if (empty($errors['password'])) {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        
        try {
            $stmt = $pdo->prepare("UPDATE etudiants SET mot_de_passe = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $_SESSION['etudiant_id']]);
            
            $success = "Mot de passe changé avec succès";
            
        } catch (PDOException $e) {
            $errors['password'][] = "Erreur lors du changement de mot de passe: " . $e->getMessage();
        }
    }
}

include 'include/head.php';
?>

<style>
    /* Dashboard Styles */
.dashboard-page {
    background-color: #f8f9fa;
}

.sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    z-index: 100;
    padding: 48px 0 0;
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
}

.sidebar .nav-link {
    font-weight: 500;
    color: #333;
    padding: 0.75rem 1rem;
    border-left: 4px solid transparent;
}

.sidebar .nav-link.active {
    color: #007bff;
    border-left: 4px solid #007bff;
    background-color: rgba(0, 123, 255, 0.05);
}

.sidebar .nav-link:hover {
    color: #007bff;
    background-color: rgba(0, 123, 255, 0.05);
}

.sidebar .nav-link i {
    margin-right: 4px;
    color: #6c757d;
}

.sidebar .nav-link.active i,
.sidebar .nav-link:hover i {
    color: inherit;
}

.main {
    margin-left: 0;
    padding: 20px;
    width: 100%;
}

@media (min-width: 768px) {
    .main {
        margin-left: 250px;
    }
}

.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, .075);
    transition: all 0.3s;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15);
}

.progress {
    height: 10px;
    border-radius: 5px;
}

.stat-card {
    border-left: 4px solid;
}

.stat-card.border-primary {
    border-left-color: #007bff;
}

.stat-card.border-success {
    border-left-color: #28a745;
}

.stat-card.border-info {
    border-left-color: #17a2b8;
}
</style>
<body class="settings-page">
    <main class="main">
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                    <?php include 'include/sidebar.php'; ?>

                <!-- Main content -->
                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Paramètres du compte</h1>
                    </div>

                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($success) ?>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Formulaire d'informations personnelles -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Informations personnelles</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (isset($errors['profile'])): ?>
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                <?php foreach ($errors['profile'] as $error): ?>
                                                    <li><?= htmlspecialchars($error) ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>

                                    <form method="POST" enctype="multipart/form-data">
                                        <div class="text-center mb-4">
                                            <img src="../assets/images/profiles/<?= htmlspecialchars($etudiant['photo'] ?? 'default_profile.jpg') ?>" 
                                                 class="rounded-circle" width="150" alt="Photo de profil">
                                            <div class="mt-3">
                                                <label class="btn btn-sm btn-outline-primary">
                                                    Changer la photo
                                                    <input type="file" name="photo" class="d-none" accept="image/*">
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="nom">Nom *</label>
                                                <input type="text" class="form-control" id="nom" name="nom" 
                                                       value="<?= htmlspecialchars($etudiant['nom']) ?>" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="prenom">Prénom *</label>
                                                <input type="text" class="form-control" id="prenom" name="prenom" 
                                                       value="<?= htmlspecialchars($etudiant['prenom']) ?>" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Email *</label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   value="<?= htmlspecialchars($etudiant['email']) ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="telephone">Téléphone</label>
                                            <input type="tel" class="form-control" id="telephone" name="telephone" 
                                                   value="<?= htmlspecialchars($etudiant['telephone'] ?? '') ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="date_naissance">Date de naissance</label>
                                            <input type="date" class="form-control" id="date_naissance" name="date_naissance" 
                                                   value="<?= htmlspecialchars($etudiant['date_naissance'] ?? '') ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="adresse">Adresse</label>
                                            <input type="text" class="form-control" id="adresse" name="adresse" 
                                                   value="<?= htmlspecialchars($etudiant['adresse'] ?? '') ?>">
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="ville">Ville</label>
                                                <input type="text" class="form-control" id="ville" name="ville" 
                                                       value="<?= htmlspecialchars($etudiant['ville'] ?? '') ?>">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="pays">Pays</label>
                                                <input type="text" class="form-control" id="pays" name="pays" 
                                                       value="<?= htmlspecialchars($etudiant['pays'] ?? '') ?>">
                                            </div>
                                        </div>

                                        <button type="submit" name="update_profile" class="btn btn-primary">
                                            Enregistrer les modifications
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Formulaire de changement de mot de passe -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Changer le mot de passe</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (isset($errors['password'])): ?>
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                <?php foreach ($errors['password'] as $error): ?>
                                                    <li><?= htmlspecialchars($error) ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>

                                    <form method="POST">
                                        <div class="form-group">
                                            <label for="current_password">Mot de passe actuel *</label>
                                            <input type="password" class="form-control" id="current_password" 
                                                   name="current_password" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="new_password">Nouveau mot de passe *</label>
                                            <input type="password" class="form-control" id="new_password" 
                                                   name="new_password" minlength="8" required>
                                            <small class="form-text text-muted">
                                                Minimum 8 caractères
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <label for="confirm_password">Confirmer le nouveau mot de passe *</label>
                                            <input type="password" class="form-control" id="confirm_password" 
                                                   name="confirm_password" required>
                                        </div>

                                        <button type="submit" name="update_password" class="btn btn-primary">
                                            Changer le mot de passe
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Paramètres de notification -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Préférences de notification</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST">
                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" id="notif_email" checked>
                                            <label class="form-check-label" for="notif_email">
                                                Recevoir des notifications par email
                                            </label>
                                        </div>
                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" id="notif_sms" checked>
                                            <label class="form-check-label" for="notif_sms">
                                                Recevoir des notifications par SMS
                                            </label>
                                        </div>
                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" id="notif_newsletter" checked>
                                            <label class="form-check-label" for="notif_newsletter">
                                                Recevoir la newsletter
                                            </label>
                                        </div>
                                        <button type="submit" name="update_notifications" class="btn btn-primary">
                                            Enregistrer les préférences
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </main>

    <script>
        // Aperçu de la photo avant upload
        document.querySelector('input[name="photo"]').addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('.rounded-circle').src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Validation côté client pour le mot de passe
        document.querySelector('form[name="update_password"]').addEventListener('submit', function(e) {
            const newPass = document.getElementById('new_password').value;
            const confirmPass = document.getElementById('confirm_password').value;
            
            if (newPass !== confirmPass) {
                e.preventDefault();
                alert('Les nouveaux mots de passe ne correspondent pas');
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>