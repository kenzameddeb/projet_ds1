<?php
if (isset($_SESSION['user'])) {
    header('Location: index.php?page=dashboard');
    exit;
}
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg">
                <div class="card-header py-4 text-center">
                    <h2 class="mb-0 fw-bold">ChallengeHub</h2>
                    <small class="text-white-50">Heureux de vous revoir !</small>
                </div>
                <div class="card-body p-5">

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger text-center fw-bold">
                            <?= htmlspecialchars($_SESSION['error']) ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form action="/projet_ds1/index.php" method="POST">
                        <input type="hidden" name="action" value="login">
                        <input type="hidden" name="page" value="login">

                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold purple">Email</label>
                            <input type="email" id="email" name="email"
                                   class="form-control form-control-lg"
                                   placeholder="example@gmail.com" required>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold purple">Mot de passe</label>
                            <input type="password" id="password" name="password"
                                   class="form-control form-control-lg"
                                   placeholder="Veuillez entrer votre mot de passe" required>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-gold btn-lg shadow-sm">
                                SE CONNECTER
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <p class="mb-1 text-muted small">
                                Pas encore de compte ?
                                <a href="index.php?page=register" class="purple fw-bold text-decoration-none">Inscrivez-vous</a>
                            </p>
                            <p class="mb-0 text-muted small">
                                Mot de passe oublié ?
                                <a href="#" class="purple fw-bold text-decoration-none">Récupérer mot de passe</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>