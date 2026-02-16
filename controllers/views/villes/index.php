<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Villes - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .header { background: #2c3e50; color: white; padding: 15px; margin: -20px -20px 20px -20px; }
        nav { background: #34495e; padding: 10px; margin: 20px -20px; }
        nav a { color: white; text-decoration: none; margin-right: 15px; padding: 8px 12px; }
        nav a.active, nav a:hover { background: #2c3e50; }
        .container { max-width: 1000px; margin: 0 auto; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin: 20px 0; }
        .filter-section { background: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .btn { padding: 8px 15px; text-decoration: none; border-radius: 3px; color: white; }
        .btn-success { background: #27ae60; }
        .btn-primary { background: #3498db; }
        .btn-warning { background: #f39c12; }
        .btn-danger { background: #e74c3c; }
        table { width: 100%; background: white; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background: #ecf0f1; }
        select { padding: 8px; border: 1px solid #ddd; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BNGRC - Gestion des Villes</h1>
    </div>

    <nav>
        <a href="/exams3-main/exams3/tableau-bord">Tableau de bord</a>
        <a href="/exams3-main/exams3/regions">Régions</a>
        <a href="/exams3-main/exams3/villes" class="active">Villes</a>
        <a href="/exams3-main/exams3/besoins">Besoins</a>
        <a href="/exams3-main/exams3/dons">Dons</a>
    </nav>

    <div class="container">
        <div class="page-header">
            <h2>Liste des Villes</h2>
            <a href="/exams3-main/exams3/villes/create" class="btn btn-success">Ajouter</a>
        </div>

        <div class="filter-section">
            <label>Filtrer par région:</label>
            <form method="GET" style="display: inline; margin-left: 10px;">
                <select name="region_id" onchange="this.form.submit()">
                    <option value="">Toutes les régions</option>
                    <?php foreach ($regions as $region): ?>
                        <option value="<?= $region['id'] ?>" <?= ($region_id == $region['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($region['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
            
            <?php if ($region_selected): ?>
                <p style="margin: 10px 0 0 0;"><strong>Affichage :</strong> Villes de la région <?= htmlspecialchars($region_selected['nom']) ?></p>
            <?php endif; ?>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Région</th>
                <th>Actions</th>
            </tr>
            <?php if (empty($villes)): ?>
                <tr>
                    <td colspan="4" style="text-align: center; padding: 30px;">
                        Aucune ville enregistrée
                        <?php if ($region_selected): ?>
                            dans <?= htmlspecialchars($region_selected['nom']) ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($villes as $ville): ?>
                <tr>
                    <td><?= $ville['id'] ?></td>
                    <td><?= htmlspecialchars($ville['nom']) ?></td>
                    <td><?= htmlspecialchars($ville['region_nom'] ?? 'N/A') ?></td>
                    <td>
                        <a href="/exams3-main/exams3/villes/<?= $ville['id'] ?>" class="btn btn-primary">Voir</a>
                        <a href="/exams3-main/exams3/villes/<?= $ville['id'] ?>/edit" class="btn btn-warning">Modifier</a>
                        <a href="/exams3-main/exams3/villes/<?= $ville['id'] ?>/delete" class="btn btn-danger" onclick="return confirm('Supprimer cette ville?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>