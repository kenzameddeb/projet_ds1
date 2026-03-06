document.addEventListener('DOMContentLoaded', function () {

    // Récupère le champ de recherche
    const searchInput = document.querySelector('input[name="search"]');
    const searchForm  = document.querySelector('form');

    if (!searchInput || !searchForm) return;

    // Si l'utilisateur appuie sur Entrée
    searchInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            searchForm.submit();
        }
    });

    // Sélectionne tout le texte au focus
    searchInput.addEventListener('focus', function () {
        this.select();
    });

    // ── Autocomplétion ──
    const suggestionsList = document.getElementById("suggestions-list");

    if (suggestionsList) {

        searchInput.addEventListener("input", function () {
            const terme = this.value.trim();

            if (terme.length < 1) {
                suggestionsList.style.display = "none";
                suggestionsList.innerHTML = "";
                return;
            }

            fetch("index.php?ajax=suggestions&q=" + encodeURIComponent(terme))
                .then(res => res.json())
                .then(data => {
                    suggestionsList.innerHTML = "";

                    if (data.length === 0) {
                        suggestionsList.style.display = "none";
                        return;
                    }

                    data.forEach(item => {
                        const li = document.createElement("li");
                        li.textContent = item;
                        li.addEventListener("click", () => {
                            searchInput.value = item;
                            suggestionsList.style.display = "none";
                            searchInput.closest("form").submit();
                        });
                        suggestionsList.appendChild(li);
                    });

                    suggestionsList.style.display = "block";
                })
                .catch(err => console.error("Erreur fetch:", err));
        });

        // Fermer si clic ailleurs
        document.addEventListener("click", function (e) {
            if (!searchInput.contains(e.target) && !suggestionsList.contains(e.target)) {
                suggestionsList.style.display = "none";
            }
        });
    }

});