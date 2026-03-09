<?php
require_once '../../contenus/config.php';
require_once '../../contenus/functions.php';
require_once '../../contenus/auth.php';
admin_only();

$formation_id = $_GET['id'] ?? 0;

// Récupérer la formation à éditer
$stmt = $pdo->prepare("SELECT * FROM formations WHERE id = ?");
$stmt->execute([$formation_id]);
$formation = $stmt->fetch();

if (!$formation) {
    $_SESSION['error'] = "formation introuvable";
    redirect('../galerie.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = clean_input($_POST['titre']);
    $description = clean_input($_POST['description']);
    $formateur = clean_input($_POST['formateur']);
    $date_debut = clean_input($_POST['date_debut']);
    $date_fin = clean_input($_POST['date_fin']);
    $duree_totale = clean_input($_POST['duree_totale']);
    $niveau = clean_input($_POST['niveau']);
    $prix = clean_input($_POST['prix']);
    $statut = clean_input($_POST['statut']);
    $objectifs = clean_input($_POST['objectifs']);

    // Gestion de l'image
    $fichier_url = $formation['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        // Supprimer l'ancienne image si elle existe
        if ($fichier_url && file_exists("../../assets/images/formations/$fichier_url")) {
            unlink("../../assets/images/formations/$fichier_url");
        }

        // Uploader la nouvelle image
        $target_dir = "../../assets/images/formations/";
        $fichier_url = upload_file($_FILES['image'], $target_dir);
    }

    $stmt = $pdo->prepare("UPDATE `formations` SET `titre`=?,`description`=?,`formateur`=?,`image`=?,`date_debut`=?,
    `date_fin`=?,`duree_totale`=?,`niveau`=?,`prix`=?,`statut`=?, `objectifs`=? WHERE `id`= ?");
    $stmt->execute([
        $titre,
        $description,
        $formateur,
        $fichier_url,
        $date_debut,
        $date_fin,
        $duree_totale,
        $niveau,
        $prix,
        $statut,
        $objectifs,
        $formation_id
    ]);

    $_SESSION['success'] = "formation mise à jour avec succès";
    redirect('../formation.php');
}
?>

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>qadiriyya</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../assets//images/img/images (1).png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="../../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../../assets/css/demo.css" />
    <!-- Boxicons CDN -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />


    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="../../assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <?php include '../../contenus/admin/aside.php'; ?>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- Place this tag where you want the button to render. -->
                            <li class="nav-item lh-1 me-3">
                                <?php $author_id = $_SESSION['username']; ?>
                                <a class="github-button"
                                    href="https://github.com/themeselection/sneat-html-admin-template-free"
                                    data-icon="octicon-star" data-size="large" data-show-count="true"
                                    aria-label="Star themeselection/sneat-html-admin-template-free on GitHub"><?php echo $author_id; ?></a>
                            </li>
                        </ul>
                    </div>
                </nav>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"> Modifier la Formation</h5>
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="titre" class="form-label">Titre de la
                                                            formation</label>
                                                        <input type="text" class="form-control" id="titre" name="titre" value="<?= htmlspecialchars($formation['titre']) ?>"
                                                            required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="date_debut" class="form-label">Date de début</label>
                                                        <input type="date" class="form-control" id="date_debut" value="<?= htmlspecialchars($formation['date_debut']) ?>"
                                                            name="date_debut" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="formateur" class="form-label">Formateur(trice)</label>
                                                        <input type="text" class="form-control" id="formateur"
                                                            name="formateur" value="<?= htmlspecialchars($formation['formateur']) ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="date_fin" class="form-label">Date de fin</label>
                                                        <input type="date" class="form-control" id="date_fin"
                                                            name="date_fin" value="<?= htmlspecialchars($formation['date_fin']) ?>" required>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="niveau" class="form-label">Niveau</label>
                                                        <input type="text" class="form-control" id="niveau"
                                                            name="niveau" required value="<?= htmlspecialchars($formation['niveau']) ?>">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="statut" class="form-label">Statut</label>
                                                        <select name="statut" id="statut" class="form-select">
                                                            <option value="">-- selectionner un statut</option>
                                                            <option value="brouillon">brouillon</option>
                                                            <option value="publié">publié</option>
                                                            <option value="archivé">archivé</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="duree_totale" class="form-label">Duree (nombre
                                                            d'heure en chiffre)</label>
                                                        <input type="number" class="form-control" id="duree_totale"
                                                            name="duree_totale" value="<?= htmlspecialchars($formation['duree_totale']) ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="prix" class="form-label">Prix</label>
                                                        <input type="number" class="form-control" id="prix" name="prix" value="<?= htmlspecialchars($formation['prix']) ?>"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="image" class="form-label">Image illustrative</label>
                                                        <input type="file" class="form-control" id="image" name="image"
                                                            accept="image/*">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="object" class="form-label">Objectif</label>
                                                        <input type="text" class="form-control" id="objectifs" required
                                                            name="objectifs" value="<?= htmlspecialchars($formation['objectifs']) ?>" >
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Description -->
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Description</label>
                                                <textarea class="form-control" id="description" name="description"
                                                    rows="4" required><?= htmlspecialchars($formation['description']) ?></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                                            <a href="../formation.php" class="btn btn-secondary">Annuler</a>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <?php include '../../contenus/admin/footer.php'; ?>
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

    <?php include '../../contenus/admin/script.php'; ?>
    <!-- TinyMCE Editor -->
    <script src="https://cdn.tiny.cloud/1/YOUR_API_KEY/tinymce/5/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#content',
            plugins: 'link image table code',
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
            height: 400
        });
    </script>
</body>