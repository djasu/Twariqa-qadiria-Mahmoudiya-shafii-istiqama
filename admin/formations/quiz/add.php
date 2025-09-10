<!-- Modal d'ajout à la galerie -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST"  enctype="multipart/form-data" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Ajouter une image à la galerie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <div class="modal-body">
                <!-- Sélection de l'événement -->
                <div class="mb-3">
                    <label for="evenement_id" class="form-label">Événement</label>
                    <select class="form-select" id="evenement_id" name="evenement_id" required>
                        <option value="">-- Sélectionnez un événement --</option>
                        <?php
                        // La connexion à la base est déjà incluse ailleurs
                        $stmt = $pdo->query("SELECT id, titre FROM evenements ORDER BY id DESC");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['titre']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <!-- Type de média -->
                <div class="mb-3">
                    <label for="type" class="form-label">Type de média</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="">-- Sélectionnez un type --</option>
                        <option value="photo">Photo</option>
                        <option value="video">Vidéo</option>
                        <option value="audio">Audio</option>
                    </select>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                </div>

                <!-- Image -->
                <div class="mb-3">
                    <label for="image" class="form-label">Fichier</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*,video/*,audio/*" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            </div>

        </form>
    </div>
</div>
