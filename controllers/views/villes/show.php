<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ville <?= htmlspecialchars($ville['nom']) ?> - BNGRC</title>
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
        .section { margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BNGRC - Ville <?= htmlspecialchars($ville['nom']) ?></h1>
    </div>

    <nav>
        <a href="/exams3-main/exams3/tableau-bord">Tableau de bord</a>
        <a href="/exams3-main/exams3/regions">Régions</a>
        <a href="/exams3-main/exams3/villes" class="active">Villes</a>
        <a href="/exams3-main/exams3/besoins">Besoins</a>
        <a href="/exams3-main/exams3/dons">Dons</a>
    </nav>

    <div class="container">
        <div class="info-card">
            <h2><?= htmlspecialchars($ville['nom']) ?></h2>
            <p><strong>ID:</strong> <?= $ville['id'] ?></p>
            <p><strong>Région:</strong> <?= htmlspecialchars($ville['region_nom'] ?? 'N/A') ?></p>
            <p><strong>Date de création:</strong> <?= date('d/m/Y H:i', strtotime($ville['created_at'])) ?></p>
            
            <div style="margin-top: 20px;">
                <a href="/exams3-main/exams3/villes/<?= $ville['id'] ?>/edit" class="btn btn-warning">Modifier</a>
                <a href="/exams3-main/exams3/villes/<?= $ville['id'] ?>/delete" class="btn btn-danger" onclick="return confirm('Supprimer cette ville?')">Supprimer</a>
                <a href="/exams3-main/exams3/villes" class="btn btn-primary">Retour à la liste</a>
            </div>
        </div>

        <div class="section">
            <h3>Besoins de cette ville</h3>
            <?php if (empty($besoins)): ?>
                <div class="info-card">
                    <p>Aucun besoin enregistré pour cette ville.</p>
                    <a href="/exams3-main/exams3/besoins/create?ville_id=<?= $ville['id'] ?>" class="btn btn-success">Déclarer un besoin</a>
                </div>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Type de besoin</th>
                        <th>Quantité</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($besoins as $besoin): ?>
                    <tr>
                        <td><?= htmlspecialchars($besoin['nom']) ?></td>
                        <td><?= $besoin['nombre'] ?></td>
                        <td><?= date('d/m/Y', strtotime($besoin['created_at'])) ?></td>
                        <td>
                            <a href="/exams3-main/exams3/besoins/<?= $besoin['id'] ?>" class="btn btn-primary">Voir</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>

        <div class="section">
            <h3>Dons reçus pour cette ville</h3>
            <?php if (empty($dons)): ?>
                <div class="info-card">
                    <p>Aucun don enregistré pour cette ville.</p>
                    <a href="/exams3-main/exams3/dons/create?ville_id=<?= $ville['id'] ?>" class="btn btn-success">Enregistrer un don</a>
                </div>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Donneur</th>
                        <th>Type de don</th>
                        <th>Quantité</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                    <?php foreach ($dons as $don): ?>
                    <tr>
                        <td><?= htmlspecialchars($don['nom_donneur']) ?></td>
                        <td><?= htmlspecialchars($don['type_don']) ?></td>
                        <td><?= $don['nombre_don'] ?></td>
                        <td><?= date('d/m/Y', strtotime($don['created_at'])) ?></td>
                        <td>
                            <a href="/exams3-main/exams3/dons/<?= $don['id'] ?>" class="btn btn-primary">Voir</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>