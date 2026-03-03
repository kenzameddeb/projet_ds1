<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>ChallengeHub</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <header>
        <h1>ChallengeHub</h1>
        <nav>
            <a href="index.php?controller=challenge&action=index">Défis</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="index.php?controller=user&action=profile">Profil</a>
                <a href="index.php?controller=user&action=logout">Déconnexion</a>
            <?php else: ?>
                <a href="index.php?controller=user&action=login">Connexion</a>
                <a href="index.php?controller=user&action=register">Inscription</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>