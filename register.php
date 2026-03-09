<?php
require_once 'contenus/config.php';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $telephone = trim($_POST['telephone'] ?? '');
    $date_naissance = $_POST['date_naissance'] ?? '';

    // Validation
    if (empty($nom)) $errors[] = "Le nom est requis";
    if (empty($prenom)) $errors[] = "Le prénom est requis";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide";
    }
    if (strlen($password) < 8) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas";
    }

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT id FROM etudiants WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $errors[] = "Cet email est déjà utilisé";
    }

    // Si aucune erreur, enregistrer
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $photo = 'default_profile.jpg'; // Photo par défaut

        try {
            $stmt = $pdo->prepare("INSERT INTO etudiants 
                (nom, prenom, email, mot_de_passe, telephone, date_naissance, photo, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            
            $stmt->execute([
                $nom, 
                $prenom, 
                $email, 
                $hashed_password, 
                $telephone, 
                $date_naissance,
                $photo
            ]);

            // Redirection après succès
            $_SESSION['success'] = "Inscription réussie! Vous pouvez maintenant vous connecter.";
            header('Location: login.php');
            exit;

        } catch (PDOException $e) {
            $errors[] = "Erreur technique: " . $e->getMessage();
        }
    }
}

include 'contenus/head.php';
?>

<body class="registration-page">
    <main class="main">
        <section class="section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-danger">
                                <h3 class="mb-0 text-white">Inscription Étudiant</h3>
                            </div>
                            <div class="card-body">

                                <?php if (!empty($errors)): ?>
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            <?php foreach ($errors as $error): ?>
                                                <li><?= htmlspecialchars($error) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <form method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nom">Nom <span>*</span></label>
                                                <input type="text" class="form-control" id="nom" name="nom" 
                                                    value="<?= htmlspecialchars($nom ?? '') ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="prenom">Prénom *</label>
                                                <input type="text" class="form-control" id="prenom" name="prenom" 
                                                    value="<?= htmlspecialchars($prenom ?? '') ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email *</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                            value="<?= htmlspecialchars($email ?? '') ?>" required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password">Mot de passe *</label>
                                                <input type="password" class="form-control" id="password" name="password" 
                                                    minlength="8" required>
                                                <small class="form-text text-muted">Minimum 8 caractères</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="confirm_password">Confirmer le mot de passe *</label>
                                                <input type="password" class="form-control" id="confirm_password" 
                                                    name="confirm_password" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="telephone">Téléphone</label>
                                                <input type="tel" class="form-control" id="telephone" name="telephone" 
                                                    value="<?= htmlspecialchars($telephone ?? '') ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="date_naissance">Date de naissance</label>
                                                <input type="date" class="form-control" id="date_naissance" 
                                                    name="date_naissance" value="<?= htmlspecialchars($date_naissance ?? '') ?>">
                                            </div>
                                        </div>
                                    </div>
                                        <br>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-danger btn-block">S'inscrire</button>
                                    </div>

                                    <div class="text-center mt-3">
                                        <p>Déjà inscrit? <a href="login.php">Connectez-vous ici</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script>
        // Validation côté client
        document.querySelector('form').addEventListener('submit', function(e) {
            let password = document.getElementById('password').value;
            let confirm = document.getElementById('confirm_password').value;
            
            if (password !== confirm) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas');
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>