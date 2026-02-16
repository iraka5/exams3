<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Région <?= htmlspecialchars($region['nom']) ?> - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .header { background: #2c3e50; color: white; padding: 15px; margin: -20px -20px 20px -20px; }
        nav { background: #34495e; padding: 10px; margin: 20px -20px; }
        nav a { color: white; text-decoration: none; margin-right: 15px; padding: 8px 12px; }
        nav a.active, nav a:hover { background: #2c3e50; }
        .container { max-width: 1000px; margin: 0 auto; }
        .info-card { background: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .btn { padding: 8px 15px; text-decoration: none; border-radius: 3px; color: white; margin-right: 10px; }
        .btn-primary { background: #3498db; }
        .btn-warning { background: #f39c12; }
        .btn-danger { background: #e74c3c; }
        .btn-success { background: #27ae60; }
        table { width: 100%; background: white; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background: #ecf0f1; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BNGRC - Région <?= htmlspecialchars($region['nom']) ?></h1>
    </div>

    <nav>
        <a href="/exams3-main/exams3/tableau-bord">Tableau de bord</a>
        <a href="/exams3-main/exams3/regions" class="active">Régions</a>
        <a href="/exams3-main/exams3/villes">Villes</a>
        <a href="/exams3-main/exams3/besoins">Besoins</a>
        <a href="/exams3-main/exams3/dons">Dons</a>
    </nav>

    <div class="container">
        <div class="info-card">
            <h2><?= htmlspecialchars($region['nom']) ?></h2>
            <p><strong>ID:</strong> <?= $region['id'] ?></p>
            <p><strong>Date de création:</strong> <?= date('d/m/Y H:i', strtotime($region['created_at'])) ?></p>
            
            <div style="margin-top: 20px;">
                <a href="/exams3-main/exams3/regions/<?= $region['id'] ?>/edit" class="btn btn-warning">Modifier</a>
                <a href="/exams3-main/exams3/regions/<?= $region['id'] ?>/delete" class="btn btn-danger" onclick="return confirm('Supprimer cette région?')">Supprimer</a>
                <a href="/exams3-main/exams3/regions" class="btn btn-primary">Retour à la liste</a>
            </div>
        </div>

        <h3>Villes de cette région</h3>
        <?php if (empty($villes)): ?>
            <div class="info-card">
                <p>Aucune ville enregistrée dans cette région.</p>
                <a href="/exams3-main/exams3/villes/create?region_id=<?= $region['id'] ?>" class="btn btn-success">Ajouter une ville</a>
            </div>
        <?php else: ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nom de la ville</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($villes as $ville): ?>
                <tr>
                    <td><?= $ville['id'] ?></td>
                    <td><?= htmlspecialchars($ville['nom']) ?></td>
                    <td><?= date('d/m/Y', strtotime($ville['created_at'])) ?></td>
                    <td>
                        <a href="/exams3-main/exams3/villes/<?= $ville['id'] ?>" class="btn btn-primary">Voir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <p style="margin-top: 20px;">
                <a href="/exams3-main/exams3/villes/create?region_id=<?= $region['id'] ?>" class="btn btn-success">Ajouter une ville</a>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>