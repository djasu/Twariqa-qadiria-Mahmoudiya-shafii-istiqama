<?php
require_once '../contenus/config.php';
require_once '../contenus/functions.php';
require_once '../contenus/auth.php';
admin_only();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = clean_input($_POST['titre']);
    $content = clean_input($_POST['contenu']);
    $date = clean_input($_POST['date_publication']);
    $author_id = $_SESSION['admin_id'];

    // Upload de l'image
    $image_url = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../assets/images/articles/";
        $image_url = upload_file($_FILES['image'], $target_dir);
    }

    $stmt = $pdo->prepare("INSERT INTO Articles  (titre, contenu, date_publication ,image ,auteur_id ) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title, $content, $date, $image_url, $author_id]);

    $_SESSION['success'] = "Article publié avec succès";
    redirect('article.php');
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
                                        <h5 class="card-title"> les Articles</h5>
                                        <?php include 'articles/add.php'; ?>
                                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                            data-bs-target="#addArticleModal">
                                            Ajouter un article
                                        </button>

                                        <?php include 'articles/list.php'; ?>

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