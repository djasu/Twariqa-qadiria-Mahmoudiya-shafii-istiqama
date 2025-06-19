<!DOCTYPE html>
<html lang="en">
<?php  require_once 'contenus/config.php';

// Récupérer les statistiques
$articles_count = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$activities_count = $pdo->query("SELECT COUNT(*) FROM evenements")->fetchColumn();
$resources_count = $pdo->query("SELECT COUNT(*) FROM ressources ")->fetchColumn();
$formations_count = $pdo->query("SELECT COUNT(*) FROM formations ")->fetchColumn();
?>

<?php include 'contenus/head.php'; ?>

<body class="about-page">

<?php include 'contenus/header.php'; ?>

  <main class="main">
    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Appropos</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.php">Accuiel</a></li>
            <li class="current">Appropos</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->
    <!-- Hero Section -->
    <?php include 'section-about/about1.php'; ?>
    <!-- /Hero Section -->
<?php include 'section-about/stats.php'; ?>
  </main>

  <?php include 'contenus/footer.php'; ?>

<?php include 'contenus/script.php'; ?>

</body>

</html>