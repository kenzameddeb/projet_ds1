
<?php include __DIR__ . '/../partials/navbar.php'; ?>

<main class="container container-sm">
    <div class="page-header">
        <h1> Participer au défi</h1>
        <a href="/challenge/show/<?= $challenge['id'] ?>" class="btn btn-ghost"><svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
            fill="currentColor" viewBox="0 0 24 24" >
            <path d="m9 17 6-5-6-5v4H2v2h7z"></path><path d="M13 3v2c3.86 0 7 3.14 7 7s-3.14 7-7 7v2c4.96 0 9-4.04 9-9s-4.04-9-9-9"></path>
            </svg>Retour au défi</a>
    </div>

    <!-- Rappel du défi -->
    <div class="challenge-recap">
        <h2><?= htmlspecialchars($challenge['title']) ?></h2>
        <p><?= htmlspecialchars(mb_strimwidth($challenge['description'], 0, 200, '...')) ?></p>
        <span class="badge"><?= htmlspecialchars($challenge['category']) ?></span>
        <span class="text-muted">Deadline : <?= date('d/m/Y', strtotime($challenge['deadline'])) ?></span>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="/submission/create/<?= $challenge['id'] ?>" method="POST" enctype="multipart/form-data" class="form-card">

        <div class="form-group">
            <label for="description">Votre participation *</label>
            <textarea
                id="description"
                name="description"
                class="input textarea"
                rows="6"
                placeholder="Décrivez votre participation, votre approche, ce que vous avez fait..."
                required
            ><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="image">Photo / Illustration (optionnel)</label>
            <input type="file" id="image" name="image" class="input-file" accept="image/*">
            <small class="form-hint">JPG, PNG ou GIF max 2 Mo</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">Soumettre ma participation</button>
            <a href="/challenge/show/<?= $challenge['id'] ?>" class="btn btn-ghost">Annuler</a>
        </div>

    </form>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
