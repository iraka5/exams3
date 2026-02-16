<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Utilisateur - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f8f9fa; }
        .header { background: linear-gradient(135deg, #27ae60, #2ecc71); color: white; padding: 20px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header h1 { margin: 0; font-size: 28px; }
        .header p { margin: 5px 0 0 0; opacity: 0.9; }
        .user-info { background: rgba(255,255,255,0.2); padding: 10px 20px; margin: 15px auto; border-radius: 8px; display: inline-block; }
        nav { background-color: #2c3e50; padding: 15px; }
        nav a { color: white; text-decoration: none; margin-right: 25px; padding: 10px 15px; border-radius: 6px; display: inline-block; transition: background-color 0.3s; }
        nav a:hover, nav a.active { background-color: #34495e; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin: 30px 0; }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .card:hover { transform: translateY(-5px); }
        .card h3 { margin: 0 0 15px 0; color: #2c3e50; font-size: 20px; }
        .card p { color: #7f8c8d; margin-bottom: 20px; line-height: 1.5; }
        .btn { display: inline-block; padding: 12px 20px; margin: 5px; text-decoration: none; border-radius: 6px; font-weight: 500; transition: all 0.3s; }
        .btn-primary { background-color: #3498db; color: white; }
        .btn-primary:hover { background-color: #2980b9; transform: translateY(-2px); }
        .btn-success { background-color: #27ae60; color: white; }
        .btn-success:hover { background-color: #229954; transform: translateY(-2px); }
        .btn-info { background-color: #17a2b8; color: white; }
        .btn-info:hover { background-color: #138496; transform: translateY(-2px); }
        .btn-danger { background-color: #e74c3c; color: white; }
        .btn-danger:hover { background-color: #c0392b; transform: translateY(-2px); }
        .welcome { background: white; padding: 25px; border-radius: 12px; margin-bottom: 25px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .welcome h2 { color: #27ae60; margin: 0 0 10px 0; }
        .card-icon { font-size: 48px; margin-bottom: 15px; display: block; }
        .card-besoins { border-left: 5px solid #e74c3c; }
        .card-dons { border-left: 5px solid #27ae60; }
        .card-villes { border-left: 5px solid #3498db; }
        .limitations { background: #fff3dc; border: 1px solid #ffc107; color: #856404; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .limitations h4 { margin: 0 0 10px 0; color: #856404; }
        .logout-section { text-align: center; margin-top: 30px; padding: 20px; }
    </style>
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