<?php
$base = '/exams3-main/exams3';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Régions - BNGRC</title>
    <style>
        :root { --brand: #13265C; --muted: #6b7280; --bg: #f6f8fb; }
        body { font-family: Arial, sans-serif; background: var(--bg); margin: 0; padding: 0; }
        .header { background: var(--brand); color: white; padding: 20px; text-align: center; }
        .header h1 { margin: 0; }
        nav { background: white; padding: 10px; display: flex; gap: 10px; justify-content: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        nav a { color: var(--brand); text-decoration: none; padding: 8px 15px; border-radius: 20px; }
        nav a:hover, nav a.active { background: var(--brand); color: white; }
        .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
        .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 20px;
            background: var(--brand);
            color: white;
            text-decoration: none;
            font-weight: 600;
        }
        .btn-success { background: #28a745; }
        .btn-danger { background: #dc3545; }
        .btn-warning { background: #ffc107; color: black; }
        table { width: 100%; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        th { background: var(--brand); color: white; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #ddd; }
        tr:hover { background: #f5f5f5; }
        .actions { display: flex; gap: 5px; }
        .btn-small { padding: 5px 10px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BNGRC - Gestion des Régions</h1>
    </div>

    <nav>
        <a href="<?= $base ?>/">Accueil</a>
        <a href="<?= $base ?>/regions" class="active">Régions</a>
        <a href="<?= $base ?>/villes">Villes</a>
        <a href="<?= $base ?>/besoins">Besoins</a>
        <a href="<?= $base ?>/dons">Dons</a>
        <a href="<?= $base ?>/logout">Sortir</a>
    </nav>

    <div class="container">
        <div class="header-actions">
            <h2>Liste des régions</h2>
            <a href="<?= $base ?>/regions/create" class="btn btn-success">➕ Nouvelle région</a>
        </div>

        <?php if (empty($regions)): ?>
            <p style="text-align: center; padding: 40px; background: white; border-radius: 8px;">
                Aucune région enregistrée. <a href="<?= $base ?>/regions/create">Ajouter une région</a>
            </p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($regions as $region): ?>
                    <tr>
                        <td><?= $region['id'] ?></td>
                        <td><strong><?= htmlspecialchars($region['nom']) ?></strong></td>
                        <td class="actions">
                            <a href="<?= $base ?>/regions/<?= $region['id'] ?>" class="btn btn-small">Voir</a>
                            <a href="<?= $base ?>/regions/<?= $region['id'] ?>/edit" class="btn btn-small btn-warning">Modifier</a>
                            <a href="<?= $base ?>/regions/<?= $region['id'] ?>/delete" class="btn btn-small btn-danger" onclick="return confirm('Supprimer cette région ?')">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>