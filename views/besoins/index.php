<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Besoins - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .header { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .nav { display: flex; gap: 15px; margin-bottom: 20px; }
        .nav a { padding: 8px 16px; background: #3498db; color: white; text-decoration: none; border-radius: 4px; }
        .nav a:hover { background: #2980b9; }
        .filter { background: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .filter select, .filter button { padding: 8px; margin: 0 5px; }
        table { width: 100%; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        th { background: #34495e; color: white; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #eee; }
        .actions { display: flex; gap: 5px; }
        .actions a { padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; }
        .btn-view { background: #3498db; color: white; }
        .btn-edit { background: #f39c12; color: white; }
        .btn-delete { background: #e74c3c; color: white; }
        .btn-add { background: #27ae60; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Gestion des Besoins</h1>
        <div class="nav">
            <a href="/exams3-main/exams3/">Accueil</a>
            <a href="/exams3-main/exams3/regions">Régions</a>
            <a href="/exams3-main/exams3/villes">Villes</a>
            <a href="/exams3-main/exams3/besoins">Besoins</a>
            <a href="/exams3-main/exams3/dons">Dons</a>
        </div>
    </div>

    <div class="filter">
        <form method="GET" action="/exams3-main/exams3/besoins">
            <label>Filtrer par ville :</label>
            <select name="id_ville">
                <option value="0">-- Toutes les villes --</option>
                <?php foreach ($villes as $v): ?>
                    <option value="<?= $v["id"] ?>" <?= ($id_ville == $v["id"]) ? "selected" : "" ?>>
                        <?= htmlspecialchars($v["nom"]) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Filtrer</button>
        </form>
        <a href="/exams3-main/exams3/besoins/create" class="btn-add">Ajouter un Besoin</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Quantité</th>
                <th>Ville</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($besoins)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px; color: #666;">
                        Aucun besoin trouvé.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($besoins as $besoin): ?>
                    <tr>
                        <td><?= $besoin['id'] ?></td>
                        <td><?= htmlspecialchars($besoin['nom']) ?></td>
                        <td><?= number_format($besoin['nombre'], 0, ',', ' ') ?></td>
                        <td><?= htmlspecialchars($besoin['ville_nom']) ?></td>
                        <td>
                            <div class="actions">
                                <a href="/exams3-main/exams3/besoins/<?= $besoin['id'] ?>" class="btn-view">Voir</a>
                                <a href="/exams3-main/exams3/besoins/<?= $besoin['id'] ?>/edit" class="btn-edit">Modifier</a>
                                <a href="/exams3-main/exams3/besoins/<?= $besoin['id'] ?>/delete" 
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce besoin ?')" 
                                   class="btn-delete">Supprimer</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
