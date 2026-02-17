<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Régions - BNGRC</title>
<<<<<<< HEAD
    <link rel="stylesheet" href="/exams3-main/exams3/public/css/styles.css">
=======
    <style>
        :root { --brand: #13265C; --muted: #6b7280; --bg: #f6f8fb; }
        body { font-family: Arial, sans-serif; background: var(--bg); margin: 0; padding: 0; }
        .header { background: var(--brand); color: white; padding: 20px; text-align: center; }
        nav { background: white; padding: 10px; display: flex; gap: 10px; justify-content: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        nav a { color: var(--brand); text-decoration: none; padding: 8px 15px; border-radius: 20px; }
        nav a:hover, nav a.active { background: var(--brand); color: white; }
        .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
        .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { display: inline-block; padding: 10px 20px; border-radius: 20px; background: var(--brand); color: white; text-decoration: none; font-weight: 600; }
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
>>>>>>> 7d7cc3b657c0f4235199ad9f22097ff9ac7e2299
</head>
<body>
    <div class="container">
        <!-- Header avec logo BNGRC -->
        <header class="header">
            <div class="logo">
                BNG<span>RC</span>
            </div>
        </header>

        <!-- Hero avec fond du thème -->
        <div class="hero" style="background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--accent-blue-light) 50%, var(--bg-secondary) 100%); padding: 2.5rem; border-radius: 16px; margin-bottom: 2rem; text-align: center; border: 1px solid var(--border-light);">
            <h1 style="color: var(--text-primary); font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">BNGRC - Gestion des Régions</h1>
            <p style="color: var(--text-secondary); font-size: 1.1rem; font-weight: 300;">Administration des régions de Madagascar</p>
        </div>

        <!-- Navigation avec boutons arrondis individuels -->
        <nav class="nav-buttons" style="display: flex; gap: 0.75rem; margin-bottom: 2rem; flex-wrap: wrap; justify-content: center;">
            <a href="<?= $base ?>/" class="btn btn-outline" style="border-radius: 25px;">Accueil</a>
            <a href="<?= $base ?>/regions" class="btn btn-primary" style="border-radius: 25px;">Régions</a>
            <a href="<?= $base ?>/villes" class="btn btn-outline" style="border-radius: 25px;">Villes</a>
            <a href="<?= $base ?>/besoins" class="btn btn-outline" style="border-radius: 25px;">Besoins</a>
            <a href="<?= $base ?>/dons" class="btn btn-outline" style="border-radius: 25px;">Dons</a>
            <a href="<?= $base ?>/config-taux" class="btn btn-outline" style="border-radius: 25px;">Config V3</a>
            <a href="<?= $base ?>/reset-data" class="btn btn-outline" style="border-radius: 25px;">Reset</a>
        </nav>

        <!-- Contenu principal -->
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
