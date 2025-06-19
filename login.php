<?php
require_once 'contenus/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $errors = [];

    if (empty($email) || empty($password)) {
        $errors[] = "Email et mot de passe requis";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['etudiant_id'] = $user['id'];
            $_SESSION['etudiant_nom'] = $user['nom'];
            $_SESSION['etudiant_email'] = $user['email'];
            
            header('Location: users/dashboard.php');
            exit;
        } else {
            $errors[] = "Identifiants incorrects";
        }
    }
}

include 'contenus/head.php';
?>

<body class="login-page">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-header bg-danger">
                        <h4 class="mb-0 text-white">Connexion Étudiant</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <?php foreach ($errors as $error): ?>
                                    <p class="mb-0"><?= htmlspecialchars($error) ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div><br>
                            <button type="submit" class="btn btn-danger btn-block">Se connecter</button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <p>Pas encore inscrit? <a href="register.php">Créer un compte</a></p>
                            <p><a href="forgot_password.php">Mot de passe oublié?</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>