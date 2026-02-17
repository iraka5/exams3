<?php
// views/partials/header.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= isset(
        $pageTitle) ? $pageTitle : 'BNGRC'; ?></title>
    <link rel="stylesheet" href="/exams3-main/exams3/public/css/styles.css">
</head>
<body>
    <div class="container">
        <!-- Header avec logo -->
        <header class="header">
            <div class="logo">
                BNG<span>RC</span>
            </div>
            <div class="user-menu">
                <span class="user-name"><?php echo htmlspecialchars($_SESSION['nom'] ?? 'Utilisateur'); ?></span>
                <div class="avatar">
                    <?php 
                    $initiales = '';
                    if (isset($_SESSION['nom'])) {
                        $nom = $_SESSION['nom'];
                        $mots = explode(' ', $nom);
                        foreach ($mots as $mot) {
                            $initiales .= strtoupper(substr($mot, 0, 1));
                        }
                    } else {
                        $initiales = 'USR';
                    }
                    echo substr($initiales, 0, 2);
                    ?>
                </div>
                <a href="/exams3-main/exams3/logout" class="btn-logout" title="Déconnexion">↪</a>
            </div>
        </header>

        <!-- Navigation -->
        <nav class="nav">
            <a href="/exams3-main/exams3/">Accueil</a>
            <a href="/exams3-main/exams3/regions">Régions</a>
            <a href="/exams3-main/exams3/villes">Villes</a>
            <a href="/exams3-main/exams3/besoins">Besoins</a>
            <a href="/exams3-main/exams3/dons">Dons</a>
            <a href="/exams3-main/exams3/config-taux" <?php echo (strpos($_SERVER['REQUEST_URI'], 'config-taux') !== false) ? 'class="active"' : ''; ?>>Config V3</a>
            <a href="/exams3-main/exams3/reset-data" <?php echo (strpos($_SERVER['REQUEST_URI'], 'reset-data') !== false) ? 'class="active"' : ''; ?>>Reset</a>
        </nav>