<!-- Modal d'ajout de ressource -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" enctype="multipart/form-data" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Ajouter une ressource</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <div class="modal-body">
                <!-- Titre -->
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre de la ressource</label>
                    <input type="text" class="form-control" id="titre" name="titre" required>
                </div>

                <!-- Type -->
                <div class="mb-3">
                    <label for="type" class="form-label">Type de ressource</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="">-- Sélectionnez un type --</option>
                        <option value="pdf">PDF</option>
                        <option value="word">Word</option>
                        <option value="ppt">PowerPoint</option>
                        <option value="image">Image</option>
                        <option value="video">Vidéo</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>

                <!-- Auteur -->
                <div class="mb-3">
                    <label for="auteur" class="form-label">Auteur</label>
                    <input type="text" class="form-control" id="auteur" name="auteur" required>
                </div>

                <!-- Image -->
                <div class="mb-3">
                    <label for="image" class="form-label">Image illustrative</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>

                <!-- Fichier -->
                <div class="mb-3">
                    <label for="fichier" class="form-label">Fichier de la ressource</label>
                    <input type="file" class="form-control" id="fichier" name="fichier" accept=".pdf,.doc,.docx,.ppt,.pptx,.jpg,.png,.mp4,.avi,.zip" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            </div>

        </form>
    </div>
</div>
