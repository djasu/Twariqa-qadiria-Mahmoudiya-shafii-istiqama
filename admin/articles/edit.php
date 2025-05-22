<?php
require_once '../../includes/auth.php';
admin_only();

$article_id = $_GET['id'] ?? 0;

// Récupérer l'article à éditer
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$article_id]);
$article = $stmt->fetch();

if (!$article) {
    $_SESSION['error'] = "Article introuvable";
    redirect('/admin/articles/list.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = clean_input($_POST['title']);
    $content = clean_input($_POST['content']);
    
    // Gestion de l'image
    $image_url = $article['image_url'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        // Supprimer l'ancienne image si elle existe
        if ($image_url && file_exists("../../assets/images/uploads/$image_url")) {
            unlink("../../assets/images/uploads/$image_url");
        }
        
        // Uploader la nouvelle image
        $target_dir = "../../assets/images/uploads/";
        $image_url = upload_file($_FILES['image'], $target_dir);
    }
    
    $stmt = $pdo->prepare("UPDATE articles SET title = ?, content = ?, image_url = ? WHERE id = ?");
    $stmt->execute([$title, $content, $image_url, $article_id]);
    
    $_SESSION['success'] = "Article mis à jour avec succès";
    redirect('/admin/articles/list.php');
}
?>

<?php include '../../includes/header.php'; ?>

<h2>Modifier l'Article</h2>

<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="title" class="form-label">Titre</label>
        <input type="text" class="form-control" id="title" name="title" 
               value="<?= htmlspecialchars($article['title']) ?>" required>
    </div>
    
    <div class="mb-3">
        <label for="content" class="form-label">Contenu</label>
        <textarea class="form-control" id="content" name="content" rows="10" required>
            <?= htmlspecialchars($article['content']) ?>
        </textarea>
    </div>
    
    <div class="mb-3">
        <label for="image" class="form-label">Image</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*">
        
        <?php if ($article['image_url']): ?>
            <div class="mt-2">
                <img src="/assets/images/uploads/<?= $article['image_url'] ?>" 
                     alt="Image actuelle" style="max-height: 200px;">
                <p class="text-muted mt-1">Image actuelle</p>
            </div>
        <?php endif; ?>
    </div>
    
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="/admin/articles/list.php" class="btn btn-secondary">Annuler</a>
</form>

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

<?php include '../../includes/footer.php'; ?>