<?php
if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit();
}
$user = $_SESSION['user'];
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg">
                <div class="card-header py-4 text-center">
                    <h2 class="mb-0 fw-bold">Profil de <?= htmlspecialchars($user['name']) ?></h2>
                </div>

                <div class="text-center mt-4">
                    <img id="preview" src="https://cdn-icons-png.flaticon.com/512/149/149071.png"
                         class="rounded-circle border shadow-sm" style="width:80px; height:80px;" alt="Profil">
                    <div class="mt-2">
                        <label for="file-input" class="btn btn-sm btn-light border shadow-sm rounded-pill py-1 px-3">
                            <span class="me-1"></span> <small class="fw-bold">Ajouter une photo</small>
                        </label>
                        <input id="file-input" type="file" name="avatar" accept="image/*"
                               onchange="previewImage(event)" style="display:none;">
                    </div>
                </div>

                <div class="card-body p-5">
                    <h4 class="purple fw-bold border-bottom pb-2">Informations personnelles</h4>

                    <div class="mb-3">
                        <label class="small text-muted fw-bold text-uppercase">Nom</label>
                        <h6 class="purple fw-bold"><?= htmlspecialchars($user['name']) ?></h6>
                    </div>

                    <div class="mb-3">
                        <label class="small text-muted fw-bold text-uppercase">Adresse Email</label>
                        <h6 class="purple fw-bold"><?= htmlspecialchars($user['email']) ?></h6>
                    </div>

                    <div class="mb-4">
                        <label class="small text-muted fw-bold text-uppercase">Mot de passe</label>
                        <div class="input-group">
                            <input type="password" id="pass" 
                                   class="form-control border-0 bg-white purple fw-bold p-0 fs-5"
                                   value="••••••••" readonly>
                            <button class="btn btn-sm btn-outline-secondary border-0" 
                                    type="button" onclick="togglePass()">
                                <span id="eye-icon"></span>
                            </button>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-grid gap-3">
                        <a href="index.php?page=edit" class="btn btn-gold btn-lg shadow-sm">
                            MODIFIER MON PROFIL
                        </a>
                        <a href="/projet_ds1/app/controllers/UserController.php?action=logout" 
                           class="btn btn-outline-danger btn-sm">
                            Supprimer mon compte
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="index.php" class="purple text-decoration-none fw-bold small">Retour à l'accueil</a>
            </div>
        </div>
    </div>
</div>

<script>
function togglePass() {
    const passInput = document.getElementById('pass');
    const eyeIcon   = document.getElementById('eye-icon');
    if (passInput.type === "password") {
        passInput.type = "text";
        eyeIcon.innerText = <svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
            fill="currentColor" viewBox="0 0 24 24" >
            <path d="m21.95 12.32-1.9-.64C19.98 11.9 18.16 17 12 17s-7.98-5.1-8.05-5.32l-1.9.63s.28.8.93 1.81L.7 15.85l1.21 1.6 2.3-1.74c.58.62 1.29 1.24 2.16 1.77l-1.51 2.48L6.57 21l1.62-2.65c.83.3 1.78.5 2.82.59v3.05h2v-3.05c1.05-.08 1.99-.29 2.82-.59L17.45 21l1.71-1.04-1.51-2.48c.87-.53 1.58-1.15 2.16-1.77l2.3 1.74 1.21-1.6-2.28-1.73c.65-1.01.92-1.79.93-1.81Z"></path>
            </svg>;
    } else {
        passInput.type = "password";
        eyeIcon.innerText = <svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
        fill="currentColor" viewBox="0 0 24 24" >
        <path d="M12 9a3 3 0 1 0 0 6 3 3 0 1 0 0-6"></path><path d="M12 19c7.63 0 9.93-6.62 9.95-6.68.07-.21.07-.43 0-.63-.02-.07-2.32-6.68-9.95-6.68s-9.93 6.61-9.95 6.67c-.07.21-.07.43 0 .63.02.07 2.32 6.68 9.95 6.68Zm0-12c5.35 0 7.42 3.85 7.93 5-.5 1.16-2.58 5-7.93 5s-7.42-3.84-7.93-5c.5-1.16 2.58-5 7.93-5"></path>
        </svg>;
    }
}

function previewImage(event) {
    const reader = new FileReader();
    const imageField = document.getElementById("preview");
    reader.onload = function () {
        if (reader.readyState === 2) {
            imageField.src = reader.result;
        }
    }
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}
</script>