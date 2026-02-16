<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Régions - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f5f5f5; }
        .header { background-color: #007bff; color: white; padding: 20px; text-align: center; }
        nav { background-color: #333; padding: 10px; }
        nav a { color: white; text-decoration: none; margin-right: 15px; padding: 8px 12px; border-radius: 4px; display: inline-block; }
        nav a:hover, nav a.active { background-color: #555; }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 15px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .btn { display: inline-block; padding: 8px 15px; margin: 2px; text-decoration: none; border-radius: 4px; font-size: 12px; }
        .btn-success { background-color: #28a745; color: white; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-warning { background-color: #ffc107; color: black; }
        .btn-primary { background-color: #007bff; color: white; }
        .alert { padding: 15px; margin: 20px 0; border-radius: 4px; background-color: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🗺️ Gestion des Régions</h1>
        <p>BNGRC - Bureau National de Gestion des Risques et Catastrophes</p>
    </div>

    <nav>
        <a href="/exams3/">🏠 Accueil</a>
        <a href="/exams3/regions" class="active">🗺️ Régions</a>
        <a href="/exams3/villes">🏘️ Villes</a>
        <a href="/exams3/besoins">📦 Besoins</a>
        <a href="/exams3/dons">🎁 Dons</a>
        <a href="/exams3/tableau-bord">📊 Tableau de bord</a>
    </nav>

    <div class="container">
        <div class="alert">
            <strong>ℹ️ Information :</strong> Configuration de la base de données en cours. Données de démonstration affichées.
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Liste des Régions</h2>
            <a href="/exams3/regions/create" class="btn btn-success">➕ Ajouter une région</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom de la Région</th>
                    <th>Nombre de Villes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Analamanga</td>
                    <td>2 villes</td>
                    <td>
                        <a href="/exams3/regions/1" class="btn btn-primary">👁️ Voir</a>
                        <a href="/exams3/regions/1/edit" class="btn btn-warning">✏️ Modifier</a>
                        <a href="/exams3/regions/1/delete" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette région ?')">🗑️ Supprimer</a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Vakinankaratra</td>
                    <td>2 villes</td>
                    <td>
                        <a href="/exams3/regions/2" class="btn btn-primary">👁️ Voir</a>
                        <a href="/exams3/regions/2/edit" class="btn btn-warning">✏️ Modifier</a>
                        <a href="/exams3/regions/2/delete" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette région ?')">🗑️ Supprimer</a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Itasy</td>
                    <td>1 ville</td>
                    <td>
                        <a href="/exams3/regions/3" class="btn btn-primary">👁️ Voir</a>
                        <a href="/exams3/regions/3/edit" class="btn btn-warning">✏️ Modifier</a>
                        <a href="/exams3/regions/3/delete" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette région ?')">🗑️ Supprimer</a>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Bongolava</td>
                    <td>1 ville</td>
                    <td>
                        <a href="/exams3/regions/4" class="btn btn-primary">👁️ Voir</a>
                        <a href="/exams3/regions/4/edit" class="btn btn-warning">✏️ Modifier</a>
                        <a href="/exams3/regions/4/delete" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette région ?')">🗑️ Supprimer</a>
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 30px; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3>🎯 Prochaines étapes</h3>
            <p>Pour activer toutes les fonctionnalités :</p>
            <ol>
                <li>Vérifiez que XAMPP MySQL est démarré</li>
                <li>Importez le fichier <code>database/init.sql</code> dans phpMyAdmin</li>
                <li>Vérifiez la configuration de la base de données dans <code>config/config.php</code></li>
            </ol>
        </div>
    </div>
</body>
</html>