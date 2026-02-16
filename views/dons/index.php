<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Dons - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .header { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .nav { display: flex; gap: 15px; margin-bottom: 20px; }
        .nav a { padding: 8px 16px; background: #3498db; color: white; text-decoration: none; border-radius: 4px; }
        .nav a:hover { background: #2980b9; }
        table { width: 100%; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        th { background: #34495e; color: white; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #eee; }
        .actions { display: flex; gap: 5px; }
        .actions a { padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; }
        .btn-view { background: #3498db; color: white; }
        .btn-edit { background: #f39c12; color: white; }
        .btn-delete { background: #e74c3c; color: white; }
        .btn-add { background: #27ae60; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Gestion des Dons</h1>
        <div class="nav">
            <a href="/exams3-main/exams3/">Accueil</a>
            <a href="/exams3-main/exams3/regions">Régions</a>
            <a href="/exams3-main/exams3/villes">Villes</a>
            <a href="/exams3-main/exams3/besoins">Besoins</a>
            <a href="/exams3-main/exams3/dons">Dons</a>
        </div>
    </div>

    <a href="/exams3-main/exams3/dons/create" class="btn-add">Ajouter un Don</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Donneur</th>
                <th>Type</th>
                <th>Quantité</th>
                <th>Ville</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($dons)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #666;">
                        Aucun don trouvé.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($dons as $don): ?>
                    <tr>
                        <td><?= $don['id'] ?></td>
                        <td><?= htmlspecialchars($don['nom_donneur']) ?></td>
                        <td><?= htmlspecialchars($don['type_don']) ?></td>
                        <td><?= number_format($don['nombre_don'], 0, ',', ' ') ?></td>
                        <td><?= htmlspecialchars($don['ville_nom']) ?></td>
                        <td>
                            <div class="actions">
                                <a href="/exams3-main/exams3/dons/<?= $don['id'] ?>" class="btn-view">Voir</a>
                                <a href="/exams3-main/exams3/dons/<?= $don['id'] ?>/edit" class="btn-edit">Modifier</a>
                                <a href="/exams3-main/exams3/dons/<?= $don['id'] ?>/delete" 
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce don ?')" 
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
