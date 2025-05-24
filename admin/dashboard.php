<?php
require_once '../contenus/auth.php';
admin_only();

// Récupérer les statistiques
$articles_count = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$activities_count = $pdo->query("SELECT COUNT(*) FROM evenements")->fetchColumn();
$unread_contacts = $pdo->query("SELECT COUNT(*) FROM contacts ")->fetchColumn();
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
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <div class="card text-white bg-primary">
                                        <div class="card-body">
                                            <h5 class="card-title">Articles</h5>
                                            <p class="card-text"><?= $articles_count ?> publiés</p>
                                            <a href="/admin/articles/list.php" class="btn btn-light">Gérer</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-4">
                                    <div class="card text-white bg-success">
                                        <div class="card-body">
                                            <h5 class="card-title">Activités</h5>
                                            <p class="card-text"><?= $activities_count ?> planifiées</p>
                                            <a href="/admin/activities/list.php" class="btn btn-light">Gérer</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-4">
                                    <div class="card text-white bg-warning">
                                        <div class="card-body">
                                            <h5 class="card-title">Messages</h5>
                                            <p class="card-text"><?= $unread_contacts ?> non lus</p>
                                            <a href="/admin/contacts/" class="btn btn-light">Voir</a>
                                        </div>
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