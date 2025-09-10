<?php
require_once '../contenus/config.php';
require_once '../contenus/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean_input($_POST['username']);
    $password = clean_input($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM Utilisateurs  WHERE nom = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['mot_de_passe'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['username'] = $admin['nom'];

        $redirect = $_SESSION['redirect_url'] ?? 'dashboard.php';
        unset($_SESSION['redirect_url']);
        redirect($redirect);
    } else {
        $error = "Identifiants incorrects";
    }
}
?>
<?php include '../contenus/admin/head.php'; ?>
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <!-- Register -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center">
                        <a href="index.html" class="app-brand-link gap-2">
                            <span class="app-brand-text demo text-body fw-bolder">Quadiriyya</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-2">Login!</h4>
                    <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $error ?> 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>

                </div>
                <?php endif; ?>
                    <form id="formAuthentication" class="mb-3" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username"
                                placeholder="Enter your username" autofocus />
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Password</label>
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password"
                                    placeholder="****" aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-me" />
                                <label class="form-check-label" for="remember-me">rappellez-moi</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                        </div>
                    </form>
                    </p>
                </div>
            </div>
            <!-- /Register -->
        </div>
    </div>
</div>
