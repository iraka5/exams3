<?php require_once __DIR__ . '/../partials/header.php'; ?>
</head>
<body>
    <div class="header">
        <h1>🏛️ BNGRC - Espace Utilisateur</h1>
        <p>Bureau National de Gestion des Risques et Catastrophes</p>
        <div class="user-info">
            👤 Bienvenue, <strong><?= $_SESSION['user_name'] ?? 'Utilisateur' ?></strong>
        </div>
    </div>

    <nav>
        <a href="/exams3-main/exams3/user/dashboard" class="active">Accueil</a>
        <a href="/exams3-main/exams3/user/besoins">Voir les Besoins</a>
        <a href="/exams3-main/exams3/user/dons">Faire un Don</a>
        <a href="/exams3-main/exams3/user/villes">Tableaux de Bord par Ville</a>
        <a href="/exams3-main/exams3/user/logout" style="float: right;">Déconnexion</a>
        <a href="<?= $base ?>/achats/recapitulatif" class="btn btn-info">📈 Voir le Récapitulatif</a>
    </nav>

    <div class="container">
        <div class="welcome">
            <h2>Bienvenue dans votre espace personnel</h2>
            <p>Vous pouvez consulter les besoins des sinistrés, faire des dons, et suivre la situation par ville.</p>
        </div>

        <?php if (isset($_GET['success']) && $_GET['success'] == 'don'): ?>
            <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                <strong>🎉 Merci pour votre don !</strong><br>
                Votre générosité contribue directement à aider les sinistrés. Votre don a été enregistré avec succès.
            </div>
        <?php endif; ?>

        <div class="limitations">
            <h4>📋 Rappel Important</h4>
            <p><strong>Votre mot de passe a été utilisé.</strong> Après votre déconnexion, vous devrez créer un nouveau compte 
            avec un nouveau mot de passe pour vous reconnecter. Cette mesure de sécurité garantit l'unicité de chaque connexion.</p>
        </div>

        <div class="cards">
            <div class="card card-besoins">
                <span class="card-icon">📋</span>
                <h3>Consulter les Besoins</h3>
                <p>Découvrez les besoins urgents des sinistrés dans toutes les régions de Madagascar. Vous pouvez filtrer par ville pour voir les besoins spécifiques.</p>
                <a href="/exams3-main/exams3/user/besoins" class="btn btn-primary">💡 Voir les Besoins</a>
            </div>
            
            <div class="card card-dons">
                <span class="card-icon">🎁</span>
                <h3>Faire un Don</h3>
                <p>Aidez les sinistrés en faisant un don. Vous pouvez donner des biens matériels, de la nourriture, ou de l'aide financière.</p>
                <a href="/exams3-main/exams3/user/dons" class="btn btn-success">❤️ Faire un Don</a>
            </div>
            
            <div class="card card-villes">
                <span class="card-icon">🏙️</span>
                <h3>Tableaux de Bord par Ville</h3>
                <p>Consultez les statistiques détaillées de chaque ville : besoins vs dons reçus, situation par région, et indicateurs de couverture.</p>
                <a href="/exams3-main/exams3/user/villes" class="btn btn-info">📊 Voir les Statistiques</a>
            </div>
        </div>

        <div class="welcome">
            <h2>🤝 Votre Impact</h2>
            <p>Chaque don compte ! Votre solidarité contribue directement à améliorer les conditions de vie des personnes touchées par les catastrophes naturelles à Madagascar.</p>
        </div>

        <div class="logout-section">
            <a href="/exams3-main/exams3/user/logout" class="btn btn-danger">🚪 Se Déconnecter</a>
            <p style="color: #7f8c8d; font-size: 14px; margin-top: 10px;">
                Pensez à vous déconnecter après utilisation
            </p>
        </div>
    </div>
</body>
</html>