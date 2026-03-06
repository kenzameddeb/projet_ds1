
<?php include __DIR__ . '/../partials/navbar.php'; ?>

<main class="container">
    <div class="page-header">
        <h1> Classement des participations</h1>
    </div>

    <!-- Filtres -->
    <form method="GET" action="/ranking" class="filters-bar">
        <select name="challenge_id" class="input-select">
            <option value="">Tous les défis</option>
            <?php foreach ($challenges ?? [] as $ch): ?>
                <option value="<?= $ch['id'] ?>" <?= (($_GET['challenge_id'] ?? '') == $ch['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($ch['title']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="category" class="input-select">
            <option value="">Toutes catégories</option>
            <?php foreach ($categories ?? [] as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>" <?= (($_GET['category'] ?? '') === $cat) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="btn btn-secondary">Filtrer</button>
        <a href="/ranking" class="btn btn-ghost">Réinitialiser</a>
    </form>

    <!-- Podium Top 3 -->
    <?php if (!empty($submissions) && count($submissions) >= 3): ?>
        <div class="podium">
            <!-- 2e place -->
            <div class="podium-item podium-2">
                <div class="podium-rank"></div>
                <?php if (!empty($submissions[1]['image'])): ?>
                    <img src="/public/uploads/<?= htmlspecialchars($submissions[1]['image']) ?>" alt="2e">
                <?php endif; ?>
                <strong><?= htmlspecialchars($submissions[1]['author_name'] ?? 'Anonyme') ?></strong>
                <span><?= (int)$submissions[1]['vote_count'] ?> votes</span>
                <a href="/challenge/show/<?= $submissions[1]['challenge_id'] ?>#submission-<?= $submissions[1]['id'] ?>" class="btn btn-sm btn-outline">Voir</a>
            </div>

            <!-- 1re place -->
            <div class="podium-item podium-1">
                <div class="podium-rank"></div>
                <?php if (!empty($submissions[0]['image'])): ?>
                    <img src="/public/uploads/<?= htmlspecialchars($submissions[0]['image']) ?>" alt="1er">
                <?php endif; ?>
                <strong><?= htmlspecialchars($submissions[0]['author_name'] ?? 'Anonyme') ?></strong>
                <span><?= (int)$submissions[0]['vote_count'] ?> votes</span>
                <a href="/challenge/show/<?= $submissions[0]['challenge_id'] ?>#submission-<?= $submissions[0]['id'] ?>" class="btn btn-sm btn-outline">Voir</a>
            </div>

            <!-- 3e place -->
            <div class="podium-item podium-3">
                <div class="podium-rank"></div>
                <?php if (!empty($submissions[2]['image'])): ?>
                    <img src="/public/uploads/<?= htmlspecialchars($submissions[2]['image']) ?>" alt="3e">
                <?php endif; ?>
                <strong><?= htmlspecialchars($submissions[2]['author_name'] ?? 'Anonyme') ?></strong>
                <span><?= (int)$submissions[2]['vote_count'] ?> votes</span>
                <a href="/challenge/show/<?= $submissions[2]['challenge_id'] ?>#submission-<?= $submissions[2]['id'] ?>" class="btn btn-sm btn-outline">Voir</a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Tableau classement complet -->
    <?php if (empty($submissions)): ?>
        <div class="empty-state">
            <p>Aucune participation à classer pour l'instant.</p>
        </div>
    <?php else: ?>
        <div class="ranking-table-wrapper">
            <table class="ranking-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Participant</th>
                        <th>Défi</th>
                        <th>Catégorie</th>
                        <th>Votes</th>
                        <th>Commentaires</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($submissions as $i => $submission): ?>
                        <tr class="<?= $i < 3 ? 'top-' . ($i + 1) : '' ?>">
                            <td class="rank-cell">
                                <?php
                                if ($i === 0) echo '1st place';
                                elseif ($i === 1) echo '2nd place';
                                elseif ($i === 2) echo '3d place';
                                else echo '#' . ($i + 1);
                                ?>
                            </td>
                            <td><?= htmlspecialchars($submission['author_name'] ?? 'Anonyme') ?></td>
                            <td><?= htmlspecialchars($submission['challenge_title'] ?? '') ?></td>
                            <td><span class="badge badge-sm"><?= htmlspecialchars($submission['category'] ?? '') ?></span></td>
                            <td class="votes-cell"><strong><?= (int)$submission['vote_count'] ?></strong></td>
                            <td><?= (int)($submission['comment_count'] ?? 0) ?></td>
                            <td><?= date('d/m/Y', strtotime($submission['created_at'])) ?></td>
                            <td>
                                <a href="/challenge/show/<?= $submission['challenge_id'] ?>#submission-<?= $submission['id'] ?>" class="btn btn-sm btn-outline">Voir <svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
                                fill="currentColor" viewBox="0 0 24 24" >
                                <path d="m9 17 6-5-6-5v4H2v2h7z"></path><path d="M13 3v2c3.86 0 7 3.14 7 7s-3.14 7-7 7v2c4.96 0 9-4.04 9-9s-4.04-9-9-9"></path>
                                </svg></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
