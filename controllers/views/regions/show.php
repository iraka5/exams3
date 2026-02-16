<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($region["nom"]) ?> - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        nav { background-color: #333; padding: 10px; margin-bottom: 20px; }
        nav a { color: white; text-decoration: none; margin-right: 15px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { display: inline-block; padding: 8px 15px; margin: 5px; text-decoration: none; background-color: #007bff; color: white; border-radius: 3px; }
        .btn-success { background-color: #28a745; }
        .btn-warning { background-color: #ffc107; color: black; }
        .stats { display: flex; gap: 20px; margin: 15px 0; }
        .stat { background: #f8f9fa; padding: 15px; border-radius: 5px; text-align: center; flex: 1; }
    </style>
</head>
<body>

<nav>
    <a href="/regions">Régions</a>
    <a href="/villes">Villes</a>
    <a href="/besoins">Besoins</a>
    <a href="/dons">Dons</a>
    <a href="/tableau-bord">Tableau de Bord</a>
</nav>

<h1>🏛️ Région : <?= htmlspecialchars($region["nom"]) ?></h1>

<div class="card">
    <div class="stats">
        <div class="stat">
            <strong><?= count($villes) ?></strong><br>
            <small>Villes</small>
        </div>
    </div>

    <div style="margin-top: 20px;">
        <a href="/regions/<?= $region["id"] ?>/edit" class="btn btn-warning">✏️ Modifier</a>
        <a href="/villes/create?region_id=<?= $region["id"] ?>" class="btn btn-success">➕ Ajouter une ville</a>
        <a href="/regions" class="btn">🔙 Retour</a>
    </div>
</div>

<div class="card">
    <h2>🏘️ Villes de cette région</h2>

    <?php if (empty($villes)): ?>
        <p style="text-align: center; color: #999;">
            Aucune ville enregistrée dans cette région.
        </p>
        <div style="text-align: center;">
            <a href="/villes/create?region_id=<?= $region["id"] ?>" class="btn btn-success">
                ➕ Ajouter la première ville
            </a>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom de la Ville</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($villes as $ville): ?>
                    <tr>
                        <td><?= $ville["id"] ?></td>
                        <td><?= htmlspecialchars($ville["nom"]) ?></td>
                        <td>
                            <a href="/villes/<?= $ville["id"] ?>" class="btn">👁️ Voir</a>
                            <a href="/villes/<?= $ville["id"] ?>/edit" class="btn btn-warning">✏️ Modifier</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>