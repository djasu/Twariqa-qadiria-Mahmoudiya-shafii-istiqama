<?php
require_once '../contenus/config.php';
require_once '../contenus/functions.php';
require_once '../contenus/auth.php';
admin_only();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = clean_input($_POST['titre']);
    $description = clean_input($_POST['description']);
    $formateur = clean_input($_POST['formateur']);
    $date_debut = clean_input($_POST['date_debut']);
    $date_fin = clean_input($_POST['date_fin']);

    // Upload de l'image
    $image_url = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../assets/images/formations/";
        $image_url = upload_file($_FILES['image'], $target_dir);
    }

    // Enregistrement en base de données
    $stmt = $pdo->prepare("INSERT INTO formations (titre, description, formateur, image, date_debut, date_fin)
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$titre, $description, $formateur, $image_url, $date_debut, $date_fin]);

    $_SESSION['success'] = "Formation ajoutée avec succès.";
    redirect('formation.php');
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
                                        <h5 class="card-title">Les formations</h5>
                                        <?php include 'formations/add.php'; ?>
                                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                            data-bs-target="#addModal">
                                            Ajouter une formation
                                        </button>

                                        <?php include 'formations/list.php'; ?>

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