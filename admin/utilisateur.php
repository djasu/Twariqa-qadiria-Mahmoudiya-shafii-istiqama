<?php
require_once '../contenus/config.php';
require_once '../contenus/functions.php';
require_once '../contenus/auth.php';
admin_only();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = clean_input($_POST['nom']);
    $email = clean_input($_POST['email']);
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT); // Hachage du mot de passe
    $role = clean_input($_POST['role']);

    // Upload de l'image de profil
    $image_url = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../assets/images/users/";
        $image_url = upload_file($_FILES['image'], $target_dir);
    }

    // Enregistrement dans la base de données
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role, image)
                           VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $email, $mot_de_passe, $role, $image_url]);

    $_SESSION['success'] = "Utilisateur ajouté avec succès.";
    redirect('utilisateur.php');
}
?>

<?php include '../contenus/admin/head.php'; ?>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <?php include '../contenus/admin/aside.php'; ?>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <?php include '../contenus/admin/nav.php'; ?>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Les Utilisateurs</h5>
                                        <?php include 'Auth/add.php'; ?>
                                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                            data-bs-target="#addModal">
                                            Ajouter un utilisateur
                                        </button>

                                        <?php include 'Auth/list.php'; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- / Content -->

                <!-- Footer -->
                <?php include '../contenus/admin/footer.php'; ?>
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <?php include '../contenus/admin/script.php'; ?>

</body>