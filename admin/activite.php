<?php
require_once '../contenus/config.php';
require_once '../contenus/auth.php';
admin_only();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = clean_input($_POST['titre']);
    $content = clean_input($_POST['description']);
    $date = clean_input($_POST['date']);
    $type = clean_input($_POST['type']);
    $lieu = clean_input($_POST['lieu']);
    $author_id = $_SESSION['admin_id'];

    $stmt = $pdo->prepare("INSERT INTO Evenements  (titre, description, date ,type ,lieu ) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title, $content, $date, $type, $lieu]);

    $_SESSION['success'] = "Activite publié avec succès";
    redirect('activite.php');
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
                                        <h5 class="card-title"> les Evenements</h5>
                                        <?php include 'activites/add.php'; ?>
                                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                            data-bs-target="#addModal">
                                            Ajouter un evenement
                                        </button>

                                        <?php include 'activites/list.php'; ?>

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