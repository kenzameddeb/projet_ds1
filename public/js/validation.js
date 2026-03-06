document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("chform");

    // Afficher une erreur
    function showError(fieldId, message) {
        const field = document.getElementById(fieldId);
        field.classList.add("is-invalid");
        field.classList.remove("is-valid");

        let err = document.getElementById("err-" + fieldId);
        if (!err) {
            err = document.createElement("div");
            err.id = "err-" + fieldId;
            err.className = "error-msg";
            field.insertAdjacentElement("afterend", err);
        }
        err.textContent = message;
    }

    // Supprimer une erreur
    function clearError(fieldId) {
        const field = document.getElementById(fieldId);
        field.classList.remove("is-invalid");
        field.classList.add("is-valid");

        const err = document.getElementById("err-" + fieldId);
        if (err) err.textContent = "";
    }

    // Validation principale
    function validate() {
        let valid = true;

        const titre      = document.getElementById("titre").value.trim();
        const description= document.getElementById("description").value.trim();
        const date_debut = document.getElementById("date_debut").value;
        const date_fin   = document.getElementById("date_fin").value;
        const difficulte = document.getElementById("difficulte").value;
        const points     = document.getElementById("points").value;
        const image      = document.getElementById("image").files[0];
        const today      = new Date().toISOString().split("T")[0];

        // Titre
        if (titre.length < 5 || titre.length > 100) {
            showError("titre", "Le titre doit contenir entre 5 et 100 caractères.");
            valid = false;
        } else {
            clearError("titre");
        }

        // Description
        if (description.length < 20) {
            showError("description", "La description doit contenir au moins 20 caractères.");
            valid = false;
        } else {
            clearError("description");
        }

        // Date début
        if (!date_debut) {
            showError("date_debut", "La date de début est obligatoire.");
            valid = false;
        } else if (date_debut < today) {
            showError("date_debut", "La date de début ne peut pas être dans le passé.");
            valid = false;
        } else {
            clearError("date_debut");
        }

        // Date fin
        if (!date_fin) {
            showError("date_fin", "La date de fin est obligatoire.");
            valid = false;
        } else if (date_fin <= date_debut) {
            showError("date_fin", "La date de fin doit être après la date de début.");
            valid = false;
        } else {
            clearError("date_fin");
        }

        // Difficulté
        if (!difficulte || difficulte === "0") {
            showError("difficulte", "Veuillez choisir un niveau de difficulté.");
            valid = false;
        } else {
            clearError("difficulte");
        }

        // Points
        if (!points || isNaN(points) || points < 1 || points > 10000) {
            showError("points", "Les points doivent être un nombre entre 1 et 10 000.");
            valid = false;
        } else {
            clearError("points");
        }

        // Image (optionnelle, vérifiée uniquement si fournie)
        if (image) {
            const allowed = ["image/jpeg", "image/png", "image/webp"];
            if (!allowed.includes(image.type)) {
                showError("image", "Format accepté : JPG, PNG ou WEBP.");
                valid = false;
            } else if (image.size > 2 * 1024 * 1024) {
                showError("image", "L'image ne doit pas dépasser 2 Mo.");
                valid = false;
            } else {
                clearError("image");
            }
        }

        return valid;
    }

    // Soumission
    form.addEventListener("submit", function (e) {
        e.preventDefault();
        if (validate()) form.submit();
    });

    // Temps réel : vérification au blur (quand on quitte un champ)
    ["titre", "description", "date_debut", "date_fin", "difficulte", "points"].forEach((id) => {
        const field = document.getElementById(id);
        if (field) field.addEventListener("blur", validate);
    });
});