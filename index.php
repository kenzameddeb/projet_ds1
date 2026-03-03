<?php
session_start();

// Routing simple (contrôleur frontal)
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Liste blanche des pages autorisées (sécurité)
$pages_autorisees = ['dashboard', 'login', 'register'];


// Titre dynamique
$titre = "ChallengeHub";
if ($page == "dashboard") {
    $titre = "Dashboard - ChallengeHub";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titre) ?></title>
    <!--<link rel="stylesheet" href="public/css/style.css">-->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
    

</head>

<body style="background: #0f0f13;">
<header class="bg-[#1a1a24] border-b border-[#2a2a3a] px-6 py-4">
    <div class="max-w-6xl mx-auto flex items-center justify-between gap-4">

        <!-- Logo -->
        <a href="index.php" class="text-2xl font-extrabold tracking-tight text-white">
            ⚡ <span class="text-indigo-400">Challenge</span>Hub
        </a>

        <!-- Nav -->
        <nav class="flex items-center gap-6 text-sm font-semibold">
            <a href="index.php?page=dashboard" class="text-gray-400 hover:text-white transition">Dashboard</a>

            <?php if (!isset($_SESSION['user'])): ?>
                <a href="index.php?page=login" class="text-gray-400 hover:text-white transition">Connexion</a>
                <a href="index.php?page=register" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg transition">
                    Inscription
                </a>
            <?php else: ?>
                <a href="index.php?page=logout" class="bg-red-900/50 hover:bg-red-700 text-red-300 px-4 py-2 rounded-lg transition">
                    Déconnexion
                </a>
            <?php endif; ?>
        </nav>

        <!-- Recherche -->
        <form method="GET" action="index.php" class="flex items-center gap-2">
            <input type="hidden" name="page" value="dashboard">
            <input type="text" name="search"
                   placeholder="Rechercher un défi..."
                   value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                   class="bg-[#0f0f13] border border-[#2a2a3a] rounded-xl px-4 py-2 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-indigo-500 transition w-48">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-xl text-sm transition">
                🔍
            </button>
        </form>

    </div>
</header>
<main>
    <?php
    // Inclusion dynamique de la vue
    if (in_array($page, $pages_autorisees)) {
    require_once __DIR__ . '/app/views/' . $page . '.php';}
    else 
    require_once __DIR__ . '/app/views/dashbord.php';
    ?>
</main>

<footer>
    <p>Contactez-nous : 25 258 258</p>
    <p>Email : defi@gmail.com</p>
    <p>&copy; <?= date("Y"); ?> ChallengeHub</p>
</footer>

</body>
</html>