<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Régions - BNGRC</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .header { background: #2c3e50; color: white; padding: 15px; margin: -20px -20px 20px -20px; }
        nav { background: #34495e; padding: 10px; margin: 20px -20px; }
        nav a { color: white; text-decoration: none; margin-right: 15px; padding: 8px 12px; }
        nav a.active, nav a:hover { background: #2c3e50; }
        .container { max-width: 1000px; margin: 0 auto; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin: 20px 0; }
        .btn { padding: 8px 15px; text-decoration: none; border-radius: 3px; color: white; }
        .btn-success { background: #27ae60; }
        .btn-primary { background: #3498db; }
        .btn-warning { background: #f39c12; }
        .btn-danger { background: #e74c3c; }
        table { width: 100%; background: white; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background: #ecf0f1; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BNGRC - Gestion des Régions</h1>
    </div>

    <nav>
        <a href="/exams3-main/exams3/tableau-bord">Tableau de bord</a>
        <a href="/exams3-main/exams3/regions" class="active">Régions</a>
        <a href="/exams3-main/exams3/villes">Villes</a>
        <a href="/exams3-main/exams3/besoins">Besoins</a>
        <a href="/exams3-main/exams3/dons">Dons</a>
    </nav>

    <div class="container">
        <div class="page-header">
            <h2>Liste des Régions</h2>
            <a href="/exams3-main/exams3/regions/create" class="btn btn-success">Ajouter</a>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Villes</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($regions as $region): ?>
            <tr>
                <td><?= $region['id'] ?></td>
                <td><?= htmlspecialchars($region['nom']) ?></td>
                <td><?= $region['villes_count'] ?? 0 ?></td>
                <td>
                    <a href="/exams3-main/exams3/regions/<?= $region['id'] ?>" class="btn btn-primary">Voir</a>
                    <a href="/exams3-main/exams3/regions/<?= $region['id'] ?>/edit" class="btn btn-warning">Modifier</a>
                    <a href="/exams3-main/exams3/regions/<?= $region['id'] ?>/delete" class="btn btn-danger" onclick="return confirm('Supprimer cette région?')">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>