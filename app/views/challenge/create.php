<?php
$categories = ['Photographie', 'Art', 'Sport', 'Cuisine', 'Musique', 'Technologie', 'Nature', 'Humour', 'Autre'];
?>

<div class="container mt-4 pb-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="fw-bold text-white">Créer un défi</h1>
        <a href="index.php?page=challenges" class="btn btn-outline-secondary">← Retour</a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="dash-card mb-5">
        <form action="index.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="challenge_create">
            <input type="hidden" name="page" value="challenge_create">

            <!-- Titre -->
            <div class="mb-4">
                <label class="form-label fw-bold text-white">Titre du défi *</label>
                <input type="text" name="title" class="form-control form-control-lg"
                       placeholder="Ex: Meilleure photo de coucher de soleil"
                       value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label class="form-label fw-bold text-white">Description *</label>
                <textarea name="description" class="form-control" rows="5"
                          placeholder="Décrivez les règles, objectifs et critères du défi..."
                          required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>

            <div class="row g-3 mb-4">
                <!-- Catégorie -->
                <div class="col-md-6">
                    <label class="form-label fw-bold text-white">Catégorie *</label>
                    <select name="category" class="form-select" required>
                        <option value="">-- Choisir --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat ?>"
                                <?= (($_POST['category'] ?? '') === $cat) ? 'selected' : '' ?>>
                                <?= $cat ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Date limite -->
                <div class="col-md-6">
                    <label class="form-label fw-bold text-white">Date limite *</label>
                    <input type="date" name="deadline" class="form-control"
                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                           value="<?= htmlspecialchars($_POST['deadline'] ?? '') ?>" required>
                </div>
            </div>

            <!-- Image -->
            <div class="mb-5">
                <label class="form-label fw-bold text-white">Image du défi (optionnel)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <small class="text-secondary">JPG, PNG ou GIF max 2 Mo</small>
            </div>

            <!-- Boutons -->
            <div class="d-flex gap-3 mt-4">
                <button type="submit" class="btn btn-indigo btn-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.59 6.59c-.78.78-.78 2.05 0 2.83s2.05.78 2.83 0 .78-2.05 0-2.83-2.05-.78-2.83 0M5 16c-2 1-2 5-2 5s3 0 5-2z"></path>
                        <path d="M21 2h-3.69c-2.4 0-4.66.94-6.36 2.64L8.69 6.9a8.4 8.4 0 0 0-6.24 1.27c-.25.17-.41.44-.44.73s.08.59.29.81l12 12c.2.2.45.29.71.29s.51-.1.71-.29c1.9-1.9 1.6-5.08 1.38-6.38l2.28-2.28c1.7-1.7 2.64-3.96 2.64-6.36V3c0-.55-.45-1-1-1Zm-1 4.69c0 1.87-.73 3.63-2.05 4.95l-2.66 2.66c-.25.25-.35.61-.26.95.19.79.45 2.78-.17 4.2L4.65 9.24c2.11-.89 3.94-.32 4.03-.29.36.12.76.03 1.02-.24l2.66-2.66A6.96 6.96 0 0 1 17.31 4H20z"></path>
                    </svg> Publier le défi
                </button>
                <a href="index.php?page=challenges" class="btn btn-outline-secondary btn-lg">Annuler</a>
            </div>

        </form>
    </div>
</div>