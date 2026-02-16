<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dons - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f5f5f5; }
        .header { background-color: #28a745; color: white; padding: 20px; text-align: center; }
        nav { background-color: #333; padding: 10px; }
        nav a { color: white; text-decoration: none; margin-right: 15px; padding: 8px 12px; border-radius: 4px; display: inline-block; }
        nav a:hover, nav a.active { background-color: #555; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 15px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .btn { display: inline-block; padding: 8px 15px; margin: 2px; text-decoration: none; border-radius: 4px; font-size: 12px; }
        .btn-success { background-color: #28a745; color: white; }
        .alert { padding: 15px; margin: 20px 0; border-radius: 4px; background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎁 Gestion des Dons</h1>
        <p>BNGRC - Dons Reçus pour les Sinistrés</p>
    </div>

    <nav>
        <a href="/exams3-main/exams3/">🏠 Accueil</a>
        <a href="/exams3-main/exams3/regions">🗺️ Régions</a>
        <a href="/exams3-main/exams3/villes">🏘️ Villes</a>
        <a href="/exams3-main/exams3/besoins">📦 Besoins</a>
        <a href="/exams3-main/exams3/dons" class="active">🎁 Dons</a>
        <a href="/exams3-main/exams3/tableau-bord">📊 Tableau de bord</a>
    </nav>

    <div class="container">
        <div class="alert">
            <strong>✅ Information :</strong> Système en configuration. Liste des dons temporairement indisponible.
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Liste des Dons</h2>
            <a href="/exams3-main/exams3/dons/create" class="btn btn-success">➕ Enregistrer un don</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Donneur</th>
                    <th>Type de don</th>
                    <th>Quantité</th>
                    <th>Destination</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ONU Madagascar</td>
                    <td>Eau potable</td>
                    <td>800 L</td>
                    <td>Antananarivo</td>
                </tr>
                <tr>
                    <td>Croix Rouge</td>
                    <td>Vivres</td>
                    <td>300 kg</td>
                    <td>Antsirabe</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>