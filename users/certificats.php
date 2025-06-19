<?php
require_once '../contenus/config.php';

// Vérifier si l'étudiant est connecté
if (!isset($_SESSION['etudiant_id'])) {
    header('Location: ../login.php');
    exit;
}

// Récupérer les informations de l'étudiant
$etudiant = $pdo->prepare("SELECT * FROM etudiants WHERE id = ?");
$etudiant->execute([$_SESSION['etudiant_id']]);
$etudiant = $etudiant->fetch();

// Récupérer les certificats
$certificats = $pdo->prepare("
    SELECT c.*, f.titre as formation_titre, f.image as formation_image
    FROM certifications c
    JOIN formations f ON c.formation_id = f.id
    WHERE c.etudiant_id = ?
    ORDER BY c.date_obtention DESC
");
$certificats->execute([$_SESSION['etudiant_id']]);
$certificats = $certificats->fetchAll();

include 'include/head.php';
?>
    <style>
        /* Impression */
        @media print {
            body * {
                visibility: hidden;
            }
        }
    </style>
<body class="certificats-page">
    <?php include 'include/sidebar.php'; ?>

    <main class="main">
        <div class="container-fluid">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Mes Certificats</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                            <i class="fas fa-print"></i> Imprimer
                        </button>
                    </div>
                </div>
            </div>

            <?php if (empty($certificats)): ?>
                <div class="alert alert-info">
                    Vous n'avez pas encore de certificats. Complétez des formations pour obtenir vos certificats.
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($certificats as $certificat): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card certificate-card h-100">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Certificat de Réussite</h5>
                                </div>
                                <div class="card-body text-center">
                                    <div class="certificate-badge mb-3">
                                        <i class="fas fa-certificate fa-4x text-warning"></i>
                                    </div>
                                    <h4><?= htmlspecialchars($certificat['formation_titre']) ?></h4>
                                    <p class="text-muted">
                                        Délivré le <?= date('d/m/Y', strtotime($certificat['date_obtention'])) ?>
                                    </p>
                                    <div class="mb-3">
                                        <img src="../assets/images/formations/<?= htmlspecialchars($certificat['formation_image']) ?>"
                                            class="img-fluid rounded" style="max-height: 100px;"
                                            alt="<?= htmlspecialchars($certificat['formation_titre']) ?>">
                                    </div>
                                    <p class="small text-muted">
                                        Certificat ID: <?= htmlspecialchars($certificat['code_verification']) ?>
                                    </p>
                                </div>
                                <div class="card-footer bg-white">
                                    <div class="d-flex justify-content-between">
                                        <a href="certificat_view.php?id=<?= $certificat['id'] ?>"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                        <a href="<?= htmlspecialchars($certificat['url_certificat']) ?>"
                                            class="btn btn-sm btn-primary" download>
                                            <i class="fas fa-download"></i> Télécharger
                                        </a>
                                        <button class="btn btn-sm btn-outline-secondary share-certificate"
                                            data-id="<?= $certificat['id'] ?>">
                                            <i class="fas fa-share-alt"></i> Partager
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Modal pour partager un certificat -->
    <div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Partager ce certificat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Lien de partage</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="shareLink" readonly>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" id="copyLinkBtn">
                                    <i class="fas fa-copy"></i> Copier
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="social-share mt-3">
                        <button class="btn btn-facebook mr-2">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </button>
                        <button class="btn btn-twitter mr-2">
                            <i class="fab fa-twitter"></i> Twitter
                        </button>
                        <button class="btn btn-linkedin">
                            <i class="fab fa-linkedin-in"></i> LinkedIn
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>



    <script>
        // Gestion du partage des certificats
        $(document).ready(function () {
            $('.share-certificate').click(function () {
                const certId = $(this).data('id');
                const shareUrl = `${window.location.origin}/certificat_view.php?id=${certId}`;

                $('#shareLink').val(shareUrl);
                $('#shareModal').modal('show');
            });

            $('#copyLinkBtn').click(function () {
                const shareLink = document.getElementById('shareLink');
                shareLink.select();
                document.execCommand('copy');

                $(this).html('<i class="fas fa-check"></i> Copié!');
                setTimeout(() => {
                    $(this).html('<i class="fas fa-copy"></i> Copier');
                }, 2000);
            });

            // Boutons de partage social
            $('.btn-facebook').click(function () {
                const url = encodeURIComponent($('#shareLink').val());
                window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
            });

            $('.btn-twitter').click(function () {
                const url = encodeURIComponent($('#shareLink').val());
                const text = "J'ai obtenu un certificat sur la plateforme Qadiriyya!";
                window.open(`https://twitter.com/intent/tweet?text=${text}&url=${url}`, '_blank');
            });

            $('.btn-linkedin').click(function () {
                const url = encodeURIComponent($('#shareLink').val());
                window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}`, '_blank');
            });
        });
    </script>
</body>

</html>