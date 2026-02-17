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
                        $initiales = 'U';
                    }
                    echo substr($initiales, 0, 2);
                    ?>
                </div>
                <a href="/exams3-main/exams3/logout" class="btn-logout" title="Déconnexion">↪</a>
            </div>
        </header>

        <!-- Navigation -->
        <nav class="nav">
            <a href="/exams3-main/exams3/user/dashboard" <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'class="active"' : ''; ?>>Dashboard</a>
            <a href="/exams3-main/exams3/user/besoins" <?php echo (strpos($_SERVER['REQUEST_URI'], 'besoins') !== false) ? 'class="active"' : ''; ?>>Besoins</a>
            <a href="/exams3-main/exams3/user/dons" <?php echo (strpos($_SERVER['REQUEST_URI'], 'dons') !== false) ? 'class="active"' : ''; ?>>Dons</a>
            <a href="/exams3-main/exams3/user/villes" <?php echo (strpos($_SERVER['REQUEST_URI'], 'villes') !== false) ? 'class="active"' : ''; ?>>Villes</a>
            <a href="#">Statistiques</a>
            <a href="#">Rapports</a>
        </nav>