<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/Challenge.php';

$database       = new Database();
$pdo            = $database->connect();
$challengeModel = new Challenge($pdo);

$id        = $_GET['id'] ?? 0;
$challenge = $challengeModel->findById($id);

if (!$challenge) {
    echo '<div class="alert alert-danger text-center mt-4">Défi introuvable.</div>';
    return;
}

$submissions = [];
?>

<div class="container mt-4 pb-5">

    <!-- En-tête du défi -->
    <div class="dash-card mb-4">

        <?php if (!empty($challenge['image'])): ?>
            <img src="/projet_ds1/public/uploads/<?= htmlspecialchars($challenge['image']) ?>"
                 alt="<?= htmlspecialchars($challenge['title']) ?>"
                 class="w-100 rounded mb-3" style="height:250px; object-fit:cover;">
        <?php endif; ?>

        <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
            <h1 class="fw-bold text-white"><?= htmlspecialchars($challenge['title']) ?></h1>
            <span class="badge bg-indigo fs-6"><?= htmlspecialchars($challenge['category']) ?></span>
        </div>

        <p class="text-secondary mb-3"><?= nl2br(htmlspecialchars($challenge['description'])) ?></p>

        <div class="d-flex gap-4 small text-secondary mb-3 flex-wrap">
            <span><svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
            fill="currentColor" viewBox="0 0 24 24" >
            <path d="M12 12c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5m0-8c1.65 0 3 1.35 3 3s-1.35 3-3 3-3-1.35-3-3 1.35-3 3-3M4 22h16c.55 0 1-.45 1-1v-1c0-3.86-3.14-7-7-7h-4c-3.86 0-7 3.14-7 7v1c0 .55.45 1 1 1m6-7h4c2.76 0 5 2.24 5 5H5c0-2.76 2.24-5 5-5"></path>
            </svg> Par <strong class="text-white"><?= htmlspecialchars($challenge['author_name'] ?? 'Anonyme') ?></strong></span>
            <span><svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
                fill="currentColor" viewBox="0 0 24 24" >
                <path d="M19 4h-2V2h-2v2H9V2H7v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2M5 20V8h14V6v14z"></path><path d="M7 11h10v2H7z"></path>
                </svg> Deadline : <strong class="text-white"><?= date('d/m/Y', strtotime($challenge['deadline'])) ?></strong></span>
            <span><svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
            fill="currentColor" viewBox="0 0 24 24" >
            <path d="M21 4h-3V3c0-.55-.45-1-1-1H7c-.55 0-1 .45-1 1v1H3c-.55 0-1 .45-1 1v3c0 4.29 1.79 6.88 4.81 6.99A6 6 0 0 0 11 17.91V20H8v2h8v-2h-3v-2.09a5.98 5.98 0 0 0 4.19-2.92C20.2 14.88 22 12.29 22 8V5c0-.55-.45-1-1-1M4 8V6h2v6c0 .28.03.56.06.83C4.22 12.12 4 9.31 4 8m12 4c0 2.21-1.79 4-4 4s-4-1.79-4-4V4h8zm4-4c0 1.31-.22 4.12-2.06 4.83.04-.27.06-.55.06-.83V6h2z"></path>
            </svg> <strong class="text-white"><?= count($submissions) ?></strong> participation(s)</span>
                    </div>

        <!-- Actions propriétaire -->
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $challenge['user_id']): ?>
            <div class="d-flex gap-2 mt-3">
                <a href="index.php?page=challenge_edit&id=<?= $challenge['id'] ?>"
                   class="btn btn-secondary btn-sm"><svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
                    fill="currentColor" viewBox="0 0 24 24" >
                    <path d="m17.71 7.29-3-3a.996.996 0 0 0-1.41 0l-11.01 11A1 1 0 0 0 2 16v3c0 .55.45 1 1 1h3c.27 0 .52-.11.71-.29l11-11a.996.996 0 0 0 0-1.41ZM5.59 18H4v-1.59l7.5-7.5 1.59 1.59zm8.91-8.91L12.91 7.5 14 6.41 15.59 8zM11 18h11v2H11z"></path>
                    </svg>Modifier</a>
                <form action="index.php" method="POST"
                      onsubmit="return confirm('Supprimer ce défi ?')">
                    <input type="hidden" name="action" value="challenge_delete">
                    <input type="hidden" name="id" value="<?= $challenge['id'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm"><svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
                        fill="currentColor" viewBox="0 0 24 24" >
                        <path d="M17 6V4c0-1.1-.9-2-2-2H9c-1.1 0-2 .9-2 2v2H2v2h2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8h2V6zM9 4h6v2H9zM6 20V8h12v12z"></path><path d="M9 10h2v8H9zm4 0h2v8h-2z"></path>
                        </svg>Supprimer</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bouton participer -->
    <?php if (isset($_SESSION['user']) && strtotime($challenge['deadline']) >= time()): ?>
        <div class="dash-card mb-4 text-center py-4">
            <p class="text-secondary mb-3">Tu veux relever ce défi ?</p>
            <a href="index.php?page=submission_create&id=<?= $challenge['id'] ?>"
               class="btn btn-indigo btn-lg">
               <svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
                    fill="currentColor" viewBox="0 0 24 24" >
                    <path d="M14.59 6.59c-.78.78-.78 2.05 0 2.83s2.05.78 2.83 0 .78-2.05 0-2.83-2.05-.78-2.83 0M5 16c-2 1-2 5-2 5s3 0 5-2z"></path><path d="M21 2h-3.69c-2.4 0-4.66.94-6.36 2.64L8.69 6.9a8.4 8.4 0 0 0-6.24 1.27c-.25.17-.41.44-.44.73s.08.59.29.81l12 12c.2.2.45.29.71.29s.51-.1.71-.29c1.9-1.9 1.6-5.08 1.38-6.38l2.28-2.28c1.7-1.7 2.64-3.96 2.64-6.36V3c0-.55-.45-1-1-1Zm-1 4.69c0 1.87-.73 3.63-2.05 4.95l-2.66 2.66c-.25.25-.35.61-.26.95.19.79.45 2.78-.17 4.2L4.65 9.24c2.11-.89 3.94-.32 4.03-.29.36.12.76.03 1.02-.24l2.66-2.66A6.96 6.96 0 0 1 17.31 4H20z"></path>
                    </svg> Participer à ce défi
                                </a>
        </div>
    <?php elseif (!isset($_SESSION['user'])): ?>
        <div class="dash-card mb-4 text-center py-4">
            <p class="text-secondary mb-2">Connectez-vous pour participer !</p>
            <a href="index.php?page=login" class="btn btn-indigo">Se connecter</a>
        </div>
    <?php elseif (strtotime($challenge['deadline']) < time()): ?>
        <div class="alert alert-warning text-center mb-4">
             Ce défi est terminé.
        </div>
    <?php endif; ?>

    <!-- Participations -->
    <div class="dash-card">
        <h5 class="fw-bold text-white mb-3"><svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
            fill="currentColor" viewBox="0 0 24 24" >
            <path d="M13.08 8.63 12 6.44l-1.08 2.19-2.42.35 1.75 1.71-.41 2.41L12 11.96l2.16 1.14-.41-2.41 1.75-1.71z"></path><path d="M12 2c-4.41 0-8 3.59-8 8 0 2.52 1.17 4.77 3 6.24v4.77c0 .35.18.67.47.85s.66.2.97.04l3.55-1.78 3.55 1.78a.997.997 0 0 0 1.45-.89v-4.76c1.83-1.47 3-3.72 3-6.24 0-4.41-3.59-8-8-8Zm0 14c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6"></path>
            </svg> Participations</h5>

        <?php if (empty($submissions)): ?>
            <div class="text-center py-4">
                <p class="text-secondary mb-3">Aucune participation pour l'instant.</p>
                <?php if (isset($_SESSION['user']) && strtotime($challenge['deadline']) >= time()): ?>
                    <a href="index.php?page=submission_create&id=<?= $challenge['id'] ?>"
                       class="btn btn-indigo btn-sm">
                        Soyez le premier !
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column gap-3">
                <?php foreach ($submissions as $submission): ?>
                    <div class="defi-card" id="submission-<?= $submission['id'] ?>">

                        <?php if (!empty($submission['image'])): ?>
                            <img src="/projet_ds1/public/uploads/<?= htmlspecialchars($submission['image']) ?>"
                                 alt="Participation"
                                 class="w-100 rounded mb-3" style="height:150px; object-fit:cover;">
                        <?php endif; ?>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong class="text-white">
                                <?= htmlspecialchars($submission['author_name'] ?? 'Anonyme') ?>
                            </strong>
                            <span class="text-secondary small">
                                <?= date('d/m/Y à H:i', strtotime($submission['created_at'])) ?>
                            </span>
                        </div>

                        <p class="text-secondary small mb-2">
                            <?= nl2br(htmlspecialchars($submission['description'])) ?>
                        </p>

                        <!-- Votes -->
                        <div class="d-flex align-items-center gap-2 mt-2">
                            <span class="text-secondary small">
                                 <strong class="text-white"><?= (int)($submission['vote_count'] ?? 0) ?></strong> votes
                            </span>
                        </div>

                        <!-- Actions propriétaire submission -->
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $submission['user_id']): ?>
                            <div class="d-flex gap-2 mt-3">
                                <a href="index.php?page=submission_edit&id=<?= $submission['id'] ?>"
                                   class="btn btn-secondary btn-sm"><svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
                                        fill="currentColor" viewBox="0 0 24 24" >
                                        <path d="m17.71 7.29-3-3a.996.996 0 0 0-1.41 0l-11.01 11A1 1 0 0 0 2 16v3c0 .55.45 1 1 1h3c.27 0 .52-.11.71-.29l11-11a.996.996 0 0 0 0-1.41ZM5.59 18H4v-1.59l7.5-7.5 1.59 1.59zm8.91-8.91L12.91 7.5 14 6.41 15.59 8zM11 18h11v2H11z"></path>
                                        </svg>Modifier</a>
                                <form action="index.php" method="POST"
                                      onsubmit="return confirm('Supprimer cette participation ?')">
                                    <input type="hidden" name="action" value="submission_delete">
                                    <input type="hidden" name="id" value="<?= $submission['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm"><svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
                                    fill="currentColor" viewBox="0 0 24 24" >
                                    <path d="M17 6V4c0-1.1-.9-2-2-2H9c-1.1 0-2 .9-2 2v2H2v2h2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8h2V6zM9 4h6v2H9zM6 20V8h12v12z"></path><path d="M9 10h2v8H9zm4 0h2v8h-2z"></path>
                                    </svg>Supprimer</button>
                                </form>
                            </div>
                        <?php endif; ?>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>