<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    <div class="sidebar-sticky pt-3">
        <div class="text-center mb-4">
            <img src="../assets/images/profiles/<?= htmlspecialchars($etudiant['photo'] ?? 'default_profile.jpg') ?>" 
                 class="rounded-circle" width="100" alt="Photo de profil">
            <h5 class="mt-2"><?= htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']) ?></h5>
            <p class="text-muted"><?= htmlspecialchars($etudiant['email']) ?></p>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="dashboard.php">
                    <i class="fas fa-tachometer-alt"></i> Tableau de bord
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="mes_formations.php">
                    <i class="fas fa-book"></i> Mes formations
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="progression.php">
                    <i class="fas fa-chart-line"></i> Ma progression
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="certificats.php">
                    <i class="fas fa-certificate"></i> Mes certificats
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="parametre.php">
                    <i class="fas fa-cog"></i> Paramètres
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </li>
        </ul>
    </div>
</nav>