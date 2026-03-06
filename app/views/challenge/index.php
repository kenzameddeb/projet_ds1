<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/Challenge.php';

$database = new Database();
$pdo = $database->connect();
$challengeModel = new Challenge($pdo);

$recherche = isset($_GET['search']) ? trim($_GET['search']) : '';
$categorie = isset($_GET['category']) ? trim($_GET['category']) : '';
$tri       = isset($_GET['sort']) ? trim($_GET['sort']) : 'date';

// Récupération des défis
if ($recherche !== '') {
    $challenges = $challengeModel->search($recherche);
} else {
    $challenges = $challengeModel->findAll();
}

// Filtre par catégorie
if ($categorie !== '') {
    $challenges = array_filter($challenges, function($c) use ($categorie) {
        return $c['category'] === $categorie;
    });
    $challenges = array_values($challenges);
}

// Tri
usort($challenges, function($a, $b) use ($tri) {
    if ($tri === 'deadline') {
        return strtotime($a['deadline']) - strtotime($b['deadline']);
    }
    if ($tri === 'popular') {
        return ($b['submission_count'] ?? 0) - ($a['submission_count'] ?? 0);
    }
    return strtotime($b['deadline']) - strtotime($a['deadline']);
});

$categories = ['Photographie', 'Art', 'Sport', 'Cuisine', 'Musique', 'Technologie', 'Nature', 'Humour', 'Autre'];
?>

<div class="container mt-4 pb-5">

    <!-- En-tête -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="fw-bold text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                fill="currentColor" viewBox="0 0 24 24">
                <path d="M21 4h-3V3c0-.55-.45-1-1-1H7c-.55 0-1 .45-1 1v1H3c-.55 0-1 .45-1 1v3c0 4.29 1.79 6.88 4.81 6.99A6 6 0 0 0 11 17.91V20H8v2h8v-2h-3v-2.09a5.98 5.98 0 0 0 4.19-2.92C20.2 14.88 22 12.29 22 8V5c0-.55-.45-1-1-1M4 8V6h2v6c0 .28.03.56.06.83C4.22 12.12 4 9.31 4 8m12 4c0 2.21-1.79 4-4 4s-4-1.79-4-4V4h8zm4-4c0 1.31-.22 4.12-2.06 4.83.04-.27.06-.55.06-.83V6h2z"></path>
            </svg> Les Défis
        </h1>
        <?php if (isset($_SESSION['user'])): ?>
            <a href="index.php?page=challenge_create" class="btn btn-indigo fw-semibold">
                + Créer un défi
            </a>
        <?php else: ?>
            <a href="index.php?page=login" class="btn btn-outline-secondary fw-semibold">
                Connectez-vous pour créer un défi
            </a>
        <?php endif; ?>
    </div>

    <!-- Recherche & Filtres -->
    <form method="GET" action="index.php" class="d-flex gap-2 mb-4 flex-wrap align-items-center">
        <input type="hidden" name="page" value="challenges">

        <input type="text" name="search"
               placeholder="Rechercher un défi..."
               value="<?= htmlspecialchars($recherche) ?>"
               class="form-control form-control-sm search-input" style="width:200px;">

        <select name="category" class="form-select form-select-sm" style="width:180px;">
            <option value="">Toutes les catégories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>"
                    <?= ($categorie === $cat) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="sort" class="form-select form-select-sm" style="width:160px;">
            <option value="date"     <?= ($tri === 'date')     ? 'selected' : '' ?>>Plus récents</option>
            <option value="popular"  <?= ($tri === 'popular')  ? 'selected' : '' ?>>Plus populaires</option>
            <option value="deadline" <?= ($tri === 'deadline') ? 'selected' : '' ?>>Deadline proche</option>
        </select>

        <button type="submit" class="btn btn-secondary btn-sm">Filtrer</button>
        <a href="index.php?page=challenges" class="btn btn-outline-secondary btn-sm">Réinitialiser</a>
    </form>

    <!-- Résultats -->
    <?php if (empty($challenges)): ?>
        <div class="dash-card text-center py-5">
            <p class="text-secondary mb-3">Aucun défi trouvé.</p>
            <?php if (isset($_SESSION['user'])): ?>
                <a href="index.php?page=challenge_create" class="btn btn-indigo btn-sm">
                    + Soyez le premier !
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($challenges as $challenge): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="defi-card h-100">
                        <?php if (!empty($challenge['image'])): ?>
                            <img src="/projet_ds1/public/uploads/<?= htmlspecialchars($challenge['image']) ?>"
                                 alt="<?= htmlspecialchars($challenge['title']) ?>"
                                 class="w-100 rounded mb-3" style="height:150px; object-fit:cover;">
                        <?php else: ?>
                            <div class="w-100 rounded mb-3" style="height:150px; background:#2a2a3a;"></div>
                        <?php endif; ?>

                        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                            <h6 class="fw-bold text-white mb-0">
                                <?= htmlspecialchars($challenge['title']) ?>
                            </h6>
                            <span class="badge bg-indigo">
                                <?= htmlspecialchars($challenge['category']) ?>
                            </span>
                        </div>

                        <p class="text-secondary small mb-3">
                            <?= htmlspecialchars(mb_strimwidth($challenge['description'], 0, 120, '...')) ?>
                        </p>

                        <div class="d-flex align-items-center justify-content-between mt-auto">
                            <div class="small text-secondary">
                                <div><svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
                                    fill="currentColor" viewBox="0 0 24 24" >
                                    <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5m0-8c1.65 0 3 1.35 3 3s-1.35 3-3 3-3-1.35-3-3 1.35-3 3-3M4 22h16c.55 0 1-.45 1-1v-1c0-3.86-3.14-7-7-7h-4c-3.86 0-7 3.14-7 7v1c0 .55.45 1 1 1m6-7h4c2.76 0 5 2.24 5 5H5c0-2.76 2.24-5 5-5"></path>
                                    </svg> <?= htmlspecialchars($challenge['author_name'] ?? 'Anonyme') ?></div>
                                <div><svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
                                    fill="currentColor" viewBox="0 0 24 24" >
                                    <path d="M19 4h-2V2h-2v2H9V2H7v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2M5 20V8h14V6v14z"></path><path d="M7 11h10v2H7z"></path>
                                    </svg> <?= date('d/m/Y', strtotime($challenge['deadline'])) ?></div>
                            </div>
                            <a href="index.php?page=challenge_show&id=<?= $challenge['id'] ?>"
                               class="btn btn-indigo btn-sm">Voir →</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>