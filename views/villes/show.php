<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($ville["nom"]) ?> - BNGRC</title>
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
        .section { margin-top: 30px; }
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

<h1>🏘️ Ville : <?= htmlspecialchars($ville["nom"]) ?></h1>
<p style="color: #666;">Région : <?= htmlspecialchars($ville["region_nom"]) ?></p>

<div class="card">
    <div class="stats">
        <div class="stat">
            <strong><?= count($besoins) ?></strong><br>
            <small>Besoins</small>
        </div>
        <div class="stat">
            <strong><?= count($dons) ?></strong><br>
            <small>Dons</small>
        </div>
    </div>

    <div style="margin-top: 20px;">
        <a href="/villes/<?= $ville["id"] ?>/edit" class="btn btn-warning">✏️ Modifier</a>
        <a href="/besoins/create?ville_id=<?= $ville["id"] ?>" class="btn btn-success">➕ Ajouter un besoin</a>
        <a href="/dons/create?ville_id=<?= $ville["id"] ?>" class="btn btn-success">➕ Ajouter un don</a>
        <a href="/villes" class="btn">🔙 Retour</a>
    </div>
</div>

<div class="section">
    <div class="card">
        <h2>📋 Besoins de cette ville</h2>

        <?php if (empty($besoins)): ?>
            <p style="text-align: center; color: #999;">
                Aucun besoin enregistré pour cette ville.
            </p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Besoin</th>
                        <th>Quantité</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($besoins as $besoin): ?>
                        <tr>
                            <td><?= htmlspecialchars($besoin["nom"]) ?></td>
                            <td><?= $besoin["nombre"] ?></td>
                            <td><?= date("d/m/Y", strtotime($besoin["created_at"])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<div class="section">
    <div class="card">
        <h2>🎁 Dons pour cette ville</h2>

        <?php if (empty($dons)): ?>
            <p style="text-align: center; color: #999;">
                Aucun don enregistré pour cette ville.
            </p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Donneur</th>
                        <th>Type</th>
                        <th>Quantité</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dons as $don): ?>
                        <tr>
                            <td><?= htmlspecialchars($don["nom_donneur"]) ?></td>
                            <td><?= htmlspecialchars($don["type_don"]) ?></td>
                            <td><?= $don["nombre_don"] ?></td>
                            <td><?= date("d/m/Y", strtotime($don["created_at"])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

</body>
</html>