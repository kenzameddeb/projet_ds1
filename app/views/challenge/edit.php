<?php include _DIR_ . '/../partials/navbar.php'; ?>

<main class="container container-sm">
    <div class="page-header">
        <h1>Modifier le défi</h1>
        <a href="/challenge/show/<?= $challenge['id'] ?>" class="btn btn-ghost">← Retour</a>
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

    <form action="/challenge/edit/<?= $challenge['id'] ?>" method="POST" enctype="multipart/form-data" class="form-card">

        <div class="form-group">
            <label for="title">Titre du défi *</label>
            <input
                type="text"
                id="title"
                name="title"
                class="input"
                value="<?= htmlspecialchars($_POST['title'] ?? $challenge['title']) ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="description">Description *</label>
            <textarea
                id="description"
                name="description"
                class="input textarea"
                rows="5"
                required
            ><?= htmlspecialchars($_POST['description'] ?? $challenge['description']) ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="category">Catégorie *</label>
                <select id="category" name="category" class="input" required>
                    <option value="">-- Choisir --</option>
                    <?php
                    $categories = ['Photographie', 'Art', 'Sport', 'Cuisine', 'Musique', 'Technologie', 'Nature', 'Humour', 'Autre'];
                    $current = $_POST['category'] ?? $challenge['category'];
                    foreach ($categories as $cat):
                    ?>
                        <option value="<?= $cat ?>" <?= ($current === $cat) ? 'selected' : '' ?>>
                            <?= $cat ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="deadline">Date limite *</label>
                <input
                    type="date"
                    id="deadline"
                    name="deadline"
                    class="input"
                    value="<?= htmlspecialchars($_POST['deadline'] ?? $challenge['deadline']) ?>"
                    required
                >
            </div>
        </div>

        <div class="form-group">
            <label>Image actuelle</label>
            <?php if (!empty($challenge['image'])): ?>
                <div class="current-image">
                    <img src="/public/uploads/<?= htmlspecialchars($challenge['image']) ?>" alt="Image actuelle" style="max-height:150px; border-radius:8px;">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remove_image" value="1">
                        Supprimer l'image
                    </label>
                </div>
            <?php else: ?>
                <p class="form-hint">Aucune image pour ce défi.</p>
            <?php endif; ?>

            <label for="image">Changer l'image (optionnel)</label>
            <input type="file" id="image" name="image" class="input-file" accept="image/*">
            <small class="form-hint">JPG, PNG ou GIF max 2 Mo</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg"> Enregistrer les modifications</button>
            <a href="/challenge/show/<?= $challenge['id'] ?>" class="btn btn-ghost">Annuler</a>
        </div>

    </form>
</main>

<?php include _DIR_ . '/../partials/footer.php'; ?>