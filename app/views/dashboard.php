<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/models/Challenge.php';

$database = new Database();
$pdo = $database->connect();
$challengeModel = new Challenge($pdo);

// Recherche
$recherche = isset($_POST['search']) ? trim($_POST['search']) : '';
$defis     = $recherche !== '' ? $challengeModel->search($recherche) : $challengeModel->findAll();

$total   = count($defis);
$recents = array_slice($defis, 0, 3);

// Catégories
$categories = [];
foreach ($defis as $d) {
    $cat = $d['category'];
    $categories[$cat] = ($categories[$cat] ?? 0) + 1;
}

// Expirent dans 7 jours
$bientot = array_filter($defis, function ($d) {
    $diff = (strtotime($d['deadline']) - time()) / 86400;
    return $diff >= 0 && $diff <= 7;
});
?>

<!-- ══ HERO ══ -->
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 bg-dark">
    <div>
        <h1 class="fw-bold text-white mb-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.24 4.76c-2.3-2.29-5.87-2.35-8.24-.19-2.37-2.16-5.93-2.09-8.24.2-2.36 2.37-2.36 6.07 0 8.43l7.53 7.52c.2.19.45.29.71.29s.51-.1.71-.29l7.53-7.52c2.36-2.36 2.36-6.06 0-8.43ZM12 18.59l-6.82-6.81a3.92 3.92 0 0 1 0-5.6C5.97 5.39 6.98 5 7.99 5s2.02.39 2.8 1.18l.5.5-2.38 2.39c-.51.52-.51 1.36 0 1.88.49.49 1.13.73 1.77.73s1.28-.24 1.77-.73l1.64-1.64 3.59 3.59-1.04 1.04-2.3-2.3-.71.71 2.3 2.3-.79.79-2.3-2.3-.71.71 2.3 2.3-.79.79-2.29-2.29-.71.71 2.29 2.29-.94.94Zm6.82-6.81-.42.42-3.59-3.59 1.24-1.24-.71-.71-3.59 3.59c-.58.58-1.54.58-2.12 0a.33.33 0 0 1 0-.47l3.42-3.44.16-.16c1.57-1.57 4.04-1.57 5.62 0 1.57 1.58 1.57 4.04 0 5.6Z"></path>
            </svg> Bonjour,
            <?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['name'] ?? 'Visiteur') : 'Visiteur' ?> !
        </h1>
        <p class="text-secondary mb-0">Bienvenue dans ChallengeHub.</p>
    </div>
    <?php if (isset($_SESSION['user'])): ?>
        <a href="index.php?page=challenge_create" class="btn btn-indigo fw-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                fill="currentColor" viewBox="0 0 24 24">
                <path d="M3 13h8v8h2v-8h8v-2h-8V3h-2v8H3z"></path>
            </svg> Nouveau défi
        </a>
    <?php endif; ?>
</div>

<!-- ══ STATS ══ -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-indigo">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    fill="currentColor" viewBox="0 0 24 24">
                    <path d="M21.56 5.17a1 1 0 0 0-.93-.1c-4.18 1.69-7.7-2.53-7.85-2.7-.38-.47-1.17-.47-1.55 0-.04.04-3.64 4.4-7.85 2.7a.99.99 0 0 0-.93.1c-.28.19-.44.49-.44.83 0 .52.05 12.76 9.69 15.95.1.03.21.05.31.05s.21-.02.31-.05c9.58-3.17 9.69-15.42 9.69-15.94 0-.34-.17-.65-.44-.84ZM12 19.94c-6.25-2.29-7.62-9.55-7.92-12.58 3.61.68 6.57-1.6 7.92-2.9 1.35 1.3 4.31 3.59 7.93 2.9-.28 3.04-1.6 10.27-7.93 12.59Z"></path>
                </svg>
            </div>
            <div class="stat-number"><?= $total ?></div>
            <div class="stat-label">Défis au total</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-emerald">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 4h-8.59L10 2.59C9.62 2.21 9.12 2 8.59 2H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2m0 14H4V6h16z"></path>
                    <path d="M8 11h8v2H8z"></path>
                </svg>
            </div>
            <div class="stat-number"><?= count($categories) ?></div>
            <div class="stat-label">Catégories</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-amber">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    fill="currentColor" viewBox="0 0 24 24">
                    <path d="M5 2H4v2h1v1c0 2.46 1.32 4.77 3.43 6.02.35.21.57.55.57.9v.16c0 .35-.21.69-.57.9A7.01 7.01 0 0 0 5 19v1H4v2h16v-2h-1v-1c0-2.46-1.32-4.77-3.43-6.02-.36-.21-.57-.55-.57-.9v-.16c0-.35.21-.69.57-.9A7.01 7.01 0 0 0 19 5V4h1V2zm12 3c0 1.76-.94 3.41-2.45 4.3-.97.57-1.55 1.55-1.55 2.62v.16c0 1.07.58 2.05 1.55 2.62 1.51.89 2.45 2.54 2.45 4.3v1H7v-1c0-1.76.94-3.41 2.45-4.3.97-.57 1.55-1.55 1.55-2.62v-.16c0-1.07-.58-2.05-1.55-2.62A5.01 5.01 0 0 1 7 5V4h10z"></path>
                </svg>
            </div>
            <div class="stat-number"><?= count($bientot) ?></div>
            <div class="stat-label">Expirent dans 7 jours</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-rose">
            <div class="stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    fill="currentColor" viewBox="0 0 24 24">
                    <path d="M21 4h-3V3c0-.55-.45-1-1-1H7c-.55 0-1 .45-1 1v1H3c-.55 0-1 .45-1 1v3c0 4.29 1.79 6.88 4.81 6.99A6 6 0 0 0 11 17.91V20H8v2h8v-2h-3v-2.09a5.98 5.98 0 0 0 4.19-2.92C20.2 14.88 22 12.29 22 8V5c0-.55-.45-1-1-1M4 8V6h2v6c0 .28.03.56.06.83C4.22 12.12 4 9.31 4 8m12 4c0 2.21-1.79 4-4 4s-4-1.79-4-4V4h8zm4-4c0 1.31-.22 4.12-2.06 4.83.04-.27.06-.55.06-.83V6h2z"></path>
                </svg>
            </div>
            <div class="stat-number">0</div>
            <div class="stat-label">Soumissions</div>
        </div>
    </div>
</div>

<!-- ══ CONTENU PRINCIPAL ══ -->
<div class="row g-4">

    <!-- Défis récents -->
    <div class="col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold text-white mb-0"><svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
            fill="currentColor" viewBox="0 0 24 24" >
            <path d="M4.88 8.42 3.1 7.5a10 10 0 0 0-.98 2.95l1.97.32c.13-.81.39-1.6.78-2.35Zm-2.76 5.14c.17 1.02.5 2.01.98 2.94l1.78-.92c-.38-.74-.65-1.53-.78-2.35l-1.97.32ZM4.92 19c.73.74 1.57 1.36 2.48 1.85l.94-1.77c-.73-.39-1.4-.89-1.99-1.49L4.93 19ZM8.33 4.92l-.94-1.77C6.48 3.64 5.64 4.26 4.91 5l1.42 1.41c.59-.6 1.26-1.1 1.99-1.49ZM12 2c-.56 0-1.12.05-1.67.14l.34 1.97c.44-.08.88-.11 1.32-.11 4.34 0 8 3.66 8 8s-3.66 8-8 8c-.44 0-.89-.04-1.32-.11l-.34 1.97c.55.1 1.11.14 1.67.14 5.42 0 10-4.58 10-10S17.42 2 12 2"></path><path d="M11 7v6h6v-2h-4V7z"></path>
            </svg> Défis récents</h5>
            <a href="index.php?page=challenges" class="text-indigo text-decoration-none small">Voir tout →</a>
        </div>

        <?php if (empty($recents)): ?>
            <div class="dash-card text-center py-5">
                <p class="text-secondary mb-3">Aucun défi pour l'instant.</p>
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="index.php?page=challenge_create" class="btn btn-indigo btn-sm">
                        + Créer le premier défi
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column gap-3">
                <?php foreach ($recents as $defi):
                    $jours   = ceil((strtotime($defi['deadline']) - time()) / 86400);
                    $urgence = $jours <= 3 ? 'text-danger' : ($jours <= 7 ? 'text-warning' : 'text-secondary');
                ?>
                    <div class="defi-card">
                        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                            <h6 class="fw-bold text-white mb-0"><?= htmlspecialchars($defi['title']) ?></h6>
                            <span class="badge bg-indigo"><?= htmlspecialchars($defi['category']) ?></span>
                        </div>
                        <p class="text-secondary small mb-3"><?= htmlspecialchars($defi['description']) ?></p>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="small <?= $urgence ?>">
                            <svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
                                fill="currentColor" viewBox="0 0 24 24" >
                                <path d="M19 4h-2V2h-2v2H9V2H7v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2M5 20V8h14V6v14z"></path><path d="M7 11h10v2H7z"></path>
                                </svg> <?= date('d/m/Y', strtotime($defi['deadline'])) ?>
                                (<?= $jours >= 0 ? "$jours j restants" : "expiré" ?>)
                            </span>
                            <a href="index.php?page=challenge_show&id=<?= $defi['id'] ?>"
                               class="text-indigo text-decoration-none small">Voir <svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
                                fill="currentColor" viewBox="0 0 24 24" >
                                <path d="m9 17 6-5-6-5v4H2v2h7z"></path><path d="M13 3v2c3.86 0 7 3.14 7 7s-3.14 7-7 7v2c4.96 0 9-4.04 9-9s-4.04-9-9-9"></path>
                                </svg></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4 d-flex flex-column gap-3">

        <!-- Catégories -->
        <div class="dash-card">
            <h6 class="fw-bold text-white mb-3"><svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
            fill="currentColor" viewBox="0 0 24 24" >
            <path d="M3 3h4v4H3zm7 0h4v4h-4z"></path><path d="M10 3h4v4h-4zm7 0h4v4h-4zM3 17h4v4H3zm7 0h4v4h-4z"></path><path d="M10 17h4v4h-4zm7 0h4v4h-4zM3 10h4v4H3zm7 0h4v4h-4z"></path><path d="M10 10h4v4h-4zm7 0h4v4h-4z"></path>
            </svg> Catégories</h6>
            <?php if (empty($categories)): ?>
                <p class="text-secondary small mb-0">Aucune donnée.</p>
            <?php else: ?>
                <?php foreach ($categories as $cat => $count):
                    $pct = $total > 0 ? round(($count / $total) * 100) : 0;
                ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-white"><?= htmlspecialchars($cat) ?></span>
                            <span class="text-secondary"><?= $count ?> (<?= $pct ?>%)</span>
                        </div>
                        <div class="progress" style="height:6px; background:#2a2a3a; border-radius:9999px;">
                            <div class="progress-bar progress-indigo" style="width:<?= $pct ?>%;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Expirent bientôt -->
        <div class="dash-card">
            <h6 class="fw-bold text-white mb-3"><svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
            fill="currentColor" viewBox="0 0 24 24" >
            <path d="M21 13c0-4.88-4.12-9-9-9s-9 4.12-9 9 4.12 9 9 9 9-4.12 9-9m-9 7c-3.79 0-7-3.21-7-7s3.21-7 7-7 7 3.21 7 7-3.21 7-7 7"></path><path d="m11 13.59-2.29-2.3-1.42 1.42 3.71 3.7 5.71-5.7-1.42-1.42zm7.7-11.3-.71.71-.71.71 1.51 1.5 1.5 1.5L21 6l.71-.71-1.51-1.5zM3.71 6.71l1.49-1.5 1.5-1.5L5.99 3l-.71-.71-1.49 1.5-1.5 1.5L3 6z"></path>
            </svg> Expirent bientôt</h6>
            <?php if (empty($bientot)): ?>
                <p class="text-secondary small mb-0">Aucun défi urgent.</p>
            <?php else: ?>
                <?php foreach ($bientot as $d):
                    $j = ceil((strtotime($d['deadline']) - time()) / 86400);
                ?>
                    <div class="d-flex justify-content-between align-items-center small mb-2">
                        <span class="text-white text-truncate me-2"><?= htmlspecialchars($d['title']) ?></span>
                        <span class="text-warning fw-bold"><?= $j ?>j</span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- CTA -->
        <a href="index.php?page=challenges" class="btn btn-indigo-gradient py-3 fw-bold text-center">
            Voir tous les défis <svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
            fill="currentColor" viewBox="0 0 24 24" >
            <path d="m9 17 6-5-6-5v4H2v2h7z"></path><path d="M13 3v2c3.86 0 7 3.14 7 7s-3.14 7-7 7v2c4.96 0 9-4.04 9-9s-4.04-9-9-9"></path>
            </svg>
        </a>

    </div>
</div>