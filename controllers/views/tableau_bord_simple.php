<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .header { background: #2c3e50; color: white; padding: 15px; margin: -20px -20px 20px -20px; text-align: center; }
        nav { background: #34495e; padding: 10px; margin: 20px -20px; }
        nav a { color: white; text-decoration: none; margin-right: 15px; padding: 8px 12px; }
        nav a.active, nav a:hover { background: #2c3e50; }
        .container { max-width: 1000px; margin: 0 auto; }
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 20px 0; }
        .card { background: white; padding: 25px; border-radius: 5px; text-align: center; }
        .card h3 { margin: 0 0 15px 0; color: #2c3e50; }
        .card p { color: #7f8c8d; margin-bottom: 20px; }
        .btn { padding: 10px 20px; text-decoration: none; border-radius: 3px; color: white; }
        .btn-primary { background: #3498db; }
        .btn-success { background: #27ae60; }
        .welcome { background: white; padding: 30px; border-radius: 5px; margin-bottom: 30px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BNGRC - Tableau de Bord</h1>
        <p>Bureau National de Gestion des Risques et Catastrophes</p>
    </div>

    <nav>
        <a href="/exams3-main/exams3/tableau-bord" class="active">Tableau de bord</a>
        <a href="/exams3-main/exams3/regions">Régions</a>
        <a href="/exams3-main/exams3/villes">Villes</a>
        <a href="/exams3-main/exams3/besoins">Besoins</a>
        <a href="/exams3-main/exams3/dons">Dons</a>
    </nav>

    <div class="container">
        <div class="welcome">
            <h2>Bienvenue dans le système BNGRC</h2>
            <p>Système de gestion des dons aux sinistrés - Madagascar</p>
        </div>

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
</body>
</html>