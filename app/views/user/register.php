<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="row justify-content-center w-100">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg">
                <div class="card-header py-4 text-center">
                    <h2 class="mb-0 fw-bold">ChallengeHub</h2>
                    <small class="text-white-50">Créez votre compte pour commencer</small>
                </div>
                <div class="card-body p-5">

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger text-center fw-bold">
                            <?= htmlspecialchars($_SESSION['error']) ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success text-center fw-bold">
                            <?= htmlspecialchars($_SESSION['success']) ?>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <form action="/projet_ds1/index.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="register">
                        <input type="hidden" name="page" value="register">

                        <!-- Nom -->
                        <div class="mb-4">
                            <label class="form-label fw-bold purple">Nom</label>
                            <input type="text" name="name" class="form-control form-control-lg"
                                   placeholder="Entrez votre nom" required>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="form-label fw-bold purple">Adresse Email</label>
                            <input type="email" name="email" class="form-control form-control-lg"
                                   placeholder="nom@exemple.com" required>
                        </div>

                        <!-- Mot de passe -->
                        <div class="mb-4">
                            <label class="form-label fw-bold purple">Mot de passe</label>
                            <input type="password" name="password" class="form-control form-control-lg"
                                   placeholder="Minimum 8 caractères" required>
                        </div>

                        <!-- Bouton -->
                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-gold btn-lg shadow-sm">
                                S'INSCRIRE MAINTENANT
                            </button>
                        </div>

                        <!-- Lien connexion -->
                        <div class="text-center mt-4">
                            <p class="text-muted small">
                                Déjà inscrit ? <a href="index.php?page=login" class="purple fw-bold text-decoration-none">Connectez-vous</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>