<script>
    document.addEventListener('DOMContentLoaded', function () {
        const items = document.querySelectorAll('.ressource-item');
        const lecteurContainer = document.getElementById('lecteur-container');
        const lecteurFichier = document.getElementById('lecteur-fichier');
        const lecteurTitre = document.getElementById('titre-lecture');
        const lecteurAuteur = document.getElementById('auteur-lecture');
        const lecteurImage = document.getElementById('lecteur-image');
        const btnTelecharger = document.getElementById('btn-telecharger');
        const fermerLecteur = document.getElementById('fermer-lecteur');

        items.forEach(item => {
            item.addEventListener('click', function () {
                const fichier = this.dataset.fichier;
                const titre = this.dataset.titre;
                const auteur = this.dataset.auteur;
                const image = this.dataset.image;

                lecteurTitre.textContent = titre;
                lecteurAuteur.textContent = "Par " + auteur;
                btnTelecharger.href = fichier;

                if (image) {
                    lecteurImage.src = image;
                    lecteurImage.style.display = 'block';
                } else {
                    lecteurImage.style.display = 'none';
                }

                // Affichage selon le type
                if (fichier.endsWith('.doc') || fichier.endsWith('.docx')) {
                    lecteurFichier.src = `https://docs.google.com/gview?url=${window.location.origin}/${fichier}&embedded=true`;
                } else {
                    lecteurFichier.src = fichier;
                }

                lecteurContainer.style.display = 'block';
            });
        });

        fermerLecteur.addEventListener('click', function () {
            lecteurContainer.style.display = 'none';
            lecteurFichier.src = ''; // Vide le lecteur
        });
    });


    // Fonction pour mettre à jour le titre de la page
    function updatePageTitle(title) {
        document.title = title;
    }

    // Mettre à jour le titre de la page au chargement
    updatePageTitle('Qadiriyya - E-Learning');



    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const items = document.querySelectorAll('.ressource-item');

        searchInput.addEventListener('input', function () {
            const search = this.value.toLowerCase();

            items.forEach(item => {
                const titre = item.dataset.titre.toLowerCase();
                const auteur = item.dataset.auteur ? item.dataset.auteur.toLowerCase() : '';

                if (titre.includes(search) || auteur.includes(search)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>