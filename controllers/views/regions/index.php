<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Régions - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { display: inline-block; padding: 8px 15px; margin: 5px; text-decoration: none; background-color: #007bff; color: white; border-radius: 3px; }
        .btn-success { background-color: #28a745; }
        .btn-danger { background-color: #dc3545; }
        .btn-warning { background-color: #ffc107; color: black; }
        nav { background-color: #333; padding: 10px; margin-bottom: 20px; }
        nav a { color: white; text-decoration: none; margin-right: 15px; }
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

<h1>🏛️ Gestion des Régions - BNGRC</h1>

<a href="/regions/create" class="btn btn-success">➕ Ajouter une région</a>

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
        <?php if (empty($regions)): ?>
            <tr>
                <td colspan="4" style="text-align: center; color: #999;">
                    Aucune région enregistrée
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($regions as $region): ?>
                <tr>
                    <td><?= $region["id"] ?></td>
                    <td>
                        <strong><?= htmlspecialchars($region["nom"]) ?></strong>
                    </td>
                    <td>
                        <span style="background: #e8f5e8; padding: 3px 8px; border-radius: 15px;">
                            <?= $region["nb_villes"] ?> ville(s)
                        </span>
                    </td>
                    <td>
                        <a href="/regions/<?= $region["id"] ?>" class="btn">👁️ Voir</a>
                        <a href="/regions/<?= $region["id"] ?>/edit" class="btn btn-warning">✏️ Modifier</a>
                        <a href="/regions/<?= $region["id"] ?>/delete" 
                           class="btn btn-danger" 
                           onclick="return confirm('Supprimer cette région ?')">🗑️ Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<br>
<p style="color: #666;">
    💡 Tip: Cliquez sur "Voir" pour consulter les villes d'une région.
</p>

</body>
</html>