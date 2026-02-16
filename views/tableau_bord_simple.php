<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f5f5f5; }
        .header { background-color: #007bff; color: white; padding: 20px; text-align: center; }
        nav { background-color: #333; padding: 10px; }
        nav a { color: white; text-decoration: none; margin-right: 15px; padding: 8px 12px; border-radius: 4px; display: inline-block; }
        nav a:hover, nav a.active { background-color: #555; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 20px 0; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .card h3 { margin: 0 0 15px 0; color: #333; }
        .status-rouge { border-left: 4px solid #dc3545; }
        .status-orange { border-left: 4px solid #ffc107; }
        .status-vert { border-left: 4px solid #28a745; }
        .btn { display: inline-block; padding: 8px 15px; margin: 5px; text-decoration: none; border-radius: 4px; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-success { background-color: #28a745; color: white; }
        .no-data { text-align: center; color: #666; padding: 40px; background: white; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BNGRC - Bureau National de Gestion des Risques et Catastrophes</h1>
        <p>Tableau de bord - Suivi des dons aux sinistrés</p>
    </div>

    <nav>
        <a href="/exams3-main/exams3/" class="active">Accueil</a>
        <a href="/exams3-main/exams3/regions">Régions</a>
        <a href="/exams3-main/exams3/villes">Villes</a>
        <a href="/exams3-main/exams3/besoins">Besoins</a>
        <a href="/exams3-main/exams3/dons">Dons</a>
        <a href="/exams3-main/exams3/logout">Déconnexion</a>
    </nav>

    <div class="container">
        <div class="no-data">
            <h2>Application de gestion des dons</h2>
            <p>Système de gestion des besoins et dons pour les sinistrés de Madagascar.</p>
            
            <div class="cards">
                <div class="card">
                    <h3>Gestion des Régions</h3>
                    <p>Gérer les régions de Madagascar</p>
                    <a href="/exams3-main/exams3/regions" class="btn btn-primary">Accéder</a>
                </div>
                
                <div class="card">
                    <h3>Gestion des Villes</h3>
                    <p>Gérer les villes et communes</p>
                    <a href="/exams3-main/exams3/villes" class="btn btn-primary">Accéder</a>
                </div>
                
                <div class="card">
                    <h3>Suivi des Besoins</h3>
                    <p>Enregistrer les besoins des sinistrés</p>
                    <a href="/exams3-main/exams3/besoins" class="btn btn-success">Accéder</a>
                </div>
                
                <div class="card">
                    <h3>Gestion des Dons</h3>
                    <p>Enregistrer les dons reçus</p>
                    <a href="/exams3-main/exams3/dons" class="btn btn-success">Accéder</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>