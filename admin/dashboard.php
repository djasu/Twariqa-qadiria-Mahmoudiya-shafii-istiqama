<?php
require_once '../contenus/auth.php';
admin_only();

// Récupérer les statistiques
$articles_count = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$activities_count = $pdo->query("SELECT COUNT(*) FROM evenements")->fetchColumn();
$unread_contacts = $pdo->query("SELECT COUNT(*) FROM contact ")->fetchColumn();
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

                        <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
                  <div class="card">
                    <div class="row row-bordered g-0">
                      <div class="col-md-8">
                        <h5 class="card-header m-0 me-2 pb-3">Total Revenue</h5>
                        <div id="totalRevenueChart" class="px-2"></div>
                      </div>
                      <div class="col-md-4">
                        <div class="card-body">
                          <div class="text-center">
                            <div class="dropdown">
                              <button
                                class="btn btn-sm btn-outline-primary dropdown-toggle"
                                type="button"
                                id="growthReportId"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                2022
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="growthReportId">
                                <a class="dropdown-item" href="javascript:void(0);">2021</a>
                                <a class="dropdown-item" href="javascript:void(0);">2020</a>
                                <a class="dropdown-item" href="javascript:void(0);">2019</a>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div id="growthChart"></div>
                        <div class="text-center fw-semibold pt-3 mb-2">62% Company Growth</div>

                        <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
                          <div class="d-flex">
                            <div class="me-2">
                              <span class="badge bg-label-primary p-2"><i class="bx bx-dollar text-primary"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                              <small>2022</small>
                              <h6 class="mb-0">$32.5k</h6>
                            </div>
                          </div>
                          <div class="d-flex">
                            <div class="me-2">
                              <span class="badge bg-label-info p-2"><i class="bx bx-wallet text-info"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                              <small>2021</small>
                              <h6 class="mb-0">$41.2k</h6>
                            </div>
                          </div>
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