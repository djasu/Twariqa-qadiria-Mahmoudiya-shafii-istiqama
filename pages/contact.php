<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = clean_input($_POST['name']);
    $email = filter_var(clean_input($_POST['email']), FILTER_VALIDATE_EMAIL);
    $message = clean_input($_POST['message']);
    
    if ($name && $email && $message) {
        $stmt = $pdo->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $message]);
        
        $success = "Votre message a été envoyé avec succès!";
    } else {
        $error = "Veuillez remplir tous les champs correctement.";
    }
}
?>

<?php include '../includes/header.php'; ?>
<h1 class="mb-4">Contactez-nous</h1>

<div class="row">
    <div class="col-md-6">
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php elseif (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Nom complet</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
    </div>
    
    <div class="col-md-6">
        <h3>Nos coordonnées</h3>
        <p><i class="bi bi-geo-alt"></i> Adresse: Goma, Nord Kivu-RDC</p>
        <p><i class="bi bi-telephone"></i> Téléphone: +243 836 567 391</p>
        <p><i class="bi bi-envelope"></i> Email: contact@qadiriyya.org</p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>