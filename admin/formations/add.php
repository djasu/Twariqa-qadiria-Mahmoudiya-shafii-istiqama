<!-- Modal d'ajout de formation -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form method="POST" enctype="multipart/form-data" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Ajouter une formation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre de la
                                formation</label>
                            <input type="text" class="form-control" id="titre" name="titre" required>
                        </div>
                        <div class="mb-3">
                            <label for="date_debut" class="form-label">Date de début</label>
                            <input type="date" class="form-control" id="date_debut" name="date_debut" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="formateur" class="form-label">Formateur(trice)</label>
                            <input type="text" class="form-control" id="formateur" name="formateur" required>
                        </div>
                        <div class="mb-3">
                            <label for="date_fin" class="form-label">Date de fin</label>
                            <input type="date" class="form-control" id="date_fin" name="date_fin" required>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="niveau" class="form-label">Niveau</label>
                            <input type="text" class="form-control" id="niveau" name="niveau" required>
                        </div>
                        <div class="mb-3">
                            <label for="statut" class="form-label">Statut</label>
                            <select name="statut" id="statut" class="form-select">
                                <option value="">-- selectionner un statut</option>
                                <option value="brouillon">brouillon</option>
                                <option value="publié">publié</option>
                                <option value="archivé">archivé</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="duree_totale" class="form-label">Duree (nombre
                                d'heure en chiffre)</label>
                            <input type="number" class="form-control" id="duree_totale" name="duree_totale" required>
                        </div>
                        <div class="mb-3">
                            <label for="prix" class="form-label">Prix</label>
                            <input type="number" class="form-control" id="prix" name="prix" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="image" class="form-label">Image illustrative</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="object" class="form-label">Objectif</label>
                            <input type="text" class="form-control" id="objectifs" required name="objectifs">
                        </div>
                    </div>
                </div>
                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            </div>

        </form>
    </div>
</div>