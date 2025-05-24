<!-- Modal d'ajout d'article -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" enctype="multipart/form-data" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Ajouter un événement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label for="title" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="title" name="titre" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="6" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Type d'événement</label>
                    <select class="form-control" id="type" name="type" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="Zikr">Zikr</option>
                        <option value="Conférence">Conférence</option>
                        <option value="Mawlid">Mawlid</option>
                        <option value="Retraite spirituelle">Retraite spirituelle</option>
                        <option value="Cours">Cours</option>
                        <option value="Visite sociale">Visite sociale</option>
                        <option value="Célébration">Célébration</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="lieu" class="form-label">Lieu</label>
                    <input type="text" class="form-control" id="lieu" name="lieu" required>
                </div>

                <div class="mb-3">
                    <label for="date" class="form-label">Date de l'événement</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            </div>

        </form>
    </div>
</div>

<!-- TinyMCE Editor -->
<script src="https://cdn.tiny.cloud/1/YOUR_API_KEY/tinymce/5/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#content',
        plugins: 'link image table code',
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code'
    });
</script>