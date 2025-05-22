<?php
require_once '../../includes/auth.php';
admin_only();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = clean_input($_POST['title']);
    $content = clean_input($_POST['content']);
    $author_id = $_SESSION['admin_id'];
    
    // Upload de l'image
    $image_url = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../../assets/images/uploads/";
        $image_url = upload_file($_FILES['image'], $target_dir);
    }
    
    $stmt = $pdo->prepare("INSERT INTO articles (title, content, image_url, author_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $content, $image_url, $author_id]);
    
    $_SESSION['success'] = "Article publié avec succès";
    redirect('/admin/articles/list.php');
}
?>

<?php include '../../includes/header.php'; ?>
<h2>Ajouter un Article</h2>

<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="title" class="form-label">Titre</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    
    <div class="mb-3">
        <label for="content" class="form-label">Contenu</label>
        <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
    </div>
    
    <div class="mb-3">
        <label for="image" class="form-label">Image</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*">
    </div>
    
    <button type="submit" class="btn btn-primary">Publier</button>
    <a href="/admin/articles/list.php" class="btn btn-secondary">Annuler</a>
</form>

<!-- TinyMCE Editor -->
<script src="https://cdn.tiny.cloud/1/YOUR_API_KEY/tinymce/5/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#content',
        plugins: 'link image table code',
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code'
    });
</script>

<?php include '../../includes/footer.php'; ?>