<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Besoins - BNGRC</title>
    <link rel="stylesheet" href="/exams3-main/exams3/public/css/styles.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f5f5f5; }
        .header { background-color: #dc3545; color: white; padding: 20px; text-align: center; }
        nav { background-color: #333; padding: 10px; }
        nav a { color: white; text-decoration: none; margin-right: 15px; padding: 8px 12px; border-radius: 4px; display: inline-block; }
        nav a:hover, nav a.active { background-color: #555; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 15px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .btn { display: inline-block; padding: 8px 15px; margin: 2px; text-decoration: none; border-radius: 4px; font-size: 12px; }
        .btn-success { background-color: #28a745; color: white; }
        .alert { padding: 15px; margin: 20px 0; border-radius: 4px; background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📦 Gestion des Besoins</h1>
        <p>BNGRC - Besoins des Sinistrés</p>
    </div>

    <nav>
        <a href="/exams3-main/exams3/">🏠 Accueil</a>
        <a href="/exams3-main/exams3/regions">🗺️ Régions</a>
        <a href="/exams3-main/exams3/villes">🏘️ Villes</a>
        <a href="/exams3-main/exams3/besoins" class="active">📦 Besoins</a>
        <a href="/exams3-main/exams3/dons">🎁 Dons</a>
        <a href="/exams3-main/exams3/tableau-bord">📊 Tableau de bord</a>
    </nav>

    <div class="container">
        <div class="alert">
            <strong>⚠️ Attention :</strong> Système en configuration. Liste des besoins temporairement indisponible.
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Liste des Besoins</h2>
            <a href="/exams3-main/exams3/besoins/create" class="btn btn-success">➕ Déclarer un besoin</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Ville</th>
                    <th>Type de besoin</th>
                    <th>Quantité</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Antananarivo</td>
                    <td>Eau potable</td>
                    <td>1000 L</td>
                    <td>🔴 Urgent</td>
                </tr>
                <tr>
                    <td>Antsirabe</td>
                    <td>Vivres</td>
                    <td>500 kg</td>
                    <td>🟡 Modéré</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>