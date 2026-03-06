// js.js

document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.querySelector('input[name="search"]');
    const searchForm  = searchInput?.closest('form');

    if (!searchInput || !searchForm) return;

    // ── Dictionnaire mots-clés → section de la page dashboard
    const MOTS_CLES = {
        // Défis
        'defi'       : 'defis',
        'challenge'  : 'defis',
        'mission'    : 'defis',
        'objectif'   : 'defis',

        // Classement
        'classement' : 'classement',
        'top'        : 'classement',
        'score'      : 'classement',
        'rang'       : 'classement',

        // Utilisateurs
        'profil'     : 'profil',
        'membre'     : 'profil',
        'utilisateur': 'profil',
    };

    // ── 1. Suggestions via <datalist> (autocomplétion native HTML)
    function initialiserSuggestions() {
        let datalist = document.getElementById('suggestions-recherche');

        // Crée le datalist s'il n'existe pas encore dans le HTML
        if (!datalist) {
            datalist = document.createElement('datalist');
            datalist.id = 'suggestions-recherche';
            document.body.appendChild(datalist);
            searchInput.setAttribute('list', 'suggestions-recherche');
        }

        // Suggestions = clés + valeurs uniques du dictionnaire
        const suggestions = [...new Set([
            ...Object.keys(MOTS_CLES),
            ...Object.values(MOTS_CLES)
        ])];

        datalist.innerHTML = '';
        suggestions.forEach(mot => {
            const option = document.createElement('option');
            option.value = mot.charAt(0).toUpperCase() + mot.slice(1);
            datalist.appendChild(option);
        });
    }

    // ── 2. Résolution mot-clé → ancre de section
    function trouverDestination(terme) {
        terme = terme.toLowerCase().trim();

        // Recherche directe dans le dictionnaire
        for (const mot in MOTS_CLES) {
            if (terme.includes(mot)) {
                return MOTS_CLES[mot];
            }
        }
        return null; // aucune correspondance
    }

    // ── 3. Soumission du formulaire
    searchForm.addEventListener('submit', function (e) {
        const terme = searchInput.value.trim();

        // Champ vide → bloque + focus
        if (terme.length === 0) {
            e.preventDefault();
            searchInput.focus();
            afficherMessage('Veuillez entrer un terme de recherche.', 'warning');
            return;
        }

        const destination = trouverDestination(terme);

        if (destination) {
            e.preventDefault(); // on gère nous-mêmes la navigation
            afficherMessage(`✅ Redirection vers : ${destination}`, 'success');

            // Redirige vers le dashboard avec l'ancre de la section
            window.location.href = `index.php?page=dashboard#${destination}`;
        }
        // Sinon : laisse le formulaire se soumettre normalement en POST
        // → PHP reçoit $_POST['search'] et filtre les résultats côté serveur
    });

    // ── 4. Soumission auto après 400 ms (debounce, min 3 caractères)
    let timer;
    searchInput.addEventListener('input', function () {
        clearTimeout(timer);
        if (this.value.trim().length >= 3) {
            timer = setTimeout(() => searchForm.submit(), 400);
        }
    });

    // ── 5. Échap = vide le champ
    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            this.value = '';
            this.blur();
            cacherMessage();
        }
    });

    // ── Utilitaire : bandeau message temporaire (remplace les alert())
    function afficherMessage(texte, type = 'info') {
        cacherMessage();

        const couleurs = {
            success : 'bg-green-800 text-green-200',
            warning : 'bg-yellow-800 text-yellow-200',
            error   : 'bg-red-800   text-red-200',
            info    : 'bg-indigo-800 text-indigo-200',
        };

        const bandeau = document.createElement('div');
        bandeau.id = 'bandeau-recherche';
        bandeau.className = `fixed top-4 right-4 z-50 px-5 py-3 rounded-xl text-sm font-semibold shadow-lg transition ${couleurs[type] ?? couleurs.info}`;
        bandeau.textContent = texte;
        document.body.appendChild(bandeau);

        // Disparaît automatiquement après 3 s
        setTimeout(cacherMessage, 3000);
    }

    function cacherMessage() {
        document.getElementById('bandeau-recherche')?.remove();
    }

    // ── Init
    initialiserSuggestions();
});