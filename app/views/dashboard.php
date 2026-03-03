<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/models/Challenge.php';

$database = new Database();
$pdo = $database->connect();

$challengeModel = new Challenge($pdo);

$challengeModel = new Challenge($pdo);
$defis  = $challengeModel->findAll();
$total  = count($defis);
$recents = array_slice($defis, 0, 3);

// Compter par catégorie
$categories = [];
foreach ($defis as $d) {
    $cat = $d['categorie'];
    $categories[$cat] = ($categories[$cat] ?? 0) + 1;
}

// Défis dont la date limite approche (dans 7 jours)
$bientot = array_filter($defis, function($d) {
    $diff = (strtotime($d['date_limite']) - time()) / 86400;
    return $diff >= 0 && $diff <= 7;
});
?>

<style>
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up { animation: fadeUp 0.5s ease forwards; opacity: 0; }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }
    .delay-5 { animation-delay: 0.5s; }

    .stat-card {
        background: linear-gradient(135deg, #1a1a24 0%, #1e1e2e 100%);
        border: 1px solid #2a2a3a;
        border-radius: 1.25rem;
        padding: 1.75rem;
        transition: all 0.25s;
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: -30px; right: -30px;
        width: 100px; height: 100px;
        border-radius: 50%;
        opacity: 0.08;
    }
    .stat-card:hover { transform: translateY(-3px); border-color: #4f46e5; }
    .stat-card.indigo::before  { background: #6366f1; }
    .stat-card.emerald::before { background: #10b981; }
    .stat-card.amber::before   { background: #f59e0b; }
    .stat-card.rose::before    { background: #f43f5e; }

    .defi-card {
        background: #1a1a24;
        border: 1px solid #2a2a3a;
        border-radius: 1rem;
        padding: 1.25rem;
        transition: all 0.2s;
    }
    .defi-card:hover { border-color: #6366f1; transform: translateX(4px); }

    .progress-bar {
        height: 6px;
        border-radius: 9999px;
        background: #2a2a3a;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        border-radius: 9999px;
        background: linear-gradient(90deg, #6366f1, #a78bfa);
    }
</style>

<!--  HERO  -->
<div class="fade-up mb-10">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <p class="text-indigo-400 text-sm font-semibold uppercase tracking-widest mb-1">
                Tableau de bord
            </p>
            <h1 class="text-4xl font-extrabold text-white leading-tight">
                Bonjour <?= isset($_SESSION['user']) ? '👋 ' . htmlspecialchars($_SESSION['user']['nom']) : '👋 Visiteur' ?> !
            </h1>
            <p class="text-gray-400 mt-1">
                <?= date('d/m/Y') ?> — voici l'état de vos défis.
            </p>
        </div>
        <?php if (isset($_SESSION['user'])): ?>
        <a href="index.php?page=challenges&action=create"
           class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-6 py-3 rounded-xl transition">
            ➕ Nouveau défi
        </a>
        <?php endif; ?>
    </div>
</div>

<!--  STATS  -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
    <div class="stat-card indigo fade-up delay-1">
        <p class="text-3xl mb-1">⚡</p>
        <p class="text-3xl font-extrabold text-white"><?= $total ?></p>
        <p class="text-sm text-gray-400 mt-1">Défis au total</p>
    </div>
    <div class="stat-card emerald fade-up delay-2">
        <p class="text-3xl mb-1">📂</p>
        <p class="text-3xl font-extrabold text-white"><?= count($categories) ?></p>
        <p class="text-sm text-gray-400 mt-1">Catégories</p>
    </div>
    <div class="stat-card amber fade-up delay-3">
        <p class="text-3xl mb-1">⏳</p>
        <p class="text-3xl font-extrabold text-white"><?= count($bientot) ?></p>
        <p class="text-sm text-gray-400 mt-1">Expirent dans 7 jours</p>
    </div>
    <div class="stat-card rose fade-up delay-4">
        <p class="text-3xl mb-1">🏆</p>
        <p class="text-3xl font-extrabold text-white">0</p>
        <p class="text-sm text-gray-400 mt-1">Soumissions</p>
    </div>
</div>

<!--  CONTENU PRINCIPAL  -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 fade-up delay-5">

    <!-- Défis récents -->
    <div class="lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-white">🕐 Défis récents</h2>
            <a href="index.php?page=challenges" class="text-sm text-indigo-400 hover:text-indigo-300 transition">
                Voir tout →
            </a>
        </div>

        <?php if (empty($recents)): ?>
            <div class="bg-[#1a1a24] border border-dashed border-[#2a2a3a] rounded-2xl p-10 text-center">
                <p class="text-4xl mb-3">🎯</p>
                <p class="text-gray-400">Aucun défi pour l'instant.</p>
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="index.php?page=challenges&action=create"
                       class="inline-block mt-4 bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2.5 rounded-xl text-sm transition">
                        Créer le premier défi
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="flex flex-col gap-3">
                <?php foreach ($recents as $defi):
                    $jours = ceil((strtotime($defi['date_limite']) - time()) / 86400);
                    $urgence = $jours <= 3 ? 'text-red-400' : ($jours <= 7 ? 'text-amber-400' : 'text-gray-500');
                ?>
                <div class="defi-card">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h3 class="font-bold text-white leading-snug"><?= htmlspecialchars($defi['titre']) ?></h3>
                        <span class="badge badge-<?= strtolower($defi['categorie']) ?> shrink-0">
                            <?= htmlspecialchars($defi['categorie']) ?>
                        </span>
                    </div>
                    <p class="text-gray-400 text-sm mb-3"><?= htmlspecialchars($defi['description']) ?></p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs <?= $urgence ?>">
                            📅 <?= date('d/m/Y', strtotime($defi['date_limite'])) ?>
                            (<?= $jours >= 0 ? "$jours j restants" : "expiré" ?>)
                        </span>
                        <a href="index.php?page=challenges&action=show&id=<?= $defi['id'] ?>"
                           class="text-xs text-indigo-400 hover:text-indigo-300 transition">Voir →</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="flex flex-col gap-5">

        <!-- Catégories avec barres -->
        <div class="bg-[#1a1a24] border border-[#2a2a3a] rounded-2xl p-5">
            <h2 class="text-lg font-bold text-white mb-4">📊 Catégories</h2>
            <?php if (empty($categories)): ?>
                <p class="text-gray-500 text-sm">Aucune donnée.</p>
            <?php else: ?>
                <div class="flex flex-col gap-3">
                    <?php foreach ($categories as $cat => $count):
                        $pct = $total > 0 ? round(($count / $total) * 100) : 0;
                    ?>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-300"><?= htmlspecialchars($cat) ?></span>
                            <span class="text-gray-500"><?= $count ?> (<?= $pct ?>%)</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= $pct ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Expirent bientôt -->
        <div class="bg-[#1a1a24] border border-[#2a2a3a] rounded-2xl p-5">
            <h2 class="text-lg font-bold text-white mb-4">⏰ Expirent bientôt</h2>
            <?php if (empty($bientot)): ?>
                <p class="text-gray-500 text-sm">Aucun défi urgent.</p>
            <?php else: ?>
                <div class="flex flex-col gap-2">
                    <?php foreach ($bientot as $d):
                        $j = ceil((strtotime($d['date_limite']) - time()) / 86400);
                    ?>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-300 truncate mr-2"><?= htmlspecialchars($d['titre']) ?></span>
                        <span class="text-amber-400 shrink-0 font-bold"><?= $j ?>j</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- CTA -->
        <a href="index.php?page=challenges"
           class="bg-gradient-to-r from-indigo-700 to-indigo-500 hover:from-indigo-600 hover:to-indigo-400 text-white font-bold px-6 py-4 rounded-2xl transition text-center block">
            🚀 Voir tous les défis
        </a>

    </div>
</div>