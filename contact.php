<!DOCTYPE html>
<html lang="en">
<?php
require_once 'contenus/config.php';

$successMessage = null;
$errorMessage = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = clean_input($_POST['name']);
    $email = clean_input($_POST['email']);
    $subject = clean_input($_POST['subject']);
    $message = clean_input($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact (name, email, subject, message) VALUES (?, ?, ?, ?)");
            $success = $stmt->execute([$name, $email, $subject, $message]);

            if ($success) {
                $successMessage = "Votre message a été envoyé avec succès.";
            } else {
                $errorMessage = "Échec de l'envoi du message.";
            }
        } catch (PDOException $e) {
            $errorMessage = "Erreur PDO : " . $e->getMessage();
        }
    } else {
        $errorMessage = "Veuillez remplir tous les champs.";
    }
}
?>

<?php include 'contenus/head.php'; ?>

<body class="about-page">

    <?php include 'contenus/header.php'; ?>

    <main class="main">
        <!-- Page Title -->
        <div class="page-title light-background">
            <div class="container d-lg-flex justify-content-between align-items-center">
                <h1 class="mb-2 mb-lg-0">Contact</h1>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="index.php">Accuiel</a></li>
                        <li class="current">Contact</li>
                    </ol>
                </nav>
            </div>
        </div><!-- End Page Title -->
        <!-- Hero Section -->
        <section id="contact" class="contact section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="mb-4" data-aos="fade-up" data-aos-delay="200">
                    <!-- <iframe style="border:0; width: 100%; height: 270px;"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1994.123456789!2d29.233456!3d-1.678901!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19c2c123456789ab%3A0xabcdef1234567890!2sMosqu%C3%A9e%20IBB%20Station%2C%20Goma%2C%20RDC!5e0!3m2!1sfr!2scd!4v1620000000000!5m2!1sfr!2scd"
                        frameborder="0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe> -->

                </div><!-- End Google Maps -->

                <div class="row gy-4">

                    <div class="col-lg-4">
                        <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                            <i class="bi bi-geo-alt flex-shrink-0"></i>
                            <div>
                                <h3>Address</h3>
                                <p>A108 Adam Street, New York, NY 535022</p>
                            </div>
                        </div><!-- End Info Item -->

                        <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                            <i class="bi bi-telephone flex-shrink-0"></i>
                            <div>
                                <h3>Call Us</h3>
                                <p>+1 5589 55488 55</p>
                            </div>
                        </div><!-- End Info Item -->

                        <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="500">
                            <i class="bi bi-envelope flex-shrink-0"></i>
                            <div>
                                <h3>Email Us</h3>
                                <p>info@qadiriyya.com</p>
                            </div>
                        </div><!-- End Info Item -->

                    </div>

                    <div class="col-lg-8">
                        <div class="section-title" data-aos="fade-up" data-aos-delay="100">
                            <h2>Contact Us</h2>
                            <p>Feel free to reach out to us with any questions or concerns.</p>
                            
                        <form action="contact.php" method="POST" class="php-email-form" data-aos="fade-up"
                            data-aos-delay="200">
                            <div class="row gy-4">

                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control" placeholder="Your Name"
                                        required="">
                                </div>

                                <div class="col-md-6 ">
                                    <input type="email" class="form-control" name="email" placeholder="Your Email"
                                        required="">
                                </div>

                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="subject" placeholder="Subject"
                                        required="">
                                </div>

                                <div class="col-md-12">
                                    <textarea class="form-control" name="message" rows="6" placeholder="Message"
                                        required=""></textarea>
                                </div>

                                <div class="col-md-12 text-center">
                                    <div class="sent-message"><?php if ($successMessage): ?>
                                        <div class="alert alert-success" role="alert">
                                            <?= $successMessage ?>
                                        </div>

                                    <?php elseif ($errorMessage): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?= $errorMessage ?>
                                        </div>
                                    <?php endif; ?></div>
                                    <button type="submit">Send Message</button>
                                </div>

                            </div>
                        </form>
                    </div><!-- End Contact Form -->

                </div>

            </div>

        </section>
    </main>

    <?php include 'contenus/footer.php'; ?>

    <?php include 'contenus/script.php'; ?>

</body>

</html>