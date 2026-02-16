<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villes - BNGRC</title>
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
        .filter-form { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        select, button { padding: 8px; margin: 5px; }
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

<h1>🏘️ Gestion des Villes - BNGRC</h1>

<div class="filter-form">
    <form method="GET" action="/villes">
        <label>Filtrer par région :</label>
        <select name="region_id">
            <option value="0">-- Toutes les régions --</option>
            <?php foreach ($regions as $r): ?>
                <option value="<?= $r["id"] ?>" <?= ($region_id == $r["id"]) ? "selected" : "" ?>>
                    <?= htmlspecialchars($r["nom"]) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn">🔍 Filtrer</button>
    </form>
</div>

<?php if ($region_selected): ?>
    <div style="background: #e8f5e8; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
        📍 Affichage des villes de la région : <strong><?= htmlspecialchars($region_selected["nom"]) ?></strong>
    </div>
<?php endif; ?>

<a href="/villes/create" class="btn btn-success">➕ Ajouter une ville</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom de la Ville</th>
            <th>Région</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($villes)): ?>
            <tr>
                <td colspan="4" style="text-align: center; color: #999;">
                    <?php if ($region_selected): ?>
                        Aucune ville dans la région "<?= htmlspecialchars($region_selected["nom"]) ?>"
                    <?php else: ?>
                        Aucune ville enregistrée
                    <?php endif; ?>
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($villes as $ville): ?>
                <tr>
                    <td><?= $ville["id"] ?></td>
                    <td>
                        <strong><?= htmlspecialchars($ville["nom"]) ?></strong>
                    </td>
                    <td>
                        <span style="background: #e3f2fd; padding: 3px 8px; border-radius: 15px;">
                            <?= htmlspecialchars($ville["region_nom"]) ?>
                        </span>
                    </td>
                    <td>
                        <a href="/villes/<?= $ville["id"] ?>" class="btn">👁️ Voir</a>
                        <a href="/villes/<?= $ville["id"] ?>/edit" class="btn btn-warning">✏️ Modifier</a>
                        <a href="/villes/<?= $ville["id"] ?>/delete" 
                           class="btn btn-danger" 
                           onclick="return confirm('Supprimer cette ville ?')">🗑️ Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<br>
<div style="text-align: center; margin-top: 20px;">
    <a href="/tableau-bord" class="btn" style="background: #6f42c1;">
        📊 Voir le Tableau de Bord
    </a>
</div>

</body>
</html>