<!-- Modal d'ajout d'utilisateur -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" enctype="multipart/form-data" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Ajouter un utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <div class="modal-body">
                <!-- Nom -->
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse e-mail</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <!-- Mot de passe -->
                <div class="mb-3">
                    <label for="mot_de_passe" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                </div>

                <!-- Rôle -->
                <div class="mb-3">
                    <label for="role" class="form-label">Rôle</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="">-- Sélectionner un rôle --</option>
                        <option value="admin">Admin</option>
                        <option value="editeur">Éditeur</option>
                        <option value="utilisateur">Utilisateur</option>
                    </select>
                </div>

                <!-- Image de profil -->
                <div class="mb-3">
                    <label for="image" class="form-label">Photo de profil</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            </div>

        </form>
    </div>
</div>
