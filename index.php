<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Gestion filtres (POST sans action)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action']) && isset($_POST['page'])) {
    $page = $_POST['page'];
}

// Gestion des actions POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    require_once __DIR__ . '/app/controllers/UserController.php';
    require_once __DIR__ . '/app/controllers/ChallengeController.php';

    try {
        $database       = new Database();
        $db             = $database->connect();
        $controller     = new UserController($db);
        $chalController = new ChallengeController($db);

        switch ($_POST['action']) {
            case 'register':         $controller->register();   break;
            case 'login':            $controller->login();      break;
            case 'logout':           $controller->logout();     break;
            case 'challenge_create': $chalController->create(); break;
            case 'challenge_edit':
                $id = $_POST['id'] ?? 0;
                $chalController->edit($id);
                break;
            case 'challenge_delete':
                $id = $_POST['id'] ?? 0;
                $chalController->delete($id);
                break;
        }
    } catch (Exception $e) {
        if ($e->getCode() == 23000) {
            $_SESSION['error'] = "Cet e-mail est déjà utilisé !";
        } else {
            $_SESSION['error'] = "Erreur : " . $e->getMessage();
        }
        header('Location: /projet_ds1/index.php?page=register');
        exit();
    }
}

// Gestion logout via GET
if (isset($_GET['page']) && $_GET['page'] === 'logout') {
    session_destroy();
    header('Location: /projet_ds1/index.php?page=login');
    exit();
}

$page = $page ?? $_GET['page'] ?? $_POST['page'] ?? 'dashboard';

$pages_autorisees = [
    'dashboard',
    'login',
    'register',
    'profile',
    'challenges',
    'challenge_create',
    'challenge_show',
    'challenge_edit'
];

// Mapping pages → fichiers
$mapping = [
    'challenges'       => 'challenge/index.php',
    'challenge_create' => 'challenge/create.php',
    'challenge_show'   => 'challenge/show.php',
    'challenge_edit'   => 'challenge/edit.php',
    'dashboard'        => 'dashboard.php',
];

$titre = "ChallengeHub";
if ($page == "dashboard")        $titre = "Dashboard - ChallengeHub";
if ($page == "login")            $titre = "Connexion - ChallengeHub";
if ($page == "register")         $titre = "Inscription - ChallengeHub";
if ($page == "profile")          $titre = "Profil - ChallengeHub";
if ($page == "challenges")       $titre = "Défis - ChallengeHub";
if ($page == "challenge_create") $titre = "Créer un défi - ChallengeHub";
if ($page == "challenge_show")   $titre = "Détail du défi - ChallengeHub";
if ($page == "challenge_edit")   $titre = "Modifier le défi - ChallengeHub";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titre) ?></title>
    <link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/projet_ds1/public/css/c1.css" rel="stylesheet">
    <link href="/projet_ds1/public/css/c2.css" rel="stylesheet">
    <link href="/projet_ds1/public/css/c3.css" rel="stylesheet">
</head>
<body>

<!-- HEADER -->
<header class="site-header">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between py-3 gap-3">

            <!-- Logo -->
            <a href="index.php" class="logo text-decoration-none fw-bold fs-4">
                <span class="logo-accent">Challenge</span>Hub
            </a>

            <!-- Nav -->
            <nav class="d-flex align-items-center gap-3">
                <a href="index.php?page=dashboard" class="nav-lien text-decoration-none fw-semibold">Dashboard</a>
                <a href="index.php?page=challenges" class="nav-lien text-decoration-none fw-semibold">Défis</a>
                <?php if (!isset($_SESSION['user'])): ?>
                    <a href="index.php?page=login" class="nav-lien text-decoration-none fw-semibold">Connexion</a>
                    <a href="index.php?page=register" class="btn btn-inscription fw-semibold">Inscription</a>
                <?php else: ?>
                    <a href="index.php?page=profile" class="nav-lien text-decoration-none fw-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.24 4.76c-2.3-2.29-5.87-2.35-8.24-.19-2.37-2.16-5.93-2.09-8.24.2-2.36 2.37-2.36 6.07 0 8.43l7.53 7.52c.2.19.45.29.71.29s.51-.1.71-.29l7.53-7.52c2.36-2.36 2.36-6.06 0-8.43ZM12 18.59l-6.82-6.81a3.92 3.92 0 0 1 0-5.6C5.97 5.39 6.98 5 7.99 5s2.02.39 2.8 1.18l.5.5-2.38 2.39c-.51.52-.51 1.36 0 1.88.49.49 1.13.73 1.77.73s1.28-.24 1.77-.73l1.64-1.64 3.59 3.59-1.04 1.04-2.3-2.3-.71.71 2.3 2.3-.79.79-2.3-2.3-.71.71 2.3 2.3-.79.79-2.29-2.29-.71.71 2.29 2.29-.94.94Zm6.82-6.81-.42.42-3.59-3.59 1.24-1.24-.71-.71-3.59 3.59c-.58.58-1.54.58-2.12 0a.33.33 0 0 1 0-.47l3.42-3.44.16-.16c1.57-1.57 4.04-1.57 5.62 0 1.57 1.58 1.57 4.04 0 5.6Z"></path>
                        </svg>
                        <?= htmlspecialchars($_SESSION['user']['name']) ?>
                    </a>
                    <a href="index.php?page=logout" class="btn btn-deconnexion fw-semibold">Déconnexion</a>
                <?php endif; ?>
            </nav>

            <!-- Recherche -->
            <form method="POST" action="index.php" class="d-flex align-items-center gap-2">
                <input type="hidden" name="page" value="dashboard">
                <input type="text" name="search"
                       placeholder="Rechercher un défi..."
                       value="<?= isset($_POST['search']) ? htmlspecialchars($_POST['search']) : '' ?>"
                       class="form-control form-control-sm search-input">
                <button type="submit" class="btn btn-search btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 10.5C19 5.81 15.19 2 10.5 2S2 5.81 2 10.5 5.81 19 10.5 19c1.98 0 3.81-.69 5.25-1.83L20 21.42l1.41-1.41-4.25-4.25a8.47 8.47 0 0 0 1.83-5.25Zm-15 0C4 6.92 6.92 4 10.5 4S17 6.92 17 10.5 14.08 17 10.5 17 4 14.08 4 10.5"></path>
                        <path d="M11.5 6h-2v3.5H6v2h3.5V15h2v-3.5H15v-2h-3.5z"></path>
                    </svg>
                </button>
            </form>

        </div>
    </div>
</header>

<!-- MAIN -->
<main class="pb-5">
    <?php
    if (in_array($page, $pages_autorisees)) {
        if (isset($mapping[$page])) {
            $fichier = __DIR__ . '/app/views/' . $mapping[$page];
            if (file_exists($fichier)) {
                require_once $fichier;
            } else {
                echo '<div class="alert alert-danger text-center mt-4">Fichier introuvable : ' . htmlspecialchars($mapping[$page]) . '</div>';
            }
        } elseif (file_exists(__DIR__ . '/app/views/user/' . $page . '.php')) {
            require_once __DIR__ . '/app/views/user/' . $page . '.php';
        } elseif (file_exists(__DIR__ . '/app/views/' . $page . '.php')) {
            require_once __DIR__ . '/app/views/' . $page . '.php';
        } else {
            echo '<div class="alert alert-danger text-center mt-4">Page introuvable.</div>';
        }
    } else {
        require_once __DIR__ . '/app/views/dashboard.php';
    }
    ?>
</main>

<!-- FOOTER -->
<footer class="site-footer mt-5 py-4">
    <div class="container text-center">
        <div class="d-flex justify-content-center align-items-center gap-4 mb-2 flex-wrap">
            <span class="footer-contact">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     fill="currentColor" viewBox="0 0 24 24">
                    <path d="M18.07 22h.35c.47-.02.9-.26 1.17-.64l2.14-3.09c.23-.33.32-.74.24-1.14s-.31-.74-.64-.97l-4.64-3.09a1.47 1.47 0 0 0-.83-.25c-.41 0-.81.16-1.1.48l-1.47 1.59c-.69-.43-1.61-1.07-2.36-1.82-.72-.72-1.37-1.64-1.82-2.36l1.59-1.47c.54-.5.64-1.32.23-1.93L7.84 2.67c-.22-.33-.57-.57-.97-.64a1.46 1.46 0 0 0-1.13.24L2.65 4.41c-.39.27-.62.7-.64 1.17-.03.69-.16 6.9 4.68 11.74 4.35 4.35 9.81 4.69 11.38 4.69Z"></path>
                </svg>
                25 258 258
            </span>
            <span class="footer-contact">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2m0 2v.51l-8 6.22-8-6.22V6zM4 18V9.04l7.39 5.74c.18.14.4.21.61.21s.43-.07.61-.21L20 9.03v8.96H4Z"></path>
                </svg>
                defi@gmail.com
            </span>
        </div>
        <p class="footer-copy mb-0">
            &copy; <?= date("Y") ?> <span class="footer-brand">Challenge</span>Hub — Tous droits réservés
        </p>
    </div>
</footer>

<script src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="/projet_ds1/public/js/js.js"></script>
</body>
</html>